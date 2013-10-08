<?php

use pages\Page;

class PageTest extends PHPUnit_Framework_TestCase {

   const EXISTING_URL = "http://pages.ws.local/exist.php";
   const NON_EXISTING = "http://pages.ws.local/not.php";
   const REDIRECTING_URL = "http://pages.ws.local/redirect.php";
   const TAKING_LONG_TIME_URL = "http://pages.ws.local/taking_long_time.php";

   /**
    * @var pages\Page
    */
   protected $page = null;

   public function setUp() {
      $this->page = new Page(self::EXISTING_URL);
   }

   public function tearDown() {
      $this->page = null;
   }

   public function testExisting() {
      $this->page->setUrl(self::EXISTING_URL);
      $bool = $this->page->checkExistence();
      $this->assertTrue($bool);
   }

   public function testExistingButNoProtocalSpecified() {
      $this->page->setUrl("pages.ws.local/exist.php");
      $bool = $this->page->checkExistence();
      $this->assertTrue($bool);
   }

   public function testRedirecting() {
      $this->page->setUrl(self::REDIRECTING_URL);
      $bool = $this->page->checkExistence();
      $this->assertFalse($bool);
      $this->assertEquals(1, count($this->page->getNotes()));
   }

   public function testNonExisting() {
      $this->page->setUrl(self::NON_EXISTING);
      $bool = $this->page->checkExistence();
      $this->assertFalse($bool);
      $this->assertEquals(0, count($this->page->getNotes()));
   }

   /**
    * @expectedException RuntimeException
    */
   public function testTakingLongTimeToRespond() {
      $this->page->setUrl(self::TAKING_LONG_TIME_URL);
      $bool = $this->page->checkExistence();
   }

   public function testIsLocationFormallyValid() {
      $this->page->setUrl(self::EXISTING_URL);
      $bool = $this->page->isLocationFormallyValid();
      $this->assertTrue($bool);
   }

   public function testIsLocationFormallyValidWithQueryString() {
      $this->page->setUrl(self::EXISTING_URL . "?a=1&b=2");
      $bool = $this->page->isLocationFormallyValid();
      $this->assertTrue($bool);
   }

   public function testGetTitle() {
      $expected = "TITLE";
      $this->page->setUrl(self::EXISTING_URL);
      $title = $this->page->getTitle();
      $this->assertEquals($expected, $title);
   }

   public function testGetDescription() {
      $expected = "DESCRIPTION";
      $this->page->setUrl(self::EXISTING_URL);
      $description = $this->page->getDescription();
      $this->assertEquals($expected, $description);
   }
}