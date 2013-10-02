<?php

namespace pages;

class Page {

   protected $url = null;
   protected $content = null;

   protected function getTagValue($html, $tagName) {
      $matches = array();
      $pattern = "/<$tagName ?.*>(.*)<\/$tagName>/";
      preg_match($pattern, $html, $matches);
      return $matches[1];
   }

   protected function fetchContent() {
      if ($this->content == null) {
         $this->content = file_get_contents($this->url);
      }
   }

   public function __construct($url = null) {
      $this->url = $url;
   }

   public function getUrl() {
      return $this->url;
   }

   public function setUrl($url) {
      $this->url = $url;
   }

   public function doesExist() {
      $headers = @get_headers($this->url);
      if ($headers === false) {
         return $headers;
      } else {
         $status = $headers[0];
         return strpos($status, "200") !== false;
      }
   }

   public function hasValidLocation() {
      if (filter_var($this->url, FILTER_VALIDATE_URL) === false) {
         return false;
      }
      return true;
   }

   public function getTitle() {
      $this->fetchContent();
      return $this->getTagValue($this->content, "title");
   }

   public function getDescription() {
      $this->fetchContent();
      // ...
   }
}