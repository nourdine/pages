<?php

namespace pages;

class Page {

   protected $url;

   public function __construct($url = null) {
      $this->url = $url;
   }

   public function getUrl() {
      return $this->url;
   }

   public function setUrl($url) {
      $this->url = $url;
   }

   public function exist() {
      $headers = get_headers($this->url);
      $status = $headers[0];
      return strpos($status, "200") !== false;
   }
}