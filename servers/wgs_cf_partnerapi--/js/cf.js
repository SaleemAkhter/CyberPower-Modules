function website(site) {
    $("#website").val(site);
    $("#cf_action").val('manageWebsite');
    $("#website-form").submit();
}

function submitCloudflareForm() {
    $("#cloudflare-form").submit();
}

function addZone(site){
    $("#website").val(site);
    $("#cf_action").val('addWebsite');
    $("#website-form").submit();
}

function deleteZone(site) {
    if(confirm("Are you sure to delete?")){
    $("#website").val(site);
    $("#cf_action").val('deleteWebsite');
    $("#website-form").submit();
    }
}

function showsuccess(){
    
}

function showerror(){
    
}
