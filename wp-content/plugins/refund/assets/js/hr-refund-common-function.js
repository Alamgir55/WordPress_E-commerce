/* global hr_refund_common_obj */

jQuery(function ($) {

    var HRR_Common_Function = {
        init: function () {
            this.trigger_on_page_load();
        },
        trigger_on_page_load: function () {
            HRR_Common_Function.initialize_select_event();
        },
        initialize_select_event: function () {
            if (hr_refund_common_obj.hr_refund_wc_version <= parseFloat('2.2.0')) {
                $('#hr_refund_include_user_role_srch').chosen();
                $('#hr_refund_include_categories_srch').chosen();
                $('#hr_refund_buttons_by_order_status').chosen();
            } else {
                $('#hr_refund_include_user_role_srch').select2();
                $('#hr_refund_include_categories_srch').select2();
                $('#hr_refund_buttons_by_order_status').select2();
            }
        },
    };
    HRR_Common_Function.init();
});
