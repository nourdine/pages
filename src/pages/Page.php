<?php

namespace pages;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Message\Response;
use regular\Regular;

class Page {

   const REDIRECT_REQUIRED = "302";

   protected $timeout;
   protected $httpClient = null;
   protected $response = null;
   protected $url = null;

   public function __construct($url = null, $timeout = 0.5) {
      $this->timeout = $timeout;
      $this->url = $this->addProtocol($url);
      $this->httpClient = new Client();
   }

   public function getUrl() {
      return $this->url;
   }

   public function setUrl($url) {
      $this->url = $this->addProtocol($url);
   }

   /**
    * Check whether the page url is valid or not.
    * 
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
    * 
    * @throws RequestException
    * @return boolean
    */
   public function exists() {
      return 200 === $this->fetchRemote()->getStatusCode();
   }

   /**
    * Fetch the page title or an empty string (title missing).
    * 
    * @throws RequestException
    * @return string|null
    */
   public function getTitle() {
      $re = new Regular("/<title ?.*>(.*)<\/title>/i");
      $matches = $re->match($this->fetchRemote()->getBody());
      if ($matches->isSuccess()) {
         return trim($matches->getCaptured(0)->getValue());
      }
   }

   /**
    * Fetch the page description or an empty string (description missing). 
    * 
    * @throws RequestException
    * @return string|null
    */
   public function getDescription() {
      $re = new Regular('/<meta name="description".{0,}content="([^"]{1,})"\s{0,}\/{0,1}>/i');
      $matches = $re->match($this->fetchRemote()->getBody());
      if ($matches->isSuccess()) {
         return trim($matches->getCaptured(0)->getValue());
      }
   }

   /**
    * Fetch the page if not already retrieved.
    * 
    * @throws RequestException
    * @return Response
    */
   protected function fetchRemote() {
      if ($this->response === null) {
         $this->response = $this->httpClient->get($this->url, [
             "timeout" => $this->timeout
         ]);
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
}
