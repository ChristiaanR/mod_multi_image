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
	 * Update visibility of images based on viewport width
	 * @param {string} moduleId - The module container ID
	 * @param {Object} config - Configuration with breakpoint values
	 */
	/*
	function updateVisibility(moduleId, config) {
		var width = window.innerWidth;
		var module = document.getElementById(moduleId);

		if (!module) {
			return;
		}

		var items = module.querySelectorAll('.multi-image-item');

		items.forEach(function (item, index) {
			var show = item.getAttribute('data-show');

			if (index === 0) {
				// First image always visible
				item.style.display = '';
			} else if (show === 'double' && width >= config.breakpointDouble) {
				item.style.display = '';
			} else if (show === 'triple' && width >= config.breakpointTriple) {
				item.style.display = '';
			} else if (index > 0) {
				item.style.display = 'none';
			}
		});
	}
*/
	function updateVisibility(moduleId, config) {
		var width = window.innerWidth;
		var module = document.getElementById(moduleId);

		if (!module) {
			return;
		}

		var items = module.querySelectorAll('.multi-image-item');

		items.forEach(function (item, index) {
			// Haal de waarde op van het 'data-show' attribuut.
			var showValue = item.getAttribute('data-show');

			// Converteer de stringwaarde ('true' of 'false') naar een boolean.
			// Dit is de nieuwe boolean die bepaalt of het item getoond moet worden
			// op basis van de oude 'double'/'triple' logica.
			// We gaan ervan uit dat je deze al hebt ingesteld in de HTML.
			var shouldShow = showValue === 'true';

			// 1. Eerste afbeelding (index 0) altijd zichtbaar.
			if (index === 0) {
				item.style.display = '';

				// 2. Gebruik de nieuwe boolean 'shouldShow'.
				//    Als shouldShow 'true' is, toon het item.
			} else if (shouldShow) {
				item.style.display = '';

				// 3. Voor alle andere items (index > 0) die NIET getoond moeten worden (shouldShow is 'false').
			} else if (index > 0) {
				item.style.display = 'none';
			}
		});
	}
	/* -----------------------------------*/

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
