<script type="text/x-template" id="t-mg-wp-details-{$elementId|strtolower}"
        :component_id="component_id"
        :component_namespace="component_namespace"
        :component_index="component_index"
        >


    <div class=" lu-col-md-12 " id="mg-wp-details">
    <div class="box light">
        <div class="box-title">
            <div class="caption">
                <span class="caption-subject bold font-red-thunderbird uppercase">{$MGLANG->T("letsEncryptForm")}</span>
            </div>
            <div class="rc-actions lu-pull-right" style="display: inline-flex;"></div>
        </div>
        <div class="box-body">
            <div class="lu-row">
                <div class="lu-col-md-12 p-20">
                    <form  action="" :id="'letsEncryptForm'" method="POST" :index="component_index" mgformtype="letsEncrypt" :namespace="component_namespace">
                        <div id="basicSection" class="lu-col-md-12 ">
                            {* <div class="lu-form-group">
                                <label class="lu-form-label">{$MGLANG->T("commonname")}</label>
                                <input type="text" placeholder="" name="commonname" value="" class="lu-form-control">
                                <div hidden="hidden" class="lu-form-feedback lu-form-feedback--icon"></div>
                            </div> *}
                            <div class="form-group">
                                <label class="lu-form-label">{$MGLANG->T("commonname")}</label>
                                <div class="input-group">
                                   <input type="text" placeholder="" name="commonname" value="{$rawObject->getDomainName()}" class="lu-form-control">
                                   <div class="input-group-addon"><div style="display:flex;" > <input type="checkbox" id="wildcard" aria-label="" :name="'wildcard'" @change="changeWildcard($event)" v-model="data.wildcard"><label for="wildcardcheckbox">Wildcard</label></div></div>
                               </div>
                           </div>

                           <div class="lu-form-group">
                            <label class="lu-form-label">{$MGLANG->T("keysize")}</label>
                            <select name="size" bi-event-change="initReloadModal" class="lu-form-control selectized" tabindex="-1" style="display: none;">
                                    <option value="2048">2048-bit</option>
                                    <option value="4096">4096-bit</option>
                                    <option value="prime256v1">EC-256</option>
                                    <option selected="" value="secp384r1">EC-384</option>
                                    <option value="secp521r1">EC-521</option>
                            </select>
                        </div>
                        <div class="lu-form-group">
                            <label class="lu-form-label">{$MGLANG->T("certificatetype")}</label>
                            <select name="encryption" bi-event-change="initReloadModal" class="lu-form-control selectized" tabindex="-1" style="display: none;">
                                <option value="sha256" selected="">SHA256</option>
                            </select>
                        </div>
                        <div class="lu-form-group" id="dnsprovider" v-show="data.wildcard">
                            <label class="lu-form-label">DNS Provider</label>
                            <div class="input-group">
                            <select name="provider" id="dnsproviders" v-model="data.dnsprovider" bi-event-change="initReloadModal" class="lu-form-control selectized" tabindex="-1" style="display: none;">
                                <option v-for="(option, key) in data.dnsprovidersoptions"  :value="key">{literal}{{ option.name }}{/literal}</option>
                            </select>
                            <div class="input-group-addon" v-show="showcustomize"><div style="display:flex; cursor:pointer;" @click="showDnsProviderFields">Customize</div></div>
                            </div>
                        </div>
                        <div class="checkbox">
                              <label style="display:inherit; padding:0px;">
                                <input type="checkbox" id="checkAll" name="checkall">
                                <span style="font-weight:bold;">Certificate Entries</span>
                            </label>
                        </div>
                        <div v-show="!data.wildcard" class="subdomainentries">
                            <div class="certificateentry checkbox webs-section" v-for="(checked, subdomain,index) in data.subdomains" >
                                <label style="display:inherit; padding:0px;">
                                <input type="checkbox" :name="'webs['+index+']'" :checked="checked=='checked'" :value="subdomain">{literal}{{subdomain}}{/literal}</label>
                            </div>
                        </div>
                        <div v-show="data.wildcard" class="wildcardsubdomainentries">
                            <div class="certificateentry checkbox webs-section" v-for="(checked, subdomain,index) in data.wildcard_subdomains" >
                                <label style="display:inherit; padding:0px;">
                                <input type="checkbox" :name="'wildcardentries['+index+']'" :checked="checked=='checked'" :value="subdomain">{literal}{{subdomain}}{/literal}</label>
                            </div>
                        </div>

                    </div>
                    <div class="row">
                        <div class="col-lg-4">

                            <div class="lu-form-check lu-m-b-2x">
                                <label>
                                    <div class="lu-switch">
                                        <input type="checkbox" name="forcessl" v-model="data.forcessl" id="forcessl" class="lu-switch__checkbox">
                                        <span class="lu-switch__container">
                                            <span class="lu-switch__handle"></span>
                                        </span>
                                    </div>
                                    <span class="lu-form-text">{$MGLANG->T("forcessl")}</span>
                                </label>
                            </div>
                        </div>
                    </div>

                    <div class="lu-col-md-12 ui-form-submit">
                        <a href="javascript:;" @click="createLECert('letsEncryptForm',$event)" class="lu-btn lu-btn--success mg-submit-form">{$MGLANG->T("submit")}</a>
                    </div>
                </form>
            </div>
            <div class="lu-preloader-container lu-preloader-container--full-screen lu-preloader-container--overlay" v-show="loading_state">
                        <div class="lu-preloader lu-preloader--sm"></div>
                    </div>
                    <div class="modal" id="dnsModal" tabindex="-1" role="dialog">
                      <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                <h4 class="modal-title">DNS PROVIDER CONFIGURATION</h4>
                            </div>
                            <div class="modal-body">
                                <form action=""  :id="'letsEncryptProviderForm'" method="POST" :index="component_index" mgformtype="letsEncryptProvider" :namespace="component_namespace">
                                    <input type="hidden" name="storeproviderdata" v-model="data.dnsprovider">
                                    <div v-for="(field, key) in providerfields">
                                        <div class="lu-form-group"   >
                                            <label class="lu-form-label">{literal}{{field}}{/literal}</label>
                                            <input type="text" placeholder="" :name="'providerfields['+key+']'" value="" class="lu-form-control">
                                            <div hidden="hidden" class="lu-form-feedback lu-form-feedback--icon"></div>
                                        </div>
                                    </div>
                                    <div class="providerextrafields" v-show="!showprovidersAdditionalfields" @click="showprovidersAdditionalfields=!showprovidersAdditionalfields">Show additional configuration</div>
                                    <div class="additionalfields" v-show="showprovidersAdditionalfields">
                                        <div v-for="(field, key) in providersAdditionalfields">
                                            <div class="lu-form-group"  >
                                                <label class="lu-form-label">{literal}{{field}}{/literal}</label>
                                                <input type="text" placeholder="" :name="'providerfields['+key+']'" value="" class="lu-form-control">
                                                <div hidden="hidden" class="lu-form-feedback lu-form-feedback--icon"></div>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                <button type="button" class="btn btn-primary" @click="saveprovider('letsEncryptProviderForm',$event)" >Save changes</button>
                            </div>
                        </div><!-- /.modal-content -->
                      </div><!-- /.modal-dialog -->
                    </div><!-- /.modal -->
        </div>
    </div>
</div>
</div>
</script>

