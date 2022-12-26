<div class="{if $rawObject->getClasses()}{$rawObject->getClasses()}{else} lu-form-group {/if}" class="lu-form-group">
    <label class="lu-form-label">
        {if $rawObject->isRawTitle()}{$rawObject->getRawTitle()}{elseif $rawObject->getTitle()}{$MGLANG->T($rawObject->getTitle())}{/if}
        {if $rawObject->getDescription()}
            <i data-title="{$MGLANG->T($rawObject->getDescription())}" data-toggle="lu-tooltip" class="lu-i-c-2x lu-zmdi lu-zmdi-help-outline lu-form-tooltip-helper"></i>
        {/if}
    </label>
    <div class="input-group">

        <select
        class="lu-form-control"
        name="{$rawObject->getName()}"
        {if $rawObject->isDisabled()}disabled="disabled"{/if}
        {foreach $htmlAttributes as $aValue} {$aValue@key}="{$aValue}" {/foreach}
        {if $rawObject->isMultiple()}data-options="removeButton:true; resotreOnBackspace:true; dragAndDrop:true; maxItems: null;" multiple="multiple"{/if}
    >
        {if $rawObject->getValue()|is_array}
            {foreach from=$rawObject->getAvailableValues() key=opValue item=option}
                <option value="{$opValue}" {if $opValue|in_array:$rawObject->getValue()}selected{/if}>{$option}</option>
            {/foreach}
        {else}
            {foreach from=$rawObject->getAvailableValues() key=opValue item=option}
                <option value="{$opValue}" {if $opValue == $rawObject->getValue()}selected{/if}>{$option}</option>
            {/foreach}
        {/if}
    </select>
        <div class="input-group-addon">
        {* <div class="checkbox"> *}
            <label>
              <input type="checkbox" name="virtual" {if $rawObject->getIsInputDisabled() }disabled{/if}   {if $rawObject->getIsVirtual() }checked{/if} @change="initReloadModal()"> Virtual
            </label>
          {* </div> *}
      </div>
    </div>

    <div class="lu-form-feedback lu-form-feedback--icon" hidden="hidden">
    </div>
</div>


{* <div class="form-group has-success has-feedback">
  <label class="control-label" for="inputGroupSuccess1">Input group with success</label>
  <div class="input-group">
    <span class="input-group-addon">@</span>
    <input type="text" class="form-control" id="inputGroupSuccess1" aria-describedby="inputGroupSuccess1Status">
  </div>
  <span class="glyphicon glyphicon-ok form-control-feedback" aria-hidden="true"></span>
  <span id="inputGroupSuccess1Status" class="sr-only">(success)</span>
</div> *}
