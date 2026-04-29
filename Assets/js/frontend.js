(function ($) {
    'use strict';

    // Global popup functions
    window.sepShowPopup = function (popup) {
        var animationIn = popup.data('animation-in') || 'fadeIn';
        popup.removeClass('sep-popup-hidden').addClass('sep-popup-visible ' + animationIn);
        popup.data('sep-shown', true);

        // Close handlers
        popup.find('.sep-popup-close, .sep-popup-overlay').on('click', function () {
            sepClosePopup(popup);
        });
    };

    window.sepClosePopup = function (popup) {
        var animationOut = popup.data('animation-out') || 'fadeOut';
        popup.removeClass('sep-popup-visible').addClass(animationOut);

        setTimeout(function () {
            popup.addClass('sep-popup-hidden').removeClass(animationOut);
        }, 500);
    };

    // Form submission
    $(document).on('submit', '.sep-advanced-form', function (e) {
        e.preventDefault();

        var $form = $(this);
        var formData = new FormData($form[0]);
        var fields = [];

        $form.find('.sep-field').each(function () {
            var $field = $(this);
            var name = $field.attr('name');
            var label = $field.closest('.sep-form-field').find('label').text().replace('*', '').trim();
            var value = $field.val();
            var type = $field.attr('type') || 'text';

            if (name && value) {
                fields.push({
                    name: name,
                    label: label,
                    value: value,
                    type: type
                });
            }
        });

        $.ajax({
            url: sepConfig.ajaxUrl,
            type: 'POST',
            data: {
                action: 'sep_form_submit',
                nonce: sepConfig.nonce,
                form_id: $form.data('form-id'),
                fields: fields
            },
            beforeSend: function () {
                $form.find('.sep-submit-btn').prop('disabled', true);
                $form.find('.sep-btn-text').hide();
                $form.find('.sep-btn-loader').show();
            },
            success: function (response) {
                $form.find('.sep-form-messages').html(
                    '<div class="sep-message sep-success">' + response.data.message + '</div>'
                );
                $form[0].reset();
            },
            error: function () {
                $form.find('.sep-form-messages').html(
                    '<div class="sep-message sep-error">An error occurred. Please try again.</div>'
                );
            },
            complete: function () {
                $form.find('.sep-submit-btn').prop('disabled', false);
                $form.find('.sep-btn-text').show();
                $form.find('.sep-btn-loader').hide();
            }
        });
    });

})(jQuery);