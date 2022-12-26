{**********************************************************************
* ModuleFramework product developed. (2017-11-02)
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

<div class=" text-left lu-modal show lu-modal--info modal--{$rawObject->getModalSize()} modal--zoomIn" id="confirmationModal">
    <div class="lu-modal__dialog">
        <div class="lu-modal__content" id="mgModalContainer">
            <div class="lu-modal__top lu-top">
                <div class="lu-top__title lu-type-4 lu-text-success">
                    <i class="lu-zmdi lu-zmdi-info-outline"></i>
                    {if $rawObject->isRawTitle()}{$rawObject->getRawTitle()}{elseif $rawObject->getTitle()}{$MGLANG->T('modal', $rawObject->getTitle())}{/if}
                </div>
                <div class="lu-top__toolbar">
                    <button class="lu-btn lu-btn--xs lu-btn--danger lu-btn--icon lu-btn--link lu-btn--plain closeModal" data-dismiss="lu-modal" aria-label="Close">
                        <i class="lu-btn__icon lu-zmdi lu-zmdi-close"></i>
                    </button>
                </div>
            </div>
            <div class="lu-modal__body">
                {foreach from=$rawObject->getForms() item=form }
                    {$form->getHtml()}
                {/foreach}
            </div>
            <div class="lu-modal__actions">
                {foreach from=$rawObject->getActionButtons() item=actionButton}
                    {$actionButton->getHtml()}
                {/foreach} 
            </div>
            <div class="lu-modal__actions">
                {if ($isDebug eq true AND (count($MGLANG->getMissingLangs()) != 0))}{literal}
                    <div class="lu-row">
                        {/literal}{foreach from=$MGLANG->getMissingLangs() key=varible item=value}{literal}
                            <div class="lu-col-md-12"><b>{/literal}{$varible}{literal}</b> = '{/literal}{$value}{literal}';</div>
                        {/literal}{/foreach}{literal}
                    </div>
                {/literal}{/if}
            </div>
        </div>
    </div>
</div>
