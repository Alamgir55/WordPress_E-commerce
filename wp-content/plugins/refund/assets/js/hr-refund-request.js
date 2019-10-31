/* global  hr_refund_request_form */

jQuery(function ($) {

    var HRR_Request_view = {

        init: function () {
            $(document).on('click', 'input#submit', this.change_status);
            $('div.hr-refund-request').on('click', 'button.hr_refund_request_action_button', this.do_refund_request);
        },

        do_refund_request: function (event) {
            event.preventDefault();
            var con = confirm(hr_refund_request_params.refund_request_message);
            if (con) {
                // Get line item refunds
                var line_item_qtys = {};
                var line_item_totals = {};
                var line_item_tax_totals = {};

                $('tr.hr_refund_items td.hr_refund_item_data').each(function (index, item) {
                    $row = $(item).closest('tr');
                    var $item_id = $row.data('order_item_id');
                    var total = 0;
                    if ($item_id) {
                        var checkbox = $row.find('input.hr_refund_enable_product');
                        if (checkbox.length <= 0 || checkbox.is(":checked")) {
                            total = total + 1;
                            item_qty = parseInt($(item).find('#hr_refund_request_qty').val());
                            item_value = parseFloat($(item).find('.hr_refund_request_price').val());
                            item_total = item_value * item_qty;

                            line_item_qtys[ $item_id ] = item_qty;
                            line_item_totals[ $item_id ] = accounting.unformat(item_total, hr_refund_request_params.mon_decimal_point);
                            line_item_tax_totals [ $item_id ] = {};

                            item_taxes = $(item).find('.hr_refund_request_tax').each(function (index, tax) {
                                var tax_id = $(tax).data('tax_id');
                                var selected_qty_tax_value = parseFloat(tax.value) * item_qty;
                                line_item_tax_totals [ $item_id ][ tax_id ] = accounting.unformat(selected_qty_tax_value, hr_refund_request_params.mon_decimal_point);
                            });
                        }
                    }
                });

                if ($('#hr_refund_request_amount').val() == '0') {
                    window.alert(hr_refund_request_params.refund_product_message);
                    return;
                }

                HRR_Request_view.block('.hr-refund-request');
                var data = {
                    action: 'hr_refund_request_line_items',
                    order_id: $('#hr_refund_order_id').val(),
                    request_id: $('#hr_refund_post_id').val(),
                    refund_amount: $('#hr_refund_request_amount').val(),
                    line_item_qtys: JSON.stringify(line_item_qtys, null, ''),
                    line_item_totals: JSON.stringify(line_item_totals, null, ''),
                    line_item_tax_totals: JSON.stringify(line_item_tax_totals, null, ''),
                    api_refund: $(this).attr('data-paytype'),
                    restock_refunded_items: $('#hr_refund_restock_products:checked').length ? 'true' : 'false',
                    hr_security: hr_refund_request_params.request_button_security
                };

                $.post(ajaxurl, data, function (response) {
                    if (true === response.success) {
                        HRR_Request_view.unblock('.hr-refund-request');
                        window.location.href = window.location.href;
                    } else {
                        window.alert(response.data.error);
                        HRR_Request_view.unblock('.hr-refund-request');
                    }
                });
            }
        },
        change_status: function (event) {
            event.preventDefault();
            HRR_Request_view.block('.hr_refund_status_change');
            var data = {
                action: 'hr_refund_change_status',
                request_id: $('#post_ID').val(),
                status: $('#hr_refund_status').val(),
                hr_security: hr_refund_request_params.request_status_security
            };

            $.post(ajaxurl, data, function (response) {
                if (true === response.success) {
                    HRR_Request_view.unblock('.hr_refund_status_change');
                    window.location.href = window.location.href;
                } else {
                    window.alert(response.data.error);
                    HRR_Request_view.unblock('.hr_refund_status_change');
                }
            });
        },
        block: function (id) {
            $(id).block({
                message: null,
                overlayCSS: {
                    background: '#fff',
                    opacity: 0.6
                }
            });
        },
        unblock: function (id) {
            $(id).unblock();
        },
    };
    HRR_Request_view.init();
});
