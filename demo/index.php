<?php

include_once '../vendor/autoload.php';

ini_set('default_socket_timeout', 4);

$get = function($url) {
      try {
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
      } catch (\RuntimeException $ex) {
         echo $ex->getMessage() . PHP_EOL;
      }
   };

$get("www.yahoo.com");
$get("https://www.google.com"); // redirecting
$get("https://www.nourdine.net/article/10");
$get("http://www.adam-bray.com/blog/86/Simple+CSS+3+buttons/");
$get("http://bloomwebdesign.net/2013/09/create-a-flat-website-template-htmlcss-tutorial/");
$get("http://getcomposer.org/");
$get("http://vectorboom.com/load/tutorials/web_design/how_to_create_glass_infographics_elements_in_illustrator/7-1-0-457");