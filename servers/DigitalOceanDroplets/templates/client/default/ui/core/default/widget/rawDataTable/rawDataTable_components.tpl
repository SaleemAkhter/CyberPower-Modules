{**********************************************************************
* ModuleFramework product developed. (2017-10-10)
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
        <div class="lu-t-c widgetActionComponent" id="{/literal}{$elementId}{literal}" actionid="{/literal}{$elementId}" data-table-container data-check-container>
            {if $rawObject->haveInternalAllertMessage()}
                <div class="alert {if $rawObject->getInternalAllertSize() !== ''}alert--{$rawObject->getInternalAllertSize()}{/if} alert-{$rawObject->getInternalAllertMessageType()} alert--faded datatable-alert-top">
                    <div class="lu-alert__body">
                {if $rawObject->isInternalAllertMessageRaw()|unescape:'html'}{$rawObject->getInternalAllertMessage()}{else}{$MGLANG->T($rawObject->getInternalAllertMessage())|unescape:'html'}{/if}
            </div>
        </div>
    {/if}{literal}
        <div class="lu-t-c__top lu-top lu-mob-top-search">
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
            {/literal}
            {if $rawObject->hasDropdownButtons()}
                {literal}
                    <div class="has-dropdown">
                        <button class="lu-btn lu-btn--icon lu-btn--link" data-toggle="lu-dropdown" data-options="placement: bottom right;" data-tooltip data-title="More Actions">
                            <i class="lu-btn__icon lu-zmdi lu-zmdi-more-vert"></i>
                        </button> 
                        <div class="lu-dropdown" data-dropdown-menu>
                            <div class="lu-dropdown__arrow" data-arrow></div>
                            <ul class="lu-dropdown__menu">
                                <li class="lu-dropdown__header">
                                    <span class="lu-dropdown__title">{/literal}{$MGLANG->T('datatableDropdownActions')}{literal}</span>
                                </li>
                            {/literal} 
                            {foreach from=$rawObject->getDropdownButtons() key=buttonKey item=buttonValue}
                                {$buttonValue->getHtml()}
                            {/foreach}
                            {literal}
                            </ul>
                        </div> 
                    </div>
            {/literal}{/if}{literal}                         
            </div>
        </div>
        <div class="lu-t-c__mass-actions lu-top">
            <div class="lu-top__title"><span class="lu-badge  lu-badge--primary lu-value">0</span> Items Selected</div>
            <div class="lu-top__toolbar">{/literal} 
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
                <div>
                    <table  class="lu-table lu-table--mob-collapsible lu-dataTable no-footer dtr-column" width="100%" role="grid">
                        <thead>{/literal}
                            {assign var="collArrKeys" value=$customTplVars.columns|array_keys}
                            {foreach from=$customTplVars.columns key=tplKey item=tplValue}
                                {if $rawObject->hasMassActionButtons() && $collArrKeys[0] === $tplKey}
                                <th class="{if $tplValue->orderable}{$tplValue->orderableClass}{/if} {if $tplValue->class !== ''}{$tplValue->class}{/if}"
                                    name="{$tplValue->name}">
                                    {if $rawObject->isvSortable()}
                                        <span class="drag-and-drop-icon" style="visibility: hidden;"><i class="lu-zmdi lu-zmdi-unfold-more"></i></span>
                                        {/if}
                                    <div class="lu-rail">
                                        <div class="lu-form-check">
                                            <label>
                                                <input type="checkbox" data-check-all="" class="lu-form-checkbox">
                                                <span class="lu-form-indicator"></span>
                                            </label>
                                        </div>
                                        <span class="lu-table__text" {if $tplValue->orderable}v-on:click="updateSorting"{/if}>{if $tplValue->rawTitle}{$tplValue->rawTitle}{else}{$MGLANG->T('table', $tplValue->title)}{/if}</span>
                                    </div>
                                </th>                                    
                            {else}
                                <th class="{if $tplValue->orderable}{$tplValue->orderableClass}{/if} {if $tplValue->class !== ''}{$tplValue->class}{/if}" {if $tplValue->orderable} aria-sort="descending" {/if}
                                    {if $tplValue->orderable}v-on:click="updateSorting"{/if} name="{$tplValue->name}">
                                    <span class="lu-table__text">{if $tplValue->rawTitle}{$tplValue->rawTitle}{else}{$MGLANG->T('table', $tplValue->title)}{/if}&nbsp;&nbsp;</span>
                                </th>
                            {/if}
                        {/foreach}
                        {if $rawObject->hasActionButtons()}
                            <th class="mgTableActionsHeader" name="actionsCol"></th>                            
                            {/if}                                
                        </thead>
                        <tbody>
                            <tr v-for="dataRow in dataRows" {literal}:actionid="dataRow.id"{/literal} role="lu-row">
                                {foreach from=$customTplVars.columns key=tplKey item=tplValue}
                                    {if $rawObject->hasMassActionButtons() && $collArrKeys[0] === $tplKey}
                                        <td>
                                            {if $rawObject->isvSortable()}
                                                <span class="drag-and-drop-icon"><i class="lu-zmdi lu-zmdi-unfold-more"></i></span>
                                                {/if}
                                            <div class="lu-rail">
                                                <div class="lu-form-check">
                                                    <label>
                                                        <input type="checkbox" class="lu-form-checkbox table-mass-action-check" {literal}:value="dataRow.id"{/literal}>
                                                        <span class="lu-form-indicator">
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
                <div class="lu-t-c__footer table-footer">
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
    {if ($isDebug eq true AND (count($MGLANG->getMissingLangs()) != 0))}{literal}
            <div class="lu-row">
            {/literal}{foreach from=$MGLANG->getMissingLangs() key=varible item=value}{literal}
                    <div class="lu-col-md-12"><b>{/literal}{$varible}{literal}</b> = '{/literal}{$value}{literal}';</div>
                    {/literal}{/foreach}{literal}
            </div>
    {/literal}{/if}
</template>
