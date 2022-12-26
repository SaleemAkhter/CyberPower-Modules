<script src="https://unpkg.com/dropzone@5/dist/min/dropzone.min.js"></script>
<link rel="stylesheet" href="https://unpkg.com/dropzone@5/dist/min/dropzone.min.css" type="text/css" />
<div class="p-20" id="upload_plugins_themes">
    <form  class="dropzone my-2 p-2 dz-clickable" id="upload_form" style="display:block;">
</form>
        <div class="row mt-20">
            <div class="col-sm-12">
                <div class="lu-form-check lu-m-b-2x"><label><div class="lu-switch"><input type="checkbox" name="activate_pot" class="lu-switch__checkbox" id="activate_pot"> <span class="lu-switch__container"><span class="lu-switch__handle"></span></span></div> <span class="lu-form-text">Activate the plugin after upload</span></label></div>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-12">
                <div id="add_plth" class="wpc_inline"></div>
                <button type="button" class="mt-2 btn btn-primary" id="upload_pot" name="upload_pot">Upload</button>
            </div>
        </div>

</div>
<script type="text/javascript">
    jQuery(document).ready(function(){

        function removeSpinner(event) {
                            $(event.target).html($(event.target).attr('originall-button-content'));
                            $(event.target).removeAttr('originall-button-content');
                        }
        function addSpinner(event) {
            var elWidth = $(event.target).width();
            var spinnerClass = $(event.target).hasClass('lu-btn--success') ? 'lu-preloader-success' : ($(event.target).hasClass('lu-btn--danger') ? 'lu-preloader-danger' : '');
            $(event.target).attr('originall-button-content', $(event.target).html());
            $(event.target).html('<span class="lu-btn__icon lu-temp-button-loader" style="margin: 0 0 0 0 !important; width: ' + elWidth + 'px;"><i class="lu-btn__icon lu-preloader lu-preloader--sm ' + spinnerClass + '"></i></span>');
        }
          function addAlert(type, message){
                    type = (type === 'error') ? 'danger' : type;
                    layers.alert.create({
                        $alertPosition: 'right-top',
                        $alertStatus: type,
                        $alertBody: message,
                        $alertTimeout: 10000
                    });
                }
var clickevent='';
    $("#upload_form").dropzone({ url: "{$rawObject->getUploadPluginActionUrl()}",
            autoDiscover : false,
            paramName: "custom_file",
            createImageThumbnails: false,
            acceptedFiles: ".zip",
            maxFiles: 1,
            chunking: false,
            chunkSize: 500000,
            retryChunks: true,
            retryChunksLimit: 3,
            autoProcessQueue: false,
            chunksUploaded: function(file, done) {
                    done();
                },
            init: function () {

                var myDropzone = this;

                document.querySelector("#upload_pot").addEventListener("click", function(e) {
                    clickevent=e;
                    addSpinner(e);
                    e.preventDefault();
                    e.stopPropagation();
                    myDropzone.processQueue();
                });

                this.on("sending", function(file, xhr, formData){
                    var csrf_token = $("#csrf_token").val();

                    // formData.append("insid", current_insid);
                    if(csrf_token && csrf_token.length > 0){
                        formData.append("csrf_token", csrf_token);
                    }
                    if($("#activate_pot").is(":checked")){
                        formData.append("activate", 1);
                    }
                    formData.append("hostingId", 1);
                });

                this.on("complete", function(files, response) {
                    myDropzone.removeAllFiles();
                    var response = jQuery.parseJSON( files.xhr.response );

                    addAlert(response.data.status,response.data.message);
                    removeSpinner(clickevent);
                    setTimeout(function() {
                        location.reload();
                    }, 3000);
                });
            },
        });
    });
</script>
