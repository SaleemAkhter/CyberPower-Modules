<form action="{$url}" method="post" id='daLogin'>
    <input type="hidden" name="username" value="{$username}" />
    <input type="hidden" name="password" value="{$password}"/>
    <input type="submit" hidden />
</form>

<script type='text/javascript'>
    element = document.getElementById('daLogin');
    element.submit();
</script>