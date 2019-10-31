/* global hr_wallet_args */
jQuery ( function ( $ ) {

    var hr_refund_hoicker = {
        init : function ( ) {
            this.trigger_on_page_load ( ) ;
            $ ( document ).on ( 'click' , '#hrrefundgeneral' , this.hr_refund_general_show_hide ) ;
            $ ( document ).on ( 'click' , '#hrrefundemail' , this.hr_refund_email_show_hide ) ;
            $ ( document ).on ( 'click' , '#hrrefundlocalization' , this.hr_refund_localization_show_hide ) ;
            $ ( document ).on ( 'click' , '#hrrefundshortocode' , this.hr_refund_shortcode_show_hide ) ;
            $ ( document ).on ( 'click' , '#hrcompatibility' , this.hr_refund_compatibility_show_hide ) ;
            $ ( document ).on ( 'click' , '#hr-hoicker-global-license' , this.hr_hoicker_global_verify_license ) ;
            $ ( document ).on ( 'click' , '#hr-hoicker-global-support' , this.hr_hoicker_global_support_function ) ;

        } ,
        trigger_on_page_load : function () {
            var loaded_menu_name = window.location.hash ;
           
            if ( loaded_menu_name == '#hr_a_hrrefundgeneral' || loaded_menu_name == '' ) {
                this.hr_refund_general_show_hide_function () ;
            }
            if ( loaded_menu_name == '#hr_a_hrrefundemail' ) {
                this.hr_refund_email_show_hide_function () ;
            }
            if ( loaded_menu_name == '#hr_a_hrrefundlocalization' ) {
                this.hr_refund_localization_show_hide_function () ;
            }
            if ( loaded_menu_name == '#hr_a_hrrefundshortocode' ) {
                this.hr_refund_shortcode_show_hide_function () ;
            }
            if ( loaded_menu_name == '#hr_a_hrcompatibility' ) {
                this.hr_refund_compatibility_show_hide_function () ;
            }
            if ( loaded_menu_name == '#hr_a_hr-hoicker-global-license' ) {
                this.hr_global_verify_license_show_hide () ;
            } 
            if ( loaded_menu_name == '#hr_a_hr-hoicker-global-support' ) {
                this.hr_hoicker_global_support_show_hide () ;
            }
            
            
        } ,

        hr_refund_general_show_hide : function ( ) {
            hr_refund_hoicker.hr_refund_general_show_hide_function () ;
        } ,
        hr_refund_general_show_hide_function : function () {
            jQuery ( '.hr_inner_tab_content' ).hide () ;
            jQuery ( '.hr_settings_hrrefundgeneral' ).show () ;
            jQuery ( '.hoicker_inner_tab' ).removeClass ( 'nav-tab-active' ) ;
            jQuery ( '.hrrefundgeneral' ).addClass ('nav-tab-active') ;
            jQuery ( '.hr_hoicker_settings_button' ).show () ;
        } ,

        hr_refund_email_show_hide : function ( event ) {
            hr_refund_hoicker.hr_refund_email_show_hide_function () ;
        } ,
        hr_refund_email_show_hide_function : function ( ) {
            jQuery ( '.hr_inner_tab_content' ).hide () ;
            jQuery ( '.hr_settings_hrrefundemail' ).show () ;
            jQuery ( '.hoicker_inner_tab' ).removeClass ( 'nav-tab-active' ) ;
            jQuery ( '.hrrefundemail' ).addClass ( 'nav-tab-active' ) ;
            jQuery ( '.hr_hoicker_settings_button' ).show () ;
        } ,

        hr_refund_localization_show_hide : function ( event ) {
            hr_refund_hoicker.hr_refund_localization_show_hide_function () ;
        } ,
        hr_refund_localization_show_hide_function : function (  ) {
            jQuery ( '.hr_inner_tab_content' ).hide () ;
            jQuery ( '.hr_settings_hrrefundlocalization' ).show () ;
            jQuery ( '.hoicker_inner_tab' ).removeClass ( 'nav-tab-active' ) ;
            jQuery ( '.hrrefundlocalization' ).addClass ('nav-tab-active') ;
            jQuery ( '.hr_hoicker_settings_button' ).show () ;
        } ,
        hr_refund_shortcode_show_hide : function ( event ) {
            hr_refund_hoicker.hr_refund_shortcode_show_hide_function () ;
        } ,
        hr_refund_shortcode_show_hide_function : function (  ) {
            jQuery ( '.hr_inner_tab_content' ).hide () ;
            jQuery ( '.hr_settings_hrrefundshortocode' ).show () ;
            jQuery ( '.hoicker_inner_tab' ).removeClass ( 'nav-tab-active' ) ;
            jQuery ( '.hrrefundshortocode' ).addClass ('nav-tab-active') ;
            jQuery ( '.hr_hoicker_settings_button' ).hide () ;
        } ,
        hr_refund_compatibility_show_hide : function ( event ) {
            hr_refund_hoicker.hr_refund_compatibility_show_hide_function () ;
        } ,
        hr_refund_compatibility_show_hide_function : function (  ) {
            jQuery ( '.hr_inner_tab_content' ).hide () ;
            jQuery ( '.hr_settings_hrcompatibility' ).show () ;
            jQuery ( '.hoicker_inner_tab' ).removeClass ( 'nav-tab-active' ) ;
            jQuery ( '.hrcompatibility' ).addClass ('nav-tab-active') ;
            jQuery ( '.hr_hoicker_settings_button' ).hide () ;
        } ,
        
           hr_hoicker_global_support_function : function ( event ) {
            hr_refund_hoicker.hr_hoicker_global_support_show_hide () ;
        } ,
        hr_hoicker_global_support_show_hide : function ( ) {
            jQuery ( '.hr_inner_tab_content' ).hide () ;
            jQuery ( '.hr_settings_hr-hoicker-global-support' ).show () ;
            jQuery ( '.hoicker_inner_tab' ).removeClass ( 'nav-tab-active' ) ;
            jQuery ( '#hr-hoicker-global-support' ).addClass ( 'nav-tab-active' ) ;
            jQuery ( '.hr_hoicker_settings_button' ).hide () ;
        } ,
        
        
        hr_hoicker_global_verify_license : function ( event ) {
            hr_refund_hoicker.hr_global_verify_license_show_hide () ;
        } ,
        hr_global_verify_license_show_hide : function ( ) {
            jQuery ( '.hr_inner_tab_content' ).hide () ;
            jQuery ( '.hr_settings_hr-hoicker-global-license' ).show () ;
            jQuery ( '.hoicker_inner_tab' ).removeClass ( 'nav-tab-active' ) ;
            jQuery ( '#hr-hoicker-global-license' ).addClass ( 'nav-tab-active' ) ;
            jQuery ( '.hr_hoicker_settings_button' ).hide () ;
        } ,
        
        
        
    } ;
    hr_refund_hoicker.init ( ) ;
} ) ;
