jQuery(document).ready(function($){
console.log("Admin script loaded");

 jQuery('#ofievent_date').datepicker();

 jQuery("#btn-add-new-event-test").click(function(event){
    jQuery("#ofievent-alert").html("Successfully tested!");
    jQuery("#ofievent-alert").show();
    jQuery("#ofievent-alert").removeClass("alert-danger");
    jQuery("#ofievent-alert").addClass("alert-success");

    window.setInterval(function(){
        jQuery("#ofievent-alert").show();
        jQuery("#ofievent-alert").addClass("alert-danger");
        jQuery("#ofievent-alert").removeClass("alert-success");
    }, 3000);
 });

 jQuery("#btn-add-new-event").click(function(event){
    jQuery("#ofievent-alert").hide();

    var editorContent;
    var editorId = 'ofievent_description';

    if (typeof tinyMCE !== 'undefined' && tinyMCE.get(editorId) && !tinyMCE.get(editorId).isHidden()) {
        window.tinyMCE.triggerSave();
        editorContent = tinyMCE.get(editorId).getContent();
    } else {
        editorContent = jQuery('#' + editorId).val();
    }    

    jQuery.ajax({
          type: "post",
          url: ofievent_Ajax.url,
          data:{  action: "ofievent_ajax_new_event",
                    nonce: ofievent_Ajax.nonce,
                    author_id: jQuery("#ofievent_author_id").val(),
                    event_title: jQuery("#ofievent_title").val(),
                    event_date: jQuery("#ofievent_date").val(),
                    event_time: jQuery("#ofievent_time").val(),
                    event_price: jQuery("#ofievent_price").val(),
                    event_location: jQuery("#ofievent_location").val(),
                    event_latitude: jQuery("#ofievent_latitude").val(),
                    event_longitude: jQuery("#ofievent_longitude").val(),
                    event_description: editorContent,
                    event_timezone: jQuery("#ofievent_timezone").val()
                },
          success:function(ajaxresponse){
                     response_json = JSON.parse(ajaxresponse);
                        if(response_json.status == "success"){
                            jQuery("#ofievent-alert").html(response_json.message + " ID: " + response_json.id);
                            jQuery("#ofievent-alert").show();
                            jQuery("#ofievent-alert").removeClass("alert-danger");
                            jQuery("#ofievent-alert").addClass("alert-success");
                        }else{
                            jQuery("#ofievent-alert").html(response_json.message);
                            jQuery("#ofievent-alert").show();
                            jQuery("#ofievent-alert").removeClass("alert-success");
                            jQuery("#ofievent-alert").addClass("alert-danger");
                        }
                    },
          error:function(xhr,status,error){
                    
                },
          complete:function(xhr,status,error){
                    window.setInterval(function(){
                        //jQuery("#ofievent-alert").hide();
                    }, 5000);
                  },
      });//ajax        

      
  });//button click

});