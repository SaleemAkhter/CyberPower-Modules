<div class="lu-row {$rawObject->getAdditionalClass()}">
    {foreach from=$rawObject->getSections() item=section }
        {$section->getHtml()}
    {/foreach}
</div>
