(function ($) {
    'use strict';

    $(window).on('elementor/frontend/init', function () {
        elementorFrontend.hooks.addAction('frontend/element_ready/global', function ($scope) {
            var $element = $scope;
            var settings = $element.data('settings');

            if (!settings || !settings.sep_sticky) {
                return;
            }

            var offset = settings.sep_sticky_offset || 0;
            var elementOffset = $element.offset().top;

            $(window).on('scroll', function () {
                var scrollTop = $(window).scrollTop();

                if (scrollTop >= (elementOffset - offset)) {
                    $element.css({
                        'position': 'fixed',
                        'top': offset + 'px',
                        'z-index': 999,
                        'width': '100%'
                    });
                } else {
                    $element.css({
                        'position': '',
                        'top': '',
                        'z-index': '',
                        'width': ''
                    });
                }
            });
        });
    });

})(jQuery);