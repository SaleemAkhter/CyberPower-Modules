<script type="text/x-template" id="t-mg-firewall-edit-{$elementId|strtolower}"
        :component_id="component_id"
        :component_namespace="component_namespace"
        :component_index="component_index"
        >
<div class=" lu-col-md-12 " id="mg-firewall-edit">
    <div class="box light">
        <div class="box-title">
            <div class="caption">
                {* <span class="caption-subject bold font-red-thunderbird uppercase">{$MGLANG->T("configurefirewall")}</span> *}
            </div>
            <div class="rc-actions lu-pull-right" style="display: inline-flex;"></div>
        </div>
        <div class="box-body">
            <div class="bs-callout text-center" :class="data.statusClass">
                    <h4 v-text="data.status"></h4>
            </div>
            <form  action="" :id="'configurefirewall'" method="POST" :index="component_index" mgformtype="firewall" :namespace="component_namespace">
            <div class="lu-tiles lu-row lu-row--eq-height">
                <div class="lu-col-xs-6 lu-col-md-20p">
                    <a @click="firewallaction('configurefirewall',$event,'enable')" class="lu-tile lu-tile--btn"><div class="lu-i-c-6x"><img src="modules/servers/directadminExtended/app/UI/Client/Templates/assets/img/directadmin/icon-home.svg#AddNewUser" alt=""></div> <div class="lu-tile__title">Firewall Enable</div></a>
                </div>
                <div class="lu-col-xs-6 lu-col-md-20p">
                    <a @click="firewallaction('configurefirewall',$event,'disable')" class="lu-tile lu-tile--btn"><div class="lu-i-c-6x"><img src="modules/servers/directadminExtended/app/UI/Client/Templates/assets/img/directadmin/icon-home.svg#AddNewUser" alt=""></div> <div class="lu-tile__title">Firewall Disable</div></a>
                </div>
                <div class="lu-col-xs-6 lu-col-md-20p" @click="firewallaction('configurefirewall',$event,'restart')">
                    <a  class="lu-tile lu-tile--btn"><div class="lu-i-c-6x"><img src="modules/servers/directadminExtended/app/UI/Client/Templates/assets/img/directadmin/icon-home.svg#AddNewUser" alt=""></div> <div class="lu-tile__title">Firewall Restart</div></a>
                </div>
                <div class="lu-col-xs-6 lu-col-md-20p">
                    <a @click="firewallaction('configurefirewall',$event,'denyf')" class="lu-tile lu-tile--btn"><div class="lu-i-c-6x"><img src="modules/servers/directadminExtended/app/UI/Client/Templates/assets/img/directadmin/icon-home.svg#AddNewUser" alt=""></div> <div class="lu-tile__title">Flush all Blocks</div></a>
                </div>
            </div>
            <div class="lu-row">
                <div class="lu-col-md-12 pt-20">

                        <input type="hidden" name="action" v-model="data.action"  />
                        <div id="basicSection" class=" ">
                            <div class="form-group">
                                <label class="lu-form-label">{$MGLANG->T("ip")}</label>
                                <div class="input-group">
                                   <input type="text" placeholder="e.g 192.168.10.10" name="ip" :value="data.ip" class="lu-form-control">
                               </div>
                           </div>
                        </div>
                        <div class="lu-tiles lu-row lu-row--eq-height">
                            <div class="lu-col-md-2">
                                <a href="javascript:;" @click="firewallaction('configurefirewall',$event,'qallow')" class="lu-btn lu-btn--success mg-submit-form">{$MGLANG->T("allowip")}</a>

                            </div>
                            <div class="lu-col-md-2">
                                <a href="javascript:;" @click="firewallaction('configurefirewall',$event,'qdeny')" class="lu-btn lu-btn--success mg-submit-form">{$MGLANG->T("denyip")}</a>
                            </div>
                            <div class="lu-col-md-2">
                                <a href="javascript:;" @click="firewallaction('configurefirewall',$event,'qignore')" class="lu-btn lu-btn--success mg-submit-form">{$MGLANG->T("ignoreip")}</a>

                            </div>
                            <div class="lu-col-md-2">
                                <a href="javascript:;" @click="firewallaction('configurefirewall',$event,'kill')" class="lu-btn lu-btn--success mg-submit-form">{$MGLANG->T("unblockip")}</a>

                            </div>
                            <div class="lu-col-md-2">
                                <a href="javascript:;" @click="firewallaction('configurefirewall',$event,'grep')" class="lu-btn lu-btn--success mg-submit-form">{$MGLANG->T("searchip")}</a>
                            </div>
                        </div>

                </div>
            </div>
            <div class="lu-row">
                <div class="lu-col-md-12 pt-20" v-show="outputdata.length"><pre v-html="outputdata"></pre></div>
                {if ($isDebug eq true AND (count($MGLANG->getMissingLangs()) != 0))}
                    <div class="lu-row" style="max-width: 95%;">
                        {foreach from=$MGLANG->getMissingLangs() key=varible item=value}
                            <div class="lu-col-md-12"><b>{$varible}</b> = '{$value}';</div>
                        {/foreach}
                    </div>
                {/if}
            </div>
            </form>
        </div>
    </div>
</div>
</script>

