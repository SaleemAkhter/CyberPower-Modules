{if $rawObject->haveInternalAlertMessage()}
    <div class="lu-alert {if $rawObject->getInternalAlertSize() !== ''}lu-alert--{$rawObject->getInternalAlertSize()}{/if} lu-alert-{$rawObject->getInternalAlertMessageType()} lu-alert--faded modal-alert-top">
        <div class="lu-alert__body">
            {if $rawObject->isInternalAlertMessageRaw()|unescape:'html'}{$rawObject->getInternalAlertMessage()}{else}{$MGLANG->T($rawObject->getInternalAlertMessage())|unescape:'html'}{/if}
        </div>
    </div>    
{/if}
{if $rawObject->getConfirmMessage()}
    {$rawObject->getConfirmMessage()}
{/if}

<form id="{$rawObject->getId()}" namespace="{$namespace}" index="{$rawObject->getIndex()}" mgformtype="{$rawObject->getFormType()}">
    {if $rawObject->getSections()}
        {foreach from=$rawObject->getSections() item=section }
            {$section->getHtml()}
        {/foreach}
    {else}
        {foreach from=$rawObject->getFields() item=field }
            {$field->getHtml()}
        {/foreach}
    {/if}
</form>
