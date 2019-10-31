<?php
/**
 * Refund Request View
 */
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

$user = get_userdata($request->user_id);
if (is_object($user)) {
    $user_name = $user->display_name;
    $user_email = $user->user_email;
} else {
    $user_name = _e('user details not available');
    $user_email = _e('user details not available');
}
?>
<div class = 'hr-refund-form-field'>
    <div class='hr_refund_data_type' style="margin-bottom: 5px">
        <span>
            <?php echo __('Type', HR_REFUND_LOCALE) . ':'; ?>
        </span>
        <span>
            <b> <?php echo $request->type; ?> </b>
        </span>
    </div>
    <div class='hr_refund_data_request_as' style="margin-bottom: 5px">
        <span>
            <?php echo __('Request as', HR_REFUND_LOCALE) . ':'; ?>
        </span>
        <span>
            <b> <?php echo $request->request_as; ?> </b>
        </span>
    </div>
    <div class='hr_refund_data_customer_name' style="margin-bottom: 5px">
        <span>
            <?php echo __('Customer Name', HR_REFUND_LOCALE) . ':'; ?>
        </span>
        <span>
            <b> <?php echo $user_name; ?> </b>
        </span>
    </div>
    <div class='hr_refund_data_customer_email' style="margin-bottom: 5px">
        <span>
            <?php echo __('Customer Email', HR_REFUND_LOCALE) . ':'; ?>
        </span>
        <span>
            <b> <?php echo $user_email; ?> </b>
        </span>
    </div>
    <div class='hr_refund_data_date' style="margin-bottom: 5px">
        <span>
            <?php echo __('Date', HR_REFUND_LOCALE) . ':'; ?>
        </span>
        <span>
            <b> <?php echo hr_format_date_time_by_wordpress($request->date); ?> </b>
        </span>
    </div>
</div>
    <?php
