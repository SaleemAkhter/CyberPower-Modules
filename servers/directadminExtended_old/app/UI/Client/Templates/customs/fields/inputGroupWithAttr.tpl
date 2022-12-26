<div class="form-group" {foreach $htmlAttributes as $aValue} {$aValue@key}="{$aValue}" {/foreach}>
    <label class="form-label">
        {if $rawObject->isRawTitle()}{$rawObject->getRawTitle()}{elseif $rawObject->getTitle()}{$MGLANG->T($rawObject->getTitle())}{/if}
    </label>
        <div class="input-group">
            {foreach from=$rawObject->getFields() item=field}
                {$field->getHtml()}
            {/foreach}
        </div>
    <div class="form-feedback form-feedback--icon" hidden="hidden">
    </div>    
</div>