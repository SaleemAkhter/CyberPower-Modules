<script type="text/x-template" id="t-mg-fileEdit-{$elementId|strtolower}"
        :component_id="component_id"
        :component_namespace="component_namespace"
        :component_index="component_index"
        >
    <div class=" lu-col-md-12 " id="mg-fileEdit">
    <div class="box light">

        <div class="box-body">
            <div class="lu-row">
                <div class=" lu-col-md-12 ">
                    <form  action="" :id="'fileEdit'" method="POST" :index="component_index" mgformtype="update" :namespace="component_namespace">
                        <input type="hidden" name="file" v-model="data.file"  />
                        <div class="lu-widget">
                            <div class="lu-widget__header">
                                <div class="lu-widget__nav swiper-container swiper-container-horizontal swiper-container-false" data-content-slider="" style="visibility: visible;">
                                </div>
                            </div>
                            <div class="lu-widget__body p-20">
                                <div class="form-group" >
                                    <label class="lu-form-label" id="filenamelabel" v-text="data.file"> </label>
                                    <div class="input-group">
                                       <textarea rows="6" placeholder="" id="filedata" name="text" v-model="data.FILEDATA" class="lu-form-control"></textarea>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-lg-2">
                                        <div class="form-group" >
                                            <label class="lu-form-label"> Mode</label>
                                            <div class="input-group">
                                                <select name="mode" id="mode" v-model="mode" class="lu-form-control">
                                                    <option value="plain/text">text</option>
                                                    <option value="text/html">html</option>
                                                    <option value="text/javascript">javascript</option>
                                                    <option value="text/css">css</option>
                                                    <option value="application/x-httpd-php">php</option>
                                                    <option value="text/x-perl">perl</option>
                                                    <option value="text/x-ini">ini</option>
                                                    <option value="application/xml">xml</option>
                                                    <option value="text/x-sql">sql</option>
                                                    <option value="text/x-mysql">mysql</option>
                                                    <option value="application/json">json</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-2">
                                        <div class="form-group" >
                                            <label class="lu-form-label">Theme </label>
                                            <div class="input-group">
                                               <select name="theme" id="theme" v-model="theme" class="lu-form-control">
                                                <option>Default</option>
                                                <option value="base16-light">base-16-light</option>
                                                <option value="base16-dark">base-16-dark</option>
                                                <option value="monokai">monokai</option>
                                                <option value="solarized">solarized</option>
                                               </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="footeractionButtons">

                            <div class="right">
                                <div class="ui-form-submit" >
                                    <a href="javascript:;" @click="saveFile('fileEdit',$event)" class="lu-btn lu-btn--info mg-submit-form">{$MGLANG->T("save")}</a>
                                </div>

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

</script>

