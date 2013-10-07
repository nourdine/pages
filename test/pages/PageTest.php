<?php

use pages\Page;

class PageTest extends PHPUnit_Framework_TestCase {

   const WORKING_URL = "http://www.blog.local";

   /**
    * @var pages\Page
    */
   protected $page = null;

   public function setUp() {
      $this->page = new Page(self::WORKING_URL);
   }

   public function tearDown() {
      $this->page = null;
   }

   public function testExist() {
      $this->page->setUrl(self::WORKING_URL);
      $bool = $this->page->doesExist();
      $this->assertTrue($bool);
   }

   public function testExistButNoProtocalSpecified() {
      $this->page->setUrl("www.blog.local");
      $bool = $this->page->doesExist();
      $this->assertTrue($bool);
   }

   /**
    * This will prolly redirect
    */
   public function testNotExist() {
      $this->page->setUrl("http://www.not_exisisting_but_prolly_redirected_by_ISP.com");
      $bool = $this->page->doesExist();
      $this->assertFalse($bool);
      $this->assertEquals(1, count($this->page->getNotes()));
   }

   public function testValid() {
      $this->page->setUrl(self::WORKING_URL);
      $bool = $this->page->isLocationValid();
      $this->assertTrue($bool);
   }

   public function testValidWithQueryString() {
      $this->page->setUrl(self::WORKING_URL . "?a=1&b=2");
      $bool = $this->page->isLocationValid();
      $this->assertTrue($bool);
   }

   public function testGetTitle() {
      $expected = "Nourdine's website - another damn resource on web programming and bla bla bla ;-)";
      $this->page->setUrl(self::WORKING_URL);
      $title = $this->page->getTitle();
      $this->assertEquals($expected, $title);
   }

   public function testGetDescription() {
      $expected = "Nourdine's website - another damn resource on programming and bla bla bla ;-)";
      $this->page->setUrl(self::WORKING_URL);
      $title = $this->page->getDescription();
      $this->assertEquals($expected, $title);
   }
}