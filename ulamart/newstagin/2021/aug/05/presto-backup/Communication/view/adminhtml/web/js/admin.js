require(['jquery'], function () {
    function show_alert(){
    jQuery('.del_form').submit(function() {
    var status = confirm("Are you sure you what to delete the conversation?");
    if(status == false){
    return false;
    }
    else{
    return true; 
    }
    })
}
});
