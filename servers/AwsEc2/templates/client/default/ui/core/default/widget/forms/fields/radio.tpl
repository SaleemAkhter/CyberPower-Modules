<div class="control-group {$style.classes}">
    {if $rawObject->isRawTitle() || $rawObject->getTitle()}
            <label class="checkbox-switch-label">
                {if $rawObject->isRawTitle()}{$rawObject->getRawTitle()}{elseif $rawObject->getTitle()}{$MGLANG->T($rawObject->getTitle())}{/if}
            </label>
    {/if}
    {if $rawObject->getDescription()}
        <div class="lu-row">
            <div class="lu-col-md-offset-{$rawObject->getLabelWidth()} lu-col-md-{$rawObject->getWidth()}">
                <span class="lu-help-block">
                    {$MGLANG->T($rawObject->getDescription())}
                </span>
            </div>
        </div>
    {/if}
    {if $rawObject->getAvailableValues()|is_array}
        {foreach from=$rawObject->getAvailableValues() key=opValue item=option}
            <div class="radio">
                <label class="radio-inline icheck-label">
                    <input type="radio" class="icheck-control" name="{$rawObject->getName()}" id="radio{$opValue}" value="{$opValue}"  {if $opValue|in_array:$rawObject->getValue()}checked{/if}
                        {if $rawObject->isDisabled()}disabled="disabled"{/if}
                         {foreach $htmlAttributes as $aValue} {$aValue@key}="{$aValue}" {/foreach}
                        >
                    {$option}
                </label>
            </div>
        {/foreach}
    {/if}
</div>
