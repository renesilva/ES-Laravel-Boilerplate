<?php
/**
 * Created by Eressea Solutions
 * @author Rene Silva <rsilva@eresseasolutions.com>
 */

namespace Eressea\MelianHelpers\Helpers;

class SocialMediaHelper
{
  public static function setFormatPhoneNumber($string)
  {
    $string = str_replace('.', ' ', $string);
    $string = str_replace(' ', ' ', $string);
    $string = str_replace('-', ' ', $string);
    return $string;
  }

  /**
   * Only 3 valid facebook formats, user.name or http(s)://facebook.com/test or User Name
   * @param $string
   * @return string
   */
  public static function setFormatFacebookUsername($string)
  {
    if (substr($string, 0, 4) === "http") {

    } else {
      $string = str_replace('www.facebook.com/', '', $string);
      $string = str_replace('facebook.com/', '', $string);
    }
    return $string;
  }

  public static function getFormatFacebookUsername($string)
  {
    if ($string == '') {
      return '';
    }
    //https://www.facebook.com/alba.pizarro -> https://www.facebook.com/alba.pizarro
    if (substr($string, 0, 4) === "http") {
      return $string;
    } else {
      if (strpos($string, ' ') === false) {
        //alba.pizarro -> https://www.facebook.com/alba.pizarro
        return "https://www.facebook.com/" . $string;
      } else {
        //Alba Pizarro -> https://www.facebook.com/search/str/Alba+Pizarro/keywords_search
        return "https://www.facebook.com/search/str/" . urlencode($string) . "/keywords_search";
      }
    }
  }

  /**
   * Only user.name valid format, or email
   * @param $string
   * @return string
   */
  public static function setFormatTwitterUsername($string)
  {
    if (filter_var($string, FILTER_VALIDATE_EMAIL)) {

    } else {
      $string = str_replace('http://twitter.com/', '', $string);
      $string = str_replace('https://twitter.com/', '', $string);
      $string = str_replace('@', '', $string);
    }
    return $string;
  }

  public static function getFormatTwitterUsername($string)
  {
    if ($string == '') {
      return '';
    }
    if (filter_var($string, FILTER_VALIDATE_EMAIL)) {
      return '';
    } else {
      return 'https://twitter.com/' . $string;
    }
  }

  /**
   * Only 3 valid formats, User Name, link, username
   *
   * @param $string
   * @return string
   */
  public static function setFormatLinkedinUsername($string)
  {
    //https://www.linkedin.com/search/results/index/?keywords=user%20name&origin=GLOBAL_SEARCH_HEADER
    //https://www.linkedin.com/in/username/
    //normal link
    if (strpos($string, '.com/') !== false) {
      $string = str_replace('http://', '', $string);
      $string = str_replace('https://', '', $string);
      $string = 'https://' . $string;
    }
    return $string;
  }

  public static function getFormatLinkedinUsername($string)
  {
    if ($string == '') {
      return '';
    }
    if (strpos($string, '.com/') !== false) {
      // in case the user used just www.linkedin.com/in/test
      $string = str_replace('http://', '', $string);
      $string = str_replace('https://', '', $string);
      $string = 'https://' . $string;
    } else {
      //username
      $string = 'https://www.linkedin.com/in/' . $string;
    }
    return $string;
  }

  /**
   * @param $string
   * @return string
   */
  public static function setFormatYoutubeChannel($string)
  {
    //https://www.youtube.com/user/username
    //https://www.youtube.com/results?search_query=User+Name
    if (strpos($string, '.com/') !== false) {
      $string = str_replace('http://', '', $string);
      $string = str_replace('https://', '', $string);
      $string = 'https://' . $string;
    }
    return $string;
  }

  public static function getFormatYoutubeChannel($string)
  {
    if ($string == '') {
      return '';
    }
    if (strpos($string, '.com/') !== false) {
      $string = str_replace('http://', '', $string);
      $string = str_replace('https://', '', $string);
      $string = 'https://' . $string;
    } else {
      if (strpos($string, ' ') === false) {

      } else {
        $string = 'https://www.youtube.com/results?search_query=' . $string;
      }
      //https://www.youtube.com/results?search_query=User+Name
    }
    return $string;
  }

  /**
   * @param $string
   * @return string
   */
  public static function setFormatInstagram($string)
  {
    //https://www.instagram.com/coconutricebear/
    //coconutricebear
    $string = str_replace('https://www.instagram.com/', '', $string);
    $string = str_replace('http://www.instagram.com/', '', $string);
    $string = str_replace('/', '', $string);
    $string = str_replace('@', '', $string);
    return $string;
  }

  public static function getFormatInstagram($string)
  {
    if ($string == '') {
      return '';
    }
    return 'https://www.instagram.com/' . $string;
  }


  public static function setFormatWebsite($string)
  {
    if ($string == '') {
      return '';
    }
    if (substr($string, 0, 4) === "http") {

    } else {
      $string = 'http://' . $string;
    }
    return $string;
  }

  public static function getFormatWebsite($string)
  {
    if ($string == '') {
      return '';
    }
    if (substr($string, 0, 4) === "http") {

    } else {
      $string = 'http://' . $string;
    }
    return $string;
  }
}
