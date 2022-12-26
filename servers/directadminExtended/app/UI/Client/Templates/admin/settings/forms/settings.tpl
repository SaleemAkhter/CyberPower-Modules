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
<div class="lu-modal__nav">
        <ul class="lu-nav lu-nav--md lu-nav--h lu-nav--tabs">
            {assign var="sectArrKeys" value=$rawObject->getSections()|array_keys}
            {foreach from=$rawObject->getSections() key=sectionID  item=section }
                <li class="lu-nav__item {if $sectArrKeys[0] == $sectionID}is-active{/if}">
                    <a class="lu-nav__link" data-toggle="lu-tab" href="#modalTab{$sectionID}">
                        <span class="lu-nav__link-text">{$MGLANG->T($section->getName()|cat:"title")}</span>
                    </a>
                </li>
            {/foreach}
        </ul>
    </div>
    <form id="{$rawObject->getId()}" namespace="{$namespace}" index="{$rawObject->getIndex()}" mgformtype="{$rawObject->getFormType()}"
      {foreach $htmlAttributes as $aValue} {$aValue@key}="{$aValue}" {/foreach}>
      <input type="hidden" name="formType" value="" id="formType">
    <div class="lu-tab-content mt-20">
        {assign var="sectArrKeys" value=$rawObject->getSections()|array_keys}
        {foreach from=$rawObject->getSections() key=sectionID  item=section }
            <div class="lu-tab-pane {if $sectArrKeys[0] == $sectionID}is-active{/if}" id="modalTab{$sectionID}">
                <div class="lu-list-group lu-list-group--simple lu-list-group--p-h-0x list-group--collapse lu-m-b-0x">
                    <div class="lu-row">
                        {$section->getHtml()}
                    </div>
                    <div class="lu-row">
                        <div class="lu-col-md-12 ui-form-submit ">
                            {$rawObject->getSubmitHtml()}
                        </div>
                    </div>
                </div>
            </div>

        {/foreach}
    </div>

{if ($isDebug eq true AND (count($MGLANG->getMissingLangs()) != 0))}{literal}
                <div class="box-footer">
                    <div class="lu-row">
                        {/literal}{foreach from=$MGLANG->getMissingLangs() key=varible item=value}{literal}
                            <div class="lu-col-md-12"><b>{/literal}{$varible}{literal}</b> = '{/literal}{$value}{literal}';</div>
                        {/literal}{/foreach}{literal}
                    </div>
                </div>
            {/literal}{/if}
</form>

