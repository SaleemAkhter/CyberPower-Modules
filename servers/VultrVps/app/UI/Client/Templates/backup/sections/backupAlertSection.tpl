{if $rawObject->haveInternalAlertMessage()}
    <div class="alert {if $rawObject->getInternalAlertSize() !== ''}alert--{$rawObject->getInternalAlertSize()}{/if} alert-{$rawObject->getInternalAlertMessageType()} alert--faded modal-alert-top">
        <div class="alert__body">
            {if $rawObject->isInternalAlertMessageRaw()|unescape:'html'}{$rawObject->getInternalAlertMessage()}{else}{$MGLANG->T($rawObject->getInternalAlertMessage())|unescape:'html'}{/if}
        </div>
    </div>
{/if}
{if $rawObject->getSections()}
    {foreach from=$rawObject->getSections() item=section }
        {$section->getHtml()}
    {/foreach}
{else}
    <div id="{$rawObject->getId()}" class="lu-col-md-12 {$rawObject->getClasses()}">
        {foreach from=$rawObject->getFields() item=field }
            {$field->getHtml()}
        {/foreach}
    </div>
{/if}
