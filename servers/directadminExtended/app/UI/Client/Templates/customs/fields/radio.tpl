<div class="control-group {$style.classes}">
    {if $rawObject->isRawTitle() || $rawObject->getTitle()}
            <div class="lu-col-md-{$rawObject->getLabelWidth()}">
                <label class="radio-switch-label">
                    {if $rawObject->isRawTitle()}{$rawObject->getRawTitle()}{elseif $rawObject->getTitle()}{$MGLANG->T($rawObject->getTitle())}{/if}
                </label>
            </div>
        {/if}
    {foreach from=$rawObject->getAvailableValues() key=opValue item=option}

    <div class="lu-row">
            {if $rawObject->getLabelPosition()=='left'}
                <div class="lu-col-md-{$rawObject->getLabelWidth()}">
                    <label class="radio-switch-label" for="{$rawObject->getName()}_{$opValue}">
                        {$MGLANG->T($option)}
                    </label>
                </div>
            {/if}

        <div class="lu-col-md-{$rawObject->getWidth()}">
            <div class="radio-container">
                <input  class="radio-switch"
                        data-on-text="{$MGLANG->absoluteT('bootstrapswitch','enabled')}"
                        data-off-text="{$MGLANG->absoluteT('bootstrapswitch','disabled')}"
                        data-on-color="success"
                        data-off-color="default"
                        data-size="mini"
                        data-label-width="15"
                        type="radio"
                        id="{$rawObject->getName()}_{$opValue}"
                        name="{$rawObject->getName()}"
                        value="{$opValue}"
                        {if $opValue==$rawObject->getValue()}checked{/if}
                        {if $rawObject->isDisabled()}disabled="disabled"{/if}
                        {foreach $htmlAttributes as $aValue} {$aValue@key}="{$aValue}" {/foreach}
                />
            </div>
        </div>
        {if $rawObject->getLabelPosition()=='right'}
            <div class="lu-col-md-{$rawObject->getLabelWidth()}">
                <label class="radio-switch-label" for="{$rawObject->getName()}_{$opValue}">
                    {$MGLANG->T($option)}
                </label>
            </div>
        {/if}
    </div>
    {/foreach}
    {if $rawObject->getDescription()}
        <div class="lu-row">
            <div class="lu-col-md-offset-{$rawObject->getLabelWidth()} lu-col-md-{$rawObject->getWidth()}">
                <span class="lu-help-block">
                    {$MGLANG->T($rawObject->getDescription())}
                </span>
            </div>
        </div>
    {/if}

</div>
