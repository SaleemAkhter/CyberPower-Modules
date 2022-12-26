
<div class="lu-row">
    <div class="lu-col-md-12 vueDatatableTable"  id="{$elementId}">
        <mg-component-body-{$elementId|strtolower}
                component_id='{$elementId}'
                component_namespace='{$namespace}'
                component_index='{$rawObject->getIndex()}'
        ></mg-component-body-{$elementId|strtolower}>
    </div>
</div>
