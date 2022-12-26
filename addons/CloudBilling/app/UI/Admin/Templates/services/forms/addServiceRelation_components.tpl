<script type="text/x-template" id="t-mg-service-form-{$elementId|strtolower}"
        :component_id="component_id"
        :component_namespace="component_namespace"
        :component_index="component_index"
        :autoload_data_after_created="autoload_data_after_created"
        :start_length="start_length"
>
    <form id="{$rawObject->getId()}" namespace="{$namespace}" index="{$rawObject->getIndex()}"
          mgformtype="{$rawObject->getFormType()}"
    {foreach $htmlAttributes as $aValue} {$aValue@key}="{$aValue}" {/foreach}>
    {if $rawObject->getClasses()}
    <div class="{$rawObject->getClasses()}">
        {/if}
        {if $rawObject->getSections()}
            {foreach from=$rawObject->getSections() item=section }
                {$section->getHtml()}
            {/foreach}
        {else}
            {foreach from=$rawObject->getFields() item=field }
                {$field->getHtml()}
            {/foreach}
        {/if}


        <div v-for="(customField, index) in customFields" class="lu-form-group">
            <div v-if="customField.type === 'text'">
                <label class="lu-form-label">
                    {literal}{{ customField.label }}{/literal}
                    <i v-if="customField.hasDescription" :data-title="customField.description" data-toggle="lu-tooltip" class="lu-i-c-2x lu-zmdi lu-zmdi-help-outline lu-form-tooltip-helper"></i>
                </label>
                <input class="lu-form-control" type="text" :name="index"
                       :value="customField.value ? customField.value : customField.defaultValue">
                <div class="lu-form-feedback lu-form-feedback--icon" hidden="hidden"></div>
            </div>
            <div v-if="customField.type === 'select'">
                <label class="lu-form-label">
                    {literal}{{ customField.label }}{/literal}
                    <i v-if="customField.hasDescription" :data-title="customField.description" data-toggle="lu-tooltip" class="lu-i-c-2x lu-zmdi lu-zmdi-help-outline lu-form-tooltip-helper"></i>
                </label>
                <select :name="index" class="lu-form-control" tabindex="-1">
                    <option v-for="(key, value) in customField.data" :value="key">{literal}{{value}}{/literal}</option>
                </select>
                <div class="lu-form-feedback lu-form-feedback--icon" hidden="hidden"></div>
            </div>
        </div>

        {if $rawObject->getClasses()}
    </div>
    {/if}
    </form>
</script>
