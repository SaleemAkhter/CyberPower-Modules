<table class="table table-striped">
    <thead>
        <tr>
            <th colspan="2" style="text-align:center;">{$MGLANG->T('credentials')}</th>
        </tr>
    </thead>
    <tr>
        <td>{$MGLANG->T('username')}</td>
        <td>{$username}</td>
    </tr>
    <tr>
        <td>{$MGLANG->T('password')}</td>
        <td>
            <span data-paste-password="*************">*************</span>
            <button class="btn btn-info btn-mini" type="button" name="showPassword">{$MGLANG->T('showPassword')}</button>
            <button class="btn btn-danger btn-mini" type="button" name="hidePassword" style="display:none;">{$MGLANG->T('hidePassword')}</button>
        </td>
    </tr>
    <tr>
        <td colspan="2" style="text-align:center;">
            <a href="{$loginLink}" target="_new" class="btn btn-success">{$MGLANG->T('openPanel')}</a>
        </td>
    </tr>
</table>
{literal}
    <script type="text/javascript">
        jQuery('.mg-module button[name=showPassword]').click(function(){
           JSONParser.request('getPassword',{},function(output){
              jQuery('.mg-module *[data-paste-password]').text(output.password);
              jQuery('.mg-module button[name=showPassword]').hide();
              jQuery('.mg-module button[name=hidePassword]').show();
           });
        });
        jQuery('.mg-module button[name=hidePassword]').click(function(){
            jQuery('.mg-module *[data-paste-password]').text(jQuery('.mg-module *[data-paste-password]').attr('data-paste-password'));
            jQuery('.mg-module button[name=hidePassword]').hide();
            jQuery('.mg-module button[name=showPassword]').show();
        });
    </script>
{/literal}
