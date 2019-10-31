<?php
/**
 * Refund Request Table
 */
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

wp_enqueue_script('hrr_refund_form');
do_action('hrr_before_frontend_refund_request_table', $order);

$get_items = $order->get_items();
$user_id = get_current_user_id();
$shipping_tax_cost = $order->get_shipping_tax();
$shipping_total = $order->get_total_shipping();
$currency_code = hr_refund_get_currency_from_order($order, false);
$tax_enabled = get_option('hr_refund_request_include_tax') == 'yes';
$reasons = get_option('hr_refund_preloaded_reason');
$header_columns = array('Image', 'Product Name', 'Item Value', 'Qty', 'Total', 'Refund Qty', 'Refund total');
?>
<div class='hr-refund-form'>

    <form id='hr-refund-form' method='post'>

        <div class='hr-refund-form-field'>
            <table class='shop_table hr_refund_frontend_table hr_refund_request_cart_table' id='hr_refund_cart_table' cellspacing="0" cellpadding="6">

                <thead>
                    <tr>
                        <?php do_action('hrr_forntend_item_column_header_start', $order); ?>
                        <?php foreach ($header_columns as $column_name) { ?>
                            <th>
                                <?php echo __($column_name, HR_REFUND_LOCALE); ?>
                            </th>
                        <?php } ?>
                        <?php do_action('hrr_forntend_item_column_header_end', $order); ?>
                    </tr>
                </thead>

                <tbody>
                    <?php
                    $tax_total_value = '0';
                    $shipping_tax_cost = '0';
                    $total_refund_amount = '0';
                    $partial_enabled = apply_filters('hrr_partial_enabled', false);
                    $readonly = (!$partial_enabled) ? 'readonly="readonly"' : '';
                    foreach ($get_items as $item_id => $item) {
                        $tax_value = '0';
                        $original_quantity = isset($item['quantity']) ? $item['quantity'] : $item['qty'];
                        $item_value = $item['line_total'] / $original_quantity;
                        $already_refund_qty = (int) wc_get_order_item_meta($item_id, 'hr_refund_request_item_qty');
                        $check = ($already_refund_qty < $original_quantity);
                        $quantity = $original_quantity - $already_refund_qty;
                        $total_refund = $item_value * $quantity;
                        $line_tax_data = maybe_unserialize($item['line_tax_data']);
                        if ($tax_enabled) {
                            $item_and_tax_value = ( $item['line_subtotal'] + $item['line_subtotal_tax'] ) / $original_quantity;
                            $tax_value = $item['line_subtotal_tax'] / $original_quantity;
                            $tax_total_value += $item['line_subtotal_tax'];
                            $total_refund = $item_and_tax_value * $quantity;
                        }
                        $total_refund_amount += $total_refund;

                        $quantity = ($check) ? $quantity : '0';
                        $total_refund = ($check) ? $total_refund : '0';
                        ?>
                        <tr class='hr_refund_items' data-order_item_id="<?php echo $item_id; ?>">
                            <?php do_action('hrr_forntend_item_column_start', $check, $order, $item_id); ?>
                            <td class='hr_refund_product_image' data-title="<?php esc_attr_e( 'Image' , HR_REFUND_LOCALE ) ; ?>">
                                <?php echo HR_Refund_Items_Product::get_product_image($item); ?>
                            </td>
                            <td class='hr_refund_product_name' data-title="<?php esc_attr_e( 'Product Name' , HR_REFUND_LOCALE ) ; ?>">
                                <?php echo HR_Refund_Items_Product::get_product_name($item) ?>
                            </td>
                            <td class='hr_refund_product_price' data-title="<?php esc_attr_e( 'Item Value' , HR_REFUND_LOCALE ) ; ?>">
                                <?php echo hr_wc_format_price($item_value, $currency_code); ?>
                            </td>
                            <td class='hr_refund_product_qty' data-title="<?php esc_attr_e( 'Qty' , HR_REFUND_LOCALE ) ; ?>">
                                <?php echo $item['quantity']; ?>
                            </td>
                            <td class='hr_refund_product_total' data-title="<?php esc_attr_e( 'Total' , HR_REFUND_LOCALE ) ; ?>">
                                <?php echo hr_wc_format_price($item['line_total'], $currency_code); ?>
                            </td>
                            <td class='hr_refund_product_refund_qty hr_refund_item_data' data-title="<?php esc_attr_e( 'Refund Qty' , HR_REFUND_LOCALE ) ; ?>">
                                <input type='number' id='hr_refund_item_qty' class="hr_refund_item_qty" min='1' style="width:60px"
                                       max='<?php echo $quantity; ?>' value='<?php echo $quantity; ?>' <?php echo $readonly; ?>/>

                                <input type='hidden' id='hr_refund_request_item_id' class='hr_refund_request_item_id' value='<?php echo $item_id; ?>'/>
                                <input type='hidden' id='hr_refund_request_price' class='hr_refund_request_price' value='<?php echo $item_value; ?>'/>
                                <input type='hidden' id='hr_refund_request_subtotal' class='hr_refund_request_subtotal' value='<?php echo $total_refund; ?>'/>
                                <input type='hidden' id='hr_refund_request_qty' class='hr_refund_request_qty' value='<?php echo $quantity; ?>'/>
                                <?php
                                if ($tax_enabled) {
                                    foreach ($line_tax_data['total'] as $tax_id => $value) {
                                        ?>
                                        <input type="hidden" class="hr_refund_request_tax" data-tax_id="<?php echo $tax_id; ?>" value="<?php echo $value / $item['qty']; ?>">
                                        <?php
                                    }
                                }
                                ?>
                                <input type = 'hidden' id = 'hr_refund_request_tax_value' class = 'hr_refund_request_tax_value' value = '<?php echo $tax_value; ?>'/>
                                <?php
                                ?>
                            </td>
                            <td class='hr_refund_product_refund_total' data-title="<?php esc_attr_e( 'Refund total' , HR_REFUND_LOCALE ) ; ?>">
                                <?php echo hr_wc_format_price($total_refund, $currency_code); ?>
                            </td>
                            <?php do_action('hrr_forntend_item_column_end', $check, $order, $item_id); ?>
                        </tr>
                        <?php
                    }
                    ?>
                </tbody>

                <tfoot>
                    <?php
                    $shipping_total = ($tax_enabled) ? ($shipping_total + $shipping_tax_cost) : $shipping_total;
                    $total_value = $total_refund_amount + $shipping_total;
                    $colspan = ($partial_enabled) ? 5 : 4;
                    ?>
                    <tr align='center' class='hr_refund_item_total'>
                        <th scope="row" colspan="<?php echo $colspan; ?>" style="text-align:right"><?php echo __('Total', HR_REFUND_LOCALE); ?></th>
                        <td data-title="<?php esc_attr_e( 'Total' , HR_REFUND_LOCALE ) ; ?>">
                            <?php echo $order->get_formatted_order_total(); ?>
                        </td>
                        <th scope="row" style="text-align:right"><?php echo __('Refund Total', HR_REFUND_LOCALE); ?></th>
                        <td class='hr_refund_item_total_value' data-title="<?php esc_attr_e( 'Refund Total' , HR_REFUND_LOCALE ) ; ?>">
                            <?php echo hr_wc_format_price($total_value, $currency_code); ?>
                        </td>
                    </tr>
                </tfoot>

            </table>

        </div>
        <hr>

        <div class='hr-refund-form-field'>
            <table id='hr_refund_form_table' class='shop_table hr_refund_request_form_table'>
                <tr>
                    <th>
                        <?php echo get_option('hr_refund_form_general_reason_label');
                        ?>
                    </th>
                    <td>
                        <select id='hr_refund_general_reasons' name='hr_refund_general_reasons'>
                            <?php
                            $reasons_array = explode(',', $reasons);
                            $reasons_array = array_merge($reasons_array, array('Others'));
                            $reasons_array = array_filter($reasons_array);
                            foreach ($reasons_array as $value) {
                                ?>
                                <option value='<?php echo $value; ?>'><?php echo $value; ?></option>
                            <?php } ?>
                        </select>
                    </td>
                </tr>
                <?php if ( get_option( 'hr_refund_enable_refund_method' ) == 'yes' ) { ?>
                    <tr>
                        <th>
                            <?php echo get_option( 'hr_refund_form_request_as_label' ) ; ?>
                        </th>
                        <td>
                            <select id='hr_refund_request_as' name='hr_refund_request_as'>
                                <?php
                                $request_options = hrr_request_mode_options() ;
                                $request_options = array_filter( $request_options ) ;
                                foreach ( $request_options as $request_option ) {
                                    ?>
                                    <option value="<?php echo $request_option ; ?>"><?php echo $request_option ; ?></option>
                                <?php }
                                ?>
                            </select>
                        </td>
                    </tr>
                <?php } ?>
                <?php if ( apply_filters( 'hrr_display_refund_reason_field' , true ) ) { ?>
                    <tr>
                        <th>
                            <?php echo get_option( 'hr_refund_form_details_label' ) ; ?>
                        </th>
                        <td>
                            <textarea id='hr_refund_form_details' name='hr_refund_form_details' value=''></textarea>
                        </td>
                    </tr>
                <?php } ?>
                <tr>
                    <th>
                    </th>
                    <td>
                        <input type='hidden' id='hr_refund_user_id' name='hr_refund_user_id' value='<?php echo $user_id ; ?>'/>
                        <input type='hidden' id='hr_refund_order_id' name='hr_refund_order_id' value='<?php echo $order_id ; ?>'/>
                        <input type="hidden" id="hr_refund_total" name='hr_refund_total' value="<?php echo $total_value ; ?>">
                        <input type='submit' id='hr_refund_submit' style="float:left;" value='<?php echo get_option( 'hr_refund_form_submit_button_label' ) ; ?>'/>
                        <p style="width:40px;height:35px;float:left;margin:0px;">
                            <img src="<?php echo HR_REFUND_PLUGIN_URL . '/assets/images/update.gif' ?>" id='hr_refund_img' alt="" style="width: 40px;hieght:35px;display:none;">
                        <p id='hr_refund_message' style="display:none;clear: both;text-align:left;"><?php echo __( 'Refund Request Send Successfully' ) ; ?></p>
                        </p>
                    </td>
                </tr>
            </table>
        </div>

    </form>
</div>
<?php
do_action('hrr_after_frontend_refund_request_table', $order);
