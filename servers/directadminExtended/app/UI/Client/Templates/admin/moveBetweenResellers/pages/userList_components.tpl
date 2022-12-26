{**********************************************************************
* DirectAdminExtended product developed. (2017-10-10)
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

<script type="text/x-template" id="t-mg-datatable-{$elementId|strtolower}"
        :component_id="component_id"
        :component_namespace="component_namespace"
        :component_index="component_index"
>
    <div class="lu-widget widgetActionComponent vueDatatableTable"  id="{$rawObject->getId()}"  namespace="{$namespace}" index="{$rawObject->getIndex()}" actionid="{$rawObject->getIndex()}">
        {if ($rawObject->getRawTitle() || $rawObject->getTitle() || $rawObject->titleButtonIsExist()) && $rawObject->isViewHeader()}
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
                    {if $rawObject->titleButtonIsExist()}
                        <div class="lu-top__toolbar">
                            {foreach from=$rawObject->getTitleButtons() key=buttonKey item=buttonValue}
                                {$buttonValue->getHtml()}
                            {/foreach}
                        </div>
                    {/if}
                </div>
            </div>
        {/if}
        <div class="lu-widget__body">
            <form id="{$rawObject->getId()}Form"  mgformtype="update" >
            <div class="lu-t-c  datatableLoader"  data-table-container data-check-container>
                {if $rawObject->isViewTopBody()}
                    <div class="lu-t-c__top lu-top mob-top-search">
                        <div class="lu-top__toolbar"> {$rawObject->insertSearchForm()} </div>
                        <div class="lu-top__toolbar">
                            {foreach from=$rawObject->getButtons() key=buttonKey item=buttonValue}
                                {$buttonValue->getHtml()}
                            {/foreach}
                            {if $rawObject->hasDropdownButton()}
                                {$rawObject->getDropdownButtonHtml()}
                            {/if}
                        </div>
                    </div>
                {/if}
                <div class="lu-t-c__mass-actions lu-top">
                    <div class="lu-top__title"><span class="lu-badge lu-badge--primary lu-value">0</span> {$MGLANG->absoluteT('datatableItemsSelected')}</div>
                    <div class="lu-top__toolbar">
                        {if $rawObject->hasMassActionButtons()}
                            {foreach $rawObject->getMassActionButtons() as $maButton}
                                {$maButton->getHtml()}
                            {/foreach}
                        {/if}
                    </div>
                    <div class="drop-arrow{if $rawObject->isvSortable()} drop-arrow-sorting{/if}"></div>
                </div>

                {***DATATABLE*BODY******************************************************************}

                <div class="dataTables_wrapper no-footer">
                    <div>
                        <table class="lu-table lu-table--mob-collapsible dataTable no-footer dtr-column table-striped" width="100%" role="grid">
                            <thead>
                            <tr role='row'>
                                {assign var="collArrKeys" value=$customTplVars.columns|array_keys}
                                {foreach from=$customTplVars.columns key=tplKey item=tplValue}
                                    {if $rawObject->hasMassActionButtons() && $collArrKeys[0] === $tplKey}
                                        <th class="{if $tplValue->orderable}{$tplValue->orderableClass}{/if} {if $tplValue->class !== ''}{$tplValue->class}{/if}"
                                            name="{$tplValue->name}">
                                            {if $rawObject->isvSortable()}
                                                <span class="drag-and-drop-icon" style="visibility: hidden;"><i class="zmdi zmdi-unfold-more"></i></span>
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
                            </tr>
                            </thead>
                            <tbody>
                             <template v-for="(users, reseller,index) in dataUserRows">

                                <tr  {literal}:actionid="reseller"{/literal} role="row">
                                    {foreach from=$customTplVars.columns key=tplKey item=tplValue}
                                        {if $rawObject->hasMassActionButtons() && $collArrKeys[0] === $tplKey}
                                            <td>
                                                {if $rawObject->isvSortable()}
                                                    <span class="drag-and-drop-icon"><i class="lu-zmdi lu-zmdi-unfold-more"></i></span>
                                                {/if}
                                                <div class="lu-rail">
                                                    <div class="lu-form-check" v-show="selectedreseller!=reseller">
                                                        <label>
                                                            <input type="checkbox" v-on:change="selectResellerUsers(reseller)" class="lu-form-checkbox reseller-check table-mass-action-check" {literal}:value="reseller"{/literal}>
                                                            <span class="lu-form-indicator">
                                                                </span>
                                                        </label>
                                                    </div>
                                                    <span v-html="'Reseller: '+reseller"></span>
                                                </div>
                                            </td>
                                        {elseif $customTplVars.jsDrawFunctions[$tplKey]}
                                            <td v-html="rowDrow('{$tplKey}', dataRow, '{$customTplVars.jsDrawFunctions[$tplKey]}')"></td>
                                        {elseif $rawObject->hasCustomColumnHtml($tplKey)}
                                            <td class="mgTableActions">
                                                {$rawObject->getCustomColumnHtml($tplKey)}
                                            </td>
                                        {else}
                                            <td v-html="dataRow.{$tplKey}"></td>
                                        {/if}
                                    {/foreach}
                                    {if $rawObject->hasActionButtons() || $rawObject->hasActionDropdownButton()}
                                        <td class="lu-cell-actions mgTableActions">
                                            {foreach $rawObject->getActionButtons() as $aButton}
                                                {$aButton->getHtml()}
                                            {/foreach}
                                            {if $rawObject->hasActionDropdownButton()}
                                                {$rawObject->getActionDropdownButtonHtml()}
                                            {/if}
                                        </td>
                                    {/if}
                                </tr>
                                <tr v-for="(user, index) in users" {literal}:actionid="user"{/literal} role="row" class="child-row">
                                    {foreach from=$customTplVars.columns key=tplKey item=tplValue}
                                        {if $rawObject->hasMassActionButtons() && $collArrKeys[0] === $tplKey}
                                            <td>
                                                {if $rawObject->isvSortable()}
                                                    <span class="drag-and-drop-icon"><i class="lu-zmdi lu-zmdi-unfold-more"></i></span>
                                                {/if}
                                                <div class="lu-rail">
                                                    <div class="lu-form-check" v-show="selectedreseller!=reseller">
                                                        <label>
                                                            <input type="checkbox" v-model="children" v-on:change="selectUser(user,reseller)" class="lu-form-checkbox  user-check table-mass-action-check" {literal}:value="user" :parentReseller="reseller" {/literal}>
                                                            <span class="lu-form-indicator">
                                                                </span>
                                                        </label>
                                                    </div>
                                                    <span v-html="user"></span>
                                                </div>
                                            </td>
                                        {elseif $customTplVars.jsDrawFunctions[$tplKey]}
                                            <td v-html="rowDrow('{$tplKey}', dataRow, '{$customTplVars.jsDrawFunctions[$tplKey]}')"></td>
                                        {elseif $rawObject->hasCustomColumnHtml($tplKey)}
                                            <td class="mgTableActions">
                                                {$rawObject->getCustomColumnHtml($tplKey)}
                                            </td>
                                        {else}
                                            <td v-html="dataRow.{$tplKey}"></td>
                                        {/if}
                                    {/foreach}
                                    {if $rawObject->hasActionButtons() || $rawObject->hasActionDropdownButton()}
                                        <td class="lu-cell-actions mgTableActions">
                                            {foreach $rawObject->getActionButtons() as $aButton}
                                                {$aButton->getHtml()}
                                            {/foreach}
                                            {if $rawObject->hasActionDropdownButton()}
                                                {$rawObject->getActionDropdownButtonHtml()}
                                            {/if}
                                        </td>
                                    {/if}
                                </tr>
                            </template>
                            <tr>
                                <td>
                                    <div class="lu-form-group lu-col-md-4">
                                        <label class="lu-form-label">{$MGLANG->absoluteT('selectreseller')}</label>
                                        <select v-on:change="changeReseller()" v-model="selectedreseller" name="reseller" class="lu-form-control">
                                            <option value="">{$MGLANG->absoluteT('select')}</option>
                                            <template v-for="(reseller, index) in dataResellerRows">
                                                <option :value="reseller.value" v-text="reseller.text"></option>
                                            </template>
                                         </select>
                                    </div>

                                </td>
                            </tr>
                            </tbody>
                        </table>
                        <div  style="padding: 15px; text-align: left; border-top: 1px solid #e9ebf0;">
                            <button class="lu-btn lu-btn--success submitForm mg-submit-form" :disabled="(children.length == 0 || selectedreseller.length==0)" v-on:click="submitForm('moveuserListForm',$event)">{$MGLANG->absoluteT('moveusers')}</button>
                        </div>
                        <div class="lu-preloader-container lu-preloader-container--full-screen lu-preloader-container--overlay" v-show="loading">
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
            </form>
        </div>
    </div>
</script>
