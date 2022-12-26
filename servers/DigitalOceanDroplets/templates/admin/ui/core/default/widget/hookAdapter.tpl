<div id="{$elementId}" {foreach from=$htmlAttributes key=name item=data} {$name}="{$data}"{/foreach} style="display: none;">
    {foreach from=$elements key=nameElement item=dataElement}
        <div id="{$dataElement->getId()}">
            {$dataElement->getHtml()}
        </div>
    {/foreach}
</div>
<script type="text/javascript">
    $('#{$elementId}').prependTo('#{$rawObject->adapt()}');
    $('#{$elementId}').css('display', '');
    $('#{$elementId}').parent().addClass('mgVueMainContainer');
</script>
