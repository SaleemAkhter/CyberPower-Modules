<div class="lu-alert lu-alert--outline lu-alert--info">
    <div class="lu-alert__body">
        {if $rawObject->isRawTitle()}{$rawObject->getRawTitle()}{elseif $rawObject->getTitle()}{$MGLANG->T($rawObject->getTitle())}{/if}
    </div>
</div>