(function ($) {
    'use strict';

    $(document).ready(function () {
        $('.sep-popup').each(function () {
            var $popup = $(this);
            var triggers = $popup.data('triggers') || {};

            // Page Load Trigger
            if (triggers.on_load) {
                var delay = triggers.on_load_delay || 0;
                setTimeout(function () {
                    if (typeof window.sepShowPopup === 'function') {
                        window.sepShowPopup($popup);
                    }
                }, delay * 1000);
            }

            // Scroll Depth Trigger
            if (triggers.on_scroll) {
                var depth = triggers.on_scroll_depth || 50;
                var triggered = false;
                $(window).on('scroll', function () {
                    if (triggered) return;
                    var scrollPercent = 100 * $(window).scrollTop() / ($(document).height() - $(window).height());
                    if (scrollPercent >= depth) {
                        triggered = true;
                        if (typeof window.sepShowPopup === 'function') {
                            window.sepShowPopup($popup);
                        }
                    }
                });
            }

            // Exit Intent Trigger
            if (triggers.exit_intent) {
                var exitTriggered = false;
                $(document).on('mouseleave', function (e) {
                    if (exitTriggered) return;
                    if (e.clientY < 0) {
                        exitTriggered = true;
                        if (typeof window.sepShowPopup === 'function') {
                            window.sepShowPopup($popup);
                        }
                    }
                });
            }

            // On Click Trigger
            if (triggers.on_click && triggers.on_click_selector) {
                $(document).on('click', triggers.on_click_selector, function (e) {
                    e.preventDefault();
                    if (typeof window.sepShowPopup === 'function') {
                        window.sepShowPopup($popup);
                    }
                });
            }
        });
    });

})(jQuery);