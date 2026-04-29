(function ($) {
    'use strict';

    function isInViewport($element, offset) {
        var elementTop = $element.offset().top;
        var elementBottom = elementTop + $element.outerHeight();
        var viewportTop = $(window).scrollTop();
        var viewportBottom = viewportTop + $(window).height();
        var triggerPoint = viewportBottom - ($(window).height() * (offset / 100));

        return elementTop < triggerPoint;
    }

    $(window).on('elementor/frontend/init', function () {
        elementorFrontend.hooks.addAction('frontend/element_ready/global', function ($scope) {
            var $element = $scope;
            var settings = $element.data('settings');

            if (!settings || !settings.sep_scroll_animation) {
                return;
            }

            $element.css('opacity', '0');

            function checkScroll() {
                if (isInViewport($element, settings.sep_animation_offset ? settings.sep_animation_offset.size : 80)) {
                    var delay = settings.sep_animation_delay || 0;
                    var duration = settings.sep_animation_duration || 1000;

                    setTimeout(function () {
                        $element.css({
                            'opacity': '1',
                            'animation-name': settings.sep_scroll_animation,
                            'animation-duration': duration + 'ms',
                            'animation-fill-mode': 'both'
                        });
                    }, delay);

                    $(window).off('scroll.sepAnimation');
                }
            }

            $(window).on('scroll.sepAnimation', checkScroll);
            checkScroll();
        });
    });

})(jQuery);