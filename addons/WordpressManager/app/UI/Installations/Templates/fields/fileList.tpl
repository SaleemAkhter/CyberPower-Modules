{* <div class="lu-form-group">
    <label class="lu-form-label">
        <label for="advoptions_toggle" class="sai_head mb-2">Select additional files/folders</label>
    &nbsp;<a data-toggle="tooltip" data-html="true" title="" data-original-title="Please select the additonal files/folders associated with this installation. <br>This list will be used for operations like Clone, Backup and Removal of this installation"><i class="fas fa-info-circle" style="font-size:1.1em; vertical-align:middle;cursor:pointer;color:#4B4B4B;"></i></a><span class="sai_exp" style="display: none;">Please select the additonal files/folders associated with this installation. <br>This list will be used for operations like Clone, Backup and Removal of this installation</span><br>
        <div id="advoptions_toggle"  class="sai_head" style="background: none; cursor:pointer;"><i id="advoptions_toggle_plus" class="fas fa-minus-square"></i> &nbsp; Select additional files/folders from the following list</div>
    </label>
    <div id="selectfile" style="">
        <table class="table table-hover">
            <thead class="sai_head2">
                <tr>
                    <th width="5%"><input type="checkbox" id="check_all_edit" name="check_all_edit"></th>
                    <th colspan="2">Check All</th>
                </tr>
            </thead><tbody><tr>
                        <td><input type="checkbox" name="add_to_fileindex[]" class="soft_filelist" value="cgi-bin" 1="" id="add_to_fileindex_cgi-bin"></td>
                        <td width="5%"><img src="images/themes/default/images/dir.png"></td>
                        <td><label for="add_to_fileindex_cgi-bin" style="cursor:pointer;">cgi-bin</label></td>
                    </tr><tr>
                        <td><input type="checkbox" name="add_to_fileindex[]" class="soft_filelist" value="popularfx.zip" checked="checked" id="add_to_fileindex_popularfx.zip"></td>
                        <td width="5%"><img src="images/themes/default/images/file.png"></td>
                        <td><label for="add_to_fileindex_popularfx.zip" style="cursor:pointer;">popularfx.zip</label></td>
                    </tr><tr>
                        <td><input type="checkbox" name="add_to_fileindex[]" class="soft_filelist" value="sorento" 1="" id="add_to_fileindex_sorento"></td>
                        <td width="5%"><img src="images/themes/default/images/dir.png"></td>
                        <td><label for="add_to_fileindex_sorento" style="cursor:pointer;">sorento</label></td>
                    </tr><tr>
                        <td><input type="checkbox" name="add_to_fileindex[]" class="soft_filelist" value="index.html_" 1="" id="add_to_fileindex_index.html_"></td>
                        <td width="5%"><img src="images/themes/default/images/file.png"></td>
                        <td><label for="add_to_fileindex_index.html_" style="cursor:pointer;">index.html_</label></td>
                    </tr><tr>
                        <td><input type="checkbox" name="add_to_fileindex[]" class="soft_filelist" value="staging" 1="" id="add_to_fileindex_staging"></td>
                        <td width="5%"><img src="images/themes/default/images/dir.png"></td>
                        <td><label for="add_to_fileindex_staging" style="cursor:pointer;">staging</label></td>
                    </tr><tr>
                        <td><input type="checkbox" name="add_to_fileindex[]" class="soft_filelist" value="one" 1="" id="add_to_fileindex_one"></td>
                        <td width="5%"><img src="images/themes/default/images/dir.png"></td>
                        <td><label for="add_to_fileindex_one" style="cursor:pointer;">one</label></td>
                    </tr>
        </tbody></table>
    </div>

    <div class="lu-form-feedback lu-form-feedback--icon" hidden="hidden">
    </div>
</div> *}

<div class="lu-form-group">
    <label class="lu-form-label">
        Select additional files/folders
    </label>
    <div id="advoptions_toggle" onclick="toggle_advoptions('selectfile')" class="sai_head" style="background: none; cursor:pointer;"><i id="advoptions_toggle_plus" class="fas fa-plus-square"></i> &nbsp; Select additional files/folders from the following list</div>
    <div id="selectfile" style="display: none;">
        <table class="lu-table lu-table--mob-collapsible dataTable no-footer dtr-column table-striped">
            <thead class="sai_head2">
                <tr>
                    <th width="10%"><input type="checkbox" id="check_all_edit" name="check_all_edit"></th>
                    <th colspan="2">Check All</th>
                </tr>
            </thead>
            <tbody>
                {foreach from=$rawObject->getFiles() key=file item=info }
                    <tr>
                        <td><input type="checkbox" name="add_to_fileindex[]" class="soft_filelist" value="{$file}"  id="add_to_fileindex_{$file}"></td>
                        <td width="10%"><img src="{$rawObject->getAssetsUrl()}/img/{if $info['type']=='directory'}dir.png{else}file.png{/if}"></td>
                        <td><label for="add_to_fileindex_{$file}" style="cursor:pointer;">{$file}</label></td>
                    </tr>
                {/foreach}

            </tbody>
        </table>
    </div>
    {literal}
    <script>
        jQuery("#advoptions_toggle").on("click",function(){
            jQuery("#advoptions_toggle_plus").toggleClass(['fa-plus-square','fa-minus-square']);
        });

        $(document).ready(function(){

            $("#check_all_edit").on("click", function(event){
                if(this.checked == true){
                    $(".soft_filelist").prop("checked", true);
                }else{
                    $(".soft_filelist").prop("checked", false);
                }
            });

            $("#check_all_tables").on("click", function(event){
                if(this.checked == true){
                    $(".soft_tablelist").prop("checked", true);
                }else{
                    $(".soft_tablelist").prop("checked", false);
                }
            });

            $(".sai_altrowstable tr").mouseover(function(){
                var old_class = $(this).attr("class");
                //alert(old_class);
                $(this).attr("class", "sai_tr_bgcolor");

                $(this).mouseout(function(){
                    $(this).attr("class", old_class);
                });
            });

        });
    </script>
    {/literal}
</div>
