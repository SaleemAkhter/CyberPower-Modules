<div class="row">
    <div class="col-md-6">
        <div class="alert alert-info">
            <div>{$MGLANG->T('InfoHead'|unescape:'html')}</div>
            <ol>
                <li>{$MGLANG->T('Info1')|unescape:'html'}</li>   
                <li>{$MGLANG->T('Info2')|unescape:'html'}</li> 
                <li>{$MGLANG->T('Info3')|unescape:'html'}</li>  
                <li>{$MGLANG->T('Info4')|unescape:'html'}</li>
                <li>{$MGLANG->T('Info5')|unescape:'html'}</li>
                <li>{$MGLANG->T('Info6')|unescape:'html'}</li>
            </ol>
          
    </div>
    </div>
    <div class="col-md-6">
        <div class="panel panel-primary">
            <div class="panel-heading">
                <h3 class="panel-title">{$MGLANG->T('ErrorLog')}</h3>
            </div>
            <div class="panel-body">
                <table class="table table-striped">
                    <tbody>
                        {foreach from=$logs item=log}
                        <tr>
                            <td>{$log->getDate()}</td>
                            <td>{$log->getMessage()}</td>
                        </tr>
                        {foreachelse}
                            <tr>
                                <td colspan="2" style="text-align:center;">{$MGLANG->T('table', 'empty')}</td>
                            </tr>
                        {/foreach}
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="col-md-12">
        {include file='timeChart/base.tpl'}
    </div>
    <div class="col-md-12">
        {include file='accountsChart/base.tpl'}
    </div>
</div>


