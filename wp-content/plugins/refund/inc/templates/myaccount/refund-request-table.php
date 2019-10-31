<?php
/**
 * Refund Request Table
 */
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

$columns_array = apply_filters('hr_refund_request_table_cloumns', array(
    'hr_refund_table_request_id_label' => 'ID',
    'hr_refund_table_orderid_label' => 'Order id',
    'hr_refund_table_status_label' => 'Refund request status',
    'hr_refund_table_type_label' => 'Refund Type',
    'hr_refund_table_request_as_label' => 'Refund request as',
    'hr_refund_table_user_total_label' => 'Refund Total',
    'hr_refund_table_date_label' => 'Date of refund request',
    'hr_refund_table_view_label' => 'View'
        )
);
wp_enqueue_style('hrr_footable_css');
wp_enqueue_style('hrr_footablestand_css');
wp_enqueue_style('jquery_smoothness_ui');
wp_enqueue_script('hrr_footable');
wp_enqueue_script('hrr_footable_sorting');
wp_enqueue_script('hrr_footable_paginate');
wp_enqueue_script('hrr_footable_filter');
?>
<h3><?php _e(get_option('hr_refund_table_request_title_label','Refund Requests'), HR_REFUND_LOCALE); ?></h3>
<div>
    <span>
        <select id='hr_refund_pagination'>
            <?php
            for ($k = 1; $k <= 20; $k++) {
                if ($k == 10) {
                    echo '<option value="' . $k . '" selected="selected">' . $k . '</option>';
                } else {
                    echo '<option value="' . $k . '">' . $k . '</option>';
                }
            }
            ?>
        </select>
    </span>

    <label><?php _e('Search', HR_REFUND_LOCALE); ?></label>
    <input type='text' name='hr_refund_search' id='hr_refund_search'>
</div>
<br>
<?php do_action('hr_refund_before_request_table', $request_data); ?>

<table class='hr_refund_frontend_table hr_refund_request_table table' data-page-size='10' data-filter='#hr_refund_search' data-filter-minimum='1'>
    <thead>
        <tr>
            <?php
            foreach ($columns_array as $id => $label) {
                ?>
                <th><?php echo get_option($id, $label); ?> </th>
            <?php } ?>
        </tr>
    </thead>
    <?php
    if (hr_check_is_array($request_data)) {
        foreach ($request_data as $data) {
            $request = hr_refund_create_request_post_obj($data);
            $currency_code = hr_refund_get_currency_from_order($request->order_id, true);
            ?>
            <tbody>
                <tr>
                    <td data-title="<?php esc_attr_e( 'Id' , HR_REFUND_LOCALE ) ; ?>">
                        <?php echo $request->id; ?>
                    </td>
                    <td data-title="<?php esc_attr_e( 'Order Number' , HR_REFUND_LOCALE ) ; ?>">
                        <?php echo "<a href=" . admin_url('post.php?post=' . $request->order_id . '&action=edit') . ">#" . $request->order_id . "</a>"; ?>
                    </td>
                    <td data-title="<?php esc_attr_e( 'Status' , HR_REFUND_LOCALE ) ; ?>">
                        <?php echo hr_refund_get_post_status($data); ?>
                    </td>
                    <td data-title="<?php esc_attr_e( 'Type' , HR_REFUND_LOCALE ) ; ?>">
                        <?php echo $request->type; ?>
                    </td>
                    <td data-title="<?php esc_attr_e( 'Mode' , HR_REFUND_LOCALE ) ; ?>">
                        <?php echo $request->request_as; ?>
                    </td>
                    <td data-title="<?php esc_attr_e( 'Amount' , HR_REFUND_LOCALE ) ; ?>">
                        <?php echo hr_wc_format_price($request->user_request_total, $request->current_language); ?>
                    </td>
                    <td data-title="<?php esc_attr_e( 'Date' , HR_REFUND_LOCALE ) ; ?>">
                        <?php echo hr_format_date_time_by_wordpress($request->date); ?>
                    </td>
                    <td data-title="<?php esc_attr_e( 'View' , HR_REFUND_LOCALE ) ; ?>">
                        <?php
                        HR_Refund_Button::display_refund_request_view_button($request->id);
                        ?>
                    </td>
                </tr>
            </tbody>
            <?php
        }
    } else {
        ?>
        <tr>
            <td colspan="8">
                <?php _e('No Refund Requests Send'); ?>
            </td>
        </tr>
        <?php
    }
    ?>
    <tfoot>
        <tr>
            <td colspan="8" class='actions'>
                <div class="pagination pagination-centered hide-if-no-paging"></div>
            </td>
        </tr>
    </tfoot>
</table>

<style>
    .footable > tbody > tr > td,.footable > thead > tr > th, .footable > thead > tr > td{
        text-align:center !important;
    }
</style>

<script type="text/javascript">
    jQuery(document).ready(function ($) {
        $('.hr_refund_request_table').footable();
        $('#hr_refund_pagination').val(10);
        $('#hr_refund_pagination').on('change', function () {
            $('.hr_refund_request_table').data('page-size', this.value);
            $('.hr_refund_request_table').trigger('footable_initialized');
        });
    });

</script>

<?php
do_action('hr_refund_after_request_table', $request_data);
