<label>
    <div class="lu-switch">
        <input type="checkbox" class="lu-switch__checkbox" name="{$rawObject->getName()}"
               namespace="{$namespace}" index="{$rawObject->getIndex()}" id="{$rawObject->getId()}"
        {foreach $htmlAttributes as $aValue} {$aValue@key}="{$aValue}" {/foreach}
        {literal}:checked="dataRow.{/literal}{$rawObject->getSwitchColumnName()}{literal} == {/literal}{if $rawObject->getSwitchOnValue()|is_string}'{$rawObject->getSwitchOnValue()}'{else}{$rawObject->getSwitchOnValue()}{/if}{literal} ? 'checked' : ''"{/literal}
        >
        <span data-title="{$rawObject->getTitle()}" data-toggle="lu-tooltip" class="lu-i-c-2x lu-form-tooltip-helper lu-tooltip drop-target drop-element-attached-bottom drop-element-attached-center drop-target-attached-top drop-target-attached-center drop-abutted drop-abutted-top lu-switch__container"><span class="lu-switch__handle"></span></span>
    </div>
</label>
