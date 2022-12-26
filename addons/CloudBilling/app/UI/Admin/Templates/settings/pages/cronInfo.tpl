{**********************************************************************
* CloudBilling product developed. (2019-08-13)
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

<div class="lu-row lu-row--eq-height">
    <div class="lu-col-lg-12">
        <div class="lu-widget">
            <div class="lu-widget__header">
                <div class="lu-widget__top lu-top">
                    {if $rawObject->getRawTitle() || $rawObject->getTitle()}
                        <div class="lu-top__title">
                            {if $rawObject->getIcon()}
                                <i class="{$rawObject->getIcon()}"></i>
                            {/if}
                            {if $rawObject->isRawTitle()}
                                {$rawObject->getRawTitle()}
                            {elseif $rawObject->getTitle()}
                                {$MGLANG->T($rawObject->getTitle())}
                            {/if}
                        </div>
                    {/if}
                </div>
            </div>
            <div class="lu-widget__body">
                <div  class="lu-widget__content">
                    <div class="lu-row">
                        <div class="lu-col-lg-12">
                        {foreach from=$elements key=nameElement item=dataElement}
                            {$dataElement->getHtml()}
                        {/foreach}
                        </div>
                    </div>
                    <div class="lu-row">
                        {foreach from=$rawObject->getCrons() key=nameElement item=dataElement}
                            <div class="lu-col-lg-12">
                                <div class="form-group">
                                    <label class="form-label">
                                        {$MGLANG->translate('cronTitle', $nameElement)}
                                        <i data-title="{$MGLANG->translate('cronTooltip', $nameElement)}" data-toggle="lu-tooltip" class="lu-i-c-2x lu-zmdi lu-zmdi-help-outline lu-form-tooltip-helper lu-tooltip"></i>
                                    </label>
                                    <pre>{$dataElement}</pre>
                                </div>
                            </div>
                        {/foreach}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
