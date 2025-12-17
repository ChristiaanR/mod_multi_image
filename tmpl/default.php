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
use CRu\Module\MultiImage\Site\Helper\MultiImageHelper;

$layout = $params->get('image_layout', 'horizontal');
$breakpointDouble = $params->get('breakpoint_double', '768');
$breakpointTriple = $params->get('breakpoint_triple', '1024');
$lazyLoading = (bool) $params->get('lazy_loading', 0);
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
        breakpointTriple: ' . (int)$breakpointTriple . ',
        lazyLoading: ' . ($lazyLoading ? 'true' : 'false') . '
    };
', [], ['defer' => true]);
?>

<div id="<?php echo $moduleId; ?>"
  class="mod-multi-image mod_multi_image_<?php echo $layout; ?> <?php echo $params->get('moduleclass_sfx'); ?>"
  aria-hidden="true">
  <?php foreach ($images as $index => $image) : ?>
    <?php
    // Build inline style for background image
    $style = 'background: url(\'' . htmlspecialchars($image['src'], ENT_QUOTES, 'UTF-8') . '\') no-repeat;';

    // Initialize data-show: first image always true, others false initially
    // JavaScript will update based on viewport width and breakpoints
    $dataShow = $index === 0 ? 'true' : 'false';
    ?>

    <div class="multi-image-item" style="<?php echo $style; ?>" data-show="<?php echo $dataShow; ?>">
    </div>
  <?php endforeach; ?>
</div>