<div class="lu-widget mg-configurable-options-panel {$class}" id="{$elementId}" {foreach from=$htmlAttributes key=name item=data} {$name}="{$data}"{/foreach}>
    <div class="lu-widget__header">
        <div class="lu-widget__top lu-top">
            <div class="lu-top__title">
                {if $rawObject->isRawTitle()}
                    {$rawObject->getRawTitle()}
                {elseif $rawObject->getTitle()}
                    {$MGLANG->T($rawObject->getTitle())}
                {/if}
            </div>
        </div>
        <div class="lu-top__toolbar">

        </div>
    </div>
    <div class="lu-widget__body">
        <div class="lu-widget__content config-option-box">
            {if $customTplVars['options']}
                <div class="lu-row">
                    {foreach from=$customTplVars['options'] key=optKey item=optItem}
                        <div class="lu-col-md-6 lu-p-r-4x config-option text-left">
                            <b>{$optItem['optionname']}</b>
                        </div>
                    {/foreach}
                    {if ($customTplVars['options']|count) %2 !== 0}
                        <div class="lu-col-md-6 lu-p-r-4x config-option text-left">
                        </div>
                    {/if}
                </div>
            {/if}
            <div class="col-md-12 confirm-row">
                {foreach from=$rawObject->getButtons() key=buttonKey item=button}
                    {$button->getHtml()}
                {/foreach}
            </div>
        </div>
    </div>
</div>
