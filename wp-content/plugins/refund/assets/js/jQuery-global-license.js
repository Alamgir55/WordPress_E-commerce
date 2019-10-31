/* global hr_license_params */

jQuery(function ($) {

    var HR_License_Tab = {

        init: function () {
            $(document).on('click', '#hr_activate_handler_button', this.activation_handler);
        },
        activation_handler: function (event) {
            event.preventDefault();
            var key = $('#hr_license_key').val();
            if (key == '') {
                $('.hr_activation_messages').html(hr_license_params.license_empty_message);
                return false;
            }

            var data = {
                action: 'hrr_activate_handler',
                license_key: key,
                handler: $('#hr_activate_handler_value').val(),
                hr_security: hr_license_params.license_security
            };
            HR_License_Tab.block('.hr_license_information');
            $.post(ajaxurl, data, function (response) {
                if (true === response.success) {
                    $('.hr_activation_messages').html(response.data.success_msg);
                    HR_License_Tab.unblock('.hr_license_information');
                    window.location.reload();
                } else {
                    $('.hr_activation_messages').html(response.data.error_msg);
                    HR_License_Tab.unblock('.hr_license_information');
                }
            });
        },
        block: function (id) {
            $(id).block({
                message: null,
                overlayCSS: {
                    background: '#fff',
                    opacity: 0.6
                }
            });
        },
        unblock: function (id) {
            $(id).unblock();
        },
    };
    HR_License_Tab.init();
});
