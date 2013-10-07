<?php

namespace pages;

class Note {

   protected $content;

   public function __construct($content) {
      $this->contet = $content;
   }

   public function getContent() {
      return $this->contet;
   }

   public function setContent($contet) {
      $this->contet = $contet;
   }
}