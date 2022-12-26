{**********************************************************************
* ProxmoxAddon product developed. (2017-11-16)
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

<div class="lu-modal show lu-modal--{$rawObject->getModalSize()}" id="confirmationModal" namespace="{$rawObject->getNamespace()}" index="{$rawObject->getId()}">
    <div class="lu-modal__dialog">
        <div class="lu-modal__content" id="mgModalContainer">
            <div class="lu-modal__top lu-top">
                <div class="lu-top__title lu-type-6">
                    <span class="lu-text-faded lu-font-weight-normal">
                {if $rawObject->isRawTitle()}{$rawObject->getRawTitle()}{elseif $rawObject->getTitle()}{$MGLANG->T('modal', $rawObject->getTitle())}{/if}
            </span>
        </div>
        <div class="lu-top__toolbar">
            <button class="lu-btn lu-btn--xs lu-btn--default lu-btn--icon lu-btn--link lu-btn--plain closeModal" data-dismiss="lu-modal" aria-label="Close" @click='closeModal($event)'>
                <i class="lu-btn__icon lu-zmdi lu-zmdi-close"></i>
            </button>
        </div>
    </div>
    <div class="lu-modal__nav">
        <ul class="lu-nav lu-nav--md lu-nav--h lu-nav--tabs">
            <li class="lu-nav__item is-active">
                <a class="lu-nav__link" data-toggle="lu-tab" href="#modalTabJob">
                    <span class="lu-nav__link-text">{$MGLANG->T('Info')}</span>
                </a>
            </li>
            <li class="lu-nav__item">
                <a class="lu-nav__link" data-toggle="lu-tab" href="#modalTabLogs">
                    <span class="lu-nav__link-text">{$MGLANG->T('Logs')}</span>
                </a>
            </li>
        </ul>
    </div>
    <div class="lu-modal__body">
        <div class="lu-tab-content">
            <div class="lu-tab-pane is-active" id="modalTabJob">
                <div class="lu-list-group lu-list-group--simple lu-list-group--p-h-0x list-group--collapse lu-m-b-0x">
                    <div class="lu-row">
                        <div class="lu-col-md-12">
                            <ul class="lu-list lu-list--info">
                                <li class="lu-list__item">
                                    <span class="lu-list__item-title">{$MGLANG->T('Job')} </span>
                                    <span class="lu-list__value">{$MGLANG->T($customTplVars.job.job)}</span>
                                </li>
                                <li class="lu-list__item">
                                    <span class="lu-list__item-title">{$MGLANG->T('Status')}  </span>
                                    <span class="lu-list__value">{$customTplVars.status}</span>
                                </li>
                                {if $customTplVars.data.hostingId}
                                    <li class="lu-list__item">
                                        <span class="lu-list__item-title">{$MGLANG->T('Product/Service')}  </span>
                                        <span class="lu-list__value"><a href="clientsservices.php?id={$customTplVars.data.hostingId}">{$customTplVars.productName}</a></span>
                                    </li>
                                {/if}
                                {if $customTplVars.data.softpath}
                                    <li class="lu-list__item">
                                        <span class="lu-list__item-title">{$MGLANG->T('Installation Directory')}  </span>
                                        <span class="lu-list__value">{$customTplVars.data.softpath}</span>
                                    </li>
                                {/if}
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
            <div class="lu-tab-pane" id="modalTabLogs">
                <div class="lu-list-group lu-list-group--simple lu-list-group--p-h-0x list-group--collapse lu-m-b-0x">
                    <div class="lu-row">
                        <div class="lu-col-md-12" style="overflow: auto; height: 430px;">
                            {if !$customTplVars.jobLogs}
                                <span>{$MGLANG->T('Job Logs not available.')}</span>
                            {else}
                                <table class="lu-table lu-table-hover" >
                                    <thead>
                                        <tr>
                                            <th> {$MGLANG->T('Message')}</th>
                                            <th> {$MGLANG->T('Status')}</th>
                                            <th> {$MGLANG->T('Created')}</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        {foreach from=$customTplVars.jobLogs item=jobLog}
                                            <tr>
                                                <td>{$jobLog.message}</td>
                                                <td>{$jobLog.status} </td>
                                                <td>{$jobLog.created_at} </td>
                                            </tr>
                                        {foreachelse}
                                            <tr>
                                                <td colspan="2">{$MGLANG->T('Job Logs not available.')}</td>
                                            </tr>
                                        {/foreach}
                                    </tbody> 
                                </table>
                            {/if}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="lu-modal__actions">
        {foreach from=$rawObject->getActionButtons() item=actionButton}
            {$actionButton->getHtml()}
        {/foreach} 
    </div>
    {if ($isDebug eq true AND (count($MGLANG->getMissingLangs()) != 0))}{literal}
            <div class="lu-modal__actions">
                <div class="lu-row">
                {/literal}{foreach from=$MGLANG->getMissingLangs() key=varible item=value}{literal}
                        <div class="lu-col-md-12"><b>{/literal}{$varible}{literal}</b> = '{/literal}{$value}{literal}';</div>
                        {/literal}{/foreach}{literal}
                </div>
            </div>
    {/literal}{/if}
</div>
</div>
</div