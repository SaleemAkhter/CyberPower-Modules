<div class="control-group {$style.classes}">
    <div class="lu-row">
        {if $rawObject->isRawTitle() || $rawObject->getTitle()}
            <div class="lu-col-md-{$rawObject->getLabelWidth()}">
                <label class="radio-switch-label">
                    {if $rawObject->isRawTitle()}{$rawObject->getRawTitle()}{elseif $rawObject->getTitle()}{$MGLANG->T($rawObject->getTitle())}{/if}
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
                        name="{$rawObject->getName()}"
                        {if $rawObject->getValue() === 'on'}checked{/if}
                        {if $rawObject->isDisabled()}disabled="disabled"{/if}
                />
            </div>
        </div>
    </div>

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
