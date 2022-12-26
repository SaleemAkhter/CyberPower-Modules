<script type="text/x-template" id="t-mg-updates-{$elementId|strtolower}"
        :component_id="component_id"
        :component_namespace="component_namespace"
        :component_index="component_index"
        >
    <div class=" lu-col-md-12 " id="mg-updates">
    <div class="box light">

        <div class="box-body">
            <div class="lu-row">
                <div class=" lu-col-md-12 ">

                <div class="lu-widget">
                    <div class="lu-widget__header">
                        {if $rawObject->isRawTitle() || $rawObject->getTitle()}
                            <div class="lu-widget__top lu-top">
                                <div class="lu-top__toolbar">
                                    <form action="" :id="'updatecustombuild'" method="POST" :index="component_index" mgformtype="update" :namespace="component_namespace">
                                        <input type="hidden" name="software" :value="'update'">
                                        <button class="lu-btn lu-btn--primary lu-tooltip drop-target drop-element-attached-bottom drop-element-attached-center drop-target-attached-top drop-target-attached-center updateButton" @click="update('updatecustombuild',$event)" data-update="updatecustombuild" >
                                             {$MGLANG->T("custombuild")}
                                        </button>
                                    </form>
                                    <form action="" :id="'custombuildandversion'" method="POST" :index="component_index" mgformtype="update" :namespace="component_namespace">
                                        <input type="hidden" name="software" :value="'update_script'">
                                        <button class="lu-btn lu-btn--primary lu-tooltip drop-target drop-element-attached-bottom drop-element-attached-center drop-target-attached-top drop-target-attached-center updateButton" @click="update('custombuildandversion',$event)" :data-update="'custombuildandversion'" >
                                             {$MGLANG->T("custombuildandversion")}
                                        </button>
                                    </form>

                                </div>
                            </div>
                        {/if}
                        <div class="lu-widget__nav swiper-container swiper-container-horizontal swiper-container-false" data-content-slider="" style="visibility: visible;">
                            <ul class="swiper-wrapper lu-nav lu-nav--md lu-nav--h lu-nav--tabs lu-nav--arrow">
                                <li  class="lu-nav__item swiper-slide is-active">
                                    <a class="lu-nav__link"  href="{$rawObject->getUpdateUrl()}">
                                        <span class="lu-nav__link-text">{$MGLANG->T("update")}</span>
                                    </a>
                                </li>
                                <li class="lu-nav__item swiper-slide" >
                                    <a class="lu-nav__link"  href="{$rawObject->getBuildUrl()}">

                                        <span class="lu-nav__link-text">{$MGLANG->T("build")}</span>
                                    </a>
                                </li>
                                <li class="lu-nav__item swiper-slide" >
                                    <a class="lu-nav__link"  href="{$rawObject->getEditUrl()}">
                                        <span class="lu-nav__link-text">{$MGLANG->T("edit")}</span>
                                    </a>
                                </li>

                            </ul>
                        </div>
                    </div>
                    <div class="lu-widget__body p-20">
                        <div class="lu-tab-content pt-20">
                                <div id="contTabstep1"  class="lu-tab-pane " :class="{ 'is-active': step==1 }">
                                    <div class="lu-widget" v-for="(option, software) in updates">
                                        <div class="lu-widget__header">
                                            <div class="lu-widget__top lu-top">
                                                <strong v-text="option.name"></strong>
                                            </div>
                                        </div>
                                        <div class="lu-widget__body p-20 cb-update-btns" >
                                            <span v-text="option.name +' '+option.current+'  update to '+option.offered+' is available'" ></span>
                                            <div class="btn-group-sm lu-pull-right" data-toggle="buttons">
                                                <form action="" :id="'update'+option.id" method="POST" :index="component_index" mgformtype="update" :namespace="component_namespace">
                                                    <input type="hidden" name="software" :value="software">
                                                    <button class="btn btn-primary updateButton btn-xs" @click="update('update'+option.id,$event)" :data-update="option.id" >
                                                         {$MGLANG->T("updateBtn")}
                                                    </button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                    <p class="text-center" v-show="updates.length==0">All software is up to date</p>
                                     <div class="lu-preloader-container lu-preloader-container--full-screen lu-preloader-container--overlay" v-show="loading">
                                            <div class="lu-preloader lu-preloader--sm"></div>
                                    </div>
                                </div>
                                <div id="contTabstep2" :class="{ 'is-active': step==2 }" class="lu-tab-pane">


                                </div>

                                <div id="contTabstep3" :class="{ 'is-active': step==3 }" class="lu-tab-pane">



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

