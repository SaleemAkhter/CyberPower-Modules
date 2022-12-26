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

{literal}
    <div class="lu-col-md-12">
        <div class="box light">
            <div class="box-title">

                <div class="caption">
                    {/literal}{if $rawObject->getIcon()}<i class="{$rawObject->getIcon()}"></i>{/if}{literal}
                    <span class="caption-subject bold font-red-thunderbird uppercase">
                        {/literal}{if $rawObject->isRawTitle()}{$rawObject->getRawTitle()}{elseif $rawObject->getTitle()}{$MGLANG->T($rawObject->getTitle())}{/if}{literal}
                    </span>
                </div>

                <div class="rc-actions lu-pull-right" style="display: inline-flex;">
                    {/literal}{$rawObject->insertSearchForm()}{literal}
                </div>
            </div>
            <div class="box-body">
                <div class="lu-row">
                    <div class="lu-col-md-12">{/literal}
                        <form id="{$rawObject->getId()}" mgformtype="{$rawObject->getFormType()}" id="{$elementId}"
                              {foreach $htmlAttributes as $aValue} {$aValue@key}="{$aValue}" {/foreach}>
                            {if $rawObject->getSections()}
                                {foreach from=$rawObject->getSections() item=section }
                                    {$section->getHtml()}
                                {/foreach}                                
                            {else}
                                {foreach from=$rawObject->getFields() item=field }
                                    {$field->getHtml()}
                                {/foreach}
                            {/if}
                            <div class="lu-col-md-12 ui-form-submit">
                                {$rawObject->getSubmitHtml()}
                            </div>                        
                        </form>{literal}
                    </div>
                </div>
            </div>
        </div> 
    </div>
{/literal}
