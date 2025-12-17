<?php

/**
 * @package     Joomla.Site
 * @subpackage  mod_multi_image
 *
 * @copyright   Copyright (C) 2024
 * @license     GNU General Public License version 2 or later
 */

namespace CRu\Module\MultiImage\Site\Helper;

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
        ];

        $images[] = $imageData;
      }
    }

    return $images;
  }

  /**
   * Get the image path relative to root
   *
   * @param   string  $image  Image path (may contain Joomla metadata)
   *
   * @return  string  Relative image URL
   *
   * @since   1.0.0
   */
  private static function getImagePath($image)
  {
    // Remove quotes if present
    $image = trim($image, '\'"');

    // Extract the relative path before the Joomla metadata marker (#joomlaImage://)
    if (strpos($image, '#') !== false) {
      $image = substr($image, 0, strpos($image, '#'));
    }

    // If it's already a full URL, return it
    if (strpos($image, 'http://') === 0 || strpos($image, 'https://') === 0) {
      return $image;
    }

    // Ensure path starts with /
    if (strpos($image, '/') !== 0) {
      $image = '/' . $image;
    }

    // Return the relative URL
    return $image;
  }
}
