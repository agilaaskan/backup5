require([ "jquery" ], function(jQuery) {

    jQuery(document).ready(function(){
        var checkExistt4 = setInterval(function() {
            if (jQuery('.admin__fieldset > [data-index="faq_qes"]').length) {
            jQuery('.admin__fieldset > [data-index="faq_qes"]').hide();
            jQuery('.admin__fieldset > [data-index="faq_ans"]').hide();
            
            jQuery("<textarea></textarea>").attr({type: "text", class: "faq-qes-inputbox"}).insertAfter('.admin__fieldset > [data-index="faq_ans"]');
            jQuery("<textarea></textarea>").attr({type: "text", class: "faq-ans-inputbox"}).insertAfter('.faq-qes-inputbox');
            jQuery("<input></input>").attr({type: "button", class: "faq-add", value: "Add"}).insertAfter('.faq-ans-inputbox');
            clearInterval(checkExistt4);
        }
    }, 100);
            jQuery( "body" ).delegate( ".faq-add", "click", function() {
                var qes1 = jQuery('.faq-qes-inputbox:last').val();
                var ans1 = jQuery('.faq-ans-inputbox:last').val();
                jQuery('.admin__fieldset > [data-index="faq_qes"] .admin__control-textarea').val(qes1 + ",");
                jQuery('.admin__fieldset > [data-index="faq_ans"] .admin__control-textarea').val(ans1 + ",");
                jQuery("<textarea></textarea>").attr({type: "text", class: "faq-qes-inputbox"}).insertBefore('.faq-add');
                jQuery("<textarea></textarea>").attr({type: "text", class: "faq-ans-inputbox"}).insertBefore('.faq-add');  
            });
            
        });
}); 