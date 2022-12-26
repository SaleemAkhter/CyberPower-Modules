{**********************************************************************
* WordpressManager product developed. (2017-10-04)
* *
*
*  CREATED BY MODULESGARDEN       ->       http://modulesgarden.com
*  CONTACT                        ->       contact@modulesgarden.com
*
*
* This software is furnished under a license and may be used and copied
* only  in  accordance  with  the  terms  of such  license and with the
* inclusion of the above copyright notice.  This software  or any other
* copies thereof may not be provided or otherwise made available to any
* other person.  No title to and  ownership of the  software is  hereby
* transferred.
*
*
**********************************************************************}

{**
* @author Sławomir Miśkowicz <slawomir@modulesgarden.com>
*}
<script src="//cdnjs.cloudflare.com/ajax/libs/list.js/2.3.1/list.min.js"></script>

{if $rawObject->haveInternalAlertMessage()}
    <div class="lu-alert {if $rawObject->getInternalAlertSize() !== ''}lu-alert--{$rawObject->getInternalAlertSize()}{/if} lu-alert--{$rawObject->getInternalAlertMessageType()} lu-alert--faded modal-alert-top">
        <div class="lu-alert__body">
            {if $rawObject->isInternalAlertMessageRaw()|unescape:'html'}{$rawObject->getInternalAlertMessage()}{else}{$MGLANG->T($rawObject->getInternalAlertMessage())|unescape:'html'}{/if}
        </div>
    </div>
{/if}
{if $rawObject->getConfirmMessage()}
    {if $rawObject->isTranslateConfirmMessage()}
        {$MGLANG->T($rawObject->getConfirmMessage())|unescape:'html'}
    {else}
        {$rawObject->getConfirmMessage()|unescape:'html'}
    {/if}
{/if}

<form id="{$rawObject->getId()}" namespace="{$namespace}" index="{$rawObject->getIndex()}" mgformtype="{$rawObject->getFormType()}"
      {foreach $htmlAttributes as $aValue} {$aValue@key}="{$aValue}" {/foreach}>
    {if $rawObject->getClasses()}
        <div class="{$rawObject->getClasses()}">
    {/if}
        {if $rawObject->getSections()}
            {foreach from=$rawObject->getSections() item=section }
                {$section->getHtml()}
            {/foreach}
        {else}
            {foreach from=$rawObject->getFields() item=field }
                {$field->getHtml()}
            {/foreach}
        {/if}
    {if $rawObject->getClasses()}
        </div>
    {/if}
    <div class="lu-col-md-12 ui-form-submit">
        {$rawObject->getSubmitHtml()}
    </div>
</form>
<div id="progress_bar" style="display: none;" >
    <div class="shadow1 p-20 ">
        <center>
            <div class="row sai_main_head" style="width:100%;" align="center">
                <div class="col-sm-5 col-xs-5" style="padding:0 10px 0 0; text-align:right;">
                    <i class="fas fa-copy fa-2x" style="color:#00A0D2;"></i>
                </div>
                <div class="col-sm-7 col-xs-7" style="padding-top:10px; padding-left:0; text-align:left;">Installing New Wordpress</div>
            </div><hr><br>

            <font size="4" id="progress_txt" style="width: 100%;">Checking the submitted data </font>
            <font style="font-size: 18px;font-weight: 400; width: 100%;" id="progress_percent"></font><br><br>
        </center>
        <table width="500" cellpadding="0" cellspacing="0" id="table_progress" border="0" align="center" height="28" style="border:1px solid #CCC; -moz-border-radius: 5px;
        -webkit-border-radius: 5px; border-radius: 5px; width: 50%;">
            <tbody><tr>
                <td id="progress_color" width="1%" style="background-image: url(modules/addons/WordpressManager/templates/client/default/assets/img/wordpressmanager/bar.gif); -moz-border-radius: 4px; -webkit-border-radius: 4px; border-radius: 4px;"></td>
                <td id="progress_nocolor">&nbsp;</td>
            </tr>
        </tbody></table>
        <br><center><b>NOTE:</b> This may take 3-4 minutes. You can leave this page if you wish !</center><br><br>
    </div>
</div>
<div class="shadow1 p-20 " id="completed" style="display: none;" >
        <h5>Congratulations, the software was installed successfully</h5>
        <hr class="sai_main_head_hr" style="width:20%;">
        <p style="font-size:13px;">
        WordPress has been successfully installed at : <br>
        <a id="insurl" href="" target="_blank"></a>
        <br>Admin URL : <a id="adminurl" href="" target="_blank"></a><br><br>
        We hope the installation process was easy.<br><br>
        <b>NOTE</b>: Softaculous is just a auto installer and does not provide any support for the software packages. Please visit the script or software's web site for any kind of support!<br><br>
        Regards,<br>
        Softaculous Auto Installer
        </p>
        <br><br>
        <center><a id="import_wp_management" role="button" href="{$rawObject->installationPageLink()}"><span class="btn btn-primary">Return to Installations</span></a>&nbsp;</center>
    </div>
    <div class="shadow1 p-20 " id="completed_error" style="display: none;" >
        <h5>Unfortunatly  the software could not be installed successfully</h5>
        <hr class="sai_main_head_hr" style="width:20%;">
        <p style="font-size:13px;">
        There was an error installing wordpress. Please try again later.
        </p>
        <br><br>
        <center><a id="import_wp_management" role="button" href="{$rawObject->installationPageLink()}"><span class="btn btn-primary">Return to Installations</span></a>&nbsp;</center>
    </div>
