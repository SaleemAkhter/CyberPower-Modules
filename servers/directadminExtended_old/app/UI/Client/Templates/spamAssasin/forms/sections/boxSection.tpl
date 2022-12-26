
{if $rawObject->getSections()}
    {foreach from=$rawObject->getSections() item=section }
        {$section->getHtml()}
    {/foreach}
{else}
        {foreach from=$rawObject->getFields() item=field }
            {$field->getHtml()}
        {/foreach}
{/if}



