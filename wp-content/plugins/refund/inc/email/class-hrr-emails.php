<?php

/**
 * Send Refund Email
 */
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

if (!class_exists('HR_Refund_Email')) {

    include_once(HR_REFUND_PLUGIN_PATH . '/inc/email/class-hr-email.php');

    /**
     * HR_Refund_Email Class.
     */
    class HR_Refund_Email extends HR_Email {

        /**
         * User Object
         */
        protected static $user_object;

        /**
         * User Name
         */
        public static $user_name;

        /**
         * User Email
         */
        public static $user_email;

        /**
         * Shortcodes
         */
        protected static $shortcodes = array(
            '{hr-refund.date}',
            '{hr-refund.time}',
            '{hr-refund.orderid}',
            '{hr-refund.sitename}',
            '{hr-refund.newstaus}',
            '{hr-refund.oldstaus}',
            '{hr-refund.requestid}',
            '{hr-refund.customername}',
        );

        /**
         * Format Email to Send
         */
        public static function format_email_to_send($to, $subject, $message) {
            $from_name = get_option('hr_refund_request_email_from_name');
            $from_email = get_option('hr_refund_request_email_from_email');
            $mail_template = get_option('hr_refund_request_email_type');
            $headers = self::format_email_headers($from_name, $from_email);
            $template = self::format_email_template($message, $subject, $mail_template);

            return self::hr_send_mail($to, $subject, $template, $headers, $mail_template);
        }

        /**
         * Get Email form User Id.
         */
        public static function get_email_to_address($user_id) {
            self::$user_object = get_userdata($user_id);
            if (is_object(self::$user_object)) {
                self::$user_name = self::$user_object->user_login;
                self::$user_email = self::$user_object->user_email;
                return self::$user_object->user_email;
            }

            return false;
        }

        /**
         * format to replace shortcode
         */
        public static function format_shortcode_to_replace($request_obj, $content) {
            $sitename = get_bloginfo();
            $date = hr_format_date_by_wordpress($request_obj->date);
            $time = hr_format_time_by_wordpress($request_obj->date);
            $new_status = hr_refund_post_status($request_obj->status);
            $old_status = hr_refund_post_status($request_obj->old_status);
            $user_id = $request_obj->user_id;
            $get_user_data = get_userdata ( $user_id ) ;
            $customer_name = is_object ( $get_user_data ) ?  $get_user_data->user_login : '' ;
            $replace_array = array(
                $date,
                $time,
                $request_obj->order_id,
                $sitename,
                $new_status,
                $old_status,
                $request_obj->id,
                $customer_name
            );

            return self::replace_shortcode_from_message($content, $replace_array);
        }

        /**
         * replace shortcode from message.
         */
        public static function replace_shortcode_from_message($content, $replace_array, $shortcode_array = array()) {
            if (!hr_check_is_array($shortcode_array))
                $shortcode_array = self::$shortcodes;

            return str_replace($shortcode_array, $replace_array, $content);
        }

        /**
         * Refund Request send eamil to customer
         */
        public static function refund_request_sent_email_to_customer($request_id) {
            $request_obj = hr_refund_create_request_post_obj($request_id);
            if (get_post_meta($request_obj->user_id, 'hr_refund_unsubscribed_id', true) != 'yes') {
                $enabled = get_option('hr_refund_request_sent_user_enable');
                if ($enabled == 'yes') {
                    $to = self::get_email_to_address($request_obj->user_id);
                    if ($to) {
                        $subject = get_option('hr_refund_request_sent_user_sub');
                        $message = get_option('hr_refund_request_sent_user_msg');
                        $subject = hr_get_wpml_text('hr_refund_request_sent_user_sub_wpml', $request_obj->current_language, $subject);
                        $message = hr_get_wpml_text('hr_refund_request_sent_user_msg_wpml', $request_obj->current_language, $message);
                        $subject = self::format_shortcode_to_replace($request_obj, $subject);
                        $message = self::format_shortcode_to_replace($request_obj, $message);

                        return self::format_email_to_send($to, $subject, $message);
                    }
                }
            }
            return false;
        }

        /**
         * Refund Request send email to customer
         */
        public static function refund_request_sent_email_to_admin($request_id) {
            $request_obj = hr_refund_create_request_post_obj($request_id);
            $enabled = get_option('hr_refund_request_sent_admin_enable');
            if ($enabled == 'yes') {
                self::$user_email = get_option('hr_refund_request_email_from_email');
                self::$user_name = get_option('hr_refund_request_email_from_name');
                $to = (self::$user_email) ? self::$user_email : get_option('woocommerce_email_from_address');
                if ($to) {
                    $subject = get_option('hr_refund_request_sent_admin_sub');
                    $message = get_option('hr_refund_request_sent_admin_msg');
                    $subject = hr_get_wpml_text('hr_refund_request_sent_admin_sub_wpml', 'en', $subject);
                    $message = hr_get_wpml_text('hr_refund_request_sent_admin_msg_wpml', 'en', $message);
                    $subject = self::format_shortcode_to_replace($request_obj, $subject);
                    $message = self::format_shortcode_to_replace($request_obj, $message);

                    return self::format_email_to_send($to, $subject, $message);
                }
            }
            return false;
        }

        /**
         * Refund Request Accept email to customer
         */
        public static function refund_request_accept_email_to_customer($request_id) {
            $request_obj = hr_refund_create_request_post_obj($request_id);
            if (get_post_meta($request_obj->user_id, 'hr_refund_unsubscribed_id', true) != 'yes') {
                $enabled = get_option('hr_refund_request_accept_user_enable');
                if ($enabled == 'yes') {
                    $to = self::get_email_to_address($request_obj->user_id);
                    if ($to) {
                        $subject = get_option('hr_refund_request_accept_user_sub');
                        $message = get_option('hr_refund_request_accept_user_msg');
                        $subject = hr_get_wpml_text('hr_refund_request_accept_user_sub_wpml', $request_obj->current_language, $subject);
                        $message = hr_get_wpml_text('hr_refund_request_accept_user_msg_wpml', $request_obj->current_language, $message);
                        $subject = self::format_shortcode_to_replace($request_obj, $subject);
                        $message = self::format_shortcode_to_replace($request_obj, $message);

                        return self::format_email_to_send($to, $subject, $message);
                    }
                }
            }
            return false;
        }

        /**
         * Refund Request Accept email to admin
         */
        public static function refund_request_accept_email_to_admin($request_id) {
            $request_obj = hr_refund_create_request_post_obj($request_id);
            $enabled = get_option('hr_refund_request_accept_admin_enable');
            if ($enabled == 'yes') {
                self::$user_email = get_option('hr_refund_request_email_from_email');
                self::$user_name = get_option('hr_refund_request_email_from_name');
                $to = (self::$user_email) ? self::$user_email : get_option('woocommerce_email_from_address');
                if ($to) {
                    $subject = get_option('hr_refund_request_accept_admin_sub');
                    $message = get_option('hr_refund_request_accept_admin_msg');
                    $subject = hr_get_wpml_text('hr_refund_request_accept_admin_sub_wpml', 'en', $subject);
                    $message = hr_get_wpml_text('hr_refund_request_accept_admin_msg_wpml', 'en', $message);
                    $subject = self::format_shortcode_to_replace($request_obj, $subject);
                    $message = self::format_shortcode_to_replace($request_obj, $message);

                    return self::format_email_to_send($to, $subject, $message);
                }
            }
            return false;
        }

        /**
         * Refund Request Reject email to customer
         */
        public static function refund_request_reject_email_to_customer($request_id) {
            $request_obj = hr_refund_create_request_post_obj($request_id);
            if (get_post_meta($request_obj->user_id, 'hr_refund_unsubscribed_id', true) != 'yes') {
                $enabled = get_option('hr_refund_request_reject_user_enable');
                if ($enabled == 'yes') {
                    $to = self::get_email_to_address($request_obj->user_id);
                    if ($to) {
                        $subject = get_option('hr_refund_request_reject_user_sub');
                        $message = get_option('hr_refund_request_reject_user_msg');
                        $subject = hr_get_wpml_text('hr_refund_request_reject_user_sub_wpml', $request_obj->current_language, $subject);
                        $message = hr_get_wpml_text('hr_refund_request_reject_user_msg_wpml', $request_obj->current_language, $message);
                        $subject = self::format_shortcode_to_replace($request_obj, $subject);
                        $message = self::format_shortcode_to_replace($request_obj, $message);

                        return self::format_email_to_send($to, $subject, $message);
                    }
                }
            }
            return false;
        }

        /**
         * Refund Request Reject email to admin
         */
        public static function refund_request_reject_email_to_admin($request_id) {
            $request_obj = hr_refund_create_request_post_obj($request_id);
            $enabled = get_option('hr_refund_request_reject_admin_enable');
            if ($enabled == 'yes') {
                self::$user_email = get_option('hr_refund_request_email_from_email');
                self::$user_name = get_option('hr_refund_request_email_from_name');
                $to = (self::$user_email) ? self::$user_email : get_option('woocommerce_email_from_address');
                if ($to) {
                    $subject = get_option('hr_refund_request_reject_admin_sub');
                    $message = get_option('hr_refund_request_reject_admin_msg');
                    $subject = hr_get_wpml_text('hr_refund_request_reject_admin_sub_wpml', 'en', $subject);
                    $message = hr_get_wpml_text('hr_refund_request_reject_admin_msg_wpml', 'en', $message);
                    $subject = self::format_shortcode_to_replace($request_obj, $subject);
                    $message = self::format_shortcode_to_replace($request_obj, $message);

                    return self::format_email_to_send($to, $subject, $message);
                }
            }
            return false;
        }

        /**
         * Refund Request Reject email to customer
         */
        public static function refund_request_status_change_email_to_customer($request_id) {
            $request_obj = hr_refund_create_request_post_obj($request_id);
            if (get_post_meta($request_obj->user_id, 'hr_refund_unsubscribed_id', true) != 'yes') {
                $enabled = get_option('hr_refund_request_status_change_user_enable');
                if ($enabled == 'yes') {
                    $to = self::get_email_to_address($request_obj->user_id);
                    if ($to) {
                        $subject = get_option('hr_refund_request_status_change_user_sub');
                        $message = get_option('hr_refund_request_status_change_user_msg');
                        $subject = hr_get_wpml_text('hr_refund_request_status_change_user_sub_wpml', $request_obj->current_language, $subject);
                        $message = hr_get_wpml_text('hr_refund_request_status_change_user_msg_wpml', $request_obj->current_language, $message);
                        $subject = self::format_shortcode_to_replace($request_obj, $subject);
                        $message = self::format_shortcode_to_replace($request_obj, $message);

                        return self::format_email_to_send($to, $subject, $message);
                    }
                }
            }
            return false;
        }

        /**
         * Refund Request Reject email to admin
         */
        public static function refund_request_status_change_email_to_admin($request_id) {
            $request_obj = hr_refund_create_request_post_obj($request_id);
            $enabled = get_option('hr_refund_request_status_change_admin_enable');
            if ($enabled == 'yes') {
                self::$user_email = get_option('hr_refund_request_email_from_email');
                self::$user_name = get_option('hr_refund_request_email_from_name');
                $to = (self::$user_email) ? self::$user_email : get_option('woocommerce_email_from_address');
                if ($to) {
                    $subject = get_option('hr_refund_request_status_change_admin_sub');
                    $message = get_option('hr_refund_request_status_change_admin_msg');
                    $subject = hr_get_wpml_text('hr_refund_request_status_change_admin_sub_wpml', 'en', $subject);
                    $message = hr_get_wpml_text('hr_refund_request_status_change_admin_msg_wpml', 'en', $message);
                    $subject = self::format_shortcode_to_replace($request_obj, $subject);
                    $message = self::format_shortcode_to_replace($request_obj, $message);

                    return self::format_email_to_send($to, $subject, $message);
                }
            }
            return false;
        }

    }

}
    