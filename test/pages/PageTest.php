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
      $this->page = new Page();
   }

   public function tearDown() {
      $this->page = null;
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

   public function testCheckExistence_existing() {
      $this->page->setUrl(self::EXISTING_URL);
      $bool = $this->page->checkExistence();
      $this->assertTrue($bool);
   }

   public function testCheckExistence_no_protocol_specified() {
      $this->page->setUrl("pages.ws.local/exist.php");
      $bool = $this->page->checkExistence();
      $this->assertTrue($bool);
   }

   public function testCheckExistence_redirecting() {
      $this->page->setUrl(self::REDIRECTING_URL);
      $bool = $this->page->checkExistence();
      $this->assertFalse($bool);
      $this->assertEquals(1, count($this->page->getNotes()));
   }

   public function testCheckExistence_non_existing() {
      $this->page->setUrl(self::NON_EXISTING);
      $bool = $this->page->checkExistence();
      $this->assertFalse($bool);
      $this->assertEquals(0, count($this->page->getNotes()));
   }

   /**
    * @expectedException RuntimeException
    */
   public function testCheckExistence_taking_long_time() {
      $this->page->setUrl(self::TAKING_LONG_TIME_URL);
      $bool = $this->page->checkExistence();
   }

   public function testGetTitle() {
      $expected = "TITLE";
      $this->page->setUrl(self::EXISTING_URL);
      $title = $this->page->getTitle();
      $this->assertEquals($expected, $title);
   }

   /**
    * @expectedException RuntimeException
    */
   public function testGetTitle_taking_long_time() {
      $this->page->setUrl(self::TAKING_LONG_TIME_URL);
      $title = $this->page->getTitle();
   }

   public function testGetDescription() {
      $expected = "DESCRIPTION";
      $this->page->setUrl(self::EXISTING_URL);
      $description = $this->page->getDescription();
      $this->assertEquals($expected, $description);
   }

   /**
    * @expectedException RuntimeException
    */
   public function testGetDescription_taking_long_time() {
      $this->page->setUrl(self::TAKING_LONG_TIME_URL);
      $title = $this->page->getDescription();
   }
}