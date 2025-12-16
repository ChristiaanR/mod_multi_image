<?php

/**
 * @package     Joomla.Site
 * @subpackage  mod_multi_image
 *
 * @copyright   Copyright (C) 2024
 * @license     GNU General Public License version 2 or later
 */

defined('_JEXEC') or die;

use Joomla\CMS\Factory;
use Joomla\CMS\HTML\HTMLHelper;
use ChristiaanRuiter\Module\MultiImage\Site\Helper\MultiImageHelper;

$target = MultiImageHelper::getTarget($params->get('target', 0));
$layout = $params->get('layout', 'horizontal');
$width = $params->get('width', '');
$height = $params->get('height', '200');
$breakpointDouble = $params->get('breakpoint_double', '768');
$breakpointTriple = $params->get('breakpoint_triple', '1024');
$moduleId = 'mod-multi-image-' . $module->id;

// Load CSS and JS from media folder
/** @var Joomla\CMS\WebAsset\WebAssetManager $wa */
$wa = $app->getDocument()->getWebAssetManager();
$wa->registerAndUseStyle('mod_multi_image', 'mod_multi_image/mod_multi_image.css');
$wa->registerAndUseScript('mod_multi_image', 'mod_multi_image/mod_multi_image.js', [], ['defer' => true]);

// Pass configuration to JavaScript
$wa->addInlineScript('
    window.modMultiImageConfig = window.modMultiImageConfig || {};
    window.modMultiImageConfig["' . $moduleId . '"] = {
        breakpointDouble: ' . (int)$breakpointDouble . ',
        breakpointTriple: ' . (int)$breakpointTriple . '
    };
', [], ['defer' => true]);
?>

<div id="<?php echo $moduleId; ?>"
  class="mod-multi-image mod-multi-image-<?php echo $layout; ?> <?php echo $params->get('moduleclass_sfx'); ?>">
  <?php foreach ($images as $index => $image) : ?>
  <?php
    // Build inline style for background image
    $style = 'background-image: url(\'' . htmlspecialchars($image['src'], ENT_QUOTES, 'UTF-8') . '\');';
    /* $style = 'background-image: url("' 
       . Uri::root(true) . $image->url 
       . '");';*/

    // if (!empty($width)) {
    //   $style .= ' width: ' . htmlspecialchars($width) . 'px;';
    // }

    // if (!empty($height)) {
    //   $style .= ' height: ' . htmlspecialchars($height) . 'px;';
    // }

    // Determine data-show attribute
    $dataShow = '';
    if ($index === 1) {
      $dataShow = 'true';
    } elseif ($index === 2) {
      $dataShow = 'false';
    }
    ?>

  <div class="multi-image-item" style="<?php echo $style; ?>"
    <?php if ($dataShow) : ?>data-show="<?php echo $dataShow; ?>" <?php endif; ?>>
    <?php if (!empty($image['link'])) : ?>
    <a href="<?php echo htmlspecialchars($image['link']); ?>" class="multi-image-link"
      <?php if ($target === '_blank') : ?> target="_blank" rel="noopener noreferrer"
      <?php elseif ($target === 'modal') : ?> data-bs-toggle="modal" data-bs-target="#imageModal" <?php endif; ?>>
    </a>
    <?php endif; ?>
  </div>
  <?php endforeach; ?>
</div>