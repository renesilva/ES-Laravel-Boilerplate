<?php
/**
 * Created by Eressea Solutions
 * @author Rene Silva <rsilva@eresseasolutions.com>
 */

namespace Eressea\MelianHelpers\Helpers;

use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\Storage;

class FileUploadHelper
{
  public static function formatFile($slug, $size = 'original')
  {
    switch ($size) {

      case '220x220':
        return url('/') . Storage::url('uploads/images/custom_thumbnail_sizes/220x220/' . $slug . '.jpg');
        break;

      default:
      case 'original':
        return url('/') . Storage::url('uploads/images/original/' . $slug . '.jpg');
        break;
    }
  }

  public static function returnDefaultUserProfile()
  {
    return url('/') . '/img/image-profile.png';
  }

  public static function returnDefaultObjectLogo()
  {
    return url('/') . '/img/icon_default_projects.png';
  }

  public static function returnDefaultActivityIcon()
  {
    return url('/') . '/img/imagen_default_activity.png';
  }

  public static function returnDefaultLessonIcon()
  {
    return url('/') . '/img/icon_default_kp_page.svg';
  }

  public static function returnDefaultCourseIcon()
  {
    return url('/') . '/img/icon_default_projects.png';
  }

  public static function returnDefaultMenuIcon()
  {
    return url('/') . '/img/icon_default_menu.svg';
  }

  public static function returnDefaultBackgroundImg()
  {
    return url('/') . '/img/certificado.png';
  }

  public static function getUsersPhotos($users)
  {
    $photos = [];
    foreach ($users as $user) {
      $photos[] = self::getUserPhoto($user);
    }
    return $photos;
  }

  public static function getUserPhoto($user, $thumbnailSize = '220x220')
  {
    if ($user->profile_picture) {
      $photo = self::getImage($user->profile_picture, $thumbnailSize);
    } else {
      $photo = self::returnDefaultUserProfile();
    }
    return $photo;
  }

  public static function getObjectsPhotos($objects)
  {
    $photos = [];
    foreach ($objects as $object) {
      $photos[] = self::getObjectPhoto($object);
    }
    return $photos;
  }

  public static function getObjectPhoto($object, $thumbnailSize = '220x220')
  {
    if (isset($object->object_logo) && $object->object_logo) {
      $photo = self::getImage($object->object_logo, $thumbnailSize);
    } else {
      $photo = self::returnDefaultObjectLogo();
    }
    return $photo;
  }

  public static function getActivityIcon($edactivity, $thumbnailSize = '220x220')
  {
    if (isset($edactivity->activity_icon) && $edactivity->activity_icon) {
      $photo = self::getImage($edactivity->activity_icon, $thumbnailSize);
    } else {
      $photo = self::returnDefaultActivityIcon();
    }
    return $photo;
  }

  public static function getLessonIcon($edlesson, $thumbnailSize = '220x220')
  {
    if (isset($edlesson->lesson_icon) && $edlesson->lesson_icon) {
      $photo = self::getImage($edlesson->lesson_icon, $thumbnailSize);
    } else {
      $photo = self::returnDefaultLessonIcon();
    }
    return $photo;
  }

  public static function getCourseIcon($edcourse, $thumbnailSize = '220x220')
  {
    if (isset($edcourse->course_icon) && $edcourse->course_icon) {
      $photo = self::getImage($edcourse->course_icon, $thumbnailSize);
    } else {
      $photo = self::returnDefaultCourseIcon();
    }
    return $photo;
  }

  public static function getCompanyLogo($company, $thumbnailSize = '220x220')
  {
    if (isset($company->company_logo) && $company->company_logo) {
      $photo = self::getImage($company->company_logo, $thumbnailSize);
    } else {
      $photo = self::returnDefaultObjectLogo();
    }
    return $photo;
  }

  public static function getBackgroundImg($certificate, $thumbnailSize = 'original')
  {
    if (isset($certificate->background_img) && $certificate->background_img) {
      $photo = self::getImage($certificate->background_img, $thumbnailSize);
    } else {
      $photo = self::returnDefaultBackgroundImg();
    }
    return $photo;
  }

  public static function saveImage($fileName, $fileBaseName, $generateThumbnails = true)
  {
    $user = auth()->user();

    /*
    //check extensions
    $extension = strtolower($tempImage->getClientOriginalExtension());

    //check iphones photos
    if( !in_array($extension,['jpg','png','jpeg'])){
      return false;
    }
     */

    $image = Image::make($fileName);
    //check sizes
    $imageWidth = $image->width();
    $imageHeight = $image->width();

    if ($generateThumbnails && ($imageWidth < 220 || $imageHeight < 220)) {
      return false;
    }

    //name
    $baseName = $user->id . '-' . str_random(5) . '-' . $fileBaseName;
    $fileName = $baseName . '.jpg';

    //original
    $image->save(storage_path('app/public/uploads/images/original/' . $fileName), 100);

    if ($generateThumbnails) {
      self::generateThumbnails($image, $fileName);
    }

    return $baseName;
  }

  public static function generateThumbnails($image, $fileName)
  {
    $imageWidth = $image->width();
    $imageHeight = $image->width();
    //600x600
    if ($imageWidth >= 600 || $imageHeight >= 600) {
      $image->fit(600, 600)->save(storage_path('app/public/uploads/images/thumbnails/600x600/' . $fileName), 100);
    }
    //220x220
    $image->fit(220, 220)->save(storage_path('app/public/uploads/images/thumbnails/220x220/' . $fileName), 100);
  }

  public static function getImage($baseName, $thumbnailSize = '220x220')
  {
    $fileName = '';

    $fileNameOriginal = 'uploads/images/original/' . $baseName . '.jpg';
    $fileName600x600 = 'uploads/images/thumbnails/600x600/' . $baseName . '.jpg';
    $fileName220x220 = 'uploads/images/thumbnails/220x220/' . $baseName . '.jpg';

    switch ($thumbnailSize) {
      case 'original':
        $fileName = $fileNameOriginal;
        break;
      case '600x600':
        $fileName = $fileName600x600;
        break;
      case '220x220':
        $fileName = $fileName220x220;
        break;
    }

    if (Storage::exists('public/' . $fileName)) {
      return url('/') . Storage::url($fileName);
    } else {
      //at least return the 220x220 file
      if (Storage::exists('public/' . $fileName220x220)) {
        return url('/') . Storage::url($fileName220x220);
      }
      //return false; Return default image
      return self::returnDefaultObjectLogo();
    }

  }

  public static function getPngImage($baseName)
  {
    $fileName = 'uploads/images/original/' . $baseName . '.png';
    if (Storage::exists('public/' . $fileName)) {
      return url('/') . Storage::url($fileName);
    } else {
      return self::returnDefaultUserProfile();
    }
  }

  public static function getApplicantFile($baseName)
  {
    $fileName = 'uploads/application_awards/' . $baseName;
    if (Storage::exists('public/' . $fileName)) {
      return url('/') . Storage::url($fileName);
    } else {
      return '';
    }
  }
}
