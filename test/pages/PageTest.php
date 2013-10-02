<?php

use pages\Page;

class PageTest extends PHPUnit_Framework_TestCase {

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

   public function testExist() {
      $this->page->setUrl("http://www.nourdine.net");
      $bool = $this->page->exist();
      $this->assertTrue($bool);
   }

   public function testNotExist() {
      $this->page->setUrl("http://www.nxoxuxrxdxixnxe.net");
      $bool = $this->page->exist();
      $this->assertFalse($bool);
   }

   public function testValid() {
      $this->page->setUrl("http://www.nourdine.net");
      $bool = $this->page->valid();
      $this->assertTrue($bool);
   }

   public function testValidWithQueryString() {
      $this->page->setUrl("http://www.nourdine.net?a=1&b=2");
      $bool = $this->page->valid();
      $this->assertTrue($bool);
   }
}