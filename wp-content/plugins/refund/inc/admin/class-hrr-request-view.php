<?php
/**
 * Refund Request View Post Type.
 */
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

if (!class_exists('HR_Refund_Request_View')) {

    /**
     * HR_Refund_Request_View Class
     */
    class HR_Refund_Request_View {

        /**
         * HR_Refund_Request_View Class initialization
         */
        public static function init() {
            add_action('admin_init', array(__CLASS__, 'hr_refund_remove_editor_and_title'));
            add_action('add_meta_boxes', array(__CLASS__, 'hr_refund_add_meta_boxes'), 10, 2);
            add_action('wp_ajax_hr_refund_change_status', array(__CLASS__, 'hr_refund_change_status'));
            add_action('wp_ajax_hr_refund_request_line_items', array(__CLASS__, 'hr_refund_do_refund_request'));
        }

        /**
         * Remove Editor and Title Meta boxes
         */
        public static function hr_refund_remove_editor_and_title() {
            $remove_field_array = array('editor', 'title');
            foreach ($remove_field_array as $remove_field) {
                remove_post_type_support('hr_refund_request', $remove_field);
            }
        }

        /**
         * Add Required Meta Boxes
         */
        public static function hr_refund_add_meta_boxes($post_type, $post) {
            if ($post_type == 'hr_refund_request') {
                //remove submit button 
                remove_meta_box('submitdiv', 'hr_refund_request', 'side');
                add_meta_box('hr_refund_table', __('Refund Request', HR_REFUND_LOCALE), array(__CLASS__, 'hr_refund_reason_table'), 'hr_refund_request', 'normal');
                add_meta_box('hr_refund_conversation', __('Refund Request Conversation', HR_REFUND_LOCALE), array(__CLASS__, 'hr_refund_display_request_conversation'), 'hr_refund_request', 'normal');

                add_meta_box('hr_refund_request_data', __('Refund Data', HR_REFUND_LOCALE), array(__CLASS__, 'hr_refund_request_data'), 'hr_refund_request', 'side', 'low');
                add_meta_box('hr_refund_submit_button', __('Refund Status', HR_REFUND_LOCALE), array(__CLASS__, 'hr_refund_status_changes'), 'hr_refund_request', 'side', 'high');
            }
        }

        /**
         * Refund Reason Table
         */
        public static function hr_refund_reason_table($post) {
            if (!$post)
                return;

            $template_path = HRREFUND()->hr_refund_template_path();
            $request = hr_refund_create_request_post_obj($post->ID);
            $order = hr_get_order_obj($request->order_id);
            if ($order) {
                //display refund request table
                wc_get_template('admin/refund-request-table.php', array('order' => $order, 'request' => $request), HR_REFUND_FOLDER_NAME, $template_path);
            } else {
                echo '<h1>' . __("Order Data is not available", HR_REFUND_LOCALE) . '</h1>';
            }
        }

        /**
         * Refund Status change
         */
        public static function hr_refund_status_changes($post) {
            if (!$post)
                return;
            ?>
            <div class="hr_refund_status_change">
                <ul>
                    <li class="wide" id='major-publishing-actions'>
                        <p>
                            <span style="background: #ffff99;font-size: 120%;">
                                <?php _e('Current Status'); ?>: <b><?php echo hr_refund_get_post_status($post->ID); ?></b>
                            </span>
                        </p>
                    </li>
                    <li class="wide" id="hr-refund-actions">
                        <div class="submitbox" id="submitpost">
                            <div id='major-publishing-actions'>
                                <div id="publishing-action">
                                    <select id='hr_refund_status' name='hr_refund_status' style="width:150px;">
                                        <option value=""><?php _e('Actions', HR_REFUND_LOCALE); ?></option>
                                        <option value="hr-refund-reject"><?php _e('Rejected', HR_REFUND_LOCALE); ?></option>
                                        <option value="hr-refund-processing"><?php _e('Processing', HR_REFUND_LOCALE); ?></option>
                                        <option value="hr-refund-on-hold"><?php _e('On-Hold', HR_REFUND_LOCALE); ?></option>
                                    </select>
                                    <?php submit_button(__('Update', HR_REFUND_LOCALE), 'primary large', 'submit', false); ?>
                                </div>
                                <div class="clear"></div>
                            </div>
                        </div>
                    </li>
                </ul>
            </div>
            <?php
        }

        /**
         * Refund Request Conversation
         */
        public static function hr_refund_display_request_conversation($post) {
            if (!$post)
                return;
            ?>
            <style>
                .hr_refund_reply_request_tickets{
                    padding: 30px;
                    border-color: #dddddd;
                    border-style: solid;
                    overflow:scroll; 
                    max-height: 500px;
                } 
                .hr_refund_reply_ticket{
                    margin-bottom: 30px;
                    border-color: #dddddd;
                    border-style: solid;
                    padding-top: 25px;
                    padding-right: 25px;
                    padding-bottom: 30px;
                    padding-left: 25px;
                    width: auto;
                    word-wrap:break-word;
                }
                .hr_refund_admin{
                    background-color: #9fc69f;
                }
                .hr_refund_customer{
                    background-color: #b6b6d3;
                }
                .hr_refund_request_created{
                    background-color: #c5dae2;
                }
            </style>
            <div class="hr_refund_reply_request">
                <div class="hr_refund_reply_request_tickets">
                    <div class="hr_refund_request_created hr_refund_reply_ticket">
                        <?php
                        $user_obj = get_userdata($post->post_author);
                        ?>
                        <div class="hr_refund_avator">
                            <a href="<?php echo admin_url('user-edit.php') . "?user_id=" . $post->post_author; ?>"><?php echo get_avatar($post->post_author, '50'); ?></a>
                        </div>
                        <div class="hr_refund_creator_name">
                            <?php echo "Created by: " . $user_obj->display_name . " (" . $user_obj->user_email . ")"; ?>
                        </div>
                        <div class="hr_refund_created_on" title="<?php echo $post->post_date; ?>">
                            <?php echo "Created on: $post->post_date (" . human_time_diff(strtotime($post->post_date), (current_time('timestamp'))) . " ago)"; ?>
                        </div>
                        <?php do_action('hr_refund_system_before_request_content', $post); ?>
                        <hr>
                        <h1><?php echo $post->post_title; ?></h1>
                        <div class="hr_refund_request_content">
                            <?php
                            $find_array = array('<?', '?>');
                            $replace_array = array('&lt;?', '?&gt;');
                            echo wpautop(str_replace($find_array, $replace_array, $post->post_content), true);
                            ?>
                        </div>
                    </div>
                    <?php
                    $children_object = hr_refund_request_children_posts($post->ID);
                    if (hr_check_is_array($children_object)) {
                        foreach ($children_object as $eachobject) {
                            echo self::display_formatted_reply_layout($eachobject);
                        }
                    }
                    ?>
                </div>
                <?php do_action('hrr_after_admin_converation'); ?>
            </div>
            <?php
        }

        /**
         * Display request Data
         */
        public static function hr_refund_request_data($post) {
            if (!$post)
                return;

            $template_path = HRREFUND()->hr_refund_template_path();
            $request = hr_refund_create_request_post_obj($post->ID);
            $order = hr_get_order_obj($request->order_id);

            if ($order) {
                //display refund request table
                wc_get_template('admin/refund-request-data.php', array('order' => $order, 'request' => $request), HR_REFUND_FOLDER_NAME, $template_path);
            } else {
                echo '<h3>' . __("Order Data is not available", HR_REFUND_LOCALE) . '</h3>';
            }
        }

        /**
         * Format reply layout
         */
        public static function display_formatted_reply_layout($post_object) {
            ob_start();
            $current_user_id = $post_object->post_author;
            $user_obj = get_userdata($current_user_id);
            $firstname = $user_obj->display_name != '' ? $user_obj->display_name : $user_obj->user_login;
            $admin_message_class = (user_can($user_obj, 'manage_woocommerce')) ? 'hr_refund_admin' : 'hr_refund_customer';
            ?>
            <div class="hr_refund_reply_ticket hr_refund_reply_ticket<?php echo $post_object->ID . ' ' . $admin_message_class; ?>" >
                <div class="hr_refund_reply_user_details">
                    <?php echo get_avatar($post_object->post_author, '50'); ?><br>
                    <span class="hr_refund_reply_user_name"><?php echo $firstname; ?></span>
                    <span class="hr_refund_reply_date">
                        <?php echo "Replied on: $post_object->post_date ( " . human_time_diff(strtotime($post_object->post_date), (current_time('timestamp'))) . " ago )"; ?>
                    </span>
                </div>
                <hr>
                <div class="hr_refund_reply_content">
                    <?php
                    $find_array = array('<?', '?>');
                    $replace_array = array('&lt;?', '?&gt;');
                    echo wpautop(str_replace($find_array, $replace_array, $post_object->post_content), true);
                    ?>
                </div>
            </div>
            <?php
            return ob_get_clean();
        }

        /**
         * Do Refund Request
         */
        public static function hr_refund_do_refund_request() {
            check_ajax_referer('hr-refund-button', 'hr_security');

            if (!isset($_POST))
                throw new exception(__('Invalid Request', HR_REFUND_LOCALE));

            $refund = false;
            $response_data = array();
            $order_id = absint($_POST['order_id']);
            $request_id = absint($_POST['request_id']);
            $old_status = get_post_status($request_id);
            $gateway_type = stripslashes($_POST['api_refund']);
            $api_refund = ( $gateway_type == 'gateway' ) ? true : false;
            $restock_refunded_items = 'true' === $_POST['restock_refunded_items'];
            $line_item_qtys = json_decode(sanitize_text_field(stripslashes($_POST['line_item_qtys'])), true);
            $line_item_totals = json_decode(sanitize_text_field(stripslashes($_POST['line_item_totals'])), true);
            $line_item_tax_totals = json_decode(sanitize_text_field(stripslashes($_POST['line_item_tax_totals'])), true);
            $refund_amount = wc_format_decimal(sanitize_text_field($_POST['refund_amount']), wc_get_price_decimals());
            $request_obj = hr_refund_create_request_post_obj($request_id);

            try {

                $order = hr_get_order_obj($order_id);
                $order_items = $order->get_items();
                $current_time = current_time('timestamp');
                $max_refund = wc_format_decimal($order->get_total() - $order->get_total_refunded(), wc_get_price_decimals());

                if (!$refund_amount || $max_refund < $refund_amount || 0 > $refund_amount)
                    throw new exception(__('Invalid refund amount', HR_REFUND_LOCALE));

                // Prepare line items which we are refunding
                $line_items = array();
                $line_item_qty_keys = array_keys($line_item_qtys);
                $line_item_total_keys = array_keys($line_item_totals);
                $item_ids = array_unique(array_merge($line_item_qty_keys, $line_item_total_keys));

                foreach ($item_ids as $item_id) {

                    $line_items[$item_id] = array('qty' => 0, 'refund_total' => 0, 'refund_tax' => array());

                    if (isset($line_item_qtys[$item_id]))
                        $line_items[$item_id]['qty'] = max($line_item_qtys[$item_id], 0);

                    if (isset($line_item_totals[$item_id]))
                        $line_items[$item_id]['refund_total'] = wc_format_decimal($line_item_totals[$item_id]);

                    if (isset($line_item_tax_totals[$item_id]))
                        $line_items[$item_id]['refund_tax'] = array_filter(array_map('wc_format_decimal', $line_item_tax_totals[$item_id]));
                }
                // Create the refund object.
                $refund = wc_create_refund(array(
                    'amount' => $refund_amount,
                    'reason' => $request_obj->title,
                    'order_id' => $order_id,
                    'line_items' => $line_items,
                    'refund_payment' => $api_refund,
                    'restock_items' => $restock_refunded_items,
                ));

                if (is_wp_error($refund)) {
                    throw new Exception($refund->get_error_message());
                } else {
                    $id = wp_update_post(array('ID' => $request_id, 'post_status' => 'hr-refund-accept', 'hr_refund_staus_modified_date' => $current_time));
                    if ($id) {
                        update_post_meta($request_id, 'hr_refund_request_old_status', $old_status);
                        //refund after success
                        do_action('hr_refund_after_refund_success', $refund_amount, $request_id, $order_id, $line_items, $refund->get_id(), $gateway_type);

                        HR_Refund_Email::refund_request_accept_email_to_customer($request_id);
                        HR_Refund_Email::refund_request_accept_email_to_admin($request_id);
                    }
                }

                wp_send_json_success($response_data);
            } catch (Exception $e) {
                if ($refund && is_a($refund, 'WC_Order_Refund'))
                    wp_delete_post($refund->get_id(), true);

                wp_send_json_error(array('error' => $e->getMessage()));
            }
        }

        /**
         * Change Refund Request Status
         */
        public static function hr_refund_change_status() {
            check_ajax_referer('hr-refund-status', 'hr_security');

            if (!isset($_POST))
                throw new exception(__('Invalid Request', HR_REFUND_LOCALE));

            $new_status = stripslashes($_POST['status']);
            $request_id = absint($_POST['request_id']);
            $old_status = get_post_status($request_id);
            $current_time = current_time('timestamp');
            try {
                $id = wp_update_post(array('ID' => $request_id, 'post_status' => $new_status, 'hr_refund_staus_modified_date' => $current_time));
                if ($id && $new_status != $old_status) {
                    update_post_meta($request_id, 'hr_refund_request_old_status', $old_status);
                    if ($new_status == 'hr-refund-reject') {
                        HR_Refund_Email::refund_request_reject_email_to_customer($request_id);
                        HR_Refund_Email::refund_request_reject_email_to_admin($request_id);
                    } else {
                        HR_Refund_Email::refund_request_status_change_email_to_customer($request_id);
                        HR_Refund_Email::refund_request_status_change_email_to_admin($request_id);
                    }
                }
                wp_send_json_success(array('id' => $id));
            } catch (Exception $e) {
                wp_send_json_error(array('error' => $e->getMessage()));
            }
        }

    }

    HR_Refund_Request_View::init();
}