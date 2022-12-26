
<div class="lu-widget">
    <div class="lu-widget__header">
        <div class="lu-widget__top lu-top">
            {if $rawObject->getRawTitle() || $rawObject->getTitle()}
                <div class="lu-top__title">
                    {if $rawObject->getIcon()}
                        <i class="{$rawObject->getIcon()}"></i>
                    {/if}
                    {if $rawObject->isRawTitle()}
                        {$rawObject->getRawTitle()}
                    {elseif $rawObject->getTitle()}
                        {$MGLANG->T($rawObject->getTitle())}
                    {/if}
                </div>
            {/if}
        </div>
    </div>
    <div class="lu-widget__body">
        <div  class="lu-widget__content">
            {foreach from=$elements key=nameElement item=dataElement}
                <div id="{$dataElement->getId()}" class="{$dataElement->getClass()}">
                    {$dataElement->getHtml()}
                </div>
            {/foreach}
        </div>

        {if ($isDebug eq true AND (count($MGLANG->getMissingLangs()) != 0))}
            <div class="lu-row" style="max-width: 95%;">
                {foreach from=$MGLANG->getMissingLangs() key=varible item=value}
                    <div class="lu-col-md-12"><b>{$varible}</b> = '{$value}';</div>
                {/foreach}
            </div>
        {/if}
    </div>
</div>


