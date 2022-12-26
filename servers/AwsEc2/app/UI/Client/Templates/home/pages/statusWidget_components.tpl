{**********************************************************************
* AwsEc2 product developed. (2019-04-26)
*
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

<script type="text/x-template" id="t-mg-service-actions-{$elementId|strtolower}"
        :component_id="component_id"
        :component_namespace="component_namespace"
        :component_index="component_index"
>
    <div class="lu-col-md-12 mg-status-widget">
        {if $customTplVars['isAdmin']}
            <div class="lu-h5 lu-m-b-3x lu-m-t-2x">{$MGLANG->translate($rawObject->getTitle())}</div>
        {else}
            <div class="lu-h4 lu-m-b-3x lu-m-t-2x">{$MGLANG->translate($rawObject->getTitle())}</div>
        {/if}

        <div class="lu-tiles lu-row lu-row--eq-height">
            {foreach from=$rawObject->getButtons() key=buttonKey item=buttonValue}
                {$buttonValue->getHtml()}
            {/foreach}
        </div>

        <div class="lu-row" v-if="detailsAvaliable()">
            <div class="lu-col-md-12" v-for="widget in data.instanceDetails">
                <div class="lu-widget">
                    <div class="lu-widget__header">
                        <div class="lu-widget__top lu-top">
                            <div class="lu-top__title">{literal}{{ widget.title }}{/literal}</div>
                        </div>
                    </div>
                    <div class="lu-widget__body">
                        <table v-if="widget.details.length > 0" class="lu-table lu-table--mob-collapsible dataTable no-footer dtr-column" width="100%" role="grid">
                            <tbody>
                                <tr role='row' v-for="detail in widget.details">
                                    <td v-html="detail.key"></td>
                                    <td v-if="detail.isStatus">
                                        <span :class="'lu-label lu-label--lg lu-label--' + statusColor + ' lu-label--status mg-status-title'">{literal}{{ detail.value }}{/literal}</span>
                                    </td>
                                    <td v-else v-html="detail.value"></td>
                                </tr>
                            </tbody>
                        </table>
                    <div v-else style="padding: 15px; text-align: center; border-top: 1px solid #e9ebf0;">
                        {$MGLANG->translate('noDataAvalible')}
                    </div>
                    </div>
                    <div class="lu-preloader-container lu-preloader-container--full-screen lu-preloader-container--overlay widget-only" v-show="loadingState">
                        <div class="lu-preloader lu-preloader--sm"></div>
                    </div>
                </div>
            </div>
        </div>

        {if ($isDebug eq true AND (count($MGLANG->getMissingLangs()) != 0))}
            <div class="lu-row" style="max-width: 95%;">
                {foreach from=$MGLANG->getMissingLangs() key=varible item=value}
                    <div class="lu-col-md-12"><b>{$varible}</b> = '{$value}';</div>
                {/foreach}
            </div>
        {/if}
    </div>
</script>
