<script type="text/x-template" id="t-mg-backup-schedule-{$elementId|strtolower}"
        :component_id="component_id"
        :component_namespace="component_namespace"
        :component_index="component_index"
        >
    <div class=" lu-col-md-12 " id="mg-backup-schedule">
    <div class="box light">

        <div class="box-body">
            <div class="lu-row">
                <div class=" lu-col-md-12 ">
                 <form  action="" :id="'backupschedule'" method="POST" :index="component_index" mgformtype="create" :namespace="component_namespace">
                    <input type="hidden" name="selectedUsers" v-model="children"  />
                <div class="lu-widget">
                    <div class="lu-widget__header">
                        {if $rawObject->isRawTitle() || $rawObject->getTitle()}
                            <div class="lu-widget__top lu-top">
                                <div class="lu-top__title">
                                    {if $rawObject->getIcon()}<i class="{$rawObject->getIcon()}"></i>{/if}
                                    {if $rawObject->isRawTitle()}{$rawObject->getRawTitle()}{elseif $rawObject->getTitle()}{$MGLANG->T($rawObject->getTitle())}{/if}
                                </div>
                            </div>
                        {/if}
                        <div class="lu-widget__nav swiper-container swiper-container-horizontal swiper-container-false" data-content-slider="" style="visibility: visible;">
                            <ul class="swiper-wrapper lu-nav lu-nav--md lu-nav--h lu-nav--tabs lu-nav--arrow">
                                <li  class="lu-nav__item swiper-slide" v-bind:class="{ 'is-active': (step == 1) }" @click="setStep(1)">
                                    <a class="lu-nav__link"  data-toggle="lu-tab" href="#contTabstep1">
                                        <div class="input-checkbox ui-steps-header-step-label">
                                            <div class="input-checkbox-control"><span class="icon" v-show="!isStepOneInvalid()"><svg version="1.1" viewBox="0 0 26 26" class="svg-icon svg-fill" style="width: 16px; height: 16px;"><path fill="#60c081" stroke="none" pid="0" d="M.3 14c-.2-.2-.3-.5-.3-.7s.1-.5.3-.7l1.4-1.4c.4-.4 1-.4 1.4 0l.1.1 5.5 5.9c.2.2.5.2.7 0L22.8 3.3h.1c.4-.4 1-.4 1.4 0l1.4 1.4c.4.4.4 1 0 1.4l-16 16.6c-.2.2-.4.3-.7.3-.3 0-.5-.1-.7-.3L.5 14.3.3 14z"></path></svg></span></div>
                                             <label class="input-checkbox-label"></label>
                                         </div>
                                        <span class="lu-nav__link-text">
                                            {$MGLANG->T("step1")}
                                            <span class="small help-text">{$MGLANG->T("step1description")}</span>
                                        </span>
                                    </a>
                                </li>
                                <li class="lu-nav__item swiper-slide" v-bind:class="{ 'is-active': (step == 2) }" @click="setStep(2)">
                                    <a class="lu-nav__link"  data-toggle="lu-tab" href="#contTabstep2">
                                        <div class="input-checkbox ui-steps-header-step-label --checked"><div class="input-checkbox-control"><span class="icon" v-show="!isStepTwoInvalid()"><svg version="1.1" viewBox="0 0 26 26" class="svg-icon svg-fill" style="width: 16px; height: 16px;"><path fill="#60c081" stroke="none" pid="0" d="M.3 14c-.2-.2-.3-.5-.3-.7s.1-.5.3-.7l1.4-1.4c.4-.4 1-.4 1.4 0l.1.1 5.5 5.9c.2.2.5.2.7 0L22.8 3.3h.1c.4-.4 1-.4 1.4 0l1.4 1.4c.4.4.4 1 0 1.4l-16 16.6c-.2.2-.4.3-.7.3-.3 0-.5-.1-.7-.3L.5 14.3.3 14z"></path></svg></span></div> <label class="input-checkbox-label"></label>
                                    </div>
                                        <span class="lu-nav__link-text">{$MGLANG->T("step2")}
                                        <span class="small help-text">{$MGLANG->T("step2description")}</span>
                                    </span>
                                    </a>
                                </li>
                                <li class="lu-nav__item swiper-slide" v-bind:class="{ 'is-active': (step == 3) }" @click="setStep(3)">
                                    <a class="lu-nav__link"  data-toggle="lu-tab" href="#contTabstep3">
                                         <div class="input-checkbox ui-steps-header-step-label --checked"><div class="input-checkbox-control"><span class="icon" v-show="!isStepThreeInvalid()"><svg version="1.1" viewBox="0 0 26 26" class="svg-icon svg-fill" style="width: 16px; height: 16px;"><path fill="#60c081" stroke="none" pid="0" d="M.3 14c-.2-.2-.3-.5-.3-.7s.1-.5.3-.7l1.4-1.4c.4-.4 1-.4 1.4 0l.1.1 5.5 5.9c.2.2.5.2.7 0L22.8 3.3h.1c.4-.4 1-.4 1.4 0l1.4 1.4c.4.4.4 1 0 1.4l-16 16.6c-.2.2-.4.3-.7.3-.3 0-.5-.1-.7-.3L.5 14.3.3 14z"></path></svg></span></div> <label class="input-checkbox-label"></label>
                                    </div>
                                        <span class="lu-nav__link-text">{$MGLANG->T("step3")}
                                        <span class="small help-text">{$MGLANG->T("step3description")}</span>
                                    </span>
                                    </a>
                                </li>
                                <li   class="lu-nav__item swiper-slide" v-bind:class="{ 'is-active': (step == 4) }" @click="setStep(4)">
                                    <a class="lu-nav__link"  data-toggle="lu-tab" href="#contTabstep4">
                                         <div class="input-checkbox ui-steps-header-step-label --checked"><div class="input-checkbox-control"><span class="icon" v-show="!isStepFourInvalid()"><svg version="1.1" viewBox="0 0 26 26" class="svg-icon svg-fill" style="width: 16px; height: 16px;"><path fill="#60c081" stroke="none" pid="0" d="M.3 14c-.2-.2-.3-.5-.3-.7s.1-.5.3-.7l1.4-1.4c.4-.4 1-.4 1.4 0l.1.1 5.5 5.9c.2.2.5.2.7 0L22.8 3.3h.1c.4-.4 1-.4 1.4 0l1.4 1.4c.4.4.4 1 0 1.4l-16 16.6c-.2.2-.4.3-.7.3-.3 0-.5-.1-.7-.3L.5 14.3.3 14z"></path></svg></span></div> <label class="input-checkbox-label"></label>
                                    </div>
                                        <span class="lu-nav__link-text">{$MGLANG->T("step4")}
                                        <span class="small help-text">{$MGLANG->T("step4description")}</span>
                                    </span>
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <div class="lu-widget__body p-20">
                        <div class="lu-tab-content pt-20">
                                <div id="contTabstep1"  class="lu-tab-pane " v-bind:class="{ 'is-active': (step == 1) }">
                                    <div class="form-group" >
                                        <label class="radio-inline">
                                          <input type="radio" name="who" v-model="data.who" id="all" value="all"> {$MGLANG->T("allusers")}
                                        </label>
                                        <label class="radio-inline">
                                          <input type="radio" name="who" v-model="data.who" id="except" value="exceptselected"> {$MGLANG->T("exceptselectedusers")}
                                        </label>
                                        <label class="radio-inline">
                                          <input type="radio" name="who" v-model="data.who" id="selectedusers" value="selected"> {$MGLANG->T("selectedusers")}
                                        </label>
                                   </div>

                                   <div class="userstable" v-show="data.who=='exceptselected' || data.who=='selected'">
                                            <div class="lu-t-c  datatableLoader"  data-table-container data-check-container>

                                        <div class="lu-t-c__mass-actions lu-top">
                                            <div class="lu-top__title"><span class="lu-badge lu-badge--primary lu-value">0</span> {$MGLANG->absoluteT('datatableItemsSelected')}</div>
                                            <div class="lu-top__toolbar">
                                            </div>
                                        </div>

                                        <div class="dataTables_wrapper no-footer">
                                            <div>
                                                <table class="lu-table lu-table--mob-collapsible dataTable no-footer dtr-column table-striped" width="100%" role="grid">
                                                    <thead>
                                                        <tr role='row'>
                                                            <th class="" name="">
                                                                <div class="lu-rail">
                                                                    <div class="lu-form-check">
                                                                        <label>
                                                                            <input v-on:change="selectAllUsers()"  type="checkbox" data-check-all="" class="lu-form-checkbox check-all-users">
                                                                            <span class="lu-form-indicator"></span>
                                                                        </label>
                                                                    </div>
                                                                    <span class="lu-table__text" >{$MGLANG->T('table')}</span>
                                                                </div>
                                                            </th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                     <template v-for="(users, reseller,index) in data.data_list">
                                                        <tr  {literal}:actionid="reseller"{/literal} role="row">
                                                            <td>
                                                                <div class="lu-rail">
                                                                    <div class="lu-form-check" v-show="selectedreseller!=reseller">
                                                                        <label>
                                                                            <input type="checkbox" v-on:change="selectResellerUsers(reseller)" class="lu-form-checkbox reseller-check table-mass-action-check"  {literal}:value="reseller"{/literal}>
                                                                            <span class="lu-form-indicator">
                                                                                </span>
                                                                        </label>
                                                                    </div>
                                                                    <span v-html="'Reseller: '+reseller"></span>
                                                                </div>
                                                            </td>
                                                        </tr>
                                                        <tr  {literal}:actionid="reseller"{/literal} role="row" class="child-row">
                                                            <td>
                                                                <div class="lu-rail">
                                                                    <div class="lu-form-check" v-show="selectedreseller!=reseller">
                                                                        <label>
                                                                            <input type="checkbox" v-model="children" v-on:change="selectUser(reseller,reseller)" class="lu-form-checkbox  user-check table-mass-action-check"  :value="reseller" :parentReseller="reseller" >
                                                                            <span class="lu-form-indicator">
                                                                                </span>
                                                                        </label>
                                                                    </div>
                                                                    <span v-html="reseller"></span>
                                                                </div>
                                                            </td>
                                                        </tr>
                                                        <tr v-for="(user, index) in users" {literal}:actionid="user"{/literal} role="row" class="child-row">
                                                            <td>
                                                                <div class="lu-rail">
                                                                    <div class="lu-form-check" v-show="selectedreseller!=reseller">
                                                                        <label>
                                                                            <input type="checkbox" v-model="children" v-on:change="selectUser(user,reseller)" class="lu-form-checkbox  user-check table-mass-action-check" {literal}:value="user" :parentReseller="reseller" {/literal}>
                                                                            <span class="lu-form-indicator">
                                                                                </span>
                                                                        </label>
                                                                    </div>
                                                                    <span v-html="user"></span>
                                                                </div>
                                                            </td>
                                                        </tr>
                                                    </template>
                                                    </tbody>
                                                </table>
                                                <div class="lu-preloader-container lu-preloader-container--full-screen lu-preloader-container--overlay" v-show="loading">
                                                    <div class="lu-preloader lu-preloader--sm"></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                   </div>
                                   <div class="lu-rail pl-10">
                                       <div class="lu-form-check skipsuspended">
                                              <label >
                                                <input type="checkbox" value="on" id="skipsuspended" name="skipsuspended" v-model="data.skipsuspended" class="lu-form-checkbox table-mass-action-check">
                                                <span class="lu-form-indicator"></span>
                                            </label>
                                        </div>
                                        <span>{$MGLANG->T("skipsuspended")}</span>
                                    </div>
                                </div>
                                <div id="contTabstep2" class="lu-tab-pane" v-bind:class="{ 'is-active': (step == 2) }" >
                                    <div class="form-group" >
                                        <label class="radio-inline">
                                          <input type="radio" name="when" v-model="data.when" id="now" value="now"> {$MGLANG->T("now")}
                                        </label>
                                        <label class="radio-inline">
                                          <input type="radio" name="when" v-model="data.when" id="cron" value="cron"> {$MGLANG->T("cronschedule")}
                                        </label>
                                    </div>
                                    <div v-show="data.when=='cron'">
                                        <div class="form-group">
                                            <h6 class="pt-20"><strong>{$MGLANG->T("Cron Settings")} </strong></h6>
                                       </div>
                                       <div class="row" >
                                           <div class="col-lg-2 form-group" >
                                                <label class="lu-form-label">{$MGLANG->T("minute")} <i data-title="{$MGLANG->T('minuteDescription')}" data-toggle="lu-tooltip" class="lu-i-c-2x lu-zmdi lu-zmdi-help-outline lu-form-tooltip-helper "></i></label>
                                                <div class="input-group">
                                                   <input type="text" placeholder="" name="minute" v-model="data.minute" class="lu-form-control">
                                               </div>
                                           </div>
                                           <div class="col-lg-2 form-group" >
                                                <label class="lu-form-label">{$MGLANG->T("hour")} <i data-title="{$MGLANG->T('hourDescription')}" data-toggle="lu-tooltip" class="lu-i-c-2x lu-zmdi lu-zmdi-help-outline lu-form-tooltip-helper "></i></label>
                                                <div class="input-group">
                                                   <input type="text" placeholder="" name="hour" v-model="data.hour" class="lu-form-control">
                                               </div>
                                           </div>
                                           <div class="col-lg-2 form-group" >
                                                <label class="lu-form-label">{$MGLANG->T("dayofmonth")} <i data-title="{$MGLANG->T('dayofmonthDescription')}" data-toggle="lu-tooltip" class="lu-i-c-2x lu-zmdi lu-zmdi-help-outline lu-form-tooltip-helper "></i></label>
                                                <div class="input-group">
                                                   <input type="text" placeholder="" name="dayofmonth" v-model="data.dayofmonth" class="lu-form-control">
                                               </div>
                                           </div>
                                           <div class="col-lg-2 form-group" >
                                                <label class="lu-form-label">{$MGLANG->T("month")} <i data-title="{$MGLANG->T('monthDescription')}" data-toggle="lu-tooltip" class="lu-i-c-2x lu-zmdi lu-zmdi-help-outline lu-form-tooltip-helper "></i></label>
                                                <div class="input-group">
                                                   <input type="text" placeholder="" name="month" v-model="data.month" class="lu-form-control">
                                               </div>
                                           </div>
                                           <div class="col-lg-2 form-group" >
                                                <label class="lu-form-label">{$MGLANG->T("dayofweek")} <i data-title="{$MGLANG->T('dayofweekDescription')}" data-toggle="lu-tooltip" class="lu-i-c-2x lu-zmdi lu-zmdi-help-outline lu-form-tooltip-helper "></i></label>
                                                <div class="input-group">
                                                   <input type="text" placeholder="" name="dayofweek" v-model="data.dayofweek" class="lu-form-control">
                                               </div>
                                           </div>
                                        </div>
                                    </div>
                                </div>
                                <div id="contTabstep3"  class="lu-tab-pane" :class="{ 'is-active': (step == 3) }">
                                    <div class="form-group" >
                                        <label class="radio-inline">
                                          <input type="radio" name="where" v-model="data.where" id="now" value="local"> {$MGLANG->T("Local")}
                                        </label>
                                        <label class="radio-inline">
                                          <input type="radio" name="where" v-model="data.where" id="cron" value="ftp"> {$MGLANG->T("FTP")}
                                        </label>
                                    </div>

                                    <div class=" form-group" v-show="data.where=='local'">
                                        <label class="lu-form-label">{$MGLANG->T("localpath")}</label>
                                        <div class="input-group">
                                           <input type="text" placeholder="" name="localpath" v-model="data.localpath" class="lu-form-control">
                                       </div>
                                    </div>
                                    <div v-show="data.where=='ftp'">
                                        <div class="form-group">
                                            <h6 class="pt-20"><strong>{$MGLANG->T("FTP Settings")} </strong></h6>
                                       </div>

                                           <div class="form-group" >
                                                <label class="lu-form-label">{$MGLANG->T("ftp_ip")} </label>
                                                <div class="input-group">
                                                   <input type="text" placeholder="" name="ftp_ip" v-model="data.ftp_ip" class="lu-form-control">
                                               </div>
                                           </div>
                                           <div class="form-group" >
                                                <label class="lu-form-label">{$MGLANG->T("ftp_username")}</label>
                                                <div class="input-group">
                                                   <input type="text" placeholder="" name="ftp_username" v-model="data.ftp_username" class="lu-form-control">
                                               </div>
                                           </div>
                                           <div class="form-group" >
                                                <label class="lu-form-label">{$MGLANG->T("ftp_password")} </label>
                                                <div class="input-group">
                                                   <input type="text" placeholder="" name="ftp_password" v-model="data.ftp_password" class="lu-form-control">
                                               </div>
                                           </div>
                                           <div class="form-group" >
                                                <label class="lu-form-label">{$MGLANG->T("ftp_path")}</label>
                                                <div class="input-group">
                                                   <input type="text" placeholder="" name="ftp_path" v-model="data.ftp_path" class="lu-form-control">
                                               </div>
                                           </div>
                                           <div class="form-group" >
                                                <label class="lu-form-label">{$MGLANG->T("ftp_port")} </label>
                                                <div class="input-group">
                                                   <input type="text" placeholder="" name="ftp_port" v-model="data.ftp_port" class="lu-form-control">
                                               </div>
                                           </div>
                                           <div class="checkbox">
                                                  <label style="display:inherit; padding:0px;">

                                                    <input type="checkbox" value="on" id="ftp_secure" name="ftp_secure" v-model="data.ftp_secure">
                                                    <span style="">{$MGLANG->T("ftp_secure")}</span>
                                                </label>
                                            </div>
                                        </div>

                                    <div class="lu-form-group">
                                        <label class="lu-form-label">{$MGLANG->T("append")}</label>
                                        <select name="append_to_pathoption" v-model="data.append_to_pathoption" id="append_to_path" class="lu-form-control selectized" tabindex="-1" style="display: none;">
                                            <option v-for="(option, key) in data.append_to_path"  :value="option.value">{literal}{{ option.text }}{/literal}</option>
                                        </select>
                                    </div>
                                    <div class="form-group" v-show="data.append_to_pathoption=='custom'">
                                        <label class="lu-form-label">{$MGLANG->T("custom_path")}</label>
                                        <div class="input-group">
                                            <div class="input-group-addon">/</div>
                                           <input type="text" placeholder="" name="custom_append" v-model="data.custom_append" class="lu-form-control">
                                       </div>
                                    </div>
                                </div>
                                <div id="contTabstep4"  class="lu-tab-pane" v-bind:class="{ 'is-active': (step == 4) }">
                                    <div class="form-group">
                                        <label class="radio-inline">
                                          <input type="radio" name="what" v-model="data.what" id="all" value="all"> {$MGLANG->T("alldata")}
                                        </label>
                                        <label class="radio-inline">
                                          <input type="radio" name="what" v-model="data.what" id="select" value="select"> {$MGLANG->T("selecteddata")}
                                        </label>
                                   </div>

                                   <div class="dataoptionsTable" v-show="data.what=='select'">
                                        <div class="row">
                                            <div class="">
                                                <table class="lu-table lu-table--mob-collapsible dataTable no-footer dtr-column">
                                                    <thead>
                                                        <tr>
                                                            <th>
                                                                <div class="lu-rail">
                                                                    <div class="lu-form-check">
                                                                        <label>
                                                                            <input type="checkbox" data-check-all=""  @click="checkallusers($event)" class="checkalldataoptions lu-form-checkbox">
                                                                            <span class="lu-form-indicator"></span>
                                                                        </label>
                                                                    </div>
                                                                    <span class="lu-table__text" >{$MGLANG->T('table', "Select All")}</span>
                                                                </div>
                                                            </th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <template v-for="(option, key) in data.dataoptions"  >
                                                            <tr role="row" >
                                                                    <td>
                                                                        <div class="lu-form-check">
                                                                        <label>
                                                                            <input type="checkbox"  class="lu-form-checkbox table-mass-action-check dataopts"  v-model="data.selecteddataoptions" :name="key"  :id="option.value" :value="option.value">
                                                                            <span class="lu-form-indicator"></span>
                                                                            <span class="checklabel pl-20"> {literal}{{ option.text }}{/literal}</span>
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
                        </div>

                        </div>
                    </div>
                </div>
                <div class="footeractionButtons">
                    <div class='left'>
                        <div class=" ui-form-submit" v-show="isPreviousButtonVisible()">
                            <a href="javascript:;" @click="previousstep('editSshKeyForm',$event)" class="lu-btn lu-btn--default mg-submit-form">{$MGLANG->T("previous")}</a>
                        </div>
                    </div>
                    <div class="right">
                        <div class="ui-form-submit" >
                            <a href="javascript:;" :disabled="isScheduleButtonDisabled()" @click="scheduleBackup('backupschedule',$event)" class="lu-btn lu-btn--info mg-submit-form">{$MGLANG->T("schedule")}</a>
                        </div>
                        <div class="ui-form-submit ml-20" v-show="isNextButtonVisible()">
                            <a href="javascript:;" @click="nextstep('editSshKeyForm',$event)" class="lu-btn lu-btn--success mg-submit-form">{$MGLANG->T("next")}</a>
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
</div>
</script>

