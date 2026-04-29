(function ($) {
    'use strict';

    $(window).on('elementor/frontend/init', function () {
        elementorFrontend.hooks.addAction('frontend/element_ready/global', function ($scope) {
            var $element = $scope;
            var settings = $element.data('settings');

            if (!settings || !settings.sep_motion_effects) {
                return;
            }

            $(window).on('scroll', function () {
                var scrolled = $(window).scrollTop();
                var offsetTop = $element.offset().top;
                var diff = scrolled - offsetTop;

                var transforms = [];

                if (settings.sep_translateY_speed) {
                    var y = diff * settings.sep_translateY_speed.size;
                    transforms.push('translateY(' + y + 'px)');
                }

                if (settings.sep_translateX_speed) {
                    var x = diff * settings.sep_translateX_speed.size;
                    transforms.push('translateX(' + x + 'px)');
                }

                if (settings.sep_scale_speed) {
                    var scale = 1 + (diff * settings.sep_scale_speed.size / 100);
                    transforms.push('scale(' + scale + ')');
                }

                if (settings.sep_rotate_speed) {
                    var rotate = diff * settings.sep_rotate_speed.size;
                    transforms.push('rotate(' + rotate + 'deg)');
                }

                if (transforms.length > 0) {
                    $element.css('transform', transforms.join(' '));
                }

                if (settings.sep_opacity_speed) {
                    var opacity = 1 - (diff * settings.sep_opacity_speed.size / 100);
                    opacity = Math.max(0, Math.min(1, opacity));
                    $element.css('opacity', opacity);
                }
            });
        });
    });

})(jQuery);