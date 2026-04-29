jQuery(document).ready(function ($) {
    'use strict';

    // Tab switching
    $('.sep-nav-tabs .nav-tab').on('click', function (e) {
        e.preventDefault();
        var tab = $(this).attr('href').replace('#', '');

        $('.nav-tab').removeClass('nav-tab-active');
        $(this).addClass('nav-tab-active');

        $('.sep-tab-content').removeClass('active');
        $('#tab-' + tab).addClass('active');
    });
});