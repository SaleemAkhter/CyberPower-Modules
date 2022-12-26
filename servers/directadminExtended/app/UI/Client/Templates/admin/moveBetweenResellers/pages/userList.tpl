<div class="lu-row">
    <div class="{$rawObject->getClass()}"  id="{$elementId}">
        <mg-component-body-{$elementId|strtolower}
            component_id='{$elementId}'
            component_namespace='{$namespace}'
            component_index='{$rawObject->getIndex()}'
        ></mg-component-body-{$elementId|strtolower}>
    </div>
</div>
