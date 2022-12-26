<script type="text/x-template" id="t-mg-edit-{$elementId|strtolower}" :component_id="component_id"
:component_namespace="component_namespace" :component_index="component_index">
<div class=" lu-col-md-12 " id="mg-edit">
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
                                        <button class="lu-btn lu-btn--primary lu-tooltip drop-target drop-element-attached-bottom drop-element-attached-center drop-target-attached-top drop-target-attached-center updateButton" @click="updateBuild('updatecustombuild',$event)" data-update="updatecustombuild" >
                                             {$MGLANG->T("custombuild")}
                                        </button>
                                    </form>
                                    <form action="" :id="'custombuildandversion'" method="POST" :index="component_index" mgformtype="update" :namespace="component_namespace">
                                        <input type="hidden" name="software" :value="'update_script'">
                                        <button class="lu-btn lu-btn--primary lu-tooltip drop-target drop-element-attached-bottom drop-element-attached-center drop-target-attached-top drop-target-attached-center updateButton" @click="updateBuild('custombuildandversion',$event)" :data-update="'custombuildandversion'" >
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
                                    <li class="lu-nav__item swiper-slide " >
                                        <a class="lu-nav__link"  href="{$rawObject->getBuildUrl()}">

                                            <span class="lu-nav__link-text">{$MGLANG->T("build")}</span>
                                        </a>
                                    </li>
                                    <li class="lu-nav__item swiper-slide is-active" >
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
                                <div id="contTabstep2" :class="{ 'is-active': step==2 }" class="lu-tab-pane">
                                </div>
                                <div id="contTabstep3"  class="lu-tab-pane " :class="{ 'is-active': step==3 }">
                                    <form  :id="'update'" method="POST" :index="component_index" mgformtype="update" :namespace="component_namespace">
                                        <div class="panel panel-default" >
                                            <div class="panel-heading">About</div>
                                            <div class="panel-body" style="text-align: center;">
                                                <div style="font-size: 14px; margin-bottom: 10px;">Blue color means that the option is active (selected), grey color - inactive (not selected).</div>
                                                <div class="btn-group" data-toggle="buttons">
                                                    <label class="btn btn-options active">
                                                        Active
                                                    </label>
                                                    <label class="btn btn-options">
                                                        Inactive
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                        <template v-for="(items, list) in settings">
                                            <div class="panel panel-default" :data-l="list" >
                                                <div   class="panel-heading" v-text="items.description"></div>
                                                <div class="panel-body" >
                                                <template v-for="(vf, key) in items.opts" >
                                                        <div  class="pull-left container-fluid min">
                                                            <h4>
                                                                <span v-text="key"></span>
                                                                <span class="badge btn-info" :data-content="makeDescription(vf['description'])">?</span>
                                                                <span class="badge btn-default" :data-content="'Default value: '+ vf['default']">!</span>
                                                            </h4>
                                                            <div :class="[vf.values[0] == 'userinput' ? 'form-group' : 'btn-group']" data-toggle="buttons">
                                                                <select v-if="['downloadserver', 'webserver'].includes(key)" class="form-control input-sm" :name="list+':'+key" :id="key">
                                                                    <template v-for="(button, k) in vf.values" >
                                                                        <option :value="button"   :selected="vf.current == button" v-text="button"></option>
                                                                    </template>

                                                                </select>
                                                                    <template v-for="(button, k) in vf.values" v-if="!['downloadserver', 'webserver'].includes(key)">
                                                                        <input v-if="button=='userinput'"  type="text" class="form-control" :name="list+':'+key" :value="vf.current"/>
                                                                        <label v-if="button!='userinput' && !(['downloadserver', 'webserver'].includes(key))"  data-container="body" data-toggle="popover" data-placement="top" :data-content=" getValidationMessage(key, button)" class="btn btn-options"
                                                                        :class="{ active: (vf['current'] == button) }" :for="list+':'+key">
                                                                            <input type="radio" :checked="vf['current'] == button"  :name="list+':'+key" :id="list+':'+key" :value="button" class="hidden"/><span v-text="button"></span>
                                                                        </label>
                                                                    </template>
                                                            </div>
                                                        </div>
                                                </template>
                                            </div>
                                            </div>
                                        </template>

                                            <div class="panel panel-default">
                                                <div class="panel-body">
                                                    <button class="btn btn-success" @click="editOptions('update',$event)" id="edit_options_submit_button" data-loading-text="Loading...">Save</button>
                                                </div>
                                            </div>

                                        </form>
                                        <div class="lu-preloader-container lu-preloader-container--full-screen lu-preloader-container--overlay" v-show="loading">
                                            <div class="lu-preloader lu-preloader--sm"></div>
                                        </div>
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
