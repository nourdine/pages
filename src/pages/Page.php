<?php

namespace pages;

use regular\Regular;

class Page {

   protected $url = null;
   protected $content = null;

   protected function fetchContent() {
      if ($this->content == null) {
         $tmp = @file_get_contents($this->url);
         if ($tmp === false) { // network error!
            $this->content = "";
         } else {
            $this->content = $tmp;
         }
      }
      return $this->content;
   }

   protected function hasProtocol($url) {
      if (strpos($url, "http://") === 0 ||
         strpos($url, "https://") === 0) {
         return true;
      }
      return false;
   }

   protected function addProtocol($url) {
      if (!$this->hasProtocol($url)) {
         $url = "http://" . $url;
      }
      return $url;
   }

   public function __construct($url = null) {
      $this->url = $this->addProtocol($url);
   }

   public function getUrl() {
      return $this->url;
   }

   public function setUrl($url) {
      $this->url = $this->addProtocol($url);
   }

   public function doesExist() {
      $headers = @get_headers($this->url);
      if ($headers === false) { // network error!
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
      $re = new Regular("/<title ?.*>(.*)<\/title>/");
      $title = $re->match($this->fetchContent())->getCaptured(0);
      return trim($title);
   }

   public function getDescription() {
      $re = new Regular('<meta name="description".{0,}content="([^"]{1,})">');
      $description = $re->match($this->fetchContent())->getCaptured(0);
      return trim($description);
   }
}