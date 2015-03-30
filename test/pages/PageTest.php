<?php

use pages\Page;

class PageTest extends PHPUnit_Framework_TestCase {

   const EXISTING_URL = "http://localhost:8080/exist.php";
   const EXISTING_URL_NO_PROTOCOL = "localhost:8080/exist.php";
   const NON_EXISTING = "http://localhost:8080/not.php";
   const REDIRECTING_URL = "http://localhost:8080/redirect.php";
   const TAKING_LONG_TIME_URL = "http://localhost:8080/taking_long_time.php";

   /**
    * @var Page
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
      $bool = $this->page->exists();
      $this->assertTrue($bool);
   }

   public function testCheckExistence_no_protocol_specified() {
      $this->page->setUrl(self::EXISTING_URL_NO_PROTOCOL);
      $bool = $this->page->exists();
      $this->assertTrue($bool);
   }

   public function testGetTitle() {
      $this->page->setUrl(self::EXISTING_URL);
      $title = $this->page->getTitle();
      $this->assertEquals("TITLE", $title);
   }

   public function testGetDescription() {
      $this->page->setUrl(self::EXISTING_URL);
      $description = $this->page->getDescription();
      $this->assertEquals("DESCRIPTION", $description);
   }

   /**
    * @expectedException GuzzleHttp\Exception\RequestException
    */
   public function testCheckExistence_non_existing() {
      $this->page->setUrl(self::NON_EXISTING);
      $bool = $this->page->exists();
      $this->assertFalse($bool);
   }

   public function testCheckExistence_redirecting() {
      $this->page->setUrl(self::REDIRECTING_URL);
      $bool = $this->page->exists();
      $this->assertFalse($bool);
   }

   /**
    * @expectedException GuzzleHttp\Exception\RequestException
    */
   public function testCheckExistence_taking_long_time() {
      $this->page->setUrl(self::TAKING_LONG_TIME_URL);
      $bool = $this->page->exists();
      $this->assertFalse($bool);
   }

   /**
    * @expectedException GuzzleHttp\Exception\RequestException
    */
   public function testGetTitle_taking_long_time() {
      $this->page->setUrl(self::TAKING_LONG_TIME_URL);
      $title = $this->page->getTitle();
   }

   /**
    * @expectedException GuzzleHttp\Exception\RequestException
    */
   public function testGetDescription_taking_long_time() {
      $this->page->setUrl(self::TAKING_LONG_TIME_URL);
      $title = $this->page->getDescription();
   }
}
