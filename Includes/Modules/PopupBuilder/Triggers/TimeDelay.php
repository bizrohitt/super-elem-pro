<?php
namespace SuperElemPro\Modules\PopupBuilder\Triggers;

if (!defined('ABSPATH'))
    exit;

/**
 * Time Delay Trigger
 * 
 * @since 1.0.0
 */
class TimeDelay
{

    public function __construct()
    {
        add_action('wp_footer', [$this, 'add_time_delay_script'], 999);
    }

    public function add_time_delay_script()
    {
        ?>
        <script>
            (function ($) {
                'use strict';

                $('.sep-popup[data-triggers*="time_delay"]').each(function () {
                    const popup = $(this);
                    const triggers = popup.data('triggers');

                    if (triggers.time_delay) {
                        setTimeout(function () {
                            if (!popup.data('sep-shown')) {
                                sepShowPopup(popup);
                            }
                        }, triggers.time_delay.seconds * 1000);
                    }
                });

            })(jQuery);
        </script>
        <?php
    }
}