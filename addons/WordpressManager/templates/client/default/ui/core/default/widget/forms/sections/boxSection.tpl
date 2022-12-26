{**********************************************************************
* WordpressManager product developed. (2017-10-06)
* *
*
*  CREATED BY MODULESGARDEN       ->       http://modulesgarden.com
*  CONTACT                        ->       contact@modulesgarden.com
*
*
* This software is furnished under a license and may be used and copied
* only  in  accordance  with  the  terms  of such  license and with the
* inclusion of the above copyright notice.  This software  or any other
* copies thereof may not be provided or otherwise made available to any
* other person.  No title to and  ownership of the  software is  hereby
* transferred.
*
*
**********************************************************************}

{**
* @author Sławomir Miśkowicz <slawomir@modulesgarden.com>
*}
<div class="lu-widget " id="{$rawObject->getId()}">
    {if ($rawObject->getRawTitle() || $rawObject->getTitle()) && $rawObject->isViewHeader()}
        <div class="lu-widget__header">
            <div class="lu-widget__top lu-top">
                <div class="lu-top__title {if $rawObject->hasCustomCollapse()}collapsablesection{/if}" >
                    {if $rawObject->hasCustomCollapse()}<i id="advoptions_toggle_plus" class="fas fa-plus-square"></i>{/if}
                    {if $rawObject->getIcon()}<i class="{$rawObject->getIcon()}"></i>{/if}
                    {if $rawObject->isRawTitle()}{$rawObject->getRawTitle()}{elseif $rawObject->getTitle()}{$MGLANG->T($rawObject->getTitle())}{/if}
                </div>
                <div>
                    {if $rawObject->getActionButtons()}
                        {foreach from=$rawObject->getActionButtons() item=button }
                            {$button->getHtml()}
                        {/foreach}
                    {/if}
                </div>

            </div>
            <hr class="sai_main_head_hr">
        </div>    
    {/if}
    <div class="lu-widget__body" {if $rawObject->hasCustomCollapse()} style="display:none;"{/if}>
        <div class="lu-widget__content">
            {if $rawObject->getElementById('sectionDescription')}
                {$rawObject->insertElementById('sectionDescription')}
            {/if}
            {if $rawObject->getId()=="pluginsBox"}
            <div class="lu-row pluginsearchcontainer">
                    <div class="lu-col-md-12 lu-p-r-4x">
                        <div class="lu-form-group "><input type="text" placeholder="{$MGLANG->T('searchpluginplaceholder')}" name="searchplugin" value="" class="lu-form-control fuzzy-search"> <div hidden="hidden" class="lu-form-feedback lu-form-feedback--icon"></div></div>
                    </div>
            </div>
            {/if}
            <div class="lu-row">

                {if $rawObject->getSections()}
                    {foreach from=$rawObject->getSections() item=section }
                        {$section->getHtml()}
                    {/foreach}  
                {else}
                    <div class="lu-col-md-12 lu-p-r-4x {$rawObject->getClasses()}">
                        {foreach from=$rawObject->getFields() item=field }
                            {$field->getHtml()}
                        {/foreach}
                    </div>
                {/if}
            </div>
        </div>
    </div>
</div>
