<?php
/**
 * Created by Eressea Solutions
 * @author Rene Silva <rsilva@eresseasolutions.com>
 */

namespace Eressea\MelianHelpers\Helpers;

use DOMDocument;

class UtilityHelper
{

  /**
   * Strips script and style tags
   * @param string $string
   * @param string $allowedTags
   * @return string
   */
  public static function formatStringWebView($string, $allowedTags = '<p><i><b><a><div><ul><ol><li>')
  {
    $string = preg_replace('#<script(.*?)>(.*?)</script>#is', '', $string);
    $string = preg_replace('#<style(.*?)>(.*?)</style>#is', '', $string);
    return strip_tags(html_entity_decode($string), $allowedTags);
  }

  /*To format html metadata values*/
  public static function removeElementsByTagName($tagName, $document)
  {
    $nodeList = $document->getElementsByTagName($tagName);
    for ($nodeIdx = $nodeList->length; --$nodeIdx >= 0;) {
      $node = $nodeList->item($nodeIdx);
      $node->parentNode->removeChild($node);
    }
  }

  /*!To format html metadata values*/
  public static function removeTags($content)
  {
    $content = trim(preg_replace('/\s+/', ' ', $content));
    $content = str_replace('<div', '<p', $content);
    $content = str_replace('</div', '</p', $content);
    $doc = new DOMDocument();
    $doc->loadHTML('<?xml encoding="utf-8" ?>' . $content, LIBXML_HTML_NOIMPLIED | # Make sure no extra BODY
      LIBXML_HTML_NODEFDTD |  # or DOCTYPE is created
      LIBXML_NOERROR |        # Suppress any errors
      LIBXML_NOWARNING        # or warnings about prefixes.
    );
    self::removeElementsByTagName('script', $doc);
    self::removeElementsByTagName('style', $doc);
    self::removeElementsByTagName('link', $doc);
    $html = $doc->saveHtml();
    $strippedHTML = strip_tags($html, '<p><ul><ol><li><strong><a><i><b>');
    $strippedHTML = preg_replace('/(<[^>]+) style=".*?"/i', '$1', $strippedHTML);
    $strippedHTML = preg_replace('/(<[^>]+) lang=".*?"/i', '$1', $strippedHTML);
    $strippedHTML = preg_replace("/(<[^>]+) style='.*?'/i", '$1', $strippedHTML);
    $strippedHTML = preg_replace('/(<[^>]+) class=".*?"/i', '$1', $strippedHTML);
    $strippedHTML = preg_replace('/(<[^>]+) align=".*?"/i', '$1', $strippedHTML);
    $strippedHTML = str_replace('<p> </p>', '', $strippedHTML);
    $strippedHTML = str_replace('<p>&nbsp;</p>', '', $strippedHTML);
    $strippedHTML = str_replace('<p></p>', '', $strippedHTML);
    return $strippedHTML;
  }
}
