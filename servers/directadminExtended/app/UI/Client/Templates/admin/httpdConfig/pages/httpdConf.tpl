{* {$rawObject->getDomainName() }



<div class="p-20">
    <ol class="config limited">
        {foreach from=$rawObject->getConfFile() item=line key=key name=name}
            <li class="line" ><pre v-text="'{$line}'"></pre></li>
        {/foreach}
    </ol>
</div> *}


<mg-component-body-{$elementId|strtolower}
component_id='{$elementId}'
component_namespace='{$namespace}'
component_index='{$rawObject->getIndex()}'
filedata='{$rawObject->getConfFile()}'
apidata=''
templates=''
></mg-component-body-{$elementId|strtolower}>
