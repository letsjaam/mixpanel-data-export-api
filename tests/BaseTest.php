<?php

use Dotenv\Dotenv;
use Jaam\Mixpanel\DataExportApi;

abstract class BaseTest extends \PHPUnit\Framework\TestCase {

    protected $mixpanel;
    protected $fromDate;
    protected $toDate;

    public function setUp() {
        if ( file_exists(__DIR__ . '/.env') ) {
            $dotenv = new Dotenv(__DIR__);
            $dotenv->load();
        }

        // Set up API wrapper
        $this->mixpanel = new DataExportApi( getenv('API_SECRET') );

        // Set up dates
        $this->toDate = new \DateTime();
        $this->fromDate = $this->toDate->sub( \DateInterval::createFromDateString('30 days') );
    }

}