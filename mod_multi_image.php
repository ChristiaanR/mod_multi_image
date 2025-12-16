<?php

/**
 * @package     Joomla.Site
 * @subpackage  mod_multi_image
 *
 * @copyright   Copyright (C) 2024
 * @license     GNU General Public License version 2 or later
 */

defined('_JEXEC') or die;

use Joomla\CMS\Helper\ModuleHelper;
use ChristiaanRuiter\Module\MultiImage\Site\Helper\MultiImageHelper;

// Get module parameters
$images = MultiImageHelper::getImages($params);

// Only display if at least one image is selected
if (!empty($images)) {
  require ModuleHelper::getLayoutPath('mod_multi_image', $params->get('layout', 'default'));
}
