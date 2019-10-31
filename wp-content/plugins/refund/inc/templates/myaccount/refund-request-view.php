<?php
/**
 * Refund Request View
 */
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}
wp_enqueue_script('hrr_refund_frontend');
$request_obj = hr_refund_create_request_post_obj($request_id);
$currency_code = hr_refund_get_currency_from_order($request_obj->order_id, true);
?>
<style>
    .hr_refund_reply_request_tickets{
        padding: 20px;
        border-color: #dddddd;
        border-style: solid;
        overflow:scroll; 
        max-height: 400px;
    } 
    .hr_refund_reply_ticket,.hr_refund_request_created{
        margin-bottom: 30px;
        border-color: #dddddd;
        border-style: solid;
        border-width: 1px;
        padding-top: 15px;
        padding-left: 15px;
        width: auto;
        word-wrap:break-word;
    }
    .hr_refund_admin{
        background-color: #b6b6d3;
    }
    .hr_refund_customer{
        background-color: #9fc69f;
    }
    .hr_refund_request_created{
        background-color: #c5dae2;
    }
</style>
<div>
    <div class = 'hr-refund-form-field'>
        <table class = 'shop_table' id = 'hr_refund_cart_table' cellspacing = "0" cellpadding = "6">
            <tr>
                <th>
                    <?php echo __('Status', HR_REFUND_LOCALE); ?>
                </th>
                <td>
                    <?php echo hr_refund_get_post_status($request_obj->id); ?>
                </td>
            </tr>
            <tr>
                <th>
                    <?php echo __('Amount', HR_REFUND_LOCALE); ?>
                </th>
                <td>
                    <?php echo hr_wc_format_price($request_obj->user_request_total, $request_obj->current_language); ?>
                </td>
            </tr>
            <tr>
                <th>
                    <?php echo __('Type', HR_REFUND_LOCALE); ?>
                </th>
                <td>
                    <?php echo $request_obj->type; ?>
                </td>
            </tr>
            <tr>
                <th>
                    <?php echo __('Request as', HR_REFUND_LOCALE); ?>
                </th>
                <td>
                    <?php echo $request_obj->request_as; ?>
                </td>
            </tr>
            <tr>
                <th>
                    <?php echo __('Date', HR_REFUND_LOCALE); ?>
                </th>
                <td>
                    <?php echo hr_format_date_time_by_wordpress($request_obj->date); ?>
                </td>
            </tr>
            <tr>
                <td colspan="2" style="text-align:center">
                    <?php
                    $url = wc_get_endpoint_url('view-order', $request_obj->order_id, wc_get_page_permalink('myaccount'));
                    ?><a href="<?php echo $url; ?>" class="button"><?php _e('View Order', HR_REFUND_LOCALE); ?></a>
                </td>
            </tr>
        </table>
    </div>
    <hr>
    <div class='hr_refund_reply_request_conversation'>
        <div>
            <p>
                <?php
                echo "<b>" . __( "Message History" , HR_REFUND_LOCALE ) . "</b>" ;
                ?>
            </p>
            <div class="hr_refund_reply_request_tickets">
                <div class="hr_refund_request_created">
                    <?php
                    $user_obj = get_userdata($request_obj->user_id);
                    ?>
                    <div class="hr_refund_avator">
                        <a href="<?php echo admin_url('user-edit.php') . "?user_id=" . $request_obj->user_id; ?>"><?php echo get_avatar($request_obj->user_id, '50'); ?></a>
                    </div>
                    <div class="hr_refund_creator_name">
                        <?php echo "Created by: " . $user_obj->display_name . " (" . $user_obj->user_email . ")"; ?>
                    </div>
                    <div class="hr_refund_created_on" title="<?php echo $request_obj->created_date; ?>">
                        <?php echo "Created on: $request_obj->created_date (" . human_time_diff(strtotime($request_obj->created_date), (current_time('timestamp'))) . " ago)"; ?>
                    </div>
                    <hr>
                    <h4><?php echo $request_obj->title ; ?></h4>
                    <div class="hr_refund_request_content">
                        <?php
                        $find_array = array('<?', '?>');
                        $replace_array = array('&lt;?', '?&gt;');
                        echo wpautop(str_replace($find_array, $replace_array, $request_obj->reason), true);
                        ?>
                    </div>
                </div>
                <?php
                $children_object = hr_refund_request_children_posts($request_obj->id);
                if (hr_check_is_array($children_object)) {
                    foreach ($children_object as $post_object) {
                        $current_user_id = $post_object->post_author;
                        $user_obj = get_userdata($current_user_id);
                        $firstname = $user_obj->display_name != '' ? $user_obj->display_name : $user_obj->user_login;
                        $admin_message_class = (user_can($user_obj, 'manage_woocommerce')) ? 'hr_refund_admin' : 'hr_refund_customer';
                        ?> <div class = "hr_refund_reply_ticket hr_refund_reply_ticket<?php echo $post_object->ID . ' ' . $admin_message_class; ?>" >
                            <div class = "hr_refund_reply_user_details">
                                <?php echo get_avatar($post_object->post_author, '50');
                                ?><br>
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
                    }
                    ?> 
                </div>
                <?php
            }
            ?>
        </div>
        <?php do_action('hrr_after_user_converation', $request_obj); ?>
    </div>
</div><?php
