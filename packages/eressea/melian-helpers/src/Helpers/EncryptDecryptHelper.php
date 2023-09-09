<?php
/**
 * Created by Eressea Solutions
 * @author Rene Silva <rsilva@eresseasolutions.com>
 */

namespace Eressea\MelianHelpers\Helpers;

use App\Models\User;
use Illuminate\Support\Facades\Auth;

class EncryptDecryptHelper
{
  public static function getKeys($user_id = 0)
  {
    $server_key = \Config::get('jwt.secret');

    if ($user_id == -1) {
      //generate encryption with server_key only
      return $server_key;
    }

    $user_key = '';

    $current_user = $user = auth()->user();

    if ($current_user) {

      if ($current_user->is_admin && $user_id != 0) {
        $new_user = User::findOrFail($user_id);
        $user_key = $new_user->user_key;
      } else {
        $user_key = $current_user->user_key;
      }

    } else {
      if ($user_id != 0) {
        //no session opened
        $new_user = User::findOrFail($user_id);
        $user_key = $new_user->user_key;
      }
    }

    return $user_key . $server_key;
  }

  public static function encrypt($string, $user_id = 0)
  {
    $key = self::getKeys($user_id);

    $ivlen = openssl_cipher_iv_length($cipher = "AES-128-CBC");
    $iv = openssl_random_pseudo_bytes($ivlen);
    $ciphertext_raw = openssl_encrypt($string, $cipher, $key, $options = OPENSSL_RAW_DATA, $iv);
    $hmac = hash_hmac('sha256', $ciphertext_raw, $key, $as_binary = true);
    $ciphertext = base64_encode($iv . $hmac . $ciphertext_raw);

    return $ciphertext;
  }

  public static function decrypt($string, $user_id = 0)
  {
    $key = self::getKeys($user_id);

    $c = base64_decode($string);
    $ivlen = openssl_cipher_iv_length($cipher = "AES-128-CBC");
    $iv = substr($c, 0, $ivlen);
    $hmac = substr($c, $ivlen, $sha2len = 32);
    $ciphertext_raw = substr($c, $ivlen + $sha2len);
    $original_plaintext = openssl_decrypt($ciphertext_raw, $cipher, $key, $options = OPENSSL_RAW_DATA, $iv);
    $calcmac = hash_hmac('sha256', $ciphertext_raw, $key, $as_binary = true);

    try {
      //PHP 5.6+ timing attack safe comparison
      if (hash_equals($hmac, $calcmac)) {
        return $original_plaintext;
      } else {
        return '';
      }
    } catch (\ErrorException $ex) {
      return '';
    }
  }

  //simple encryption
  public static function simpleEncrypt($message, $encode = false)
  {
    $key = '24e-secret-key';
    $nonceSize = openssl_cipher_iv_length(self::METHOD);
    $nonce = openssl_random_pseudo_bytes($nonceSize);

    $ciphertext = openssl_encrypt(
      $message,
      self::METHOD,
      $key,
      OPENSSL_RAW_DATA,
      $nonce
    );

    // Now let's pack the IV and the ciphertext together
    // Naively, we can just concatenate
    if ($encode) {
      return base64_encode($nonce . $ciphertext);
    }
    return $nonce . $ciphertext;
  }

  public static function simpleDecrypt($message, $encoded = false)
  {
    $key = '24e-secret-key';
    if ($encoded) {
      $message = base64_decode($message, true);
      if ($message === false) {
        throw new \Exception('Encryption failure');
      }
    }

    $nonceSize = openssl_cipher_iv_length(self::METHOD);
    $nonce = mb_substr($message, 0, $nonceSize, '8bit');
    $ciphertext = mb_substr($message, $nonceSize, null, '8bit');

    $plaintext = openssl_decrypt(
      $ciphertext,
      self::METHOD,
      $key,
      OPENSSL_RAW_DATA,
      $nonce
    );

    return $plaintext;
  }
}
