<div class="modal fade modal-lg" id="edit-set-modal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">{$MGLANG->T('close')}</span></button>
                <h4 class="modal-title">{$MGLANG->T('Configuration')}</h4>
            </div>
            <div class="modal-body">
                <form class="form-horizontal">
                    <input type="hidden" name="id" value="{$configuration->getId()}" />
                    <div class="alert alert-info">
                        <div>Application ID and Secret are the two required credentials that have to be provided to connect to the external application, in some cases they may be called Consumer Key/Secret or Client ID/Secret. The social media application will link your client area page with the provider. These credentials are needed in order for your clients to access the client area easily.
                        <br /><br />
                        Follow these steps to enable the authentication with this provider and to register a new application:
                        </div>
                        {include file="./info/"|cat:$configuration->getProvider()|cat:".tpl"}
                    </div>
                    <div class="form-group">
                        <label for="" class="col-sm-2 control-label">{$MGLANG->T('appId')}</label>
                        <div class="col-sm-10">
                            <input type="text" required="" class="form-control" name="appId" value="{$configuration->getAppId()}">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="" class="col-sm-2 control-label">{$MGLANG->T('appSecret')}</label>
                        <div class="col-sm-10">
                            <input type="text" required="" class="form-control" name="appSecret" value="{$configuration->getAppSecret()}">
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">{$MGLANG->T('close')}</button>
                <button type="button" class="btn btn-primary" data-act="saveConfiguration">{$MGLANG->T('save_changes')}</button>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->     