<?php

include_once '../vendor/autoload.php';

$get = function($url) {
   try {
      $page = new \pages\Page($url, 3);
      echo "Url: " . $url . PHP_EOL;
      if ($page->exists()) {
         echo "Title: " . $page->getTitle() . PHP_EOL;
         echo "Description: " . $page->getDescription() . PHP_EOL;
      } else {
         echo "Page not found." . PHP_EOL;
      }
   } catch (\RuntimeException $ex) {
      echo $ex->getMessage() . PHP_EOL;
   }
   echo PHP_EOL . PHP_EOL;
};

$get("http://www.nourdine.net/article/10");
$get("www.yahoo.com");
$get("https://www.google.com"); // redirecting
$get("http://www.adam-bray.com/blog/86/Simple+CSS+3+buttons/");
$get("http://bloomwebdesign.net/2013/09/create-a-flat-website-template-htmlcss-tutorial/"); // 404
$get("http://getcomposer.org/"); // redirecting
$get("http://vectorboom.com/load/tutorials/web_design/how_to_create_glass_infographics_elements_in_illustrator/7-1-0-457");
