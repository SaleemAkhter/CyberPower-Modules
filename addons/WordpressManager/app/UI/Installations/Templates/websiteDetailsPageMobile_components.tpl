<script type="text/x-template" id="t-mg-website-details-mobile-{$elementId|strtolower}"
        :component_id="component_id"
        :component_namespace="component_namespace"
        :component_index="component_index"
>
    <div class="lu-row lu-row--eq-height">
        <div class="lu-col-lg-12 mg-left-menu-content">
            <div id="mg-website-details-mobile">
                <div class="lu-widget__body" v-if="data.website">
                
                    <div class="lu-widget__content" v-if="data.website">
                        <ul class="lu-list lu-list--info">
                            {include file='websiteDetailsTpls/screenshot.tpl'}                            
 
                        </ul>
                    </div>

                    <div class="lu-widget__top top">
                        <div class="lu-top__title">{$MGLANG->T('GeneralInformations')}</div>
                    </div>

                    <div class="lu-widget__content" v-if="data.website">
                        <ul class="lu-list lu-list--info">
       
                            {include file='websiteDetailsTpls/basicStats.tpl'}
                        </ul>
                    </div>



                    <div class="lu-widget__content" v-else>
                        <ul class="lu-list lu-list--info">
                            {$MGLANG->T('NoDataAvailable')}
                        </ul>
                    </div>

                    <div class="lu-widget__top top">
                        <div class="lu-top__title">{$MGLANG->T('pageStats')}</div>
                    </div>
                    <div class="lu-widget__content" v-if="data.website.pageStats">
                        <ul class="lu-list lu-list--info">
                            {include file='websiteDetailsTpls/pageStats.tpl'}
                        </ul>
                    </div>
                    <div class="lu-widget__content" v-else>
                        <ul class="lu-list lu-list--info">
                            {$MGLANG->T('NoDataAvailable')}
                        </ul>
                    </div>

                    <div class="lu-widget__top top">
                        <div class="lu-top__title">{$MGLANG->T('opportunities')}</div>
                    </div>
                    <div class="lu-widget__content" v-if="data.website.opportunities">
                        <ul class="lu-list lu-list--info">
                            {include file='websiteDetailsTpls/opportunities.tpl'}
                        </ul>
                    </div>


                    <div class="lu-widget__content" v-else>
                        <ul class="lu-list lu-list--info">
                            {$MGLANG->T('NoDataAvailable')}
                        </ul>
                    </div>

                    <div class="lu-widget__top top">
                        <div class="lu-top__title">{$MGLANG->T('auditPassed')}</div>
                    </div>
                    <div class="lu-widget__content" v-if="data.website.audits">
                        <ul class="lu-list lu-list--info">
                            {include file='websiteDetailsTpls/auditsPassed.tpl'}
                        </ul>
                    </div>

                    <div class="lu-widget__content" v-else>
                        <ul class="lu-list lu-list--info">
                            {$MGLANG->T('NoDataAvailable')}
                        </ul>
                    </div>

                    <div class="lu-widget__top top">
                        <div class="lu-top__title">{$MGLANG->T('diagnostics')}</div>
                    </div>
                    <div class="lu-widget__content" v-if="data.website.diagnostics">
                        <ul class="lu-list lu-list--info">
                            {include file='websiteDetailsTpls/diagnostics.tpl'}
                        </ul>
                    </div>
                    <div class="lu-widget__content" v-else>
                        <ul class="lu-list lu-list--info">
                            {$MGLANG->T('NoDataAvailable')}
                        </ul>
                    </div>

                </div>

                <div class="lu-widget__body" v-else>
                    <div class="lu-widget__content">
                        <ul class="lu-alert lu-alert--info">
                            {$MGLANG->T('TryAgainLater')}
                        </ul>
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
