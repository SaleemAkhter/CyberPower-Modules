{assign var="assetsUrl" value=$rawObject->getAssetsUrl()}

<div class="lu-top lu-m-b-4x">
    <div class="lu-top__addon">
        <div class="lu-i-c lu-i-c-8x">
            <img src="{$rawObject->getAssetsUrl()}/img/directadmin/icon-home.svg#{$rawObject->getId()}">
        </div>
    </div>
    <div class="lu-top__content">
        <div class="lu-top__title">
            <div class="lu-top__title-text lu-type-4 lu-m-b-2x">{$MGLANG->T($rawObject->getTitle())}</div>
        </div>
        <div class="lu-p-3">{$MGLANG->T($rawObject->getDescription())}</div>
    </div>
</div>
{if $rawObject->haveInternalAlertMessage()}
    <div class="lu-alert {if $rawObject->getInternalAlertSize() !== ''}lu-alert--{$rawObject->getInternalAlertSize()}{/if} lu-alert--{$rawObject->getInternalAlertMessageType()} lu-alert--faded modal-alert-top">
        <div class="lu-alert__body">
            {if $rawObject->isInternalAlertMessageRaw()|unescape:'html'}{$rawObject->getInternalAlertMessage()}{else}{$MGLANG->T($rawObject->getInternalAlertMessage())|unescape:'html'}{/if}
        </div>
    </div>
{/if}
