<?php

include_once '../vendor/autoload.php';

$get = function($url) {
      $page = new \pages\Page($url);
      echo "### Page url " . $url . PHP_EOL;
      if ($page->doesExist()) {
         echo "Getting info..." . PHP_EOL;
         echo "Title: " . $page->getTitle() . PHP_EOL;
         echo "Description: " . $page->getDescription() . PHP_EOL;
      } else {
         echo "Page not found. Messages:" . PHP_EOL;
         $notes = $page->getNotes();
         foreach ($notes as $note) {
            echo "- " . $note->getContent() . PHP_EOL;
         }
      }
   };


$get("www.yahoo.com");
$get("https://www.google.com");
$get("https://www.facebook.com");
$get("http://www.nourdine.net/article/10");