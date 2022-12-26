<script type="text/x-template" id="t-mg-wp-installation-detailsC-{$elementId|strtolower}"
        :component_id="component_id"
        :component_namespace="component_namespace"
        :component_index="component_index"
>

    <div id="mg-wp-installation-detailsC">
<div class="lu-alert lu-alert--sm lu-alert--info lu-alert--faded modal-alert-top" v-if="!data.installations.length">
            <div class="lu-alert__body" style="padding: 15px; text-align: center; margin-left:auto; margin-right:auto;">
            {$MGLANG->T('noWp')}
            
            </div>
        </div>

        <div class="lu-widget installation-details" v-for="item in data.installations">
            <div class="lu-widget__header">
                <div class="lu-widget__top top">
                    <div class="lu-top__title">
                        <div class="lu-row">
                                <div class="lu-col-lg-3 textwithoutwrap">
                                    {literal}{{ item.url }}{/literal}
                                </div>
                                <div class="lu-col-lg-3 textwithoutwrap" >
                                   {literal}{{ item.site_name }}{/literal}
                                </div>
                                <div class="lu-col-lg-3 lu-col-xs-6">
                                    {foreach from=$rawObject->getButtons() key=buttonKey item=buttonValue}
                                        {if $buttonValue->getId()=="controlPanel"}
                                            {$buttonValue->getHtml()}
                                        {/if}
                                    {/foreach}
                                </div>
                                <div class="lu-col-lg-2 lu-col-xs-5">
                                    <span v-if="item.isOld" class="lu-badge lu-badge--warning lu-badge--xlg lu-align-middle">Updates Available</span>
                                    <span v-else class="lu-badge lu-badge--success lu-badge--xlg lu-align-middle">Up to date</span>
                                </div>
                                <div class="lu-col-lg-1 lu-col-xs-1" @click="item.opened = !item.opened" >
                                    <span class="expand"><span class=" lu-zmdi lu-zmdi-chevron-down"></span></span>
                                </div>
                        </div>
                         </div>
                </div>
            </div>

            <div class="lu-widget__body" v-show="item.opened">
                <div class="lu-widget__content">
                    <div class="lu-row">
                        <div class="lu-col-lg-4 lu-col-md-5 lu-col-sm-12 installation-details-image">
                            <div class="lu-row mb-20">
                                <div class="lu-col-lg-12">
                                    <h6 class="d-block d-xl-inline-block"><strong>{$MGLANG->T('SiteInfo')}</strong></h6>
                                </div>
                            </div>
                            <div class="lu-row">
                                <div class="lu-col-lg-12">
                                    <a v-if="item.screenshot" :href=" 'index.php?m=WordpressManager&mg-page=home&mg-action=detail&wpid=' + item.id"><img :src="item.screenshot"></a>
                                    <a v-else :href=" 'index.php?m=WordpressManager&mg-page=home&mg-action=detail&wpid=' + item.id">
                                        <div class="wd-image col-md-4 lu-col-sm-12">
                                            {$MGLANG->T('noImage')}
                                        </div>
                                    </a>
                                </div>
                            </div>
                            <div class="lu-row mt-25">
                                <div class="lu-col-lg-12">
                                    <form action="" :id="'saveSiteInfo_'+item.id" method="POST" index="updateSiteInfo" mgformtype="update" :namespace="component_namespace">
                                        <input type="hidden" name="wpid" :value="item.id">
                                        <input type="hidden" name="installation_id" :value="item.id">
                                        <div class="lu-form-group">
                                            <label class="lu-form-label">Website URL</label>
                                            <input type="text" class="lu-form-control" size="25"  :value="item.url" id="softurl_26_68133" name="softurl">
                                        </div>
                                        <div class="lu-form-group">
                                            <label class="lu-form-label" for="site_name_26_68133">Site Name</label>
                                            <input type="text" class="lu-form-control" size="25" :value="item.site_name" id="site_name_26_68133"  name="site_name" >
                                        </div>
                                        <div class="lu-form-group">
                                            <button @click="saveSiteInfo('saveSiteInfo_'+item.id,$event)" class="lu-btn lu-btn--success submitForm mg-submit-form">Save Site Info</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                            <div class="lu-row">
                                <div class="lu-col-lg-12">
                                    <div class="lu-row">
                                        <div class="col-sm-10 col-xs-10">
                                            <div @click="item.showDbDetails = !item.showDbDetails"
                                                 style="cursor:pointer;" class="form-label">
                                                <label class="form-label" style="cursor:pointer;">
                                                <i id="advoptions26_68133_toggle_plus" v-bind:class="[item.showDbDetails ? 'fas fa-minus-square' : 'fas fa-plus-square']"  ></i>
                                                &nbsp;&nbsp;Database Details
                                                </label>
                                            </div>
                                            <div v-show="item.showDbDetails">
                                                <table style="margin-top:5px; margin-left:5px; border-spacing: 10px; border-collapse: separate;">
                                                    <tbody><tr>
                                                        <td><b>Database Name</b></td>
                                                        <td v-text="item.additionalData.db"></td>
                                                    </tr>
                                                    <tr>
                                                        <td><b>Database User</b></td>
                                                        <td v-text="item.additionalData.dbUser"></td>
                                                    </tr>
                                                    <tr>
                                                        <td><b>Database Host</b></td>
                                                        <td v-text="item.additionalData.dbHost"></td>
                                                    </tr>
                                                </tbody></table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="lu-offset-lg-2 lu-offset-lg-1 lu-col-lg-6  lu-col-md-6 lu-col-sm-12 installation-details-info">
                            <div class="section">
                                <form action="" :id="'saveSiteConfig_'+item.id" method="POST" index="updateSiteConfig" mgformtype="saveSiteConfig" :namespace="component_namespace">
                                    <div class="lu-row title mb-20">
                                        <h6 class="lu-col-lg-4 lu-col-xs-12 d-inline-block d-xl-inline-block" style="display:inline;"><strong>{$MGLANG->T('Configuration')}</strong></h6>
                                        <div class="lu-col-lg-4 lu-col-xs-6">
                                            {foreach from=$rawObject->getButtons() key=buttonKey item=buttonValue}
                                                {if $buttonValue->getId()=="themesButton"}
                                                    {$buttonValue->getHtml()}
                                                {/if}
                                            {/foreach}
                                        </div>
                                        <div class="lu-col-lg-4 lu-col-xs-6">
                                            {foreach from=$rawObject->getButtons() key=buttonKey item=buttonValue}
                                                {if $buttonValue->getId()=="pluginsButton"}
                                                    {$buttonValue->getHtml()}
                                                {/if}
                                            {/foreach}
                                        </div>
                                    </div>
                                    <div class="lu-form-group">
                                        <label class="lu-form-label">Version</label>
                                        <span v-text="item.version"></span>
                                        <span v-show="!item.isOld" class="lu-badge lu-badge--success  mt-3">Up to date</span>
                                        <span v-show="item.isOld" class="ml-4" >
                                            {foreach from=$rawObject->getButtons() key=buttonKey item=buttonValue}
                                                {if $buttonValue->getId() =="upgradeButton"}
                                                    {$buttonValue->getHtml()}
                                                {/if}
                                            {/foreach}
                                        </span>
                                    </div>
                                    <div class="lu-form-group">
                                        <label class="lu-form-label">{$MGLANG->T('autoUpgradeWPCore')}<i data-original-title="{$MGLANG->T('autoUpgradeWPCoreTooltip')}" data-toggle="tooltip" class="lu-i-c-2x lu-zmdi lu-zmdi-help-outline lu-form-tooltip-helper"></i></label>
                                        <select name="auto_upgrade_core" :id="'auto_upgrade_core_'+item.id" class="lu-form-control" @change="saveSiteConfig('auto_upgrade_core_'+item.id,item.id,$event)">
                                            {foreach from=$rawObject->getAvailableOpts() key=val item=opt}
                                                    <option value="" :selected="(typeof item.additionalData.eu_auto_upgrade !='undefined' && item.additionalData.eu_auto_upgrade=={$val})">{$opt}</option>

                                            {/foreach}
                                        </select>
                                    </div>
                                     <div class="lu-row">
                                        <div class="lu-col-12 lu-col-lg-6">
                                            <div class="lu-form-group">
                                                <label class="lu-form-label">{$MGLANG->T('autoUpgradeWPPlugins')}<i data-original-title="{$MGLANG->T('autoUpgradeWPPluginsTooltip')}" data-toggle="tooltip" class="lu-i-c-2x lu-zmdi lu-zmdi-help-outline lu-form-tooltip-helper"></i></label>
                                                <br>
                                                <input type="radio" value="1" :id="'auto_upgrade_plugins_enabled_'+item.id" name="auto_upgrade_plugins" :checked="(typeof item.additionalData.auto_upgrade_plugins !='undefined' && item.additionalData.auto_upgrade_plugins=='on')" @change="saveSiteConfig('auto_upgrade_plugins_enabled_'+item.id,item.id,$event)">
                                                <label class="radio-label" for="'auto_upgrade_plugins_enabled_'+item.id">{$MGLANG->T('Enabled')}</label>
                                                <input type="radio" value="" :id="'auto_upgrade_plugins_disabled_'+item.id" name="auto_upgrade_plugins" :checked="(typeof item.additionalData.auto_upgrade_plugins !='undefined' && item.additionalData.auto_upgrade_plugins=='off')" @change="saveSiteConfig('auto_upgrade_plugins_disabled_'+item.id,item.id,$event)">
                                                <label class="radio-label" :for="'auto_upgrade_plugins_disabled_'+item.id" >{$MGLANG->T('Disabled')}</label>
                                            </div>

                                        </div>
                                        <div class="col-12 col-lg-6">
                                            <div class="lu-form-group">
                                                <label class="lu-form-label">{$MGLANG->T('autoUpgradeWPTheme')}<i data-original-title="{$MGLANG->T('autoUpgradeWPThemeTooltip')}" data-toggle="tooltip" class="lu-i-c-2x lu-zmdi lu-zmdi-help-outline lu-form-tooltip-helper"></i></label>
                                                <br>
                                                <input type="radio" value="1" :id="'auto_upgrade_themes_enabled_'+item.id" name="auto_upgrade_themes" :checked="(typeof item.additionalData.auto_upgrade_themes !='undefined' && item.additionalData.auto_upgrade_themes=='on')" @change="saveSiteConfig('auto_upgrade_themes_enabled_'+item.id,item.id,$event)">
                                            <label class="radio-label" :for="'auto_upgrade_themes_enabled_'+item.id">{$MGLANG->T('Enabled')}</label>
                                            <input type="radio" value="" id="auto_upgrade_themes_disabled_26_68133" name="auto_upgrade_themes" :checked="(typeof item.additionalData.auto_upgrade_themes !='undefined' && item.additionalData.auto_upgrade_themes=='off')" @change="saveSiteConfig('auto_upgrade_themes_disabled_'+item.id,item.id,$event)">
                                            <label class="radio-label" :for="'auto_upgrade_themes_disabled_'+item.id">{$MGLANG->T('Disabled')}</label>
                                            </div>

                                        </div>
                                    </div>

                                    <div class="lu-row">
                                        <div class="lu-col-12 lu-col-lg-6">
                                            <div class="lu-form-group">
                                                <label class="lu-form-label">{$MGLANG->T('wpCron')}<i data-original-title="{$MGLANG->T('wpCronTooltip')}" data-toggle="tooltip" class="lu-i-c-2x lu-zmdi lu-zmdi-help-outline lu-form-tooltip-helper"></i></label>
                                                <br>
                                               <input type="radio" value="0" :id="'disable_wp_cron_enabled_'+item.id" name="disable_wp_cron" :checked="(typeof item.additionalData.disable_wp_cron !='undefined' && !item.additionalData.disable_wp_cron)" @change="saveSiteConfig('disable_wp_cron_enabled_'+item.id,item.id,$event)">
                                                <label class="radio-label" :for="'disable_wp_cron_enabled_'+item.id">{$MGLANG->T('Enabled')}</label>
                                                <input type="radio" value="1" :id="'disable_wp_cron_disabled_'+item.id" name="disable_wp_cron" :checked="(typeof item.additionalData.disable_wp_cron !='undefined' && item.additionalData.disable_wp_cron)" @change="saveSiteConfig('disable_wp_cron_disabled_'+item.id,item.id,$event)">
                                                <label class="radio-label" :for="'disable_wp_cron_disabled_'+item.id">{$MGLANG->T('Disabled')}</label>
                                            </div>

                                        </div>
                                        <div class="lu-col-12 lu-col-lg-6">
                                            <div class="lu-row">
                                                <div class="lu-col-12">
                                                    {foreach from=$rawObject->getButtons() key=buttonKey item=buttonValue}
                                                            {if $buttonValue->getId()=="configButton" || $buttonValue->getId() =="cache" || $buttonValue->getId()=="usersButton" }
                                                                {$buttonValue->getHtml()}
                                                            {/if}
                                                    {/foreach}
                                                </div>
                                            </div>
                                            <div class="lu-row mt-10">
                                                <div class="lu-col-12">
                                                    {foreach from=$rawObject->getButtons() key=buttonKey item=buttonValue}
                                                            {if $buttonValue->getId()=="editDetails" || $buttonValue->getId() =="installationUpdateButton" || $buttonValue->getId()=="maintenanceMode" || $buttonValue->getId()=="instanceImageButton"}
                                                                {$buttonValue->getHtml()}
                                                            {/if}
                                                    {/foreach}
                                                </div>
                                            </div>

                                        </div>
                                    </div>
                                    <div class="lu-row">
                                        <div class="lu-col-12 lu-col-lg-6">
                                            <div class="lu-form-group">
                                                <label class="lu-form-label">{$MGLANG->T('debugMode')}<i data-original-title="{$MGLANG->T('debugModeTooltip')}" data-toggle="tooltip" data-html="true" class="lu-i-c-2x lu-zmdi lu-zmdi-help-outline lu-form-tooltip-helper"></i></label>
                                                <br>
                                                <input type="radio" value="1" :id="'wp_debug_enabled_'+item.id" name="wp_debug" :checked="(typeof item.additionalData.maintenanceMode !='undefined' && item.additionalData.maintenanceMode)" @change="saveSiteConfig('wp_debug_enabled_'+item.id,item.id,$event)">
                                                <label class="radio-label" :for="'wp_debug_enabled_'+item.id">{$MGLANG->T('Enabled')}</label>
                                                <input type="radio" value="" :id="'wp_debug_disabled_'+item.id" name="wp_debug" :checked="(typeof item.additionalData.maintenanceMode !='undefined' && !item.additionalData.maintenanceMode)" @change="saveSiteConfig('wp_debug_disabled_'+item.id,item.id,$event)">
                                                <label class="radio-label" :for="'wp_debug_disabled_'+item.id">{$MGLANG->T('Disabled')}</label>
                                            </div>
                                        </div>
                                    </form>
                                    </div>
                                </div>
                            <ul class="lu-list lu-list--info hidden">
                                <li class="lu-list__item">
                                    <span class="lu-list__item-title">{$MGLANG->T('url')}</span>
                                    <span class="lu-list__value">
                                <a target="_blank" style="word-break: break-all;"
                                   v-bind:href="item.url">{literal}{{ item.url }}{/literal}</a>
                                </span>
                                </li>

                                <li class="lu-list__item">
                                    <span class="lu-list__item-title">{$MGLANG->T('product')}</span>
                                    <span class="lu-list__value">
                                <a v-bind:href=" 'clientarea.php?action=productdetails&amp;id=' + item.id ">
                                {literal}{{ item.name }}{/literal}</a>
                                </span>
                                </li>

                                <li class="lu-list__item"><span class="lu-list__item-title">{$MGLANG->T('created')}</span>
                                    <span class="lu-list__value">{literal}{{ item.created_at }}{/literal} </span>
                                </li>

                                <li class="lu-list__item">
                                    <span class="lu-list__item-title">{$MGLANG->T('version')}</span>
                                    <span class="lu-list__value">{literal}{{ item.version }}{/literal}

                                 <a v-if="item.isOld" href="javascript:;" data-toggle="lu-tooltip"
                                    title="An update to a newer version of WordPress is available."
                                    class="lu-btn lu-btn--sm lu-btn--link lu-btn--icon lu-btn--danger redAlert vertical-align-middle">
                                <i class="lu-btn__icon lu-zmdi lu-zmdi-alert-circle"></i>
                                </a>
                                </span>
                                </li>
                            </ul>


                        </div>
                    </div>
                    <div class="lu-row mt-10 mb-20">
                        <div class="lu-col-lg-12 text-center">
                            <div class="actions-buttons mb-10">
                                {foreach from=$rawObject->getButtons() key=buttonKey item=buttonValue}
                                        {if $buttonValue->getId()!="controlPanel" && $buttonValue->getId() !="upgradeButton" && $buttonValue->getId()!="pluginsButton" && $buttonValue->getId() !="themesButton" && $buttonValue->getId() !="configButton" && $buttonValue->getId() !="cache" && $buttonValue->getId() !="usersButton" && $buttonValue->getId() !="editDetails" && $buttonValue->getId() !="installationUpdateButton" && $buttonValue->getId() !="maintenanceMode"  && $buttonValue->getId()!="instanceImageButton"}
                                            {$buttonValue->getHtml() }
                                        {/if}
                                {/foreach}
                            </div>
                        </div>

                        {* <div class="lu-col-lg-6 text-center">
                            <div class="actions-buttons mb-10">
                                {foreach from=$rawObject->getButtons() key=buttonKey item=buttonValue}
                                    {if $buttonValue@iteration is div by 2}
                                        {if $buttonValue->getId()!="controlPanel" && $buttonValue->getId() !="upgradeButton" && $buttonValue->getId()!="pluginsButton" && $buttonValue->getId() !="themesButton"}
                                            {$buttonValue->getHtml()}
                                        {/if}
                                    {/if}
                                {/foreach}
                            </div>
                        </div> *}
                    </div>
                </div>


            </div>
            <div class="lu-preloader-container lu-preloader-container--full-screen lu-preloader-container--overlay" v-show="loading_state">
                    <div class="lu-preloader lu-preloader--sm"></div>
                </div>
        </div>

    </div>
</script>
