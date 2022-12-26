{**********************************************************************
* ModuleFramework product developed. (2017-10-04)
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
    <div class="alert {if $rawObject->getInternalAlertSize() !== ''}alert--{$rawObject->getInternalAlertSize()}{/if} alert-{$rawObject->getInternalAlertMessageType()} alert--faded modal-alert-top">
        <div class="alert__body">
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

<form id="{$rawObject->getId()}" namespace="{$namespace}" index="{$rawObject->getIndex()}" mgformaction="{$rawObject->getFormType()}">
    {if $rawObject->getSections()}
        {foreach from=$rawObject->getSections() item=section }
            {$section->getHtml()}
        {/foreach}
    {else}
        {foreach from=$rawObject->getFields() item=field }
            {$field->getHtml()}
        {/foreach}
    {/if}

    {if $rawObject->getButtons()}
        {foreach from=$rawObject->getButtons() item=button }
                    {$button->getHtml()}
        {/foreach}
    {/if}
 </form>
