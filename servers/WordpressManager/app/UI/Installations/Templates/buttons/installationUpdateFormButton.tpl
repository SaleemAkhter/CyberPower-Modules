<a {foreach $htmlAttributes as $aValue} {$aValue@key}="{$aValue}" {/foreach}
    class="{$rawObject->getClasses()}" id="submiteditDetailForm">
    {if $rawObject->getIcon()}<i class="{$rawObject->getIcon()}"></i>{/if}
    {if $rawObject->isRawTitle()}{$rawObject->getRawTitle()}{elseif $rawObject->getTitle()}{$MGLANG->T('button', $rawObject->getTitle())}{/if}
</a>
<a data-toggle="lu-tooltip" class="lu-btn lu-btn--primary lu-tooltip drop-target drop-pinned drop-pinned-bottom drop-out-of-bounds drop-out-of-bounds-top drop-element-attached-center drop-target-attached-center" data-title="Back To Dashboard" href="index.php?m=WordpressManager&mg-page=home">Back To Dashboard</a>
<script>
    jQuery("#submiteditDetailForm").on("click",function(e){
        e.preventDefault();
        jQuery("#editDetailForm").submit();
    });
</script>
