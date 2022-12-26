<script type="text/x-template" id="t-mg-build-{$elementId|strtolower}"
        :component_id="component_id"
        :component_namespace="component_namespace"
        :component_index="component_index"
        >
    <div class=" lu-col-md-12 " id="mg-build">
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
                                <li  class="lu-nav__item swiper-slide ">
                                    <a class="lu-nav__link"  href="{$rawObject->getUpdateUrl()}">
                                        <span class="lu-nav__link-text">{$MGLANG->T("update")}</span>
                                    </a>
                                </li>
                                <li class="lu-nav__item swiper-slide is-active" >
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

                                <div id="contTabstep1" :class="{ 'is-active': step==1 }" class="lu-tab-pane">
                                </div>
                                <div id="contTabstep2"  class="lu-tab-pane " :class="{ 'is-active': step==2 }">
                                    <div class="lu-widget" v-for="(build, option) in builds">
                                        <div class="lu-widget__header">
                                            <div class="lu-widget__top lu-top">
                                                <strong v-text="build.description"></strong>
                                            </div>
                                        </div>
                                        <div class="lu-widget__body p-20 cb-update-btns" >
                                            <table class="lu-table lu-table--mob-collapsible dataTable no-footer dtr-column table-striped" width="100%" role="grid">
                                                <thead>
                                                    <tr>
                                                        <th>{$MGLANG->T("name")}</th>
                                                        <th>{$MGLANG->T("description")}</th>
                                                        <th>{$MGLANG->T("version")}</th>
                                                        <th>&nbsp;</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <template v-for="(option,software) in build.builds">
                                                        <tr v-if="option.skip=='no'">
                                                            <td v-text="option.name"></td>
                                                            <td v-text="option.description"></td>
                                                            <td v-text="option.version"></td>
                                                            <td>
                                                                <form action="" :id="'update'+option.id" method="POST" :index="component_index" mgformtype="update" :namespace="component_namespace">
                                                                    <input type="hidden" name="software" :value="software">
                                                                    <button class="btn btn-primary btn-xs updateButton" @click="update('update'+option.id,$event)" :data-update="option.id" >
                                                                         {$MGLANG->T("buildbtn")}
                                                                    </button>
                                                                </form>
                                                            </td>
                                                        </tr>
                                                    </template>
                                                </tbody>
                                            </table>

                                        </div>
                                    </div>
                                    <form action="" class="text-right" :id="'updateall'" method="POST" :index="component_index" mgformtype="update" :namespace="component_namespace">
                                        <input type="hidden" name="software" value="all">
                                        <button class="btn btn-primary btn-xs updateButton" @click="update('updateall',$event)" data-update="all" >
                                             {$MGLANG->T("buildbtnall")}
                                        </button>
                                    </form>
                                     <div class="lu-preloader-container lu-preloader-container--full-screen lu-preloader-container--overlay" v-show="loading">
                                            <div class="lu-preloader lu-preloader--sm"></div>
                                    </div>
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

