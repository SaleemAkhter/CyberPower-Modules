<script type="text/x-template" id="t-mg-backupEdit-{$elementId|strtolower}"
        :component_id="component_id"
        :component_namespace="component_namespace"
        :component_index="component_index"
        >
    <div class=" lu-col-md-12 " id="mg-backupEdit">
    <div class="box light">

        <div class="box-body">
            <div class="lu-row">
                <div class=" lu-col-md-12 ">
                 <form  action="" :id="'backupEdit'" method="POST" :index="component_index" mgformtype="update" :namespace="component_namespace">
                    <input type="hidden" name="selectedUsers" v-model="children"  />
                <div class="lu-widget">
                    <div class="lu-widget__header">

                        {if $rawObject->isRawTitle() || $rawObject->getTitle()}
                            <div class="lu-widget__top lu-top">
                                <div class="lu-top__toolbar">
                            <a data-toggle="lu-tooltip" class="lu-btn lu-btn--primary lu-tooltip drop-target drop-element-attached-bottom drop-element-attached-center drop-target-attached-top drop-target-attached-center" @click="loadModal($event, 'cronScheduleButton', 'ModulesGarden_Servers_DirectAdminExtended_App_UI_Client_Admin_SystemBackup_Buttons_CronSchedule','cronScheduleButton', null, true)" data-title="{$MGLANG->T("cronschedule")}"><span class="lu-btn__text">{$MGLANG->T("cronschedule")}</span></a>
                            <a data-toggle="lu-tooltip" class="lu-btn lu-btn--primary lu-tooltip drop-target drop-element-attached-bottom drop-element-attached-center drop-target-attached-top drop-target-attached-center" @click="ajaxAction($event, '{$rawObject->getId()}', 'ModulesGarden_Servers_DirectAdminExtended_App_UI_Client_Admin_SystemBackup_Buttons_RunBackup','{$rawObject->getIndex()}', null, true)" data-index="{$rawObject->getIndex()}" data-title="{$MGLANG->T("runsysbackupnow")}"><span class="lu-btn__text">{$MGLANG->T("runsysbackupnow")}</span></a>

                        </div>
                            </div>
                        {/if}
                        <div class="lu-widget__nav swiper-container swiper-container-horizontal swiper-container-false" data-content-slider="" style="visibility: visible;">
                            <ul class="swiper-wrapper lu-nav lu-nav--md lu-nav--h lu-nav--tabs lu-nav--arrow">
                                <li  class="lu-nav__item swiper-slide is-active" @click="setStep(1)">
                                    <a class="lu-nav__link"  data-toggle="lu-tab" href="#contTabstep1">
                                        <span class="lu-nav__link-text">
                                            {$MGLANG->T("step1")}
                                            <span class="small help-text">{$MGLANG->T("step1description")}</span>
                                        </span>
                                    </a>
                                </li>
                                <li class="lu-nav__item swiper-slide" @click="setStep(2)">
                                    <a class="lu-nav__link"  data-toggle="lu-tab" href="#contTabstep2">

                                        <span class="lu-nav__link-text">{$MGLANG->T("step2")}
                                        <span class="small help-text">{$MGLANG->T("step2description")}</span>
                                    </span>
                                    </a>
                                </li>
                                <li class="lu-nav__item swiper-slide" >
                                    <a class="lu-nav__link"  href="{$rawObject->getDirectoryUrl()}">
                                        <span class="lu-nav__link-text">{$MGLANG->T("step3")}
                                        <span class="small help-text">{$MGLANG->T("step3description")}</span>
                                    </span>
                                    </a>
                                </li>
                                <li class="lu-nav__item swiper-slide">
                                    <a class="lu-nav__link"  href="{$rawObject->getFileUrl()}">
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
                                <div id="contTabstep1"  class="lu-tab-pane " :class="{ 'is-active': step==1 }">
                                    <div class="form-group" >
                                        <label class="lu-form-label">{$MGLANG->T("backupPath")} </label>
                                        <div class="input-group">
                                           <input type="text" placeholder="" name="BACKUP_PATH" v-model="data.BACKUP_PATH" class="lu-form-control">
                                        </div>
                                    </div>
                                    <div class="form-group" >
                                        <label class="lu-form-label">{$MGLANG->T("minDisk")} </label>
                                        <div class="input-group">
                                           <input type="text" placeholder="" name="MIN_DISK" v-model="data.MIN_DISK" class="lu-form-control">
                                        </div>
                                    </div>

                                    <div class="lu-form-group">
                                        <label class="lu-form-label">{$MGLANG->T("mountPoint")}</label>
                                        <select name="SELECTED_MOUNT_POINT" v-model="data.SELECTED_MOUNT_POINT" id="SELECTED_MOUNT_POINT" class="lu-form-control selectized" tabindex="-1" style="display: none;">
                                            <option v-for="(option, key) in data.MOUNT_POINT"  :value="option.value">{literal}{{ option.text }}{/literal}</option>
                                        </select>
                                    </div>
                                    <div class="dataTables_wrapper no-footer">
                                    <div>
                                        <table class="lu-table lu-table--mob-collapsible dataTable no-footer dtr-column table-striped" width="100%" role="grid">
                                            <thead>
                                                <tr role='row'>
                                                    <th class="" name="">
                                                        <div class="lu-rail">
                                                            <span class="lu-table__text" >{$MGLANG->T('backup')}</span>
                                                        </div>
                                                    </th>
                                                </tr>
                                            </thead>
                                            <tbody>

                                                    <tr>
                                                        <td>
                                                            <div class="lu-rail">
                                                                <div class="lu-form-check">
                                                                    <label>
                                                                        <input type="checkbox" name="HTTP_BK" v-model="data.HTTP_BK"  class="lu-form-checkbox  domain-check table-mass-action-check"  >
                                                                        <span class="lu-form-indicator">
                                                                            </span>
                                                                    </label>
                                                                </div>
                                                                <span >{$MGLANG->T('httpddata')}</span>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>
                                                            <div class="lu-rail">
                                                                <div class="lu-form-check">
                                                                    <label>
                                                                        <input type="checkbox" name="BIND_BK" v-model="data.BIND_BK"  class="lu-form-checkbox  domain-check table-mass-action-check"  >
                                                                        <span class="lu-form-indicator">
                                                                            </span>
                                                                    </label>
                                                                </div>
                                                                <span >{$MGLANG->T('dnsdata')}</span>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>
                                                            <div class="lu-rail">
                                                                <div class="lu-form-check">
                                                                    <label>
                                                                        <input type="checkbox" name="MYSQL_BK" v-model="data.MYSQL_BK"  class="lu-form-checkbox  domain-check table-mass-action-check"  >
                                                                        <span class="lu-form-indicator">
                                                                            </span>
                                                                    </label>
                                                                </div>
                                                                <span >{$MGLANG->T('mysqldata')}</span>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>
                                                            <div class="lu-rail">
                                                                <div class="lu-form-check">
                                                                    <label>
                                                                        <input type="checkbox" name="CUSTOM_BK" v-model="data.CUSTOM_BK"  class="lu-form-checkbox  domain-check table-mass-action-check"  >
                                                                        <span class="lu-form-indicator">
                                                                            </span>
                                                                    </label>
                                                                </div>
                                                                <span >{$MGLANG->T('customdir')}</span>
                                                            </div>
                                                        </td>
                                                    </tr>
                                            </tbody>
                                        </table>
                                        <div class="lu-preloader-container lu-preloader-container--full-screen lu-preloader-container--overlay" v-show="loading">
                                            <div class="lu-preloader lu-preloader--sm"></div>
                                        </div>
                                    </div>
                                </div>

                                   <div class="checkbox pl-20">
                                          <label style="display:inherit; padding:0px;">
                                            <input type="checkbox" value="on" id="ADD_USERS_TO_LIST" name="ADD_USERS_TO_LIST" v-model="data.ADD_USERS_TO_LIST">
                                            <span style="">{$MGLANG->T("addUserHomeDir")}</span>
                                        </label>
                                    </div>
                                </div>
                                <div id="contTabstep2" :class="{ 'is-active': step==2 }" class="lu-tab-pane">
                                    <div class="checkbox ">
                                          <label style="display:inherit; padding:0px;">

                                            <input type="checkbox" value="on" id="USE_RTRANS" name="USE_RTRANS" v-model="data.USE_RTRANS">
                                            <span style="">{$MGLANG->T("useRemoteTransfer")}</span>

                                        </label>
                                    </div>
                                    <div class="lu-form-group">
                                        <label class="lu-form-label">{$MGLANG->T("remoteTransferMethod")}</label>
                                        <select name="RTRANS_METHOD" v-model="data.RTRANS_METHOD" id="RTRANS_METHOD" class="lu-form-control selectized" tabindex="-1" style="display: none;">
                                            <option v-for="(option, key) in data.RTRANS_SELECT"  :value="option.value">{literal}{{ option.text }}{/literal}</option>
                                        </select>
                                    </div>
                                    <div class="checkbox ">
                                          <label style="display:inherit; padding:0px;">

                                            <input type="checkbox" value="on" id="DEL_AFTERTRANS" name="DEL_AFTERTRANS" v-model="data.DEL_AFTERTRANS">
                                            <span style="">{$MGLANG->T("deleteLocalBackupAfterTransfer")}</span>

                                        </label>
                                    </div>
                                    <div class="checkbox ">
                                          <label style="display:inherit; padding:0px;">

                                            <input type="checkbox" value="on" id="FBF_RTRANS" name="FBF_RTRANS" v-model="data.FBF_RTRANS">
                                            <span style="">{$MGLANG->T("incrementalBackup")}</span>
                                        </label>
                                    </div>
                                    <div class="form-group" >
                                        <label class="lu-form-label">{$MGLANG->T("remoteHost")} </label>
                                        <div class="input-group">
                                           <input type="text" placeholder="" name="FTP_HOST" v-model="data.FTP_HOST" class="lu-form-control">
                                        </div>
                                    </div>
                                    <div class="form-group" >
                                        <label class="lu-form-label">{$MGLANG->T("remoteUser")} </label>
                                        <div class="input-group">
                                           <input type="text" placeholder="" name="FTP_USER" v-model="data.FTP_USER" class="lu-form-control">
                                        </div>
                                    </div>

                                    <div class="form-group" >
                                        <label class="lu-form-label">{$MGLANG->T("remotePassword")} </label>
                                        <div class="input-group">
                                           <input type="password" placeholder="" name="FTP_PASS" v-model="data.FTP_PASS" class="lu-form-control">
                                        </div>
                                    </div>

                                    <div class="form-group" >
                                        <label class="lu-form-label">{$MGLANG->T("remotePath")} </label>
                                        <div class="input-group">
                                           <input type="text" placeholder="" name="FTP_RPATH" v-model="data.FTP_RPATH" class="lu-form-control">
                                        </div>
                                    </div>


                                </div>
                                <div id="contTabstep3" :class="{ 'is-active': step==3 }" class="lu-tab-pane">

                                </div>
                                <div id="contTabstep4" :class="{ 'is-active': step==4 }" class="lu-tab-pane">
                                    <div class="form-group">
                                        <label class="radio-inline">
                                          <input type="radio" name="what" v-model="data.what" id="all" value="all"> {$MGLANG->T("alldata")}
                                        </label>
                                        <label class="radio-inline">
                                          <input type="radio" name="what" v-model="data.what" id="select" value="select"> {$MGLANG->T("selecteddata")}
                                        </label>
                                   </div>


                        </div>

                        </div>
                    </div>
                </div>
                <div class="footeractionButtons">

                    <div class="right">
                        <div class="ui-form-submit" >
                            <a href="javascript:;" @click="scheduleBackup('backupEdit',$event)" class="lu-btn lu-btn--info mg-submit-form">{$MGLANG->T("save")}</a>
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

