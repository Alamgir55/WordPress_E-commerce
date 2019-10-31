/* global hr_premium_args */
jQuery(function ($) {

    var HRW_Premium_Info = {
        init: function ( ) {
            this.trigger_on_page_load( );
            $(document).on('click', 'div.hidden-input', this.hr_disabled_premium_settings);
        },
        trigger_on_page_load: function () {
            $(".hr_premium_settings").attr('disabled', 'disabled');
            var hide_input = '<div class="hidden-input"></div>';
            
            if ($(".hr_premium_settings").closest('td').length > 0) {
                $(".hr_premium_settings").closest('td').append(hide_input);
            }
            if ($(".hr_premium_settings").closest('div.hr_trigger_td').length > 0) {
                $(".hr_premium_settings").closest('div.hr_trigger_td').append(hide_input);
            }

        },
        hr_disabled_premium_settings: function (event) {
            event.preventDefault();

            var $this = $(event.currentTarget);
            $('div#hr_premium_message').remove();
            $($this).after('<div class="hr_premium_message" id="hr_premium_message">' + hr_premium_args.hr_premium_info_html + '</div>');
        }
    };
    HRW_Premium_Info.init( );
});
