<script type="text/x-template" id="t-mg-edit-sshkey-{$elementId|strtolower}"
        :component_id="component_id"
        :component_namespace="component_namespace"
        :component_index="component_index"
        >
    <div class=" lu-col-md-12 " id="mg-edit-sshkey">
    <div class="box light">
        <div class="box-title">
            <div class="caption">
                <span class="caption-subject bold font-red-thunderbird uppercase">{$MGLANG->T("editSshKeyForm")}</span>
            </div>
            <div class="rc-actions lu-pull-right" style="display: inline-flex;"></div>
        </div>
        <div class="box-body">
            <div class="lu-row">
                <div class="lu-col-md-6 p-20">
                    <form  action="" :id="'editSshKeyForm'" method="POST" :index="component_index" mgformtype="letsEncrypt" :namespace="component_namespace">
                        <input type="hidden" name="selectedFields" v-model="data.optionsactivefields"  />
                        <input type="hidden" name="selectedGlobalUsers" v-model="data.selectedusers"  />
                        <div id="basicSection" class="lu-col-md-12 ">

                            <div class="form-group">
                                <label class="lu-form-label">{$MGLANG->T("comment")}</label>
                                <div class="input-group">
                                   <input type="text" placeholder="" name="comment" :value="data.comment" class="lu-form-control">
                               </div>
                           </div>
                           <div class="form-group">
                                <label class="lu-form-label">{$MGLANG->T("fingerprint")}</label>
                                <div class="input-group">
                                   <input type="text" placeholder="" name="fingerprint" disabled :value="data.fingerprint" class="lu-form-control">
                               </div>
                           </div>
                           <div class="form-group">
                                <label class="lu-form-label">{$MGLANG->T("type")}</label>
                                <div class="input-group">
                                   <input type="text" placeholder="" name="type" disabled :value="data.type" class="lu-form-control">
                               </div>
                           </div>
                           <div class="form-group">
                                <label class="lu-form-label">{$MGLANG->T("size")}</label>
                                <div class="input-group">
                                   <input type="text" placeholder="" name="size" disabled :value="data.size" class="lu-form-control">
                               </div>
                           </div>
                           <div class="form-group">
                                <h6 class="pt-20"><strong>{$MGLANG->T("keyoptions")}</strong></h6>
                           </div>
                           {* <div class="optionsfields">
                                <template v-for="(field, key) in data.optionsactivefields" >{literal}{{ field }}{/literal}</template>
                           </div> *}
                           <div class="form-group" v-show="hasField('environment')">
                                <label class="lu-form-label">{$MGLANG->T("environment")}</label>
                                <div class="input-group">
                                   <input type="text" placeholder="" name="environment" v-model="data.environment" class="lu-form-control">
                               </div>
                           </div>
                           <div class="form-group" v-show="hasField('from')">
                                <label class="lu-form-label">{$MGLANG->T("from")}</label>
                                <div class="input-group">
                                   <input type="text" placeholder="" name="from" v-model="data.from" class="lu-form-control">
                               </div>
                           </div>
                           <div class="checkbox" v-show="hasField('nox11forwarding')">
                              <label style="display:inherit; padding:0px;">
                                <input type="checkbox" id="nox11forwarding" name="nox11forwarding"  v-model="data.nox11forwarding" >
                                <span style="font-weight:bold;">{$MGLANG->T("nox11forwarding")}</span>
                            </label>
                        </div>

                        <div class="checkbox" v-show="hasField('noagentforwarding')">
                              <label style="display:inherit; padding:0px;">
                                <input type="checkbox" id="noagentforwarding" name="noagentforwarding" v-model="data.noagentforwarding">
                                <span style="font-weight:bold;">{$MGLANG->T("noagentforwarding")}</span>
                            </label>
                        </div>

                        <div class="checkbox" v-show="hasField('noportforwarding')">
                              <label style="display:inherit; padding:0px;">
                                <input type="checkbox" id="noportforwarding" name="noportforwarding" v-model="data.noportforwarding">
                                <span style="font-weight:bold;">{$MGLANG->T("noportforwarding")}</span>
                            </label>
                        </div>

                        <div class="checkbox" v-show="hasField('nopty')">
                              <label style="display:inherit; padding:0px;">
                                <input type="checkbox" id="nopty" name="nopty" v-model="data.nopty">
                                <span style="font-weight:bold;">{$MGLANG->T("nopty")}</span>
                            </label>
                        </div>

                           <div class="form-group" v-show="hasField('permitopen')">
                                <label class="lu-form-label">{$MGLANG->T("permitopen")}</label>
                                <div class="input-group">
                                   <input type="text" placeholder="" name="permitopen" v-model="data.permitopen" class="lu-form-control">
                               </div>
                           </div>
                           <div class="form-group" v-show="hasField('tunnel')">
                                <label class="lu-form-label">{$MGLANG->T("tunnel")}</label>
                                <div class="input-group">
                                   <input type="text" placeholder="" name="tunnel" v-model="data.tunnel" class="lu-form-control">
                               </div>
                           </div>
                           <div class="lu-form-group">
                                <label class="lu-form-label">{$MGLANG->T("addoption")}</label>
                                <select name="size" bi-event-change="initReloadModal" id="optionsfields" class="lu-form-control selectized" tabindex="-1" style="display: none;">
                                    <option value="">Select</option>
                                    <option v-for="(option, key) in data.options"  :value="key">{literal}{{ option }}{/literal}</option>
                                </select>
                            </div>

                    </div>
                    <div class="checkbox">
                          <label style="display:inherit; padding:0px;">
                            <input type="checkbox" value="on" id="globalkey" name="globalkey" v-model="data.globalkey">
                            <span style="font-weight:bold;">{$MGLANG->T("globalkey")}</span>
                        </label>
                    </div>

                    <div class="form-group">
                        <h6 class="pt-20"><strong>{$MGLANG->T("globalkeyoptions")}</strong></h6>
                   </div>
                   <div class="form-group" v-show="data.globalkey=='on'">
                        <label class="lu-form-label">{$MGLANG->T("applyto")}</label>
                        <label class="radio-inline">
                          <input type="radio" name="applyto" v-model="data.applyto" id="all" value="all"> {$MGLANG->T("allusers")}
                        </label>
                        <label class="radio-inline">
                          <input type="radio" name="applyto" v-model="data.applyto" id="except" value="exceptselected"> {$MGLANG->T("exceptselectedusers")}
                        </label>
                        <label class="radio-inline">
                          <input type="radio" name="applyto" v-model="data.applyto" id="selectedusers" value="selected"> {$MGLANG->T("selectedusers")}
                        </label>
                   </div>

                   <div class="userstable" v-show="data.applyto=='except' || data.applyto=='selected'">
                            <div class="row">
                                <div class="">
                                    <table class="lu-table lu-table--mob-collapsible dataTable no-footer dtr-column">
                                        <thead>
                                            <tr>
                                                <th>
                                                    <div class="lu-rail">
                                                        <div class="lu-form-check">
                                                            <label>
                                                                <input type="checkbox" data-check-all=""  @click="checkallusers($event)" class="lu-form-checkbox">
                                                                <span class="lu-form-indicator"></span>
                                                            </label>
                                                        </div>
                                                        <span class="lu-table__text" >{$MGLANG->T('table', "Select All")}</span>
                                                    </div>
                                                </th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <template v-for="(user, key) in data.users"  :value="key">
                                                <tr role="row" >
                                                        <td>
                                                            <div class="lu-form-check">
                                                            <label>
                                                                <input type="checkbox"  class="lu-form-checkbox table-mass-action-check"  v-model="data.selectedusers" name="users[]"  :id="user" :value="user">
                                                                <span class="lu-form-indicator"></span>
                                                                <span class="checklabel"> {literal}{{ user }}{/literal}</span>
                                                            </label>

                                                        </div>
                                                    </td>
                                                </tr>
                                            </template>
                                        </tbody>
                                    </table>

                                </div>
                            </div>
                   </div>

                    <div class="lu-col-md-12 ui-form-submit">
                        <a href="javascript:;" @click="updateSshKey('editSshKeyForm',$event)" class="lu-btn lu-btn--success mg-submit-form">{$MGLANG->T("submit")}</a>
                    </div>
                </form>
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

