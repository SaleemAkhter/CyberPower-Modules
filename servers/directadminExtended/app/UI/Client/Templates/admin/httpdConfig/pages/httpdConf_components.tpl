<script type="text/x-template" id="t-mg-fileEdit-{$elementId|strtolower}"
        :component_id="component_id"
        :component_namespace="component_namespace"
        :component_index="component_index"
        :filedata="filedata"
        :apidata="apidata"
        :templates="templates"
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
                                <div class="lu-widget__nav swiper-container swiper-container-horizontal swiper-container-false text-right p-10" data-content-slider="" style="visibility: visible;">
                                    <button  data-title="View All Available Tokens" class="lu-btn lu-btn--primary lu-tooltip drop-target drop-element-attached-bottom drop-element-attached-center drop-target-attached-top drop-target-attached-center" id="showtokens" @click="showtokens('showtokens',$event)"><span class="lu-btn__text">{$MGLANG->T("View All Available Tokens")}</span></button>

                                    <button  data-title="Customize" class="lu-btn lu-btn--primary lu-tooltip drop-target drop-element-attached-bottom drop-element-attached-center drop-target-attached-top drop-target-attached-center"><span class="lu-btn__text">{$MGLANG->T("Customize")}</span></button>
                                </div>
                            </div>
                            <div class="lu-widget__body p-20">
                                <div class="form-group" >
                                    <label class="lu-form-label" id="filenamelabel" v-text="'Contents of the httpd.conf file for {$rawObject->getDomainName() }'"> </label>
                                    <div class="input-group">
                                        <div id="filedata"></div>
                                    </div>
                                </div>

                            </div>
                        </div>
                        <div class="footeractionButtons">
                            <table class="lu-table lu-table--mob-collapsible dataTable no-footer dtr-column table-striped">
                                <thead><tr><th>Templates</th></tr></thead>
                                <tbody>
                                    <template  v-for="(template, key) in templatelist">
                                        <tr><td :id="'template'+key" @click="showtemplatedata('template'+key,$event,template)" v-text="template.name"></td></tr>
                                    </template>

                                </tbody>
                            </table>

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

