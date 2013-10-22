<?php

namespace pages;

use regular\Regular;
use kurl\HttpClient;

class Page {

   const REDIRECT_REQUIRED = "302";

   /**
    * @var \kurl\HttpClient
    */
   protected $httpClient = null;

   /**
    * @var \kurl\Response
    */
   protected $response = null;
   protected $url = null;
   protected $notes = array();
   protected $dictionary = array(
      "302" => "A redirect has been required"
   );

   protected function fetchRemote() {
      if ($this->response == null) {
         $this->response = $this->httpClient->request("GET", $this->url);
      }
      return $this->response;
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

   public function __construct($url = null, $timeout = 1) {
      $this->httpClient = new HttpClient($timeout);
      ini_set("default_socket_timeout", $timeout);
      $this->url = $this->addProtocol($url);
   }

   public function getUrl() {
      return $this->url;
   }

   public function setUrl($url) {
      $this->url = $this->addProtocol($url);
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
    * @throws \kurl\exception\HttpException
    * @return boolean
    */
   public function isLocationFormallyValid() {
      if (filter_var($this->url, FILTER_VALIDATE_URL) === false) {
         return false;
      }
      return true;
   }

   /**
    * Check whether a remote page exists or not.
    * To be used before Page::getTitle and Page::getDescription to make sure the wanted page actually exists.
    * 
    * @throws \kurl\exception\HttpException
    * @return boolean
    */
   public function checkExistence() {
      $sc = $this->fetchRemote()->getStatusCode();
      if ($sc === 302) {
         $this->addPageNote(self::REDIRECT_REQUIRED);
      }
      return $sc == 200;
   }

   /**
    * Fetch the page title or an empty string (title missing).
    * 
    * @throws \RuntimeException
    * @return string|null
    */
   public function getTitle() {
      $re = new Regular("/<title ?.*>(.*)<\/title>/i");
      $matches = $re->match($this->fetchRemote()->getBody());
      if ($matches->isSuccess()) {
         return trim($matches->getCaptured(0));
      }
   }

   /**
    * Fetch the page description or an empty string (description missing). 
    * 
    * @throws \RuntimeException
    * @return string|null
    */
   public function getDescription() {
      $re = new Regular('/<meta name="description".{0,}content="([^"]{1,})"\s{0,}\/{0,1}>/i');
      $matches = $re->match($this->fetchRemote()->getBody());
      if ($matches->isSuccess()) {
         return trim($matches->getCaptured(0));
      }
   }
}