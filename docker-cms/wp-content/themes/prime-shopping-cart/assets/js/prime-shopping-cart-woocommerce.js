/**
 * WooCommerce Frontend Enhancements
 *
 * Handles:
 * - Custom select dropdown styling.
 * - Quantity increment/decrement controls.
 *
 * Developer Notes:
 * - Uses delegated events for AJAX compatibility.
 * - Supports WooCommerce dynamic fragments.
 * - Prevents quantity values below min or above max.
 * - Automatically falls back to sensible defaults.
 *
 * @package prime-shopping-cart
 */

;(function ($) {

	'use strict';

	/**
	 * DOM Ready
	 */
	$(function () {

		/* ==========================================
		   Custom WooCommerce Select
		========================================== */

		if ( $('.woocommerce-ordering .orderby,table.variations select').length ) {
			$('.woocommerce-ordering .orderby,table.variations select').customSelect();
		}

		/* ==========================================
		   Quantity Buttons
		========================================== */

		/**
		 * Ensure quantity starts from minimum value.
		 */
		$('input.qty:not(.product-quantity input.qty)').each(function () {

			const min = parseFloat($(this).attr('min'));

			if ( min && min > 0 && parseFloat($(this).val()) < min ) {
				$(this).val(min);
			}
		});

		/**
		 * Quantity Increase / Decrease
		 */
		$(document).on('click', '.qty-plus, .qty-minus', function () {

			const $qty = $(this).closest('.quantity').find('.qty');

			let currentVal = parseFloat($qty.val()),
				max        = parseFloat($qty.attr('max')),
				min        = parseFloat($qty.attr('min')),
				step       = parseFloat($qty.attr('step'));

			/* ------------------------------
			   Fallback Values
			------------------------------ */

			if ( isNaN(currentVal) ) {
				currentVal = 0;
			}

			if ( isNaN(max) ) {
				max = false;
			}

			if ( isNaN(min) ) {
				min = 0;
			}

			if ( isNaN(step) || step === 0 ) {
				step = 1;
			}

			/* ------------------------------
			   Increase Quantity
			------------------------------ */

			if ( $(this).hasClass('qty-plus') ) {

				if ( max && currentVal >= max ) {
					$qty.val(max);
				} else {
					$qty.val(currentVal + step);
				}

			} else {

				/* ------------------------------
				   Decrease Quantity
				------------------------------ */

				if ( currentVal > min ) {
					$qty.val(currentVal - step);
				} else {
					$qty.val(min);
				}
			}

			/**
			 * Trigger WooCommerce update events.
			 */
			$qty.trigger('change');
		});

	var $summaryPrice = $('.summary .price');
    var defaultPrice  = $summaryPrice.html(); // store original price

    /*
    |--------------------------------------------------------------------------
    | When variation is found
    |--------------------------------------------------------------------------
    */
    $('form.variations_form').on('found_variation', function (event, variation) {

        if (variation && variation.price_html) {
            $summaryPrice.html(variation.price_html);
        }

    });

    /*
    |--------------------------------------------------------------------------
    | When variation is reset (back to default)
    |--------------------------------------------------------------------------
    */
    $('form.variations_form').on('reset_data', function () {

        $summaryPrice.html(defaultPrice);

    });


	});

})(jQuery);