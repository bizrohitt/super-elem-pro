<?php
namespace SuperElemPro\Modules\PopupBuilder\Triggers;

if (!defined('ABSPATH'))
    exit;

/**
 * Scroll Depth Trigger
 * 
 * @since 1.0.0
 */
class ScrollDepth
{

    public function __construct()
    {
        add_action('wp_footer', [$this, 'add_scroll_script'], 999);
    }

    public function add_scroll_script()
    {
        ?>
        <script>
            (function ($) {
                'use strict';

                $(window).on('scroll', function () {
                    const scrollTop = $(window).scrollTop();
                    const docHeight = $(document).height();
                    const winHeight = $(window).height();
                    const scrollPercent = (scrollTop / (docHeight - winHeight)) * 100;

                    $('.sep-popup[data-triggers*="scroll"]').each(function () {
                        const popup = $(this);
                        const triggers = popup.data('triggers');

                        if (triggers.scroll && scrollPercent >= triggers.scroll.percentage) {
                            if (!popup.data('sep-shown')) {
                                sepShowPopup(popup);
                            }
                        }
                    });
                });

            })(jQuery);
        </script>
        <?php
    }
}