<script type="text/javascript">
    $('body').append(`
        <form id="mg_daex_loginform" action="{$loginLink}" method="post" target="_blank">
        <input type="hidden" name="username" value="{$username}" />
        <input type="hidden" name="password" value="{$password}"/></form>
    `);
    $('#btnLoginLinkTrigger').parent().prepend('<a href="#" onclick="$(\'#mg_daex_loginform\').submit();" class="btn btn-primary">{$MGLANG->absoluteT('loginToPanel')} </a>');
    $('#btnLoginLinkTrigger').remove();

</script>




