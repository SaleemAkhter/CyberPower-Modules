<div class="lu-form-group">
    <label class="lu-form-label">
        {if $rawObject->isRawTitle()}{$rawObject->getRawTitle()}{elseif $rawObject->getTitle()}{$MGLANG->T($rawObject->getTitle())}{/if}
        {if $rawObject->getDescription()}
            <i data-title="{$MGLANG->T($rawObject->getDescription())}" data-toggle="lu-tooltip" class="lu-i-c-2x lu-zmdi lu-zmdi-help-outline lu-form-tooltip-helper"></i>
        {/if}
    </label>
    <mg-component-body-{$elementId|strtolower}
            component_id='{$elementId}'
            component_namespace='{$namespace}'
            component_index='{$rawObject->getIndex()}'
            predefined_date='{$rawObject->getValue()}'
            date_format = '{$rawObject->getDateFormat()}'
            code = ''
            extensions = ''

    ></mg-component-body-{$elementId|strtolower}>
    <div class="lu-form-feedback lu-form-feedback--icon" hidden="hidden">
    </div>
</div>
