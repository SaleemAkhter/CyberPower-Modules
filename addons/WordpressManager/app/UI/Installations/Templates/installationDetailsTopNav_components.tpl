<script type="text/x-template" id="t-mg-wp-installation-details-top-nav-{$elementId|strtolower}"
        :component_id="component_id"
        :component_namespace="component_namespace"
        :component_index="component_index"
        >

<div id="mg-wp-installation-details-top-nav">
   
    <div class="lu-widget">
        <div class="lu-widget__header">
            <div class="lu-widget__top top topNav">
                {foreach from=$rawObject->getButtons() key=buttonKey item=buttonValue}
               <b> {$buttonValue->getHtml()}</b>
                {/foreach}
            </div>
        </div>
    </div>
</div>
</script>