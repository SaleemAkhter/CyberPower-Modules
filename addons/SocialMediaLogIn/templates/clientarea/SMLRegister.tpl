<div style="height:200px">
<div class="text-center">
    <p>{$MGLANG->T('If you have an account already you can link it with your')}{if $provider} {$provider} {/if} {$MGLANG->T('account')}. {$MGLANG->T('If not You can create new one.')}</p>
</div>
<div class="row">
<div class="col-sm-6 text-center">
    <a href="{$connectUrl}" class="btn btn-primary">{$MGLANG->T('Connect with existing account')}</a>
</div>
<div class="col-sm-6 text-center">
    <a href="register.php" class="btn btn-primary">{$MGLANG->T('Register a new account')}</a>
</div>
</div>
</div>