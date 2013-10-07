<?php

namespace pages;

use regular\Regular;

class Page {

   const REDIRECT_REQUIRED = "302";

   protected $url = null;
   protected $content = null;
   protected $notes = array();
   protected $dictionary = array(
      "302" => "A redirect has been required"
   );

   protected function isFoundStatusCode($str) {
      return strpos($str, "302") !== false;
   }

   protected function isOKStatusCode($str) {
      return strpos($str, "200") !== false;
   }

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

   protected function addPageNote($code) {
      $this->notes[] = new Note($this->dictionary[$code]);
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

   /**
    * Check whether a remote page exists or not.
    * 
    * @throws \RuntimeException
    * @return boolean
    */
   public function doesExist() {
      $headers = @get_headers($this->url);
      if ($headers === false) {
         throw new \RuntimeException("A network error occurred");
      } else {
         $status = $headers[0];
         $_200_ = $this->isOKStatusCode($status);
         $_302_ = $this->isFoundStatusCode($status);
         if ($_302_) {
            $this->addPageNote(self::REDIRECT_REQUIRED);
         }
         return $_200_;
      }
   }

   public function hasNotes() {
      return count($this->notes) > 0;
   }

   public function getNotes() {
      return $this->notes;
   }

   /**
    * Check whether the page url is valid or not.
    * 
    * @return boolean
    */
   public function isLocationValid() {
      if (filter_var($this->url, FILTER_VALIDATE_URL) === false) {
         return false;
      }
      return true;
   }

   /**
    * The page title or an empty string (title missing).
    * 
    * @throws \RuntimeException
    * @return string|null
    */
   public function getTitle() {
      $re = new Regular("/<title ?.*>(.*)<\/title>/i");
      $matches = $re->match($this->fetchContent());
      if ($matches->isSuccess()) {
         return trim($matches->getCaptured(0));
      }
   }

   /**
    * The page description or an empty string (description missing). 
    * 
    * @throws \RuntimeException
    * @return string|null
    */
   public function getDescription() {
      $re = new Regular('/<meta name="description".{0,}content="([^"]{1,})"\s{0,}\/{0,1}>/i');
      $matches = $re->match($this->fetchContent());
      if ($matches->isSuccess()) {
         return trim($matches->getCaptured(0));
      }
   }
}