
jQuery(document).ready(function () {
    jQuery('#hr_refund_request_fromdate').datepicker({
        changeMonth: true,
        changeYear: true,
        onClose: function (selectedDate) {
            var maxDate = new Date(Date.parse(selectedDate));
            maxDate.setDate(maxDate.getDate() + 1);
            jQuery('#hr_refund_request_todate').datepicker('option', 'minDate', maxDate);
        }
    });
    jQuery('#hr_refund_request_todate').datepicker({
        changeMonth: true,
        changeYear: true,
        onClose: function (selectedDate) {
            jQuery('#hr_refund_request_fromdate').datepicker('option', 'maxDate', selectedDate);
        }
    });
});
