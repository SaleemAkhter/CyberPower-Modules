if($('#redirectDirectAdminForm').length === 1){
    sleep(500).then(() => {
        $('#redirectDirectAdminForm').trigger('submit')
    });

}
function sleep (time) {
    return new Promise((resolve) => setTimeout(resolve, time));
}