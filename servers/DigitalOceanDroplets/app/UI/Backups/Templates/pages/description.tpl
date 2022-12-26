{if $rawObject->haveInternalAllertMessage()}
    <div class="alert {if $rawObject->getInternalAllertSize() !== ''}alert--{$rawObject->getInternalAllertSize()}{/if} alert-{$rawObject->getInternalAllertMessageType()} alert--faded datatable-alert-top">
        <div class="alert__body">
            {if $rawObject->isInternalAllertMessageRaw()|unescape:'html'}{$rawObject->getInternalAllertMessage()}{else}{$MGLANG->T($rawObject->getInternalAllertMessage())|unescape:'html'}{/if}
        </div>
    </div>
{/if}