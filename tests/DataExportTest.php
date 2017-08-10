<?php

use Jaam\Mixpanel\DataExportApiException;

class DataExportTest extends BaseTest {

    public function setUp() {
        parent::setUp();
    }

    public function testExport() {
        $data = $this->mixpanel->export([
            'from_date' => $this->fromDate->format('Y-m-d'),
            'to_date' => $this->toDate->format('Y-m-d')
        ]);

        $this->assertInternalType('array', $data);
    }

    public function testMissingParams() {
        $this->expectException(DataExportApiException::class);

        // Missing `to_date` parameter
        $data = $this->mixpanel->export([
            'from_date' => $this->fromDate->format('Y-m-d')
        ]);
    }

}