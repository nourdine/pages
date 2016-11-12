<?php

use pages\Page;

class PageTest extends PHPUnit_Framework_TestCase {

   const EXISTING_URL = "http://pages.local/exist.php";
   const EXISTING_URL_NO_PROTOCOL = "pages.local/exist.php";
   const NON_EXISTING = "http://pages.local/not.php";
   const REDIRECTING_URL = "http://pages.local/redirect.php";
   const TAKING_LONG_TIME_URL = "http://pages.local/taking_long_time.php";

   public function testCheckExistence() {
      $page = new Page(self::EXISTING_URL);
      $this->assertTrue($page->fetch()->exists());
   }

   public function testGetTitle() {
      $page = new Page(self::EXISTING_URL);
      $title = $page->fetch()->getTitle();
      $this->assertEquals("TITLE & MORE", $title);
   }

   public function testGetDescription() {
      $page = new Page(self::EXISTING_URL);
      $description = $page->fetch()->getDescription();
      $this->assertEquals("DESCRIPTION", $description);
   }

   public function testCheckExistenceOfRedirectingPage() {
      $page = new Page(self::REDIRECTING_URL);
      $this->assertTrue($page->fetch()->exists());
      $this->assertTrue($page->fetch()->hasBeenRedirected());
      $this->assertEquals($page->fetch()->getFinalUrl(), self::EXISTING_URL);
   }

   /**
    * @expectedException GuzzleHttp\Exception\RequestException
    */
   public function testCheckExistenceOfNonExistingPage() {
      $page = new Page(self::NON_EXISTING);
      $page->fetch()->exists();
   }

   /**
    * @expectedException GuzzleHttp\Exception\RequestException
    */
   public function testCheckExistenceOfPageTakingALongTime() {
      $page = new Page(self::TAKING_LONG_TIME_URL);
      $page->fetch()->exists();
   }

   /**
    * @expectedException GuzzleHttp\Exception\RequestException
    */
   public function testGetTitleOfPageTakingALongTime() {
      $page = new Page(self::TAKING_LONG_TIME_URL);
      $page->fetch()->getTitle();
   }

   /**
    * @expectedException GuzzleHttp\Exception\RequestException
    */
   public function testGetDescriptionOfPageTakingALongTime() {
      $page = new Page(self::TAKING_LONG_TIME_URL);
      $page->fetch()->getDescription();
   }
}
