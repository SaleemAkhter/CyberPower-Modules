<div class="lu-row mt-20">
    <div class="lu-col-md-8">
        <div class=" lu-form-group--horizontal " {foreach $htmlAttributes as $aValue} {$aValue@key}="{$aValue}" {/foreach}>

            <label class="form-label lu-col-md-2">
                {if $rawObject->isRawTitle()}{$rawObject->getRawTitle()}{elseif $rawObject->getTitle()}{$MGLANG->T($rawObject->getTitle())}{/if}
                {if $rawObject->getDescription()}
                <i data-title="{$MGLANG->T($rawObject->getDescription())}" data-toggle="lu-tooltip" class="i-c-2x lu-zmdi lu-zmdi-help-outline form-tooltip-helper lu-tooltip "></i>
                {/if}
            </label>
            <div class="lu-flex-grow-1 lu-col-md-10">
                <div class="lu-row">
                    <div class="lu-col-md-6">
                        <select
                        class="lu-form-control"
                        name="{$rawObject->getName()}"
                        {if $rawObject->isDisabled()}disabled="disabled"{/if}
                        {if $rawObject->isMultiple()}data-options="removeButton:true; resotreOnBackspace:true; dragAndDrop:true; maxItems: null;" multiple="multiple"{/if}
                        >
                        {if $rawObject->getValue()|is_array}
                        {foreach from=$rawObject->getAvailableValues() key=opValue item=option}
                        <option value="{$opValue}" {if $opValue|in_array:$rawObject->getValue()}selected{/if}>
                            {$option}
                        </option>
                        {/foreach}
                        {else}
                        {foreach from=$rawObject->getAvailableValues() key=opValue item=option}
                        <option value="{$opValue}" {if $opValue===$rawObject->getValue()}selected{/if}>
                            {$option}
                        </option>
                        {/foreach}
                        {/if}
                    </select>
                    <div class="form-feedback form-feedback--icon" hidden="hidden">
                    </div>
                </div>
                <div class="lu-col-md-6">
                    <div class="lu-row">
                        <div class="lu-col-md-8">
                    <input class="lu-form-control" type="text" placeholder="{$rawObject->getInputPlaceholder()}" name="{$rawObject->getInputName()}" id="{$rawObject->getInputName()}"
                    value="{$rawObject->getInputValue()}" {if $rawObject->getIsInputDisabled()}disabled="disabled"{/if}
                    {foreach $htmlAttributes as $aValue} {$aValue@key}="{$aValue}" {/foreach}>
                    </div>
                        <div class="lu-col-md-4">
                    <span>{$rawObject->getInputLabel()}</span>
                    </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</div>
