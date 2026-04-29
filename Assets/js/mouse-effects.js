(function ($) {
    'use strict';

    $(window).on('elementor/frontend/init', function () {
        elementorFrontend.hooks.addAction('frontend/element_ready/global', function ($scope) {
            var $element = $scope;
            var settings = $element.data('settings');

            // Mouse Track
            if (settings && settings.sep_mouse_track) {
                var speed = settings.sep_mouse_track_speed ? settings.sep_mouse_track_speed.size : 10;

                $(document).on('mousemove', function (e) {
                    var x = (e.pageX - $(window).width() / 2) / speed;
                    var y = (e.pageY - $(window).height() / 2) / speed;

                    $element.css('transform', 'translate(' + x + 'px, ' + y + 'px)');
                });
            }

            // 3D Tilt
            if (settings && settings.sep_tilt_effect) {
                var intensity = settings.sep_tilt_intensity ? settings.sep_tilt_intensity.size : 15;

                $element.on('mousemove', function (e) {
                    var rect = this.getBoundingClientRect();
                    var x = e.clientX - rect.left;
                    var y = e.clientY - rect.top;

                    var centerX = rect.width / 2;
                    var centerY = rect.height / 2;

                    var rotateX = (y - centerY) / centerY * intensity;
                    var rotateY = (centerX - x) / centerX * intensity;

                    $(this).css('transform', 'perspective(1000px) rotateX(' + rotateX + 'deg) rotateY(' + rotateY + 'deg)');
                });

                $element.on('mouseleave', function () {
                    $(this).css('transform', 'perspective(1000px) rotateX(0) rotateY(0)');
                });
            }
        });
    });

})(jQuery);