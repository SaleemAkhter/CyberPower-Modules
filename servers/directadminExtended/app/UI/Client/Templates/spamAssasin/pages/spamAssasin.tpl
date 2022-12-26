{**********************************************************************
* ModuleFramework product developed. (2017-10-06)
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

<div id="spamAssasin" class="lu-row lu-row--eq-height">
    <div class="lu-col-lg-12">
        {if $rawObject->getErrorPage()}
            <div class="lu-alert lu-alert--danger lu-alert--faded lu-m-b-0x">
                <div class="alert__body">
                    <b>Error: </b> {$MGLANG->absoluteT('spamassasinDisabled')}
                </div>
            </div>
        {else}
            <div class="lu-widget">
                <div class="lu-widget__top lu-top">
                    <div class="lu-top__title">
                        {if $rawObject->getIcon()}<i class="{$rawObject->getIcon()}"></i>{/if}
                        {if $rawObject->isRawTitle()}{$rawObject->getRawTitle()}{elseif $rawObject->getTitle()}{$MGLANG->T($rawObject->getTitle())}{/if}
                    </div>
                </div>
                <div class="lu-widget__body">
                    <div  class="lu-widget__content">
                        <div class="lu-row" style="margin-left: 7px;margin-right: 7px">
                            {$rawObject->getOnOffSwitcher()}
                        </div>
                        <div class="lu-row">
                            <div class="lu-col-md-12">
                                <form id="{$rawObject->getId()}" mgformtype="{$rawObject->getFormType()}" id="{$elementId}" style="{if $rawObject->isDisable() === true}display: none{/if}">
                                    {if $rawObject->getSections()}
                                        {foreach from=$rawObject->getSections() item=section }
                                            {$section->getHtml()}
                                        {/foreach}
                                    {else}
                                        {foreach from=$rawObject->getFields() item=field }
                                            {$field->getHtml()}
                                        {/foreach}
                                    {/if}
                                    <div class="app__main-actions">
                                        {$rawObject->getSubmitHtml()}
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>

                </div>

            </div>
        {/if}
    </div>
</div>

