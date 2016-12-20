<?php

namespace Jaam\Mixpanel;

class DataExportApi {

    const API_URL = 'https://mixpanel.com/api';
    const API_EXPORT_URL = 'https://data.mixpanel.com/api';
    const API_VERSION = '2.0';

    private $secret;

    public function __construct($secret) {
        $this->secret = $secret;
    }

    private function request($baseUrl, $endpoint, array $params = []) {
        // JSON encode array parameters
        $params = array_map(function($value) {
            return is_array($value) ? json_encode($value) : $value;
        }, $params);

        // Build query string
        $query = http_build_query($params);

        // Build URL
        $requestUrl = sprintf('%s/%s/%s?%s', $baseUrl, self::API_VERSION, $endpoint, $query);

        // Authorization header
        $headers = [ 'Authorization: Basic ' . base64_encode($this->secret) ];

        // cURL request
        $ch = curl_init();
        curl_setopt_array($ch, [
            CURLOPT_URL => $requestUrl,
            CURLOPT_HTTPAUTH => CURLAUTH_BASIC,
            CURLOPT_HTTPHEADER => $headers,
            CURLOPT_CONNECTTIMEOUT => 2,
            CURLOPT_RETURNTRANSFER => 1
        ]);
        $response = curl_exec($ch);
        curl_close($ch);

        return $response;
    }

    public function data($endpoint, array $params) {
        // Set JSON format
        $params['format'] = 'json';

        // Execute request
        $response = $this->request(self::API_URL, $endpoint, $params);
        $json = json_decode($response, true);

        if ( isset($json['error']) ) {
            throw new DataExportApiException($json['error']);
        }

        return $json;
    }

    public function export(array $params = []) {
        // Execute request
        $response = $this->request(self::API_EXPORT_URL, 'export', $params);

        // Split data by lines
        $lines = preg_split("/" . PHP_EOL . "/", $response, null, PREG_SPLIT_NO_EMPTY);

        // Convert lines to JSON
        $json = array_map(function($line) {
            return json_decode($line, true);
        }, $lines);

        if ( isset($json[0]) && isset($json[0]['error']) ) {
            throw new DataExportApiException($json[0]['error']);
        }

        return $json;
    }

}