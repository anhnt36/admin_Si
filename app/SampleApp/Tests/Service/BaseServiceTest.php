<?php
namespace SampleApp\Tests\Service;


class BaseServiceTest extends \PHPUnit_Framework_TestCase {
    public $app;
    public function __construct() {
        parent::__construct();
        $this->app = require __DIR__.'/../../../../app/app.php';
    }
    public function setUp() {
        parent::setUp();
    }

    public function tearDown() {
        parent::tearDown();
    }

    public function test() {

    }
}