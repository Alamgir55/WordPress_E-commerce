var wplc_online_agent_count = 0;
var wplc_switchery_init = false;

jQuery(function() {
    wplc_choose_delegate();
});

jQuery(document).on("tcx_dom_ready", function(e) {
    wplc_choose_delegate();

    /**
     * New agent connected
     */
    jQuery(document).on("tcx_agent_connected", function(e) {
        wplc_ma_update_admin_bar(1, "event one"); //Add one agent
    });

    /**
     * New agent disconnected
     */
    jQuery(document).on("tcx_agent_disconnected", function(e) {
        if(typeof e.ndata.chatid !== 'undefined'){ return; }
        wplc_ma_update_admin_bar(-1, "event off"); //remove one agent
    });
});

function wplc_choose_delegate() {
  if (!!wplc_choose_accept_chats.value) {
    jQuery("#wplc_agent_status").prop("checked", true);
    wplc_online_agent_count = wplc_ma_parse_active_count_from_container();
  } else {
    jQuery("#wplc_agent_status").prop("checked", false);
  }

  var wplc_agent_status = jQuery("#wplc_agent_status").attr('checked');

  if(wplc_agent_status === 'checked'){
    jQuery("#wplc_agent_status_text").html(wplc_choose_admin_strings.accepting_chats);
  } else {
    jQuery("#wplc_agent_status_text").html(wplc_choose_admin_strings.not_accepting_chats);
  }

  //Transient
  function wplc_ma_update_agent_transient(data) {
    jQuery.post(ajaxurl, data, function(response) {
      if (response) {
        if (!config.wplc_use_node_server) {
          window.location.reload();
        } else {
          jQuery.event.trigger({type: "wplc_switchery_changed",response:response, ndata:data});
        }
      }
    });
  }

  /* Make sure switchery has been loaded on this page */
  if (typeof Switchery !== 'undefined') {
    var wplc_switchery_element = document.querySelector('.wplc_switchery');
    /* Make sure that the switch is being displayed */
    if (wplc_switchery_element !== null) {
      if (wplc_switchery_init == false) {
        wplc_switchery_init = new Switchery(wplc_switchery_element, { color: '#0596d4', secondaryColor: '#333333', size: 'small' });
      }
      var changeCheckbox = document.querySelector('#wplc_agent_status');
      changeCheckbox.onchange = function () {
        wplc_switchery_init.disable();
        setTimeout(function() {wplc_switchery_init.enable();},3000);
        var wplc_accepting_chats = jQuery(this).attr('checked');
        if (wplc_accepting_chats === 'checked') {
          connection_lost_type = '';
          jQuery("#wplc_agent_status_text").html(wplc_choose_admin_strings.accepting_status);
          var data = {
            action: 'wplc_choose_accepting',
            security: wplc_admin_strings.nonce,
            user_id:  wplc_admin_strings.user_id
          };
          wplc_ma_update_agent_transient(data);
          wplc_ma_update_admin_bar(1, "click on"); //Add one agent
        } else {
          jQuery.event.trigger({type: "wplc_end_all_open_chats"});
          jQuery("#wplc_agent_status_text").html(wplc_choose_admin_strings.not_accepting_status);
          connection_lost_type = 'offline_status';
          var data = {
            action: 'wplc_choose_not_accepting',
            security: wplc_admin_strings.nonce,
            user_id:  wplc_admin_strings.user_id
          };
          wplc_ma_update_agent_transient(data);
          wplc_ma_update_admin_bar(-1, "click off"); //remove one agent
        }
      };

    }
  }
}

//Parse the current value 
function wplc_ma_update_admin_bar(amount, where){
    wplc_online_agent_count = wplc_ma_parse_active_count_from_container();
    wplc_online_agent_count += amount; //we can add a negative value to remove

    if(wplc_online_agent_count < 0){
        wplc_online_agent_count = 0; //Force to lowest possible
    }

    if(wplc_online_agent_count > 0){
        //Online
        jQuery("#wplc_ma_online_agents_circle").removeClass("wplc_red_circle");
        if(!jQuery("#wplc_ma_online_agents_circle").hasClass("wplc_green_circle")){
            jQuery("#wplc_ma_online_agents_circle").addClass("wplc_green_circle");
        }
    } else {
        //Offline
        jQuery("#wplc_ma_online_agents_circle").removeClass("wplc_green_circle");
        if(!jQuery("#wplc_ma_online_agents_circle").hasClass("wplc_red_circle")){
            jQuery("#wplc_ma_online_agents_circle").addClass("wplc_red_circle");
        }
    }

    jQuery("#wplc_ma_online_agents_count").text(wplc_online_agent_count);
}

//Get the value currently stored in the admin bar
function wplc_ma_parse_active_count_from_container(){
    return parseInt(jQuery("#wplc_ma_online_agents_count").text());
}