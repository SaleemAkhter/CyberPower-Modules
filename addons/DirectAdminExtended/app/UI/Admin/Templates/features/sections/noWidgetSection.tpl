{foreach from=$rawObject->getFields() item=field }
    {$field->getHtml()}
{/foreach}
