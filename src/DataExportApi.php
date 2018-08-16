<?php

namespace Jaam\Mixpanel;

class DataExportApi {

    const API_URL = 'https://mixpanel.com/api';
    const API_EXPORT_URL = 'https://data.mixpanel.com/api';
    const API_VERSION = '2.0';

    private $secret;

    /**
     * Constructor
     *
     * @param string $secret Mixpanel Project API secret
     */
    public function __construct($secret) {
        $this->secret = $secret;
    }

    /**
     * Handles API requests.
     *
     * @param  string $baseUrl  Base URL for request
     * @param  string $endpoint Endpoint to be called
     * @param  array  $params   Parameter array for request
     * @return string           Raw response returned by request
     */
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

    /**
     * Call a data endpoint on the API.
     *
     * @param  string $endpoint Endpoint to be called, eg. "events"
     * @param  array  $params   Parameter array for request
     * @return json             Result JSON
     */
    public function data($endpoint, array $params) {
        // Set JSON format
        $params['format'] = 'json';

        // Execute request
        $response = $this->request(self::API_URL, $endpoint, $params);
        $json = json_decode($response, true);

        if ( isset($json['error']) && $json['error'] !== false ) {
            throw new DataExportApiException($json['error']);
        }

        return $json;
    }

    /**
     * Call the raw export endpoint on the API.
     *
     * @param  array $params Parameter array for request
     * @return array         Result array
     */
    public function export(array $params = []) {
        // Execute request
        $response = $this->request(self::API_EXPORT_URL, 'export', $params);

        // Split data by lines
        $lines = preg_split("/" . PHP_EOL . "/", $response, null, PREG_SPLIT_NO_EMPTY);

        // Convert lines to JSON
        $json = array_map(function($line) {
            $decodedLine = json_decode($line, true);

            if ( json_last_error() !== JSON_ERROR_NONE ) {
                throw new DataExportApiException($line);
            }

            return $decodedLine;
        }, $lines);

        return $json;
    }

}