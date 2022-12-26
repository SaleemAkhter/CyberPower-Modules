<script type="text/x-template" id="t-mg-wp-installation-details-{$elementId|strtolower}"
        :component_id="component_id"
        :component_namespace="component_namespace"
        :component_index="component_index"
>

    <div id="mg-wp-installation-details">

<div class="lu-alert lu-alert--sm lu-alert--info lu-alert--faded modal-alert-top" v-if="!data.installations.length">
            <div class="lu-alert__body" style="padding: 15px; text-align: center; margin-left:auto; margin-right:auto;">
            {$MGLANG->T('noWp')}
            
            </div>
        </div>

        <div class="lu-widget installation-details" v-for="item in data.installations">
            <div class="lu-widget__header">
                <div class="lu-widget__top top">
                    <div class="lu-top__title">{literal}{{ item.domain }}{/literal}</div>
                </div>
            </div>

            <div class="lu-widget__body">
                <div class="lu-widget__content">
                    <div class="lu-row">
                        <div class="lu-col-lg-4 lu-col-md-5 lu-col-sm-12 installation-details-image">
                            <a v-if="item.screenshot" :href=" 'index.php?m=WordpressManager&mg-page=home&mg-action=detail&wpid=' + item.id"><img :src="item.screenshot"></a>
                            <a v-else :href=" 'index.php?m=WordpressManager&mg-page=home&mg-action=detail&wpid=' + item.id">
                                <div class="wd-image col-md-4 lu-col-sm-12">
                                    {$MGLANG->T('noImage')}
                                </div>
                            </a>
                        </div>
                        <div class="lu-col-lg-8  lu-col-md-7 lu-col-sm-12 installation-details-info">
                            <ul class="lu-list lu-list--info">
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

                            <div class="actions-buttons">
                                {foreach from=$rawObject->getButtons() key=buttonKey item=buttonValue}
                                    {$buttonValue->getHtml()}
                                {/foreach}
                            </div>
                        </div>
                    </div>
                </div>

                <div class="lu-preloader-container lu-preloader-container--full-screen lu-preloader-container--overlay"
                     style="display: none;">
                    <div class="lu-preloader lu-preloader--sm"></div>
                </div>
            </div>
        </div>

    </div>
</script>