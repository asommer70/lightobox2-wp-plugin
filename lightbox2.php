<?php
/*
Plugin Name: Lightbox 2 Wordpress Plugin
Plugin URI: https://thehoick.com/
Version: 0.0.1
Author: Adam Sommer
Description: WordPress plugin for Lightbox 2 https://github.com/lokesh/lightbox2/.
*/

defined( 'ABSPATH' ) or die( 'No please!' );

function lightbox2_enqueue() {
  wp_enqueue_script('lightbox2_js', plugins_url('lightbox2/assets/js/lightbox.min.js'), ['jquery'], '', true);

  wp_enqueue_style( 'lightbox2_css', plugins_url( 'lightbox2/assets/css/lightbox.min.css' ), [], '1.0' );
}
add_action('wp_enqueue_scripts', 'lightbox2_enqueue');


function lightbox2_img_attrs($content) {
  global $post;

  $document = new \DOMDocument();
  libxml_use_internal_errors(true);
  $document->loadHTML(utf8_decode($content));
  $images = $document->getElementsByTagName('img');

  foreach ($images as $image) {
    $parent = $image->parentNode;
    $caption = $image->nextSibling;

    $link = $document->createElement('a');
    $link->setAttribute('class', 'lightbox-link');
    $link->setAttribute('href', $image->getAttribute('src'));
    $link->setAttribute('data-lightbox', 'post_'. $post->ID);
    $link->setAttribute('data-title', $caption->textContent);
    $link->appendChild($image);

    $parent->appendChild($link);
    $parent->appendChild($caption);
  }

  return $document->saveHTML();
}
add_filter('the_content', 'lightbox2_img_attrs');
