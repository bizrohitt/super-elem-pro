<?php
namespace SuperElemPro\Modules\PopupBuilder\Triggers;

if (!defined('ABSPATH'))
    exit;

/**
 * On Click Trigger
 * 
 * @since 1.0.0
 */
class OnClick
{

    public function __construct()
    {
        add_action('wp_footer', [$this, 'add_click_script'], 999);
    }

    public function add_click_script()
    {
        ?>
        <script>
            (function ($) {
                'use strict';

                // Support for custom click triggers
                // Usage: Add class "sep-popup-trigger" and data-popup-id="123" to any element
                $(document).on('click', '[data-sep-popup]', function (e) {
                    e.preventDefault();
                    const popupId = $(this).data('sep-popup');
                    const popup = $('#sep-popup-' + popupId);

                    if (popup.length) {
                        sepShowPopup(popup);
                    }
                });

            })(jQuery);
        </script>
        <?php
    }
}