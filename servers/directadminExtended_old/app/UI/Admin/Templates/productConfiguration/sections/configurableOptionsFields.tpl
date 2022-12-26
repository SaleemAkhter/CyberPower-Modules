<div class="lu-widget widgetActionComponent{$class}" id="{$elementId}" {foreach from=$htmlAttributes key=name item=data} {$name}="{$data}"{/foreach}>
    <div class="lu-widget__header">
        <div class="lu-widget__top lu-top">
            <div class="lu-top__title">
                {if $rawObject->getIcon()}<i class="{$rawObject->getIcon()}"></i>{/if}
                {if $rawObject->isRawTitle()}{$rawObject->getRawTitle()}{elseif $rawObject->getTitle()}{$MGLANG->T($rawObject->getTitle())}{/if}
            </div>
        </div>
        <div class="lu-top__toolbar">

        </div>
    </div>

    <div class="lu-widget__body">  
        <div class="lu-widget__content configOptionBox">
            {if $rawObject->getOptions()}
                <div class="lu-row">
                {foreach from=$rawObject->getOptions() key=oKey item=oName}
                    <div class="lu-col-md-4 lu-p-r-4x configOption text-left">
                        {if !empty($oName)}
                            <b> {$oKey}|{$oName}</b>  {* - {$MGLANG->T('description', $oKey)} *}
                        {/if}
                    </div>
                {/foreach}
                </div>
            {/if}
            {foreach from=$rawObject->getButtons() key=setting item=dataElement}
                <div class="lu-col-md-12 lu-p-r-4x center text-center configOptionButton">
                    {$dataElement->getHtml()}
                 </div>
            {/foreach}
        </div>
    </div>
</div>

{if ($isDebug && (count($MGLANG->getMissingLangs()) > 0))}
    <div class="lu-modal__actions">
        <div class="lu-row">
            {foreach from=$MGLANG->getMissingLangs() key=varible item=value}
                <div class="lu-col-md-12"><b>{$varible}</b> = '{$value}';</div>
            {/foreach}
        </div>
    </div>
{/if}
