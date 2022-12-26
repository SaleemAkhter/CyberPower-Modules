<ol>
{foreach from=$rawObject->getLines() key=k item=v}
    <li><pre>{$v}</pre></li>
{/foreach}
</ol>
