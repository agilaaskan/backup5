require([ "jquery" ], function(jQuery) {

    jQuery(document).ready(function(){
        var checkExistt4 = setInterval(function() {
            if (jQuery('.admin__fieldset > [data-index="faq_qes"]').length) {
            // jQuery('.admin__fieldset > [data-index="faq_qes"]').hide();
            // jQuery('.admin__fieldset > [data-index="faq_ans"]').hide();
            jQuery('.admin__fieldset > [data-index="faq_qes"] .admin__control-textarea').attr('readonly', true);
            jQuery('.admin__fieldset > [data-index="faq_ans"] .admin__control-textarea').attr('readonly', true);

            
            jQuery("<textarea></textarea>").attr({type: "text", class: "faq-qes-inputbox"}).insertAfter('.admin__fieldset > [data-index="faq_ans"]');
            jQuery("<textarea></textarea>").attr({type: "text", class: "faq-ans-inputbox"}).insertAfter('.faq-qes-inputbox');
            jQuery("<input></input>").attr({type: "button", class: "faq-add", value: "Add"}).insertAfter('.faq-ans-inputbox');
            jQuery(".faq-qes-inputbox").css("width", "50%");
            jQuery(".faq-ans-inputbox").css("width", "50%");
            clearInterval(checkExistt4);
        }
    }, 100);


            jQuery( "body" ).delegate( ".faq-add", "click", function() {
                var qes1 = jQuery('.faq-qes-inputbox:last').val();
                var ans1 = jQuery('.faq-ans-inputbox:last').val();
                var oldqes = jQuery('.admin__fieldset > [data-index="faq_qes"] .admin__control-textarea').val();
                var oldans = jQuery('.admin__fieldset > [data-index="faq_ans"] .admin__control-textarea').val();
                if(oldqes == null) {
                jQuery('.admin__fieldset > [data-index="faq_qes"] .admin__control-textarea').attr('value', qes1);
                }
                else {
                jQuery('.admin__fieldset > [data-index="faq_qes"] .admin__control-textarea').attr('value', oldqes + "," + qes1);

                }
                if(oldans == null) {
                jQuery('.admin__fieldset > [data-index="faq_ans"] .admin__control-textarea').attr('value', ans1);
                }
                else {
                jQuery('.admin__fieldset > [data-index="faq_ans"] .admin__control-textarea').attr('value', oldans + "," + ans1);
                }
                // jQuery('.admin__fieldset > [data-index="faq_qes"] .admin__control-textarea').val(oldqes + "," + qes1 );
                // jQuery('.admin__fieldset > [data-index="faq_ans"] .admin__control-textarea').val(oldans + "," + ans1 );
                jQuery("<textarea></textarea>").attr({type: "text", class: "faq-qes-inputbox"}).insertBefore('.faq-add');
                jQuery(".faq-qes-inputbox").css("width", "50%");
                jQuery("<textarea></textarea>").attr({type: "text", class: "faq-ans-inputbox"}).insertBefore('.faq-add');
                jQuery(".faq-ans-inputbox").css("width", "50%"); 

            });

            
        });

}); 