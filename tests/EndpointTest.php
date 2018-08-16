<?php

use Jaam\Mixpanel\DataExportApiException;

class EndpointTest extends BaseTest {

    public function setUp() {
        parent::setUp();
    }

    public function testEventsEndpoint() {
        $data = $this->mixpanel->data('events', [
            'event' => ['event_name'],
            'type' => 'unique',
            'unit' => 'day',
            'from_date' => $this->fromDate->format('Y-m-d'),
            'to_date' => $this->toDate->format('Y-m-d')
        ]);

        $this->assertInternalType('array', $data);
    }

    public function testAnnotationsEndpoint() {
        $data = $this->mixpanel->data('annotations', [
            'from_date' => $this->fromDate->format('Y-m-d'),
            'to_date' => $this->toDate->format('Y-m-d')
        ]);

        $this->assertInternalType('array', $data);
    }

    public function testInvalidEndpoint() {
        $this->expectException(DataExportApiException::class);

        $data = $this->mixpanel->data('fake_endpoint', []);
    }

    public function testMissingParams() {
        $this->expectException(DataExportApiException::class);

        // Missing `event` parameter
        $data = $this->mixpanel->data('events', [
            'type' => 'unique',
            'unit' => 'day'
        ]);
    }

}