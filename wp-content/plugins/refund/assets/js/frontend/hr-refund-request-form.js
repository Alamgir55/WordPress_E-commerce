/* global  hr_refund_request_form_params */

jQuery( function ( $ ) {

    var HRR_Request_form = {
        init : function () {
            $( document ).on( 'click' , '#hr_refund_submit' , this.save_refund_request_form ) ;
        } ,
        save_refund_request_form : function ( event ) {
            event.preventDefault() ;

            if ( hr_refund_request_form_params.reason_field_mandatory == '1' && $( 'textarea#hr_refund_form_details' ).val() == '' ) {
                alert( hr_refund_request_form_params.refund_reason_message ) ;
                return false ;
            }

            var con = confirm( hr_refund_request_form_params.refund_request_message ) ;
            if ( con ) {
                $( 'submit#hr_refund_submit' ).css( 'disabled' , 'disabled' ) ;
                $( 'img#hr_refund_img' ).css( 'display' , 'block' ) ;
                var data = $( '#hr-refund-form' ).serialize() ;
                // Get refund items
                var total = 0 ;
                var line_items = { } ;
                var line_item_ids = { } ;
                $( "tr.hr_refund_items td.hr_refund_item_data" ).each( function ( index , item ) {
                    var checkbox = $( item ).closest( 'tr.hr_refund_items' ).find( 'input.hr_refund_enable_product' ) ;
                    if ( checkbox.length <= 0 || checkbox.is( ":checked" ) ) {
                        total = total + 1 ;
                        item_id = $( item ).find( '.hr_refund_request_item_id' ).val() ;
                        item_qty = parseInt( $( item ).find( '.hr_refund_request_qty' ).val() ) ;
                        line_items[item_id] = item_qty ;
                        line_item_ids[item_id] = item_id ;
                    }
                } ) ;

                if ( total <= 0 ) {
                    $( 'submit#hr_refund_submit' ).attr( 'disabled' , 'disabled' ) ;
                    $( 'img#hr_refund_img' ).css( 'display' , 'none' ) ;
                    alert( 'Please Select a Product to refund' ) ;
                } else {
                    data += "&action=hr_refund_request" ;
                    data += "&line_item_ids=" + encodeURIComponent( JSON.stringify( line_item_ids , null , '' ) ) ;
                    data += "&line_items=" + encodeURIComponent( JSON.stringify( line_items , null , '' ) ) ;
                    data += "&hr_security=" + encodeURIComponent( hr_refund_request_form_params.request_form_security ) ;

                    var obj = $.ajax( {
                        type : 'POST' ,
                        url : hr_refund_request_form_params.ajax_url ,
                        data : data ,
                        dataType : 'json' ,
                        complete : function ( res ) {
                            $( 'submit#hr_refund_submit' ).attr( 'disabled' , 'disabled' ) ;
                            $( 'img#hr_refund_img' ).css( 'display' , 'none' ) ;
                            $( 'p#hr_refund_message' ).css( 'display' , 'block' ) ;
                            window.location.href = hr_refund_request_form_params.redirect_url ;
                        } ,
                    } ) ;
                }
            }

        } ,
        block : function ( id ) {
            $( id ).block( {
                message : null ,
                overlayCSS : {
                    background : '#fff' ,
                    opacity : 0.6
                }
            } ) ;
        } ,
        unblock : function ( id ) {
            $( id ).unblock() ;
        }
    } ;
    HRR_Request_form.init() ;
} ) ;
