<?php

namespace pages;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Message\Response;
use regular\Regular;

class Page {

   protected $timeout;
   protected $httpClient;
   protected $response;
   protected $url;
   protected $redirected;
   protected $finalUrl;

   public function __construct(string $url, float $timeout = 0.5) {
      $this->timeout = $timeout;
      $this->url = $url;
      $this->httpClient = new Client();
   }

   public function getUrl() {
      return $this->url;
   }

   /**
    * Check if the there have been redirections on the way to the declared url.
    * 
    * @return boolean
    */
   public function hasBeenRedirected() {
      return $this->redirected;
   }

   /**
    * Return the final url reached after redirections. If no redirections have been applied, then this is equal to the declared url.
    * @return string
    */
   public function getFinalUrl() {
      return $this->finalUrl;
   }

   /**
    * Check whether a remote page exists or not.
    * 
    * @throws RequestException
    * @return boolean
    */
   public function exists() {
      return 200 === $this->response->getStatusCode();
   }

   /**
    * Fetch the page title or an empty string (title missing).
    * 
    * @throws RequestException
    * @return string|null
    */
   public function getTitle() {
      $re = new Regular("/<title ?.*>([\s\S]*)<\/title>/i");
      $matches = $re->match($this->response->getBody());
      if ($matches->isSuccess()) {
         return html_entity_decode(trim($matches->getCaptured(0)->getValue()));
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
      $matches = $re->match($this->response->getBody());
      if ($matches->isSuccess()) {
         return html_entity_decode(trim($matches->getCaptured(0)->getValue()));
      }
   }

   /**
    * Fetch the whole page's HTML source code. 
    * 
    * @throws RequestException
    * @return string
    */
   public function getHTML() {
      return $this->response->getBody();
   }

   /**
    * Fetch the page if not already retrieved. The http call follows the riderect headers sent by the server.
    * @see http://guzzle.readthedocs.org/en/latest/quickstart.html#redirects
    * 
    * @throws RequestException
    * @return Response
    */
   public function fetch() {
      if ($this->response === null) {
         $this->response = $this->httpClient->get($this->url, [
             "timeout" => $this->timeout
         ]);
         $this->finalUrl = $this->response->getEffectiveUrl();
         if ($this->finalUrl !== $this->url) {
            $this->redirected = true;
         }
      }
      return $this;
   }
}
