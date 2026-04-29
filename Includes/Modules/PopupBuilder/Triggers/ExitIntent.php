<?php
namespace SuperElemPro\Modules\PopupBuilder\Triggers;

if (!defined('ABSPATH'))
    exit;

/**
 * Exit Intent Trigger
 * 
 * @since 1.0.0
 */
class ExitIntent
{

    public function __construct()
    {
        add_action('wp_footer', [$this, 'add_exit_intent_script'], 999);
    }

    public function add_exit_intent_script()
    {
        ?>
        <script>
            (function ($) {
                'use strict';

                let exitIntentTriggered = false;

                $(document).on('mouseleave', function (e) {
                    if (e.clientY < 50 && !exitIntentTriggered) {
                        exitIntentTriggered = true;

                        $('.sep-popup[data-triggers*="exit_intent"]').each(function () {
                            const popup = $(this);
                            if (!popup.data('sep-shown')) {
                                sepShowPopup(popup);
                            }
                        });
                    }
                });

            })(jQuery);
        </script>
        <?php
    }
}