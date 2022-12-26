<script type="text/x-template" id="t-mg-backup-{$elementId|strtolower}"
        :component_id="component_id"
        :component_namespace="component_namespace"
        :component_index="component_index"
        >
<div class=" lu-col-md-12 " id="mg-backup">
    <div class="box light">
        <div class="box-body">
            <form  action="" :id="'backup'" method="POST" :index="component_index" mgformtype="create" :namespace="component_namespace">
                <input type="hidden" name="selectedSettings" v-model="children"  />
                <input type="hidden" name="selectedDomains" v-model="data.selectedDomains"  />
                <div class="lu-widget">
                    <div class="lu-widget__header">
                        {if $rawObject->isRawTitle() || $rawObject->getTitle()}
                        <div class="lu-widget__top lu-top">
                            <div class="lu-top__title">
                                {if $rawObject->getIcon()}<i class="{$rawObject->getIcon()}"></i>{/if}
                                {if $rawObject->isRawTitle()}{$rawObject->getRawTitle()}{elseif $rawObject->getTitle()}{$MGLANG->T($rawObject->getTitle())}{/if}
                            </div>
                        </div>
                        {/if}
                        <div class="lu-widget__nav swiper-container swiper-container-horizontal swiper-container-false" data-content-slider="" style="visibility: visible;">

                        </div>
                    </div>
                    <div class="lu-widget__body p-20">
                        <div class="form-group" >
                            <label class="radio-inline">
                              <input type="radio" name="domains" v-model="data.domains" id="all" value="all"> {$MGLANG->T("alldomains")}
                            </label>
                            <label class="radio-inline">
                              <input type="radio" name="domains" v-model="data.domains" id="selected" value="selected"> {$MGLANG->T("selecteddomains")}
                            </label>

                        </div>
                        <div class="domainstable" v-show="data.domains=='selected'">
                            <div class="lu-t-c  datatableLoader"  data-table-container data-check-container>

                                <div class="lu-t-c__mass-actions lu-top">
                                    <div class="lu-top__title"><span class="lu-badge lu-badge--primary lu-value">0</span> {$MGLANG->absoluteT('datatableItemsSelected')}</div>
                                    <div class="lu-top__toolbar">
                                    </div>
                                </div>

                                <div class="dataTables_wrapper no-footer">
                                    <div>
                                        <table class="lu-table lu-table--mob-collapsible dataTable no-footer dtr-column table-striped" width="100%" role="grid">
                                            <thead>
                                                <tr role='row'>
                                                    <th class="" name="">
                                                        <div class="lu-rail">
                                                            <div class="lu-form-check">
                                                                <label>
                                                                    <input type="checkbox" data-check-all="" class="lu-form-checkbox">
                                                                    <span class="lu-form-indicator"></span>
                                                                </label>
                                                            </div>
                                                            <span class="lu-table__text" >{$MGLANG->T('table')}</span>
                                                        </div>
                                                    </th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <template v-for="(domain,index) in data.domains_list">
                                                    <tr  :actionid="domain" role="row" class="">
                                                        <td>
                                                            <div class="lu-rail">
                                                                <div class="lu-form-check">
                                                                    <label>
                                                                        <input type="checkbox" v-model="data.selectedDomains"  class="lu-form-checkbox  domain-check table-mass-action-check" :value="domain"  >
                                                                        <span class="lu-form-indicator">
                                                                            </span>
                                                                    </label>
                                                                </div>
                                                                <span v-html="domain"></span>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                </template>
                                            </tbody>
                                        </table>
                                        <div class="lu-preloader-container lu-preloader-container--full-screen lu-preloader-container--overlay" v-show="loading">
                                            <div class="lu-preloader lu-preloader--sm"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="lu-t-c  datatableLoader"  data-table-container data-check-container>
                            <div class="dataTables_wrapper no-footer">
                                <div>
                                    <table class="lu-table lu-table--mob-collapsible dataTable no-footer dtr-column table-striped" width="100%" role="grid">

                                        <tbody>
                                            <template v-for="(settings, group,index) in data.settings">
                                                <tr  :actionid="group" role="row">
                                                    <td>
                                                        <div class="lu-rail">
                                                            <div class="lu-form-check" >
                                                                <label>
                                                                    <input type="checkbox" v-on:change="selectSettingGroup(group,$event)" class="lu-form-checkbox group-check table-mass-action-check" v-model="data.settingroups" :value="group">
                                                                    <span class="lu-form-indicator">
                                                                        </span>
                                                                </label>
                                                            </div>
                                                            <span v-html="settings.text"></span>
                                                        </div>
                                                    </td>
                                                </tr>

                                                <tr v-for="(setting, index) in settings.children" :actionid="setting" role="row" class="child-row">
                                                    <td>
                                                        <div class="lu-rail">
                                                            <div class="lu-form-check">
                                                                <label>
                                                                    <input type="checkbox" v-model="children" v-on:change="selectSetting(setting.value,$event)" class="lu-form-checkbox  setting-check table-mass-action-check" :value="setting.value" :parentgroup="group" >
                                                                    <span class="lu-form-indicator">
                                                                        </span>
                                                                </label>
                                                            </div>
                                                            <span v-html="setting.text"></span>
                                                        </div>
                                                    </td>
                                                </tr>
                                            </template>
                                        </tbody>
                                    </table>
                                    <div class="lu-preloader-container lu-preloader-container--full-screen lu-preloader-container--overlay" v-show="loading">
                                        <div class="lu-preloader lu-preloader--sm"></div>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                    <div class="ui-form-submit p-20 text-right" >
                        <a href="javascript:;" @click="createBackup('backup',$event)" class="lu-btn lu-btn--info mg-submit-form">{$MGLANG->T("createBackup")}</a>
                    </div>
                </div>
            </form>

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
</script>

