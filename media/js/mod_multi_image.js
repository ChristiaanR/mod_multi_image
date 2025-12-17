/**
 * @package     Joomla.Site
 * @subpackage  mod_multi_image
 *
 * @copyright   Copyright (C) 2024
 * @license     GNU General Public License version 2 or later
 */

(function () {
	'use strict';

	/**
	 * Update visibility of images based on viewport width and breakpoints
	 * @param {string} moduleId - The module container ID
	 * @param {Object} config - Configuration with breakpoint values
	 */
	function updateVisibility(moduleId, config) {
		var width = window.innerWidth;
		var module = document.getElementById(moduleId);

		if (!module) {
			return;
		}

		var items = module.querySelectorAll('.multi-image-item');

		items.forEach(function (item, index) {
			var shouldDisplay = false;

			// First image always visible
			if (index === 0) {
				shouldDisplay = true;
			}
			// Second image visible at breakpointDouble
			else if (index === 1 && width >= config.breakpointDouble) {
				shouldDisplay = true;
			}
			// Third image visible at breakpointTriple
			else if (index === 2 && width >= config.breakpointTriple) {
				shouldDisplay = true;
			}

			item.setAttribute('data-show', shouldDisplay ? 'true' : 'false');
		});
	}

	/**
	 * Initialize lazy loading for background images using Intersection Observer
	 * @param {string} moduleId - The module container ID
	 * @param {Object} config - Configuration object
	 */
	function initLazyLoading(moduleId, config) {
		var module = document.getElementById(moduleId);

		if (!module || !window.IntersectionObserver) {
			// Fallback for browsers without IntersectionObserver support
			return;
		}

		var items = module.querySelectorAll('.multi-image-item');
		var observerOptions = {
			root: null,
			rootMargin: '50px', // Start loading 50px before entering viewport
			threshold: 0,
		};

		var imageObserver = new IntersectionObserver(function (entries) {
			entries.forEach(function (entry) {
				if (entry.isIntersecting) {
					var item = entry.target;
					var bgImage = item.getAttribute('data-bg-image');

					// If data-bg-image exists, apply it to the background
					if (bgImage) {
						item.style.backgroundImage = "url('" + bgImage + "')";
						item.removeAttribute('data-bg-image');
					}

					// Stop observing this item
					imageObserver.unobserve(item);
				}
			});
		}, observerOptions);

		items.forEach(function (item) {
			// Store the background image in data attribute
			var bgImage = item.style.backgroundImage;
			if (bgImage) {
				// Extract URL from background-image property
				var urlMatch = bgImage.match(/url\(['"]?([^'"]+)['"]?\)/);
				if (urlMatch && urlMatch[1]) {
					item.setAttribute('data-bg-image', urlMatch[1]);
					// Clear the background image initially
					item.style.backgroundImage = 'none';
					// Start observing
					imageObserver.observe(item);
				}
			}
		});
	}

	/**
	 * Initialize all multi-image modules on the page
	 */
	function initializeModules() {
		if (!window.modMultiImageConfig) {
			return;
		}

		// Initialize each module with its configuration
		Object.keys(window.modMultiImageConfig).forEach(function (moduleId) {
			var config = window.modMultiImageConfig[moduleId];

			// Initialize lazy loading if enabled
			if (config.lazyLoading) {
				initLazyLoading(moduleId, config);
			}

			// Update visibility on load
			updateVisibility(moduleId, config);

			// Update visibility on resize with debouncing
			var resizeTimer;
			window.addEventListener('resize', function () {
				clearTimeout(resizeTimer);
				resizeTimer = setTimeout(function () {
					updateVisibility(moduleId, config);
				}, 100);
			});
		});
	}

	// Run on DOM ready
	if (document.readyState === 'loading') {
		document.addEventListener('DOMContentLoaded', initializeModules);
	} else {
		initializeModules();
	}
})();
