<?php

/* Advanced Tab Settings */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

if (!class_exists('HR_Refund_Email_Tab')) {

    /**
     * HR_Refund_Email_Tab Class.
     */
    class HR_Refund_Email_Tab {

        /**
         * HR_Refund_Email_Tab Class initialization.
         */
        public static function init() {
            add_action('woocommerce_hr_refund_settings_hrrefundemail', array(__CLASS__, 'hr_refund_display_admin_settings'));
        }

        /**
         * Prepare setting Array to display.
         */
        public static function hr_refund_prepare_admin_settings_array() {
            return apply_filters('woocommerce_hrrefundemail_settings', array(
                array(
                    'type' => 'hoicker_section_start',
                ),
                array(
                    'name' => __('Email Settings', HR_REFUND_LOCALE),
                    'type' => 'title',
                    'id' => 'hr_refund_email_settings'
                ),
                array(
                    'name' => __('Email Type', HR_REFUND_LOCALE),
                    'type' => 'select',
                    'options' => array('woo' => __('Woocommerce Template', HR_REFUND_LOCALE), 'html' => __('HTML Template', HR_REFUND_LOCALE)),
                    'id' => 'hr_refund_request_email_type',
                    'newids' => 'hr_refund_request_email_type',
                    'desc' => __(' If "HTML Template" is selected, plain text emails will be sent. If "WooCommerce Template" is selected, email will be sent with customization made in WooCommerce Email', HR_REFUND_LOCALE),
                    'desc_tip' => true,
                ),
                array(
                    'name' => __('From Name', HR_REFUND_LOCALE),
                    'type' => 'text',
                    'id' => 'hr_refund_request_email_from_name',
                    'newids' => 'hr_refund_request_email_from_name',
                    'desc' => __('Sender name for refund emails', HR_REFUND_LOCALE),
                    'desc_tip' => true,
                    'default' => get_option('woocommerce_email_from_name'),
                    'std' => get_option('woocommerce_email_from_name'),
                    
                ),
                array(
                    'name' => __('From Email', HR_REFUND_LOCALE),
                    'type' => 'text',
                    'id' => 'hr_refund_request_email_from_email',
                    'newids' => 'hr_refund_request_email_from_email',
                    'desc' => __('Sender email for refund emails', HR_REFUND_LOCALE),
                    'desc_tip' => true,
                    'default' => get_option('woocommerce_email_from_address'),
                    'std' => get_option('woocommerce_email_from_address'),
                ),
                array(
                    'name' => __('Show Unsubscription Option', HR_REFUND_LOCALE),
                    'type' => 'checkbox',
                    'id' => 'hr_refund_request_enable_unsubscription',
                    'newids' => 'hr_refund_request_enable_unsubscription',
                    'class' => 'hr_premium_settings',
                    'default' => 'no',
                    'std' => 'no',
                     'desc' => __('If enabled, a checkbox will be displayed on "My Account Page" for the user to unsubscribe', HR_REFUND_LOCALE),
                    'desc_tip' => true,
                ),
                array(
                    'name' => __('Customize Unsubscription Heading', HR_REFUND_LOCALE),
                    'type' => 'text',
                    'id' => 'hr_refund_request_customize_unsub_heading',
                    'newids' => 'hr_refund_request_customize_unsub_heading',
                    'class' => 'hr_refund_unsubscription hr_premium_settings',
                    'std' => 'Unsubscription Settings',
                    'default' => 'Unsubscription Settings',
                    'desc' => __(' Sender email for wallet emails', HR_REFUND_LOCALE),
                    'desc_tip' => true,
                ),
                array(
                    'name' => __('Customize Unsubscription Text', HR_REFUND_LOCALE),
                    'css' => 'min-height:250px;min-width:400px;',
                    'id' => 'hr_refund_request_customize_unsub_text',
                    'std' => 'Unsubscribe here to stop receiving Refund Emails',
                    'default' => 'Unsubscribe here to stop receiving Refund Emails',
                    'type' => 'textarea',
                    'class' => 'hr_refund_unsubscription hr_premium_settings',
                    'newids' => 'hr_refund_request_customize_unsub_text',
                ),
                array(
                    'type' => 'sectionend',
                    'id' => 'hr_refund_email_settings'
                ),
                array(
                    'type' => 'hoicker_section_close',
                ),
                array(
                    'type' => 'hoicker_section_start',
                ),
                array(
                    'name' => __('New Refund Request'.' - '.' Notification Email', HR_REFUND_LOCALE),
                    'type' => 'title',
                    'id' => 'hr_refund_request_sent'
                ),
                array(
                    'name' => __('Notify Customer', HR_REFUND_LOCALE),
                    'type' => 'checkbox',
                    'id' => 'hr_refund_request_sent_user_enable',
                    'newids' => 'hr_refund_request_sent_user_enable',
                    'default' => 'no',
                    'std' => 'no',
                ),
                array(
                    'name' => __('Email Subject', HR_REFUND_LOCALE),
                    'type' => 'text',
                    'class' => 'hr_refund_request_sent_user',
                    'id' => 'hr_refund_request_sent_user_sub',
                    'newids' => 'hr_refund_request_sent_user_sub',
                    'std' => 'Refund Request Submitted on {hr-refund.sitename}',
                    'default' => 'Refund Request Submitted on {hr-refund.sitename}',
                ),
                array(
                    'name' => __('Email Message', HR_REFUND_LOCALE),
                    'css' => 'min-height:250px;min-width:400px;',
                    'class' => 'hr_refund_request_sent_user',
                    'id' => 'hr_refund_request_sent_user_msg',
                    'std' => 'Your Refund Request {hr-refund.requestid} for Order {hr-refund.orderid} has been submitted successfully on {hr-refund.sitename} at {hr-refund.date} {hr-refund.time}',
                    'default' => 'Your Refund Request {hr-refund.requestid} for Order {hr-refund.orderid} has been submitted successfully on {hr-refund.sitename} at {hr-refund.date} {hr-refund.time}',
                    'type' => 'textarea',
                    'newids' => 'hr_refund_request_sent_user_msg',
                ),
                array(
                    'name' => __('Notify Admin', HR_REFUND_LOCALE),
                    'type' => 'checkbox',
                    'id' => 'hr_refund_request_sent_admin_enable',
                    'newids' => 'hr_refund_request_sent_admin_enable',
                    'default' => 'no',
                    'std' => 'no',
                ),
                array(
                    'name' => __('Email Subject', HR_REFUND_LOCALE),
                    'type' => 'text',
                    'class' => 'hr_refund_request_sent_admin',
                    'id' => 'hr_refund_request_sent_admin_sub',
                    'newids' => 'hr_refund_request_sent_admin_sub',
                    'std' => 'New Refund Request on {hr-refund.sitename}',
                    'default' => 'New Refund Request on {hr-refund.sitename}',
                ),
                array(
                    'name' => __('Email Message', HR_REFUND_LOCALE),
                    'css' => 'min-height:250px;min-width:400px;',
                    'class' => 'hr_refund_request_sent_admin',
                    'id' => 'hr_refund_request_sent_admin_msg',
                    'std' => 'Customer {hr-refund.customername} has submitted a Refund Request {hr-refund.requestid} for Order {hr-refund.orderid} on {hr-refund.sitename} at {hr-refund.date} {hr-refund.time}',
                    'default' => 'Customer {hr-refund.customername} has submitted a Refund Request {hr-refund.requestid} for Order {hr-refund.orderid} on {hr-refund.sitename} at {hr-refund.date} {hr-refund.time}',
                    'type' => 'textarea',
                    'newids' => 'hr_refund_request_sent_admin_msg',
                ),
                array(
                    'type' => 'sectionend',
                    'id' => 'hr_refund_request_sent'
                ),
                array(
                    'type' => 'hoicker_section_close',
                ),
                array(
                    'type' => 'hoicker_section_start',
                ),
                array(
                    'name' => __('Refund Conversation'.' - '.' Notification Email', HR_REFUND_LOCALE),
                    'type' => 'title',
                    'id' => 'hr_refund_request_reply_receive'
                ),
                array(
                    'name' => __('Notify Customer', HR_REFUND_LOCALE),
                    'type' => 'checkbox',
                    'class' => 'hr_premium_settings',
                    'id' => 'hr_refund_request_reply_receive_user_enable',
                    'newids' => 'hr_refund_request_reply_receive_user_enable',
                    'default' => 'no',
                    'std' => 'no',
                ),
                array(
                    'name' => __('Email Subject', HR_REFUND_LOCALE),
                    'type' => 'text',
                    'class' => 'hr_refund_request_reply_receive_user hr_premium_settings',
                    'id' => 'hr_refund_request_reply_receive_user_sub',
                    'newids' => 'hr_refund_request_reply_receive_user_sub',
                    'std' => 'Reply regarding with Refund Request on {hr-refund.sitename}',
                    'default' => 'Reply regarding with Refund Request on {hr-refund.sitename}',
                ),
                array(
                    'name' => __('Email Message', HR_REFUND_LOCALE),
                    'css' => 'min-height:250px;min-width:400px;',
                    'class' => 'hr_refund_request_reply_receive_user hr_premium_settings',
                    'id' => 'hr_refund_request_reply_receive_user_msg',
                    'std' => 'You have got a reply from site Admin on {hr-refund.sitename} at {hr-refund.date} {hr-refund.time} regarding with your Refund Request {hr-refund.requestid} for Order {hr-refund.orderid}',
                    'default' => 'You have got a reply from site Admin on {hr-refund.sitename} at {hr-refund.date} {hr-refund.time} regarding with your Refund Request {hr-refund.requestid} for Order {hr-refund.orderid}',
                    'type' => 'textarea',
                    'newids' => 'hr_refund_request_reply_receive_user_msg',
                ),
                array(
                    'name' => __('Notify Admin', HR_REFUND_LOCALE),
                    'type' => 'checkbox',
                    'class' => 'hr_premium_settings',
                    'id' => 'hr_refund_request_reply_receive_admin_enable',
                    'newids' => 'hr_refund_request_reply_receive_admin_enable',
                    'default' => 'no',
                    'std' => 'no',
                ),
                array(
                    'name' => __('Email Subject', HR_REFUND_LOCALE),
                    'type' => 'text',
                    'class' => 'hr_refund_request_reply_receive_admin hr_premium_settings',
                    'id' => 'hr_refund_request_reply_receive_admin_sub',
                    'newids' => 'hr_refund_request_reply_receive_admin_sub',
                    'std' => 'Reply regarding with Refund Request on {hr-refund.sitename}',
                    'default' => 'Reply regarding with Refund Request on {hr-refund.sitename}',
                ),
                array(
                    'name' => __('Email Message', HR_REFUND_LOCALE),
                    'css' => 'min-height:250px;min-width:400px;',
                    'class' => 'hr_refund_request_reply_receive_admin hr_premium_settings',
                    'id' => 'hr_refund_request_reply_receive_admin_msg',
                    'std' => 'You have got a reply from Customer {hr-refund.customername} on {hr-refund.sitename} at {hr-refund.date} {hr-refund.time} regarding with Refund Request {hr-refund.requestid} for Order {hr-refund.orderid}',
                    'default' => 'You have got a reply from Customer {hr-refund.customername} on {hr-refund.sitename} at {hr-refund.date} {hr-refund.time} regarding with Refund Request {hr-refund.requestid} for Order {hr-refund.orderid}',
                    'type' => 'textarea',
                    'newids' => 'hr_refund_request_reply_receive_admin_msg',
                ),
                array(
                    'type' => 'sectionend',
                    'id' => 'hr_refund_request_reply_receive'
                ),
                array(
                    'type' => 'hoicker_section_close',
                ),
                array(
                    'type' => 'hoicker_section_start',
                ),
                array(
                    'name' => __('Refund Request Accepted'.' - '.' Notification Email', HR_REFUND_LOCALE),
                    'type' => 'title',
                    'id' => 'hr_refund_request_accept'
                ),
                array(
                    'name' => __('Notify Customer', HR_REFUND_LOCALE),
                    'type' => 'checkbox',
                    'id' => 'hr_refund_request_accept_user_enable',
                    'newids' => 'hr_refund_request_accept_user_enable',
                    'default' => 'no',
                    'std' => 'no',
                ),
                array(
                    'name' => __('Email Subject', HR_REFUND_LOCALE),
                    'type' => 'text',
                    'class' => 'hr_refund_request_accept_user',
                    'id' => 'hr_refund_request_accept_user_sub',
                    'newids' => 'hr_refund_request_accept_user_sub',
                    'std' => 'Refund Request Accepted on {hr-refund.sitename}',
                    'default' => 'Refund Request Accepted on {hr-refund.sitename}',
                ),
                array(
                    'name' => __('Email Message', HR_REFUND_LOCALE),
                    'css' => 'min-height:250px;min-width:400px;',
                    'class' => 'hr_refund_request_accept_user',
                    'id' => 'hr_refund_request_accept_user_msg',
                    'std' => 'Your Refund Request {hr-refund.requestid} for Order {hr-refund.orderid} has been Accepted by site Admin on {hr-refund.sitename} at {hr-refund.date} {hr-refund.time}',
                    'default' => 'Your Refund Request {hr-refund.requestid} for Order {hr-refund.orderid} has been Accepted by site Admin on {hr-refund.sitename} at {hr-refund.date} {hr-refund.time}',
                    'type' => 'textarea',
                    'newids' => 'hr_refund_request_accept_user_msg',
                ),
                array(
                    'name' => __('Notify Admin', HR_REFUND_LOCALE),
                    'type' => 'checkbox',
                    'id' => 'hr_refund_request_accept_admin_enable',
                    'newids' => 'hr_refund_request_accept_admin_enable',
                    'default' => 'no',
                    'std' => 'no',
                ),
                array(
                    'name' => __('Email Subject', HR_REFUND_LOCALE),
                    'type' => 'text',
                    'class' => 'hr_refund_request_accept_admin',
                    'id' => 'hr_refund_request_accept_admin_sub',
                    'newids' => 'hr_refund_request_accept_admin_sub',
                    'std' => 'Refund Request Accepted on {hr-refund.sitename}',
                    'default' => 'Refund Request Accepted on {hr-refund.sitename}',
                ),
                array(
                    'name' => __('Email Message', HR_REFUND_LOCALE),
                    'css' => 'min-height:250px;min-width:400px;',
                    'class' => 'hr_refund_request_accept_admin',
                    'id' => 'hr_refund_request_accept_admin_msg',
                    'std' => 'Refund Request {hr-refund.requestid} for Order {hr-refund.orderid} by Customer {hr-refund.customername} has been Accepted on {hr-refund.sitename} at {hr-refund.date} {hr-refund.time}',
                    'default' => 'Refund Request {hr-refund.requestid} for Order {hr-refund.orderid} by Customer {hr-refund.customername} has been Accepted on {hr-refund.sitename} at {hr-refund.date} {hr-refund.time}',
                    'type' => 'textarea',
                    'newids' => 'hr_refund_request_accept_admin_msg',
                ),
                array(
                    'type' => 'sectionend',
                    'id' => 'hr_refund_request_accept'
                ),
                array(
                    'type' => 'hoicker_section_close',
                ),
                array(
                    'type' => 'hoicker_section_start',
                ),
                array(
                    'name' => __('Refund Request Rejected'.' - '.' Notification Email', HR_REFUND_LOCALE),
                    'type' => 'title',
                    'id' => 'hr_refund_request_reject'
                ),
                array(
                    'name' => __('Notify Customer', HR_REFUND_LOCALE),
                    'type' => 'checkbox',
                    'id' => 'hr_refund_request_reject_user_enable',
                    'newids' => 'hr_refund_request_reject_user_enable',
                    'default' => 'no',
                    'std' => 'no',
                ),
                array(
                    'name' => __('Email Subject', HR_REFUND_LOCALE),
                    'type' => 'text',
                    'class' => 'hr_refund_request_reject_user',
                    'id' => 'hr_refund_request_reject_user_sub',
                    'newids' => 'hr_refund_request_reject_user_sub',
                    'std' => 'Refund Request Rejected on {hr-refund.sitename}',
                    'default' => 'Refund Request Rejected on {hr-refund.sitename}',
                ),
                array(
                    'name' => __('Email Message', HR_REFUND_LOCALE),
                    'css' => 'min-height:250px;min-width:400px;',
                    'class' => 'hr_refund_request_reject_user',
                    'id' => 'hr_refund_request_reject_user_msg',
                    'std' => 'Your Refund Request {hr-refund.requestid} for Order {hr-refund.orderid} has been Rejected by site Admin on {hr-refund.sitename} at {hr-refund.date} {hr-refund.time}',
                    'default' => 'Your Refund Request {hr-refund.requestid} for Order {hr-refund.orderid} has been Rejected by site Admin on {hr-refund.sitename} at {hr-refund.date} {hr-refund.time}',
                    'type' => 'textarea',
                    'newids' => 'hr_refund_request_reject_user_msg',
                ),
                array(
                    'name' => __('Notify Admin', HR_REFUND_LOCALE),
                    'type' => 'checkbox',
                    'id' => 'hr_refund_request_reject_admin_enable',
                    'newids' => 'hr_refund_request_reject_admin_enable',
                    'default' => 'no',
                    'std' => 'no',
                ),
                array(
                    'name' => __('Email Subject', HR_REFUND_LOCALE),
                    'type' => 'text',
                    'class' => 'hr_refund_request_reject_admin',
                    'id' => 'hr_refund_request_reject_admin_sub',
                    'newids' => 'hr_refund_request_reject_admin_sub',
                    'std' => 'Refund Request Rejected on {hr-refund.sitename}',
                    'default' => 'Refund Request Rejected on {hr-refund.sitename}',
                ),
                array(
                    'name' => __('Email Message', HR_REFUND_LOCALE),
                    'css' => 'min-height:250px;min-width:400px;',
                    'class' => 'hr_refund_request_reject_admin',
                    'id' => 'hr_refund_request_reject_admin_msg',
                    'std' => 'Refund Request {hr-refund.requestid} for Order {hr-refund.orderid} by Customer {hr-refund.customername} has been Rejected on {hr-refund.sitename} at {hr-refund.date} {hr-refund.time}',
                    'default' => 'Refund Request {hr-refund.requestid} for Order {hr-refund.orderid} by Customer {hr-refund.customername} has been Rejected on {hr-refund.sitename} at {hr-refund.date} {hr-refund.time}',
                    'type' => 'textarea',
                    'newids' => 'hr_refund_request_reject_admin_msg',
                ),
                array(
                    'type' => 'sectionend',
                    'id' => 'hr_refund_request_reject'
                ),
                array(
                    'type' => 'hoicker_section_close',
                ),
                array(
                    'type' => 'hoicker_section_start',
                ),
                array(
                    'name' => __('Refund Request Status Update'.' - '.' Notification Email', HR_REFUND_LOCALE),
                    'type' => 'title',
                    'id' => 'hr_refund_request_status_change'
                ),
                array(
                    'name' => __('Notify Customer', HR_REFUND_LOCALE),
                    'type' => 'checkbox',
                    'id' => 'hr_refund_request_status_change_user_enable',
                    'newids' => 'hr_refund_request_status_change_user_enable',
                    'default' => 'no',
                    'std' => 'no',
                ),
                array(
                    'name' => __('Email Subject', HR_REFUND_LOCALE),
                    'type' => 'text',
                    'class' => 'hr_refund_request_status_change_user',
                    'id' => 'hr_refund_request_status_change_user_sub',
                    'newids' => 'hr_refund_request_status_change_user_sub',
                    'std' => 'Refund Request is {hr-refund.newstaus} on {hr-refund.sitename}',
                    'default' => 'Refund Request is {hr-refund.newstaus} on {hr-refund.sitename}',
                ),
                array(
                    'name' => __('Email Message', HR_REFUND_LOCALE),
                    'css' => 'min-height:250px;min-width:400px;',
                    'class' => 'hr_refund_request_status_change_user',
                    'id' => 'hr_refund_request_status_change_user_msg',
                    'std' => 'Your Refund Request {hr-refund.requestid} for Order {hr-refund.orderid} is now {hr-refund.newstaus} on {hr-refund.sitename} at {hr-refund.date} {hr-refund.time}',
                    'default' => 'Your Refund Request {hr-refund.requestid} for Order {hr-refund.orderid} is now {hr-refund.newstaus} on {hr-refund.sitename} at {hr-refund.date} {hr-refund.time}',
                    'type' => 'textarea',
                    'newids' => 'hr_refund_request_status_change_user_msg',
                ),
                array(
                    'name' => __('Notify Admin', HR_REFUND_LOCALE),
                    'type' => 'checkbox',
                    'id' => 'hr_refund_request_status_change_admin_enable',
                    'newids' => 'hr_refund_request_status_change_admin_enable',
                    'default' => 'no',
                    'std' => 'no',
                ),
                array(
                    'name' => __('Email Subject', HR_REFUND_LOCALE),
                    'type' => 'text',
                    'class' => 'hr_refund_request_status_change_admin',
                    'id' => 'hr_refund_request_status_change_admin_sub',
                    'newids' => 'hr_refund_request_status_change_admin_sub',
                    'std' => 'Refund Request is {hr-refund.newstaus} on {hr-refund.sitename}',
                    'default' => 'Refund Request is {hr-refund.newstaus} on {hr-refund.sitename}',
                ),
                array(
                    'name' => __('Email Message', HR_REFUND_LOCALE),
                    'css' => 'min-height:250px;min-width:400px;',
                    'class' => 'hr_refund_request_status_change_admin',
                    'id' => 'hr_refund_request_status_change_admin_msg',
                    'std' => 'Refund Request {hr-refund.requestid} by Customer {hr-refund.customername} for Order {hr-refund.orderid} is now {hr-refund.newstaus} on {hr-refund.sitename} at {hr-refund.date} {hr-refund.time}',
                    'default' => 'Refund Request {hr-refund.requestid} by Customer {hr-refund.customername} for Order {hr-refund.orderid} is now {hr-refund.newstaus} on {hr-refund.sitename} at {hr-refund.date} {hr-refund.time}',
                    'type' => 'textarea',
                    'newids' => 'hr_refund_request_status_change_admin_msg',
                ),
                array(
                    'type' => 'sectionend',
                    'id' => 'hr_refund_request_status_change'
                ),
                array(
                    'type' => 'hoicker_section_close',
                ),
            ));
        }

        /**
         * Display tab Settings
         */
        public static function hr_refund_display_admin_settings() {
            woocommerce_admin_fields(HR_Refund_Email_Tab::hr_refund_prepare_admin_settings_array());
        }

    }

    HR_Refund_Email_Tab::init();
}