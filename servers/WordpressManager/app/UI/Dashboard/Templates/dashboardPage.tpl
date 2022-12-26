<div class="lu-row lu-row--eq-height">
    <div class="lu-col-lg-8">
        <div class="lu-widget">
            <div class="lu-widget__header">
                <div class="lu-widget__top lu-top">
                    <div class="lu-top__title">{$MGLANG->T('Overview')}</div>
                </div>
            </div>
            <div class="lu-widget__body">
                <div class="lu-widget__content">
                    <p>{$MGLANG->T('description_line_1')} </p>
                    <p>{$MGLANG->T('The very first step is to select the services that shall provide the WordPress management features. Move to the')} 
                    <a href="addonmodules.php?module=WordpressManager&mg-page=products">{$MGLANG->T('Product Settings')}</a> 
                    {$MGLANG->T('and make your choice. As soon as your clients perform any actions on their installations, i.e. activate a plugin or generate a backup, you will be able to trace them in the')}
                    <a href="addonmodules.php?module=WordpressManager&mg-page=LoggerManager">{$MGLANG->T('Logs')}</a> {$MGLANG->T('section.')}
                    </p>
                    <p>{$MGLANG->T('description_line_3')}
                       <pre>php -q {$customTplVars.cron.path}</pre>
                    </p>
                </div>
            </div>

             <div class="lu-widget__body">
                <div class="lu-widget__content">
                    <p>{$MGLANG->T('description_line_2')}
                                            <a href="addonmodules.php?module=WordpressManager&mg-page=LoggerManager&mg-action=settings">Settings</a>
                        {$MGLANG->T('description_line_4')}
                       <pre>php -q {$customTplVars.cron.path2}</pre>
                    </p>
                </div>
            </div>
        </div>
    </div>
    <div class="lu-col-lg-4">
        <div class="lu-widget">
            <div class="lu-widget__header">
                <div class="lu-widget__top top">
                    <div class="lu-top__title">{$MGLANG->T('Summary')}</div>
                </div>
            </div>
            <div class="lu-widget__body">
                <div class="lu-widget__content">   
                    <ul class="lu-nav has-icons mg-wp-home-sumary">
                        <li class="lu-list__item">
                            <span class="lu-nav__summary" >
                                <i class="lu-nav__summary-icon lu-zmdi lu-zmdi-accounts-list-alt"></i>
                                <span class="lu-list__item-title">{$MGLANG->T('Active Installations')}</span>
                                <span class="lu-badge lu-badge--default lu-badge--outline"><b>{$customTplVars.installations.total}</b></span>
                            </span>
                        </li>
                        <li class="lu-list__item">
                            <span class="lu-nav__summary" href="javascript:;">
                                <i class="lu-nav__summary-icon lu-zmdi lu-zmdi-account-box"></i>
                                <span class="lu-list__item-title">{$MGLANG->T('Active Clients')}</span>
                                <span class="lu-badge lu-badge--default lu-badge--outline"><b>{$customTplVars.clients.total}</b></span>
                            </span>
                        </li>
                        <li class="lu-list__item">
                            <span class="lu-nav__summary">
                                <i class="lu-nav__summary-icon lu-zmdi lu-zmdi lu-zmdi-settings"></i>
                                <span class="lu-list__item-title">{$MGLANG->T('Active Products')}</span>
                                <span class="lu-badge lu-badge--default lu-badge--outline"><b>{$customTplVars.products.total}</b></span>
                            </span>
                        </li>
                         <li class="lu-list__item">
                            <span class="lu-nav__summary">
                                <i class="lu-nav__summary-icon lu-zmdi lu-zmdi lu-zmdi-widgets"></i>
                                <span class="lu-list__item-title">{$MGLANG->T('Active Plugin Packages')}</span>
                                <span class="lu-badge lu-badge--default lu-badge--outline"><b>{$customTplVars.pluginPackage.total}</b></span>
                            </span>
                        </li>
                        <li class="lu-list__item">
                            <span class="lu-nav__summary">
                                <i class="lu-nav__summary-icon lu-zmdi lu-zmdi lu-zmdi-view-web"></i>
                                <span class="lu-list__item-title">{$MGLANG->T('Active Theme Packages')}</span>
                                <span class="lu-badge lu-badge--default lu-badge--outline"><b>{$customTplVars.themePackage.total}</b></span>
                            </span>
                        </li>
                        <li class="lu-list__item">
                            <span class="lu-nav__summary">
                                <i class="lu-nav__summary-icon lu-zmdi lu-zmdi lu-zmdi-group-work"></i>
                                <span class="lu-nav__summary-text">{$MGLANG->T('Active Instance Images')}</span>
                                <span class="lu-badge lu-badge--default lu-badge--outline"><b>{$customTplVars.instanceImage.total}</b></span>
                            </span>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>