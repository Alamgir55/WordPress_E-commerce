<?php
/**
 * Refund Request Table
 */
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

wp_enqueue_script('hrr_refund_request');
do_action('hrr_before_admin_refund_request_table', $order, $request);

$get_items = $order->get_items();
$shipping_tax_cost = $order->get_shipping_tax();
$shipping_total = $order->get_total_shipping();
$tax_enabled = get_option('hr_refund_request_include_tax') == 'yes';
$href = admin_url('post.php?post=' . $request->order_id . '&action=edit');
$title = sprintf(__('Go to order #%d', HR_REFUND_LOCALE), $request->order_id);
$header_columns = array('Image', 'Product Name', 'Item Value', 'Qty', 'Total', 'Refund Qty', 'Refund total');
?>
<div class='hr-refund-request'>
    <form id='hr-refund-form' method='post'>

        <div class='hr-refund-form-field'>
            <div>
                <p>
                <h1 style="display: inline-block;"> <?php echo sprintf("Refund Request #%d", $request->id); ?></h1>
                <a href='<?php echo $href; ?>' class='button-primary' style="margin-top:10px;" title="<?php echo $title; ?>"><?php _e('View Order', HR_REFUND_LOCALE); ?></a>
                </p>
            </div>

            <div class='hr_refund_items_table'>
                <table class='shop_table' id='hr_refund_cart_table' cellspacing="0" cellpadding="6" border='1' style="width:100%">
                    <thead>
                        <tr>
                            <?php do_action('hrr_admin_item_column_header_start', $order); ?>
                            <?php foreach ($header_columns as $column_name) { ?>
                                <th>
                                    <?php echo __($column_name, HR_REFUND_LOCALE); ?>
                                </th>
                            <?php } ?>
                            <?php do_action('hrr_admin_item_column_header_end', $order); ?>
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
                            if (array_key_exists($item_id, $request->line_items)) {
                                $tax_value = '0';
                                $quantity = isset($item['quantity']) ? $item['quantity'] : $item['qty'];
                                $item_value = $item['line_total'] / $item['qty'];
                                $total_refund = $item_value * $request->line_items[$item_id];
                                $line_tax_data = maybe_unserialize($item['line_tax_data']);
                                if ($tax_enabled) {
                                    $item_and_tax_value = ( $item['line_subtotal'] + $item['line_subtotal_tax'] ) / $item['qty'];
                                    $tax_value = $item['line_subtotal_tax'] / $item['qty'];
                                    $tax_total_value += $item['line_subtotal_tax'];
                                    $total_refund = $item_and_tax_value * $request->line_items[$item_id];
                                }
                                $total_refund_amount += $total_refund;
                                ?>
                                <tr class='hr_refund_items' data-order_item_id="<?php echo $item_id; ?>">
                                    <?php do_action('hrr_admin_item_column_start', $order, $item_id); ?>
                                    <td align='center'>
                                        <?php echo HR_Refund_Items_Product::get_product_image($item); ?>
                                    </td>
                                    <td align='center'>
                                        <?php echo HR_Refund_Items_Product::get_product_name($item) ?>
                                    </td>
                                    <td align='center'>
                                        <?php echo hr_wc_format_price($item_value, $request->current_language); ?>
                                    </td>
                                    <td align='center'>
                                        <?php echo $quantity; ?>
                                    </td>
                                    <td align='center'>
                                        <?php echo hr_wc_format_price($item['line_total'], $request->current_language); ?>
                                    </td>
                                    <td align='center' class='hr_refund_item_data'>
                                        <input type='number' id='hr_refund_item_qty' class="hr_refund_item_qty" min='1' style="width:60px"
                                               max='<?php echo $request->line_items[$item_id]; ?>' value='<?php echo $request->line_items[$item_id]; ?>' <?php echo $readonly; ?>/>

                                        <input type='hidden' id='hr_refund_request_item_id' class='hr_refund_request_item_id' value='<?php echo $item_id; ?>'/>
                                        <input type='hidden' id='hr_refund_request_price' class='hr_refund_request_price' value='<?php echo $item_value; ?>'/>
                                        <input type='hidden' id='hr_refund_request_subtotal' class='hr_refund_request_subtotal' value='<?php echo $total_refund; ?>'/>
                                        <input type='hidden' id='hr_refund_request_qty' class='hr_refund_request_qty' value='<?php echo $request->line_items[$item_id]; ?>'/>
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
                                    </td>
                                    <td align='center' class='hr_refund_item_subtotal'>
                                        <?php echo hr_wc_format_price($total_refund, $request->current_language); ?>
                                    </td>
                                    <?php do_action('hrr_admin_item_column_end', $order, $item_id); ?>
                                </tr>
                                <?php
                            }
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
                            <td>
                                <?php echo $order->get_formatted_order_total(); ?>
                            </td>
                            <th scope="row" style="text-align:right"><?php echo __('Refund Total', HR_REFUND_LOCALE); ?></th>
                            <td class='hr_refund_item_total_value'>
                                <?php echo hr_wc_format_price($total_value, $request->current_language); ?>
                            </td>
                        </tr>
                    </tfoot>
                </table>
            </div>


            <?php
            $payment_gateway = wc_get_payment_gateway_by_order($order);
            $check_gateway_supports_refunds = false !== $payment_gateway && $payment_gateway->supports('refunds');
            $refund_amount = hr_wc_format_price($total_value, $request->current_language);
            $gateway_name = false !== $payment_gateway ? (!empty($payment_gateway->method_title) ? $payment_gateway->method_title : $payment_gateway->get_title() ) : __('Payment Gateway', HR_REFUND_LOCALE);

            $automatic_refund_button_classes = $check_gateway_supports_refunds ? 'button-primary' : 'button-primary';
            $automatic_tip = $check_gateway_supports_refunds ? '' : 'data-tip="' . esc_attr__('The payment gateway used to place this order does not support automatic refunds.', HR_REFUND_LOCALE) . '" disabled="disabled"';
            $manual_tip = 'data-tip="' . esc_attr__('You will need to manually issue a refund through your payment gateway after using this.', HR_REFUND_LOCALE) . '"';
            ?>
            <div class='hr_refund_after_table'>

                <div class='hr_refund_restock'>
                    <p>
                        <span>
                            <input id="hr_refund_restock_products" type="checkbox">
                            <label for="hr_refund_restock_products"><?php _e('Restock selected items?', HR_REFUND_LOCALE) ?></label>
                        </span>
                    </p>
                </div>

                <div class="hr_refund_button" style="margin-top:10px;">
                    <input type="hidden" id="hr_refund_order_id" value="<?php echo $request->order_id; ?>">
                    <input type="hidden" id="hr_refund_post_id" value="<?php echo $request->id; ?>">
                    <input type="hidden" id="hr_refund_request_amount" value="<?php echo $total_value; ?>">
                    <input type="hidden" id="hr_refund_request_tax_total" value="<?php echo $tax_total_value; ?>">
                    <input type="hidden" id="hr_refund_request_item_subtotal" value="<?php echo $total_refund; ?>">
                    <div class="refund-actions">
                        <button id="hr_refund_manual_refund_button" data-paytype="manual" class="hr_refund_request_action_button button button-primary tips" <?php echo $manual_tip; ?>>
                            <?php printf(_x('Refund %s manually', 'Refund $amount manually', HR_REFUND_LOCALE), $refund_amount); ?></span>
                        </button>
                        <button id="hr_refund_api_refund_button" data-paytype="gateway" class="hr_refund_request_action_button tips button <?php echo $automatic_refund_button_classes; ?>" <?php echo $automatic_tip; ?>>
                            <?php printf(__('Refund %s via %s', 'Refund $amount', HR_REFUND_LOCALE), $refund_amount, $gateway_name); ?>
                        </button>
                        <?php
                        do_action('hr_refund_available_refund_via', $refund_amount, $request->id, $request->order_id);
                        ?>
                    </div>
                </div>

            </div>
        </div>
    </form>
</div>
<?php
do_action('hrr_after_admin_refund_request_table', $order, $request);
