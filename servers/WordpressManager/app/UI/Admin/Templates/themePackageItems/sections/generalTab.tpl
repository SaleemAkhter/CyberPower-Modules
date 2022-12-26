<form id="{$rawObject->getId()}" mgformtype="{$rawObject->getFormType()}" mgformtype="{$rawObject->getFormType()}"  namespace="{$namespace}" index="{$rawObject->getIndex()}">
    <div class="lu-row">
        <div class="lu-col-md-12">
            {if $rawObject->getSections()}
                {foreach from=$rawObject->getSections() item=section }
                    {$section->getHtml()}
                {/foreach}                                
            {else}
                {foreach from=$rawObject->getFields() item=field }
                    {$field->getHtml()}
                {/foreach}
            {/if}
                <div class="lu-app__main-actions" style="margin-bottom: 20px; margin-left: 20px;">
                {$rawObject->getSubmitHtml()} 
            </div>
        </div>
    </div>
</form>