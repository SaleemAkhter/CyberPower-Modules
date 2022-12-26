<script type="text/x-template" id="t-mg-code-mirror-{$elementId|strtolower}"
    :component_id="component_id"
    :component_namespace="component_namespace"
    :component_index="component_index"
    :predefined_date="predefined_date"
    :date_format="date_format"
    :code="code"
    :extensions="extensions"
>
<div>
  <codemirror
    v-model="code"
    placeholder="Code goes here..."
    :style="{ height: '400px' }"
    :autofocus="true"
    :indent-with-tab="true"
    :tab-size="2"
    :extensions="extensions"
    @ready="log('ready', $event)"
    @change="log('change', $event)"
    @focus="log('focus', $event)"
    @blur="log('blur', $event)"
  >
</codemirror>

</div>
</script>
