<?php

use pages\Page;

class PageTest extends PHPUnit_Framework_TestCase {

   const EXISTING_URL = "http://pages.local/exist.php";
   const EXISTING_URL_NO_PROTOCOL = "pages.local/exist.php";
   const NON_EXISTING = "http://pages.local/not.php";
   const REDIRECTING_URL = "http://pages.local/redirect.php";
   const TAKING_LONG_TIME_URL = "http://pages.local/taking_long_time.php";

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

   public function testCheckExistence() {
      $this->page->setUrl(self::EXISTING_URL);
      $this->assertTrue($this->page->fetch()->exists());
   }

   public function testGetTitle() {
      $this->page->setUrl(self::EXISTING_URL);
      $title = $this->page->fetch()->getTitle();
      $this->assertEquals("TITLE", $title);
   }

   public function testGetDescription() {
      $this->page->setUrl(self::EXISTING_URL);
      $description = $this->page->fetch()->getDescription();
      $this->assertEquals("DESCRIPTION", $description);
   }

   public function testCheckExistenceOfRedirectingPage() {
      $this->page->setUrl(self::REDIRECTING_URL);
      $this->assertTrue($this->page->fetch()->exists());
      $this->assertTrue($this->page->fetch()->hasBeenRedirected());
      $this->assertEquals($this->page->fetch()->getFinalUrl(), self::EXISTING_URL);
   }

   /**
    * @expectedException GuzzleHttp\Exception\RequestException
    */
   public function testCheckExistenceOfNonExistingPage() {
      $this->page->setUrl(self::NON_EXISTING);
      $this->page->fetch()->exists();
   }

   /**
    * @expectedException GuzzleHttp\Exception\RequestException
    */
   public function testCheckExistenceOfPageTakingALongTime() {
      $this->page->setUrl(self::TAKING_LONG_TIME_URL)->fetch()->exists();
   }

   /**
    * @expectedException GuzzleHttp\Exception\RequestException
    */
   public function testGetTitleOfPageTakingALongTime() {
      $this->page->setUrl(self::TAKING_LONG_TIME_URL)->fetch()->getTitle();
   }

   /**
    * @expectedException GuzzleHttp\Exception\RequestException
    */
   public function testGetDescriptionOfPageTakingALongTime() {
      $this->page->setUrl(self::TAKING_LONG_TIME_URL)->fetch()->getDescription();
   }
}
