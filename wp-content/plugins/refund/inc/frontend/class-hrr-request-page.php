<?php

/**
 * Refund Request Page
 */
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

if (!class_exists('HR_Refund_Request_Page')) {

    /**
     * HR_Refund_Request_Page Class.
     */
    class HR_Refund_Request_Page {

        /**
         * Refund Requests endpoint name.
         */
        public static $hr_refund_requests_endpoint = 'hr-refund-requests';

        /**
         * Refund Request Form endpoint name.
         */
        public static $hr_refund_requests_form_endpoint = 'hr-refund-request-form';

        /**
         * Refund Request View endpoint name.
         */
        public static $hr_refund_request_view_endpoint = 'hr-refund-request-view';

        /**
         * HR_Refund_Request_Page Class initialization.
         */
        public static function init() {
            add_filter('the_title', array(__CLASS__, 'hr_refund_endpoint_title'));
            add_action('init', array(__CLASS__, 'hr_refund_add_custom_end_point'));
            add_filter('query_vars', array(__CLASS__, 'hr_refund_add_custom_query_vars'), 0);
            add_action('wp_loaded', array(__CLASS__, 'hr_refund_flush_rewrite_rules'));
            add_action('wp_ajax_hr_refund_request', array(__CLASS__, 'hr_refund_request_save_data'));
            add_filter('woocommerce_account_menu_items', array(__CLASS__, 'hr_refund_add_custom_myaccount_menu'));
            add_action('woocommerce_account_' . self::$hr_refund_request_view_endpoint . '_endpoint', array(__CLASS__, 'hr_refund_request_view_content'));
            add_action('woocommerce_account_' . self::$hr_refund_requests_form_endpoint . '_endpoint', array(__CLASS__, 'hr_refund_request_form_content'));
            add_action('woocommerce_account_' . self::$hr_refund_requests_endpoint . '_endpoint', array(__CLASS__, 'hr_refund_custom_my_account_menu_content'));
        }

        /**
         * Rewrite Refund Endpoint
         */
        public static function hr_refund_add_custom_end_point() {
            add_rewrite_endpoint(self::$hr_refund_requests_endpoint, EP_ROOT | EP_PAGES);
            add_rewrite_endpoint(self::$hr_refund_requests_form_endpoint, EP_ROOT | EP_PAGES);
            add_rewrite_endpoint(self::$hr_refund_request_view_endpoint, EP_ROOT | EP_PAGES);
        }

        /**
         * Add custom Query var for Refund 
         */
        public static function hr_refund_add_custom_query_vars($vars) {
            $vars[] = self::$hr_refund_requests_endpoint;
            $vars[] = self::$hr_refund_request_view_endpoint;
            $vars[] = self::$hr_refund_requests_form_endpoint;

            return $vars;
        }

        /**
         * Flush Rewrite Rules 
         */
        public static function hr_refund_flush_rewrite_rules() {
            flush_rewrite_rules();
        }

        /**
         * Flush Rewrite Rules 
         */
        public static function hr_refund_endpoint_title($title) {
            global $wp_query;

            $refund_requests = isset($wp_query->query_vars[self::$hr_refund_requests_endpoint]);
            $refund_request_form = isset($wp_query->query_vars[self::$hr_refund_requests_form_endpoint]);
            $refund_request_view = isset($wp_query->query_vars[self::$hr_refund_request_view_endpoint]);

            if ($refund_requests && is_main_query() && in_the_loop() && is_account_page()) {
                $title = __('My Refund Requests', HR_REFUND_LOCALE);
                remove_filter('the_title', array('HR_Refund_Request_Page', 'endpoint_title'));
            } elseif ($refund_request_form && is_main_query() && in_the_loop() && is_account_page()) {
                $title = __('Refund Request Form', HR_REFUND_LOCALE);
                remove_filter('the_title', array('HR_Refund_Request_Page', 'endpoint_title'));
            } elseif ($refund_request_view && is_main_query() && in_the_loop() && is_account_page()) {
                $request_id = !empty($wp_query->query_vars['hr-refund-request-view']) ? $wp_query->query_vars['hr-refund-request-view'] : '';
                $title = sprintf(__('Refund Request #%s', HR_REFUND_LOCALE), $request_id);
                remove_filter('the_title', array('HR_Refund_Request_Page', 'endpoint_title'));
            }
            return $title;
        }

        /**
         * Add Custom My account Menu
         */
        public static function hr_refund_add_custom_myaccount_menu($items) {

            $logout = $items['customer-logout'];
            unset($items['customer-logout']);
            $label = __('Refund Request', HR_REFUND_LOCALE);
            $items[self::$hr_refund_requests_endpoint] = $label;
            $items['customer-logout'] = $logout;

            return $items;
        }

        /**
         * Display Custom Menu Content
         */
        public static function hr_refund_custom_my_account_menu_content() {
            $template_path = HRREFUND()->hr_refund_template_path();
            $args = array(
                'posts_per_page' => -1,
                'post_type' => 'hr_refund_request',
                'post_status' => array('hr-refund-new', 'hr-refund-accept', 'hr-refund-reject', 'hr-refund-on-hold', 'hr-refund-processing'),
                'order' => 'DESC',
                'author' => get_current_user_id(),
                'fields' => 'ids'
            );
            $request_data = hr_get_posts($args);
            //display refund request table
            wc_get_template('myaccount/refund-request-table.php', array('request_data' => $request_data), HR_REFUND_FOLDER_NAME, $template_path);
        }

        /**
         * Display Refund request View Content
         */
        public static function hr_refund_request_view_content() {
            global $wp_query;
            $request_id = !empty($wp_query->query_vars['hr-refund-request-view']) ? $wp_query->query_vars['hr-refund-request-view'] : '';
            $template_path = HRREFUND()->hr_refund_template_path();
            //display refund request table
            wc_get_template('myaccount/refund-request-view.php', array('request_id' => $request_id), HR_REFUND_FOLDER_NAME, $template_path);
        }

        /**
         * Display Refund request Form Content
         */
        public static function hr_refund_request_form_content() {
            global $wp_query;
            $order_id = !empty($wp_query->query_vars['hr-refund-request-form']) ? $wp_query->query_vars['hr-refund-request-form'] : '';
            $template_path = HRREFUND()->hr_refund_template_path();
            $order = hr_get_order_obj($order_id);
            //display refund request table
            wc_get_template('myaccount/refund-request-form.php', array('order_id' => $order_id, 'order' => $order), HR_REFUND_FOLDER_NAME, $template_path);
        }

        /**
         * Create Refund Request Post
         */
        public static function hr_refund_request_save_data() {
            check_ajax_referer('hr-refund-request', 'hr_security');

            if (isset($_POST)) {
                $bool = false;
                $item_count = '0';
                $total_qty_send = '0';
                $refund_item_count = '0';
                $user_id = stripslashes($_POST['hr_refund_user_id']);
                $order_id = stripslashes($_POST['hr_refund_order_id']);
                $request_as = stripslashes($_POST['hr_refund_request_as']);
                $details = stripslashes($_POST['hr_refund_form_details']);
                $reasons = stripslashes($_POST['hr_refund_general_reasons']);
                $refund_amount = stripslashes($_POST['hr_refund_total']);
                $user = get_userdata($user_id);
                $order = hr_get_order_obj($order_id);
                $user_email = is_object($user) ? $user->user_email : '';
                $username = is_object($user) ? $user->display_name : '';
                $order_items = $order->get_items();
                $line_item_ids = json_decode(sanitize_text_field(stripslashes($_POST['line_item_ids'])), true);
                $line_items = json_decode(sanitize_text_field(stripslashes($_POST['line_items'])), true);

                foreach ($order_items as $item_id => $item) {
                    $already_item_send = wc_get_order_item_meta($item_id, 'hr_refund_request_item');
                    $qty_send = (int) wc_get_order_item_meta($item_id, 'hr_refund_request_item_qty');
                    $original_quantity = isset($item['quantity']) ? $item['quantity'] : $item['qty'];
                    $item_count += $original_quantity;
                    if ($already_item_send == 'yes')
                        $total_qty_send += $qty_send;

                    if (!isset($line_items[$item_id]))
                        continue;

                    $refund_item_count += $line_items[$item_id];
                }

                if ((($item_count - $total_qty_send) - $refund_item_count) <= 0)
                    $bool = true;

                $type = hrr_refund_request_type($order, $item_count, $refund_item_count, $refund_amount);

                $post_meta = array(
                    'hr_refund_order_id' => $order_id,
                    'hr_refund_user_details' => $user_id,
                    'hr_refund_request_as' => $request_as,
                    'hr_refund_request_type' => $type,
                    'hr_refund_request_date' => current_time('timestamp'),
                    'hr_refund_user_name' => $username,
                    'hr_refund_line_items' => $line_items,
                    'hr_refund_line_item_ids' => $line_item_ids,
                    'hr_refund_user_email' => $user_email,
                    'hr_refund_request_total' => $refund_amount,
                    'hr_refund_current_language' => hr_get_order_obj_data($order, 'currency'),
                    'hr_refund_request_old_status' => 'h-refund-new'
                );

                $post_defaults = array(
                    'user_id' => $user_id,
                    'post_title' => $reasons,
                    'post_content' => $details,
                );

                if ($request_id = hr_insert_refund_request_post($post_meta, $post_defaults)) {
                    foreach ($order_items as $item_id => $item) {
                        if (!isset($line_items[$item_id]))
                            continue;

                        wc_update_order_item_meta($item_id, 'hr_refund_request_item', 'yes');
                        $prevoius_qty = (int) wc_get_order_item_meta($item_id, 'hr_refund_request_item_qty');
                        wc_update_order_item_meta($item_id, 'hr_refund_request_item_qty', $prevoius_qty + $line_items[$item_id]);
                    }

                    if ($type == 'Whole Order' || $bool)
                        update_post_meta($order_id, 'hr_refund_request_already_send', $request_id);


                    HR_Refund_Email::refund_request_sent_email_to_customer($request_id);
                    HR_Refund_Email::refund_request_sent_email_to_admin($request_id);
                    echo '1';
                }
            }
            exit();
        }

    }

    HR_Refund_Request_Page::init();
}