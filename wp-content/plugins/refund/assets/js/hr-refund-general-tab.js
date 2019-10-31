/* General Tab */
jQuery( function ( $ ) {

    var HRR_General_Tab = {
        init : function () {

            this.trigger_on_page_load() ;

            $( document ).on( 'change' , '#hr_refund_prevent_refund_request' , this.toggle_get_prevent_refund_request_option ) ;
            $( document ).on( 'change' , '#hr_refund_request_time_period' , this.toggle_get_refund_request_time_period_option ) ;
            $( document ).on( 'change' , '#hr_refund_request_prevent_users' , this.toggle_get_prevent_refund_user_request_option ) ;
            $( document ).on( 'change' , '#hr_refund_enable_refund_reason_field' , this.toggle_get_prevent_refund_reason_field ) ;
        } ,
        trigger_on_page_load : function () {
            this.get_prevent_refund_request_option( '#hr_refund_prevent_refund_request' ) ;
            this.get_refund_request_time_period_option( '#hr_refund_request_time_period' ) ;
            this.get_prevent_refund_user_request_option( '#hr_refund_request_prevent_users' ) ;
            this.get_prevent_refund_reason_field( '#hr_refund_enable_refund_reason_field' ) ;
        } ,
        toggle_get_prevent_refund_request_option : function ( event ) {
            event.preventDefault() ;
            var $this = $( event.currentTarget ) ;
            HRR_General_Tab.get_prevent_refund_request_option( $this ) ;
        } ,
        toggle_get_prevent_refund_user_request_option : function ( event ) {
            event.preventDefault() ;
            var $this = $( event.currentTarget ) ;
            HRR_General_Tab.get_prevent_refund_user_request_option( $this ) ;
        } ,
        toggle_get_refund_request_time_period_option : function ( event ) {
            event.preventDefault() ;
            var $this = $( event.currentTarget ) ;
            HRR_General_Tab.get_refund_request_time_period_option( $this ) ;
        } ,
        toggle_get_prevent_refund_reason_field : function ( event ) {
            event.preventDefault() ;
            var $this = $( event.currentTarget ) ;
            HRR_General_Tab.get_prevent_refund_reason_field( $this ) ;
        } ,
        get_prevent_refund_request_option : function ( $this ) {
            if ( $( $this ).val() == '2' ) {
                $( '#hr_refund_include_products_srch' ).closest( 'tr' ).show() ;
                $( '#hr_refund_include_categories_srch' ).closest( 'tr' ).hide() ;
            } else if ( $( $this ).val() == '4' ) {
                $( '#hr_refund_include_products_srch' ).closest( 'tr' ).hide() ;
                $( '#hr_refund_include_categories_srch' ).closest( 'tr' ).show() ;
            } else {
                $( '#hr_refund_include_products_srch' ).closest( 'tr' ).hide() ;
                $( '#hr_refund_include_categories_srch' ).closest( 'tr' ).hide() ;
            }
        } ,
        get_prevent_refund_user_request_option : function ( $this ) {
            if ( $( $this ).val() == '2' ) {
                $( '#hr_refund_include_user_role_srch' ).closest( 'tr' ).hide() ;
                $( '#hr_refund_include_user_srch' ).closest( 'tr' ).show() ;
            } else if ( $( $this ).val() == '4' ) {
                $( '#hr_refund_include_user_role_srch' ).closest( 'tr' ).show() ;
                $( '#hr_refund_include_user_srch' ).closest( 'tr' ).hide() ;
            } else {
                $( '#hr_refund_include_user_role_srch' ).closest( 'tr' ).hide() ;
                $( '#hr_refund_include_user_srch' ).closest( 'tr' ).hide() ;
            }
        } ,
        get_refund_request_time_period_option : function ( $this ) {
            if ( $( $this ).val() == '2' ) {
                $( '#hr_refund_request_no_of_days' ).closest( 'tr' ).show() ;
            } else {
                $( '#hr_refund_request_no_of_days' ).closest( 'tr' ).hide() ;
            }
        } ,
        get_prevent_refund_reason_field : function ( $this ) {
            if ( $( $this ).is( ':checked' ) ) {
                $( '#hr_refund_enable_refund_reason_field_mandatory' ).closest( 'tr' ).show() ;
            } else {
                $( '#hr_refund_enable_refund_reason_field_mandatory' ).closest( 'tr' ).hide() ;
            }
        }
    } ;
    HRR_General_Tab.init() ;
} ) ;
