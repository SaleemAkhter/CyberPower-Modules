<script type="text/x-template" id="t-mg-backupDirectory-{$elementId|strtolower}"
        :component_id="component_id"
        :component_namespace="component_namespace"
        :component_index="component_index"
        >
    <div class=" lu-col-md-12 " id="mg-backupDirectory">
    <div class="box light">

        <div class="box-body">
            <div class="lu-row">
                <div class=" lu-col-md-12 ">

                <div class="lu-widget">
                    <div class="lu-widget__header">
                        {if $rawObject->isRawTitle() || $rawObject->getTitle()}
                            <div class="lu-widget__top lu-top">
                                <div class="lu-top__toolbar">
                                    <a data-toggle="lu-tooltip" class="lu-btn lu-btn--primary lu-tooltip drop-target drop-element-attached-bottom drop-element-attached-center drop-target-attached-top drop-target-attached-center" @click="loadModal($event, 'cronScheduleButton', 'ModulesGarden_Servers_DirectAdminExtended_App_UI_Client_Admin_SystemBackup_Buttons_CronSchedule','cronScheduleButton', null, true)" data-title="{$MGLANG->T("cronschedule")}"><span class="lu-btn__text">{$MGLANG->T("cronschedule")}</span></a>
                                    <a data-toggle="lu-tooltip" class="lu-btn lu-btn--primary lu-tooltip drop-target drop-element-attached-bottom drop-element-attached-center drop-target-attached-top drop-target-attached-center" @click="ajaxAction($event, '{$rawObject->getId()}', 'ModulesGarden_Servers_DirectAdminExtended_App_UI_Client_Admin_SystemBackup_Buttons_RunBackup','{$rawObject->getIndex()}', null, true)" data-index="{$rawObject->getIndex()}" data-title="{$MGLANG->T("runsysbackupnow")}"><span class="lu-btn__text">{$MGLANG->T("runsysbackupnow")}</span></a>
                                </div>
                            </div>
                        {/if}
                        <div class="lu-widget__nav swiper-container swiper-container-horizontal swiper-container-false" data-content-slider="" style="visibility: visible;">
                            <ul class="swiper-wrapper lu-nav lu-nav--md lu-nav--h lu-nav--tabs lu-nav--arrow">
                                <li  class="lu-nav__item swiper-slide ">
                                    <a class="lu-nav__link"  href="{$rawObject->getSettingUrl()}">
                                        <span class="lu-nav__link-text">
                                            {$MGLANG->T("step1")}
                                            <span class="small help-text">{$MGLANG->T("step1description")}</span>
                                        </span>
                                    </a>
                                </li>
                                <li class="lu-nav__item swiper-slide" >
                                    <a class="lu-nav__link"  href="{$rawObject->getSettingUrl()}">

                                        <span class="lu-nav__link-text">{$MGLANG->T("step2")}
                                        <span class="small help-text">{$MGLANG->T("step2description")}</span>
                                    </span>
                                    </a>
                                </li>
                                <li class="lu-nav__item swiper-slide is-active" >
                                    <a class="lu-nav__link"  href="{$rawObject->getDirectoryUrl()}">

                                        <span class="lu-nav__link-text">{$MGLANG->T("step3")}
                                        <span class="small help-text">{$MGLANG->T("step3description")}</span>
                                    </span>
                                    </a>
                                </li>
                                <li   class="lu-nav__item swiper-slide">
                                    <a class="lu-nav__link"  href="{$rawObject->getFileUrl()}">

                                        <span class="lu-nav__link-text">{$MGLANG->T("step4")}
                                        <span class="small help-text">{$MGLANG->T("step4description")}</span>
                                    </span>
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <div class="lu-widget__body p-20">
                        <div class="lu-tab-content pt-20">
                                <div id="contTabstep1"  class="lu-tab-pane " :class="{ 'is-active': step==1 }">

                                </div>
                                <div id="contTabstep2" :class="{ 'is-active': step==2 }" class="lu-tab-pane">


                                </div>
                                <div id="contTabstep3" :class="{ 'is-active': step==3 }" class="lu-tab-pane">
                                    <div class="widgetActionComponent vueDatatableTable"  id="{$rawObject->getId()}"  namespace="{$namespace}" index="{$rawObject->getIndex()}" actionid="{$rawObject->getIndex()}">

                                    <div class="lu-t-c  datatableLoader" id="{$elementId}" data-table-container data-check-container>

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
                                                    <tr v-for="dataRow in dataRows" {literal}:actionid="dataRow.id"{/literal} role="row">
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
                                                                        <span v-html="dataRow.{$tplKey}"></span>
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
                                                    </tbody>
                                                </table>
                                                <div v-show="noData" style="padding: 15px; text-align: center; border-top: 1px solid #e9ebf0;">
                                                    {$MGLANG->absoluteT('noDataAvalible')}
                                                </div>
                                                <div class="lu-preloader-container lu-preloader-container--full-screen lu-preloader-container--overlay" v-show="loading">
                                                    <div class="lu-preloader lu-preloader--sm"></div>
                                                </div>
                                            </div>
                                            <div class=" lu-col-md-12 ">
                                                <div class="box light">
                                                    <div class="box-body">
                                                        <div class="lu-row">
                                                            <div class=" lu-col-md-12 ">
                                                                <form  action="" :id="'directoryForm'" method="POST" :index="component_index" mgformtype="update" :namespace="component_namespace">
                                                                    <input type="hidden" name="selectedUsers" v-model="children"  />
                                                                    <div class="lu-form-group">
                                                                        <div class="lu-input-group">
                                                                            <input class="lu-form-control" type="text" placeholder="" name="directory" value="" v-model="directory" required>
                                                                            <div class="ui-form-submit" >
                                                                                <a href="javascript:;" @click="addDirectory('directoryForm',$event)" class="lu-btn lu-btn--info mg-submit-form">{$MGLANG->T("add")}</a>
                                                                            </div>
                                                                        </div>
                                                                        <div class="form-feedback form-feedback--icon" hidden="hidden">
                                                                            </div>
                                                                    </div>
                                                                </form>
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
                                                </div>
                                            </div>
                                            {if $rawObject->isViewFooter()}{literal}
                                                <div class="lu-t-c__footer table-footer">
                                                <div class="dataTables_paginate paging_simple_numbers" id="DataTables_Table_0_paginate">
                                                <a :class='"paginate_button previous" + ((curPage < 2) ? " disabled" : "")' aria-controls="DataTables_Table_0" :data-dt-idx='curPage -1' tabindex="0" href="javascript:;" page="prev" v-on:click="changePage" id="{/literal}{$elementId}{literal}_previous"></a>
                                                <span v-for="pageNumber in pagesMap" >
                                                            <a v-if='pageNumber && pageNumber !== "..."' :class='"paginate_button" + (curPage === pageNumber ? " current" : "")' aria-controls="DataTables_Table_0" v-on:click="changePage" :data-dt-idx="pageNumber" tabindex="0"> {{ pageNumber}} </a>
                                                            <a v-if='pageNumber && pageNumber === "..."' class="paginate_button disabled" > {{ pageNumber}} </a>
                                                        </span>
                                                <a :class='"paginate_button next" + ((curPage === allPages || allPages === 0) ? " disabled" : "")' aria-controls="DataTables_Table_0" :data-dt-idx='curPage +1' tabindex="0" href="javascript:;" page="next" v-on:click="changePage" id="{/literal}{$elementId}{literal}_next"></a>
                                                </div>
                                                    <div class="lu-dt-buttons">
                                                        {/literal}
                                                        {foreach from=$rawObject->getTableLengthList() key=tplKey item=lengthList}
                                                            {if $lengthList == "inf"}
                                                                <a class="dt-button {if $rawObject->getTableLength() == $lengthList}active{/if}" tabindex="0" data-length="999999" v-on:click="updateLength" aria-controls="DataTables_Table_0" href="#{$elementId}">
                                                                <span>∞</span>
                                                                </a>
                                                            {else}
                                                                <a class="dt-button {if $rawObject->getTableLength() == $lengthList}active{/if}" tabindex="0" data-length="{$lengthList}" v-on:click="updateLength" aria-controls="DataTables_Table_0" href="#{$elementId}">
                                                                <span>{$lengthList}</span>
                                                                </a>
                                                            {/if}
                                                        {/foreach}
                                                    </div>
                                                    </div>
                                            {/if}
                                        </div>
                                    </div>


                                </div>


                                </div>
                            </div>
                                <div id="contTabstep4" :class="{ 'is-active': step==4 }" class="lu-tab-pane">



                                </div>

                        </div>
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
    </div>
</div>
</div>
</script>

