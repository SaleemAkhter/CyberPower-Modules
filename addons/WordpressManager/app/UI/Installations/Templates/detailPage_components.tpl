<script type="text/x-template" id="t-mg-wp-details-{$elementId|strtolower}"
        :component_id="component_id"
        :component_namespace="component_namespace"
        :component_index="component_index"
        >
    <div class="lu-row lu-row--eq-height">
        <div class="lu-col-lg-12 mg-left-menu-content">
            <div class="lu-widget" id="mg-wp-installation-details">
                <div class="lu-widget__header">
                    <div class="lu-widget__top top">
                        <div class="lu-top__title">{$MGLANG->T('Installation Details')}</div>
                    </div>
                </div>
                <div class="lu-widget__body">
                    <div class="lu-widget__content">
                        <ul class="lu-list lu-list--info">
                            <li class="lu-list__item">
                                <span class="lu-list__item-title">{$MGLANG->T('Domain')}</span>
                                <span class="lu-list__value" v-if="data.installation" ><a style="word-break: break-all;" {literal} :href=" data.installation.url "  {/literal} target="_blank">{literal}{{ data.installation.domain }}{/literal}</a></span>
                            </li>
                            <li class="lu-list__item">
                                <span class="lu-list__item-title">{$MGLANG->T('URL')}</span>
                                <span class="lu-list__value" v-if="data.installation" ><a style="word-break: break-all;"  {literal}  :href=" data.installation.url"  {/literal}  target="_blank">{literal}{{ data.installation.url }}{/literal}</a></span>
                            </li>

                            <li class="lu-list__item">
                                <span class="lu-list__item-title">{$MGLANG->T('Product')}</span>
                                <span class="lu-list__value"><a    {literal} :href="'clientarea.php?action=productdetails&id=' + data.installation.hosting_id "  {/literal} >{literal}{{ data.productName }}{/literal}</a></span>
                            </li> 
                            <li class="lu-list__item">
                                <span class="lu-list__item-title">{$MGLANG->T('Version')}</span>
                                <span class="lu-list__value" v-if="data.newVersionAvailable" v-html="data.newVersionAvailable"></span>
                                <span class="lu-list__value" v-else >{literal}{{ data.installation.version}}{/literal}</span>
                            </li>
                            <li class="lu-list__item">
                                <span class="lu-list__item-title">{$MGLANG->T('Site Name')}</span>
                                <span class="lu-list__value" style="word-break: break-all;" v-if="data.details.userins.site_name"> {literal}{{ data.details.userins.site_name }}{/literal} </span>
                                <span class="lu-list__value" style="word-break: break-all;" v-else>{literal}{{ data.details.userins.live_ins.site_name }}{/literal} </span>
                             </li>                        
                            <li class="lu-list__item">
                                <span class="lu-list__item-title">{$MGLANG->T('Created')}</span>
                                <span class="lu-list__value">{literal}{{ data.installation.created_at }}{/literal} </span>
                            </li>
                            <li class="lu-list__item">
                                <span class="lu-list__item-title">{$MGLANG->T('Directory')}</span>
                                <span class="lu-list__value" style="word-break: break-all;" >{literal}{{ data.installation.path }}{/literal}</span>
                            </li>
                            <li class="lu-list__item">
                                <span class="lu-list__item-title">{$MGLANG->T('Database Name')}</span>
                                <span class="lu-list__value" v-if="data.details">{literal}{{ data.details.userins.softdb }}{/literal}</span>
                            </li>
                            <li class="lu-list__item">
                                <span class="lu-list__item-title">{$MGLANG->T('Database User')}</span>
                                <span class="lu-list__value" v-if="data.details">{literal}{{ data.details.userins.softdbuser }}{/literal}</span>
                            </li>
                            <li class="lu-list__item">
                                <span class="lu-list__item-title">{$MGLANG->T('Database Password')}</span>
                                <span class="lu-list__value">{$rawObject->insertButton('passwordHidden')}
                                <div>
                                    <!-- show password-->
                                    <span v-show="passwordShow">{literal}{{ data.details.userins.softdbpass }}{/literal} </span>
                                    <span class="lu-icon-sm lu-btn-password-show" style="cursor: pointer;" v-show="passwordShow" @click="passwordShow = !passwordShow"><i class="lu-zmdi lu-zmdi-eye lu-btn--default lu-btn--link"></i></span>
                                    <!-- hide password-->
                                    <span v-show="!passwordShow">{literal}{{ data.details.passwordHidden }}{/literal} </span>
                                    <span class="lu-icon-sm lu-btn-default lu-btn-password-hide" style="cursor: pointer;" v-show="!passwordShow" @click="passwordShow = !passwordShow"><i  class="lu-zmdi lu-zmdi-eye-off lu-btn--default lu-btn--link"></i></span>
                                </div>
                                </span>
                            </li>
                            <li class="lu-list__item">
                                <span class="lu-list__item-title">{$MGLANG->T('Database Host')}</span>
                                <span class="lu-list__value">{literal}{{ data.details.userins.softdbhost }}{/literal}</span>
                            </li>
                            <li class="lu-list__item">
                                <span class="lu-list__item-title">{$MGLANG->T('Debug')}</span>
                                <span class="lu-list__value" v-if="data.details.WP_DEBUG">{$MGLANG->T('On')}</span>
                                <span class="lu-list__value" v-else>{$MGLANG->T('Off')}</span>
                            </li>
                            <li class="lu-list__item">
                                <span class="lu-list__item-title">{$MGLANG->tr('Instance Image')}</span>
                                <span class="lu-list__value" v-if="data.instanceImage">{$MGLANG->T('Yes')}</span>
                                <span class="lu-list__value" v-else>{$MGLANG->T('No')}</span>
                            </li>
                        </ul>


                    </div>
                    <div class="lu-preloader-container lu-preloader-container--full-screen lu-preloader-container--overlay" v-show="loading_state">
                        <div class="lu-preloader lu-preloader--sm"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    {if ($isDebug eq true AND (count($MGLANG->getMissingLangs()) != 0))}{literal}
        <div class="lu-row">
        {/literal}{foreach from=$MGLANG->getMissingLangs() key=varible item=value}{literal}
                <div class="lu-col-md-12"><b>{/literal}{$varible}{literal}</b> = '{/literal}{$value}{literal}';</div>
        {/literal}{/foreach}{literal}
        </div>
    {/literal}{/if}
</script>

