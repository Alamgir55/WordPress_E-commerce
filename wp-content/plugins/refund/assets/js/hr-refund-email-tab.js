/* Email Tab */
jQuery(function ($) {

    var HRR_Email_Tab = {
        init: function () {

            this.trigger_on_page_load();

            $(document).on('change', '#hr_refund_request_enable_unsubscription', this.toggle_get_enable_unsubscription_option);
            $(document).on('change', '#hr_refund_request_sent_user_enable', this.toggle_get_enable_request_sent_user_option);
            $(document).on('change', '#hr_refund_request_sent_admin_enable', this.toggle_get_enable_request_sent_admin_option);
            $(document).on('change', '#hr_refund_request_reply_receive_user_enable', this.toggle_get_enable_request_reply_receive_user_option);
            $(document).on('change', '#hr_refund_request_reply_receive_admin_enable', this.toggle_get_enable_request_reply_receive_admin_option);
            $(document).on('change', '#hr_refund_request_accept_user_enable', this.toggle_get_enable_request_accept_user_option);
            $(document).on('change', '#hr_refund_request_accept_admin_enable', this.toggle_get_enable_request_accept_admin_option);
            $(document).on('change', '#hr_refund_request_reject_user_enable', this.toggle_get_enable_request_reject_user_option);
            $(document).on('change', '#hr_refund_request_reject_admin_enable', this.toggle_get_enable_request_reject_admin_option);
            $(document).on('change', '#hr_refund_request_status_change_user_enable', this.toggle_get_enable_request_status_change_user_option);
            $(document).on('change', '#hr_refund_request_status_change_admin_enable', this.toggle_get_enable_request_status_change_admin_option);

        },
        trigger_on_page_load: function () {
            this.get_enable_unsubscription_option('#hr_refund_request_enable_unsubscription');
            this.get_enable_request_sent_user_option('#hr_refund_request_sent_user_enable');
            this.get_enable_request_sent_admin_option('#hr_refund_request_sent_admin_enable');
            this.get_enable_request_reply_receive_user_option('#hr_refund_request_reply_receive_user_enable');
            this.get_enable_request_reply_receive_admin_option('#hr_refund_request_reply_receive_admin_enable');
            this.get_enable_request_accept_user_option('#hr_refund_request_accept_user_enable');
            this.get_enable_request_accept_admin_option('#hr_refund_request_accept_admin_enable');
            this.get_enable_request_reject_user_option('#hr_refund_request_reject_user_enable');
            this.get_enable_request_reject_admin_option('#hr_refund_request_reject_admin_enable');
            this.get_enable_request_status_change_user_option('#hr_refund_request_status_change_user_enable');
            this.get_enable_request_status_change_admin_option('#hr_refund_request_status_change_admin_enable');
        },
        toggle_get_enable_unsubscription_option: function (event) {
            event.preventDefault();
            var $this = $(event.currentTarget);
            HRR_Email_Tab.get_enable_unsubscription_option($this);
        },
        toggle_get_enable_request_sent_user_option: function (event) {
            event.preventDefault();
            var $this = $(event.currentTarget);
            HRR_Email_Tab.get_enable_request_sent_user_option($this);
        },
        toggle_get_enable_request_sent_admin_option: function (event) {
            event.preventDefault();
            var $this = $(event.currentTarget);
            HRR_Email_Tab.get_enable_request_sent_admin_option($this);
        },
        toggle_get_enable_request_reply_receive_user_option: function (event) {
            event.preventDefault();
            var $this = $(event.currentTarget);
            HRR_Email_Tab.get_enable_request_reply_receive_user_option($this);
        },
        toggle_get_enable_request_reply_receive_admin_option: function (event) {
            event.preventDefault();
            var $this = $(event.currentTarget);
            HRR_Email_Tab.get_enable_request_reply_receive_admin_option($this);
        },
        toggle_get_enable_request_accept_user_option: function (event) {
            event.preventDefault();
            var $this = $(event.currentTarget);
            HRR_Email_Tab.get_enable_request_accept_user_option($this);
        },
        toggle_get_enable_request_accept_admin_option: function (event) {
            event.preventDefault();
            var $this = $(event.currentTarget);
            HRR_Email_Tab.get_enable_request_accept_admin_option($this);
        },
        toggle_get_enable_request_reject_user_option: function (event) {
            event.preventDefault();
            var $this = $(event.currentTarget);
            HRR_Email_Tab.get_enable_request_reject_user_option($this);
        },
        toggle_get_enable_request_reject_admin_option: function (event) {
            event.preventDefault();
            var $this = $(event.currentTarget);
            HRR_Email_Tab.get_enable_request_reject_admin_option($this);
        },
        toggle_get_enable_request_status_change_user_option: function (event) {
            event.preventDefault();
            var $this = $(event.currentTarget);
            HRR_Email_Tab.get_enable_request_status_change_user_option($this);
        },
        toggle_get_enable_request_status_change_admin_option: function (event) {
            event.preventDefault();
            var $this = $(event.currentTarget);
            HRR_Email_Tab.get_enable_request_status_change_admin_option($this);
        },
        get_enable_unsubscription_option: function ($this) {
            if ($($this).is(":checked")) {
                $('.hr_refund_unsubscription').closest('tr').show();
            } else {
                $('.hr_refund_unsubscription').closest('tr').hide();
            }
        },
        get_enable_request_sent_user_option: function ($this) {
            if ($($this).is(":checked")) {
                $('.hr_refund_request_sent_user').closest('tr').show();
            } else {
                $('.hr_refund_request_sent_user').closest('tr').hide();
            }
        },
        get_enable_request_sent_admin_option: function ($this) {
            if ($($this).is(":checked")) {
                $('.hr_refund_request_sent_admin').closest('tr').show();
            } else {
                $('.hr_refund_request_sent_admin').closest('tr').hide();
            }
        },
        get_enable_request_reply_receive_user_option: function ($this) {
            if ($($this).is(":checked")) {
                $('.hr_refund_request_reply_receive_user').closest('tr').show();
            } else {
                $('.hr_refund_request_reply_receive_user').closest('tr').hide();
            }
        },
        get_enable_request_reply_receive_admin_option: function ($this) {
            if ($($this).is(":checked")) {
                $('.hr_refund_request_reply_receive_admin').closest('tr').show();
            } else {
                $('.hr_refund_request_reply_receive_admin').closest('tr').hide();
            }
        },
        get_enable_request_accept_user_option: function ($this) {
            if ($($this).is(":checked")) {
                $('.hr_refund_request_accept_user').closest('tr').show();
            } else {
                $('.hr_refund_request_accept_user').closest('tr').hide();
            }
        },
        get_enable_request_accept_admin_option: function ($this) {
            if ($($this).is(":checked")) {
                $('.hr_refund_request_accept_admin').closest('tr').show();
            } else {
                $('.hr_refund_request_accept_admin').closest('tr').hide();
            }
        },
        get_enable_request_reject_user_option: function ($this) {
            if ($($this).is(":checked")) {
                $('.hr_refund_request_reject_user').closest('tr').show();
            } else {
                $('.hr_refund_request_reject_user').closest('tr').hide();
            }
        },
        get_enable_request_reject_admin_option: function ($this) {
            if ($($this).is(":checked")) {
                $('.hr_refund_request_reject_admin').closest('tr').show();
            } else {
                $('.hr_refund_request_reject_admin').closest('tr').hide();
            }
        },
        get_enable_request_status_change_user_option: function ($this) {
            if ($($this).is(":checked")) {
                $('.hr_refund_request_status_change_user').closest('tr').show();
            } else {
                $('.hr_refund_request_status_change_user').closest('tr').hide();
            }
        },
        get_enable_request_status_change_admin_option: function ($this) {
            if ($($this).is(":checked")) {
                $('.hr_refund_request_status_change_admin').closest('tr').show();
            } else {
                $('.hr_refund_request_status_change_admin').closest('tr').hide();
            }
        }
    };
    HRR_Email_Tab.init();
});
