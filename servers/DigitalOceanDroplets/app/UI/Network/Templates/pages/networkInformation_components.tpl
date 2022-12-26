{**********************************************************************
* UnbanCenter product developed. (2017-10-10)
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

{literal}
    <template id="mg-datatable-template{/literal}{$elementId}{literal}">
        <div class="lu-widget widgetActionComponent" actionid="{/literal}{$elementId}">
            {if $rawObject->getRawTitle() || $rawObject->getTitle()}{literal}
                    <div class="lu-widget__header" >
                        <div class="lu-widget__top top">
                            <div class="lu-top__title">
                            {/literal}
                    {if $rawObject->getIcon()}<i class="{$rawObject->getIcon()}"></i>{/if}{if $rawObject->isRawTitle()}{$rawObject->getRawTitle()}{elseif $rawObject->getTitle()}{$MGLANG->T($rawObject->getTitle())}{/if}
                    {literal}
                    </div>
                    <div class="lu-top__toolbar">

                    </div>
                </div>
            </div>
    {/literal}{/if}{literal}
        <div class="lu-widget__body">    
            <div class="t-c  datatableLoader" data-table-container data-check-container >
                <div class="lu-t-c__top top lu-mob-top-search" v-if="false">
                    <div class="lu-top__toolbar">
                    {/literal}
                    {$rawObject->insertSearchForm()}
                    {literal}
                    </div>
                    <div class="lu-top__toolbar">
                    {/literal} 
                    {foreach from=$rawObject->getButtons() key=buttonKey item=buttonValue}
                        {$buttonValue->getHtml()}
                    {/foreach}
                    {literal} 
                    </div>
                </div>
                <div class="lu-t-c__mass-actions top">
                    <div class="top__title"><span class="badge  badge--primary value">0</span> Items Selected</div>
                    <div class="top__toolbar">{/literal} 
                        {if $rawObject->hasMassActionButtons()}
                            {foreach $rawObject->getMassActionButtons() as $maButton}
                                {$maButton->getHtml()}
                            {/foreach}
                        {/if} {literal} 
                        </div>
                        <div class="drop-arrow{/literal}{if $rawObject->isvSortable()} drop-arrow-sorting{/if}{literal}"></div>
                    </div> 
                {/literal}            
                {***DATATABLE*BODY******************************************************************}
                {literal}
                    <div class="dataTables_wrapper no-footer">
                        <div style="min-height: 50px;">
                            <table  class="lu-table lu-table--mob-collapsible lu-dataTable no-footer dtr-column" width="100%" role="grid">
                                <thead>{/literal}
                                    {assign var="collArrKeys" value=$customTplVars.columns|array_keys}
                                    {foreach from=$customTplVars.columns key=tplKey item=tplValue}
                                        {if $rawObject->hasMassActionButtons() && $collArrKeys[0] === $tplKey}
                                        <th class="{if $tplValue->orderable}{$tplValue->orderableClass}{/if} {if $tplValue->class !== ''}{$tplValue->class}{/if}"
                                            name="{$tplValue->name}">
                                            {if $rawObject->isvSortable()}
                                                <span class="drag-and-drop-icon" style="visibility: hidden;"><i class="zmdi zmdi-unfold-more"></i></span>
                                                {/if}
                                            <div class="rail">
                                                <div class="form-check">
                                                    <label>
                                                        <input type="checkbox" data-check-all="" class="form-checkbox">
                                                        <span class="form-indicator"></span>
                                                    </label>
                                                </div>
                                                <span class="table__text" {if $tplValue->orderable}v-on:click="updateSorting"{/if}>{if $tplValue->rawTitle}{$tplValue->rawTitle}{else}{$MGLANG->T('table', $tplValue->title)}{/if}</span>
                                            </div>
                                        </th>                                    
                                    {else}
                                        <th class="{if $tplValue->orderable}{$tplValue->orderableClass}{/if} {if $tplValue->class !== ''}{$tplValue->class}{/if}" {if $tplValue->orderable} aria-sort="descending" {/if}
                                            {if $tplValue->orderable}v-on:click="updateSorting"{/if} name="{$tplValue->name}">
                                            <span class="table__text">{if $tplValue->rawTitle}{$tplValue->rawTitle}{else}{$MGLANG->T('table', $tplValue->title)}{/if}&nbsp;&nbsp;</span>
                                        </th>
                                    {/if}
                                {/foreach}
                                {if $rawObject->hasActionButtons()}
                                    <th class="mgTableActionsHeader" name="actionsCol"></th>                            
                                    {/if}                                
                                </thead>
                                <tbody>
                                    <tr v-for="dataRow in dataRows" {literal}:actionid="dataRow.id"{/literal} role="row">
                                        {foreach from=$customTplVars.columns key=tplKey item=tplValue}
                                            {if $rawObject->hasMassActionButtons() && $collArrKeys[0] === $tplKey}
                                                <td>
                                                    {if $rawObject->isvSortable()}
                                                        <span class="drag-and-drop-icon"><i class="zmdi zmdi-unfold-more"></i></span>
                                                        {/if}
                                                    <div class="rail">
                                                        <div class="form-check">
                                                            <label>
                                                                <input type="checkbox" class="form-checkbox table-mass-action-check" {literal}:value="dataRow.id"{/literal}>
                                                                <span class="form-indicator">
                                                                </span>
                                                            </label>
                                                        </div>
                                                        <span v-html="dataRow.{$tplKey}">

                                                        </span>
                                                    </div> 
                                                </td>
                                            {elseif $customTplVars.jsDrawFunctions[$tplKey]}
                                                <td v-html="rowDrow('{$tplKey}', dataRow, '{$customTplVars.jsDrawFunctions[$tplKey]}')">

                                                </td>
                                            {elseif $rawObject->hasCustomColumnHtml($tplKey)}
                                                <td class="mgTableActions">
                                                    {$rawObject->getCustomColumnHtml($tplKey)}
                                                </td>                                              
                                            {else}
                                                <td v-html="dataRow.{$tplKey}">

                                                </td>                                            
                                            {/if}
                                        {/foreach}
                                        {if $rawObject->hasActionButtons()}
                                            <td class="lu-cell-actions mgTableActions">
                                                {foreach $rawObject->getActionButtons() as $aButton}
                                                    {$aButton->getHtml()}
                                                {/foreach}
                                            </td>
                                        {/if}
                                    </tr>
                                </tbody>{literal}
                            </table>
                            <div v-show="noData" style="padding: 15px; text-align: center;">
                            {/literal}{$MGLANG->absoluteT('noDataAvalible')}{literal}
                            </div>
                            <div class="lu-preloader-container lu-preloader-container--full-screen lu-preloader-container--overlay" v-show="loading">
                                <div class="lu-preloader lu-preloader--sm"></div>
                            </div>
                        </div>
                    {/literal}                
                    {***DATATABLE*FOOTER*****************************************************************}
                    {literal}
                        <div class="t-c__footer table-footer" v-if="false">
                            <div class="dataTables_paginate paging_simple_numbers" id="DataTables_Table_0_paginate">
                                <a :class='"paginate_button previous" + ((curPage < 2) ? " disabled" : "")' aria-controls="DataTables_Table_0" :data-dt-idx='curPage -1' tabindex="0" href="javascript:;" page="prev" v-on:click="changePage" id="{/literal}{$elementId}{literal}_previous"></a>
                                <span v-for="pageNumber in pagesMap" >
                                    <a v-if='pageNumber && pageNumber !== "..."' :class='"paginate_button" + (curPage === pageNumber ? " current" : "")' aria-controls="DataTables_Table_0" v-on:click="changePage" :data-dt-idx="pageNumber" tabindex="0"> {{ pageNumber}} </a>
                                    <a v-if='pageNumber && pageNumber === "..."' class="paginate_button disabled" > {{ pageNumber}} </a>
                                </span>
                                <a :class='"paginate_button next" + ((curPage === allPages || allPages === 0) ? " disabled" : "")' aria-controls="DataTables_Table_0" :data-dt-idx='curPage +1' tabindex="0" href="javascript:;" page="next" v-on:click="changePage" id="{/literal}{$elementId}{literal}_next"></a>
                            </div>
                            <div class="dt-buttons">
                                <a class="dt-button active" tabindex="0" data-length="10" v-on:click="updateLength" aria-controls="DataTables_Table_0" href="#{/literal}{$elementId}{literal}">
                                    <span>10</span>
                                </a>
                                <a class="dt-button" tabindex="0" data-length="25" v-on:click="updateLength" aria-controls="DataTables_Table_0" href="#{/literal}{$elementId}{literal}">
                                    <span>25</span>
                                </a>
                                <a class="dt-button" tabindex="0" data-length="999999" v-on:click="updateLength" aria-controls="DataTables_Table_0" href="#{/literal}{$elementId}{literal}">
                                    <span>∞</span>
                                </a>
                            </div>
                        </div>
                    {/literal}
                    {***END*OF*DATATABLE*FOOTER**********************************************************}                          

                </div>
            </div>
        </div>
    </div>
</template>
