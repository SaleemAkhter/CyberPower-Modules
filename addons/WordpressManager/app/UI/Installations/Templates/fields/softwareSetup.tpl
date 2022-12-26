{* <button type="button" class="lu-btn pull-right lu-btn--success sai-button quick_install_form_toggle_btn" id="show_full_form" style="display:none;" onclick="custom_install();return false;">Custom Install</button>
<button type="button" class="lu-btn pull-right lu-btn--success sai-button quick_install_form_toggle_btn" id="show_less_form" style="" onclick="quick_install();return false;">Quick Install</button> *}
<div class="lu-row">
    <div class="lu-col-sm-7">
        <label class="lu-form-label d-block mb-0" for="softdirectory">Choose Installation URL</label>
        <span class="sai_exp2">Please choose the URL to install the software</span>
        <div class="lu-row mt-2" style="margin-left:0px; margin-right:0px;">
            <div class="lu-col-sm-3 custom_install" style="padding-left:0px; padding-right:1px; width:24%;">
                <select name="softproto" class="lu-form-control" id="softproto" onblur="checkhttps('softproto', 'softdomain', false)"><option value="1">http://</option><option value="2">http://www.</option><option value="3">https://</option><option value="4">https://www.</option></select>
                <span class="sai_exp2 ml-1">Choose Protocol
                    &nbsp;<a data-toggle="tooltip" data-html="true" title="" data-original-title="If your site has SSL, then please choose the HTTPS protocol."><i class="fas fa-info-circle" style="font-size:1.1em; vertical-align:middle;cursor:pointer;color:#4B4B4B;"></i></a><span class="sai_exp" style="display: none;">If your site has SSL, then please choose the HTTPS protocol.</span>
                </span>
            </div>
            <div class="lu-col-sm-6" style="padding-left:0px; padding-right:0px;">
                <select name="softdomain" class="lu-form-control" id="softdomain" onchange="softmail();change_admin_prefix(this.value);" onblur="checkhttps('softproto', 'softdomain', false)">
                    {foreach from=$rawObject->getDomains() item=domain key=key name=domainlist}
                        <option value="{$domain}">{$domain}</option>
                    {/foreach}
                </select>
                <span class="sai_exp2 ml-1">Choose Domain
                    &nbsp;<a data-toggle="tooltip" data-html="true" title="" data-original-title="Please choose the domain to install the software."><i class="fas fa-info-circle" style="font-size:1.1em; vertical-align:middle;cursor:pointer;color:#4B4B4B;"></i></a><span class="sai_exp" style="display: none;">Please choose the domain to install the software.</span>
                </span>
            </div>
            <div class="lu-col-sm-3" style="padding-left:1px;padding-right:0px;">
                <input type="text" name="softdirectory" class="lu-form-control" id="softdirectory" size="30" value="wp">
                <span class="sai_exp2" style="margin-left:4px;">In Directory
                    &nbsp;<a data-toggle="tooltip" data-html="true" title="" data-original-title="The directory is relative to your domain and <b>should not exist</b>. e.g. To install at http://mydomain/dir/ just type <b>dir</b>. To install only in http://mydomain/ leave this empty."><i class="fas fa-info-circle" style="font-size:1.1em; vertical-align:middle;cursor:pointer;color:#4B4B4B;"></i></a><span class="sai_exp" style="display: none;">The directory is relative to your domain and <b>should not exist</b>. e.g. To install at http://mydomain/dir/ just type <b>dir</b>. To install only in http://mydomain/ leave this empty.</span>
                </span>
            </div>

            <div class="lu-row lu-col-sm-12">
                <div style="display:none;" id="checkhttps_wait">
                    <img src="images/themes/default/images/progress.gif" alt="Please wait..">
                </div>
                <span id="httpserror" style="display:none; padding:10px; margin-bottom:0px;" class="alert alert-warning"></span>
            </div>

        </div><!-- -- lu-row mt-2 ---->
    </div><!-- -- lu-col-7 ---->

    <div class="lu-col-sm-5">
        <label for="softbranch" class="lu-form-label d-block mb-0">Choose the version you want to install</label>
        <span class="sai_exp2">Please select the version to install.</span><br>
        <select name="softbranch" class="lu-form-control d-inline-block softbranch mt-2" id="softbranch" onchange="selectversion()" style="width:25%;"><option value="26" selected="selected">6.0.1</option><option value="702" >5.9.3</option><option value="694">5.8.4</option><option value="684">5.7.6</option><option value="678">5.6.8</option><option value="672">5.5.9</option><option value="666">5.4.10</option><option value="660">5.3.12</option><option value="656">5.2.15</option><option value="650">5.1.13</option><option value="648">5.0.16</option><option value="646">4.9.20</option>
        </select>
        <div style="display:none;" id="multiver_wait"><img src="images/themes/default/images/progress.gif" alt="please wait.."></div>
        <br>
    </div><!-- -- lu-col-5 ---->

</div>
