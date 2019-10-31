<?php

/**
 * Sned Email
 */
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

if (!class_exists('HR_Email')) {

    /**
     * HR_Email Class.
     */
    class HR_Email {

        protected static $from_email_address;
        protected static $from_name;
        public static $sending;

        /**
         * Format Email Header
         */
        public static function format_email_headers($from_name = '', $from_email = '', $reply_to = false, $bcc = false) {
            $headers = '';
            //header charset
            $headers .= "Content-Type: text/html; charset=UTF-8\r\n";

            self::$from_name = !empty($from_name) ? $from_name : get_option('woocommerce_email_from_name');
            self::$from_email_address = !empty($from_email) ? $from_email : get_option('woocommerce_email_from_address');

            $headers .= "From: " . self::$from_name . " <" . self::$from_email_address . ">\r\n";

            //header for reply to
            if ($reply_to)
                $headers .= "Reply-To: " . self::$from_name . " <" . self::$from_email_address . ">\r\n";

            //header BCC.
            if ($bcc)
                $headers .= "Bcc: " . $bcc . "\r\n";

            return apply_filters('hr_email_headers', $headers, self::$from_name, self::$from_email_address);
        }

        /**
         * Format Email Template
         */
        public static function format_email_template($message, $subject, $mail_template = '', $logo = false) {

            if (($mail_template == 'woo')) {
                ob_start();
                if (function_exists('wc_get_template')) {
                    wc_get_template('emails/email-header.php', array('email_heading' => $subject));
                    echo $message;
                    wc_get_template('emails/email-footer.php');
                } else {
                    woocommerce_get_template('emails/email-header.php', array('email_heading' => $subject));
                    echo $message;
                    woocommerce_get_template('emails/email-footer.php');
                }
                $woo_temp_msg = ob_get_clean();
            } elseif ($mail_template == 'plain') {
                $woo_temp_msg = $logo . $message;
            } else {
                $woo_temp_msg = $message;
            }

            return apply_filters('hr_format_email_template', $woo_temp_msg, $message, $subject);
        }

        /*
         * Send Email
         */

        public static function hr_send_mail($to, $subject, $temp_msg, $headers, $html_template = 'woo') {
            global $woocommerce;

            //This hook for email return path header
            add_action('phpmailer_init', array('HR_Email', 'hr_phpmailer_init'), 10, 1);
            self::$sending = true;
            if ($html_template == 'woo') {
                self::send_email_via_woocommerce_mailer($to, $subject, $temp_msg, $headers);
                return true;
            } else {
                return wp_mail($to, $subject, $temp_msg, $headers);
            }
        }

        /*
         * Send Email Via Woocmmerce Template
         */

        public static function send_email_via_woocommerce_mailer($to, $subject, $message, $headers) {
            add_filter('woocommerce_email_from_address', array('HR_Email', 'alter_from_email_of_woocommerce'), 10, 2);
            add_filter('woocommerce_email_from_name', array('HR_Email', 'alter_from_name_of_woocommerce'), 10, 2);
            $mailer = WC()->mailer();
            $return = $mailer->send($to, $subject, $message, $headers, '');
            remove_filter('woocommerce_email_from_address', array('HR_Email', 'alter_from_email_of_woocommerce'), 10, 2);
            remove_filter('woocommerce_email_from_name', array('HR_Email', 'alter_from_name_of_woocommerce'), 10, 2);
            self::$from_email_address = false;
            self::$from_name = false;

            return $return;
        }

        /*
         * Alter From Email
         */

        public static function alter_from_email_of_woocommerce($from_email, $object) {
            $get_email_address = self::$from_email_address;
            if ($get_email_address)
                return '<' . $get_email_address . '>';

            return $from_email;
        }

        /*
         * Alter From Address
         */

        public static function alter_from_name_of_woocommerce($from_name, $object) {
            $get_from_email_name = self::$from_name;
            if ($get_from_email_name)
                return $get_from_email_name;

            return $from_name;
        }

        // Return path Header
        public static function hr_phpmailer_init($phpmailerobj) {
            if (self::$sending) {
                $phpmailerobj->Sender = self::$from_email_address;
                self::$sending = false;
            }
        }

    }

}
    