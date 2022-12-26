<div class="top m-b-4x">
    <div class="top__addon">
        <div class="i-c i-c-8x">
            <img src="{$rawObject->getAssetsUrl()}/img/cpanel/icon-{$rawObject->getId()}.png">
        </div>
    </div>
    <div class="top__content">
        <div class="top__title">
            <div class="top__title-text type-4 m-b-2x">{$MGLANG->T($rawObject->getTitle())}</div>
        </div>
        <div class="p-3">{$MGLANG->T($rawObject->getDescription())}</div>
    </div>
</div>
{if $rawObject->haveInternalAllertMessage()}
    <div class="alert {if $rawObject->getInternalAllertSize() !== ''}alert--{$rawObject->getInternalAllertSize()}{/if} alert-{$rawObject->getInternalAllertMessageType()} alert--faded datatable-alert-top">
        <div class="alert__body">
            {if $rawObject->isInternalAllertMessageRaw()|unescape:'html'}{$rawObject->getInternalAllertMessage()}{else}{$MGLANG->T($rawObject->getInternalAllertMessage())|unescape:'html'}{/if}
        </div>
    </div>
{/if}