/*global hr_hoicker_args*/
jQuery ( function ( $ ) {

    var hr_hoicker_run_through_scripts = {
        init : function ( ) {
            this.initiate_trigger_queries () ;
            $ ( document ).on ( 'click' , ".hr_section h2" , this.hr_hoicker_section_toggle ) ;
            $ ( document ).on ( 'click' , ".hr-save img" , this.hr_hoicker_save_alert ) ;
            $ ( document ).on ( 'click' , ".hr-reset img" , this.hr_hoicker_reset_alert ) ;
        } ,

        initiate_trigger_queries : function () {
            jQuery ( '.hr_section h2' ).attr ( 'data-section_close' , 'no' ) ;
            jQuery ( '.hr_section h2' ).addClass ( 'hr_section_open' ) ;
            jQuery ( this ).css ( 'border-bottom-left-radius' , '3px' ) ;
            jQuery ( this ).css ( 'border-bottom-right-radius' , '3px' ) ;
        } ,

        hr_hoicker_section_toggle : function () {
            var is_section_close = jQuery ( this ).attr ( 'data-section_close' ) ;
            if ( is_section_close === 'yes' ) {
                jQuery ( this ).attr ( 'data-section_close' , 'no' ) ;
                jQuery ( this ).removeClass ( 'hr_section_close' ) ;
                jQuery ( this ).addClass ( 'hr_section_open' ) ;
                jQuery ( this ).css ( 'border-bottom-left-radius' , '0px' ) ;
                jQuery ( this ).css ( 'border-bottom-right-radius' , '0px' ) ;
            } else {
                jQuery ( this ).attr ( 'data-section_close' , 'yes' ) ;
                jQuery ( this ).removeClass ( 'hr_section_open' ) ;
                jQuery ( this ).addClass ( 'hr_section_close' ) ;
                jQuery ( this ).css ( 'border-bottom-left-radius' , '3px' ) ;
                jQuery ( this ).css ( 'border-bottom-right-radius' , '3px' ) ;
            }
            jQuery ( this ).nextUntil ( 'h2' ).toggle () ;
        } ,
        hr_hoicker_save_alert : function () {
            jQuery ( ".hr-save" ).css ( "display" , "none" ) ;
        } ,
        hr_hoicker_reset_alert : function () {
            jQuery ( ".hr-reset" ).css ( "display" , "none" ) ;
        } ,
      

    } ;
    hr_hoicker_run_through_scripts.init ( ) ;
} ) ;
