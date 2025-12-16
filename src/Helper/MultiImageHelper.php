<?php

/**
 * @package     Joomla.Site
 * @subpackage  mod_multi_image
 *
 * @copyright   Copyright (C) 2024
 * @license     GNU General Public License version 2 or later
 */

namespace ChristiaanRuiter\Module\MultiImage\Site\Helper;

defined('_JEXEC') or die;

use Joomla\CMS\Factory;
use Joomla\CMS\Uri\Uri;

/**
 * Helper for mod_multi_image
 *
 * @since  1.0.0
 */
class MultiImageHelper
{
  /**
   * Get images from module parameters
   *
   * @param   \Joomla\Registry\Registry  $params  Module parameters
   *
   * @return  array  Array of image data
   *
   * @since   1.0.0
   */
  public static function getImages($params)
  {
    $images = [];

    // Process up to 3 images - only add if image is defined
    for ($i = 1; $i <= 3; $i++) {
      $image = $params->get('image' . $i);

      // Only add to array if image is actually selected
      if (!empty($image)) {
        $imageData = [
          'src'    => self::getImagePath($image),
          'link'   => $params->get('link' . $i, ''),
          'width'  => $params->get('width', ''),
          'height' => $params->get('height', ''),
        ];

        $images[] = $imageData;
      }
    }

    return $images;
  }

  /**
   * Get the full image path
   *
   * @param   string  $image  Image path
   *
   * @return  string  Full image URL
   *
   * @since   1.0.0
   */
  private static function getImagePath($image)
  {
    // If it's already a full URL, return it
    if (strpos($image, 'http://') === 0 || strpos($image, 'https://') === 0) {
      return $image;
    }

    // Remove leading slash if present
    $image = ltrim($image, '/');

    // Return the full URL
    return Uri::root() . $image;
  }

  /**
   * Get link target attribute
   *
   * @param   int  $target  Target parameter value
   *
   * @return  string  Target attribute
   *
   * @since   1.0.0
   */
  public static function getTarget($target)
  {
    switch ($target) {
      case 1:
        return '_blank';
      case 2:
        return 'modal';
      default:
        return '';
    }
  }
}