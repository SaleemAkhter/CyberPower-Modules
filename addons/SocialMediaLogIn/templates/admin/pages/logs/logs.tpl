{**********************************************************************
 * DiscountCenter product developed. (2015-11-16)
 * *
 *
 *  CREATED BY MODULESGARDEN       ->       http://modulesgarden.com
 *  CONTACT                        ->       contact@modulesgarden.com
 *
 *
 * This software is furnished under a license and may be used and copied
 * only  in  accordance  with  the  terms  of such  license and with the
 * inclusion of the above copyright notice.  This software  or any other
 * copies thereof may not be provided or otherwise made available to any
 * other person.  No title to and  ownership of the  software is  hereby
 * transferred.
 *
 *
 **********************************************************************}

{**
* @author Paweł Kopeć <pawelk@modulesgarden.com>
*}
<div class="panel panel-primary">
    <div class="panel-heading">
            <h3 class="panel-title">{$MGLANG->T('header','Logs')}</h3>
    </div> 
    <div class="box light">
        <div class="row">
    <div class="col-lg-12" id="mg-logs-content" >
        <table class="table table-hover" id="mg-data-list" >
            <thead>
                <tr>
                    <th>{$MGLANG->T('tableHeader','ID')}</th>
                    <th style="width:80%">{$MGLANG->T('tableHeader','Message')}</th>
                    <th>{$MGLANG->T('tableHeader','Date')}</th>
                    <th style="width: 40px; text-align: center;">{$MGLANG->T('tableHeader','Action')}</th>
                </tr>
            </thead>
            <tbody>
            </tbody> 
        </table>

        <div class="well well-sm" style="margin-top:10px;">
            <button class="btn btn-danger btn-inverse" type="button"  data-toggle="modal" data-target="#mg-modal-delete-all-entries">               
                    <i class="glyphicon glyphicon-remove"></i>
    {$MGLANG->T('button','Delete All Entries')}
            </button>
        </div>

    {*Modal Delete All Entries*}
                <div class="modal fade bs-example-modal-lg" id="mg-modal-delete-all-entries" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                                <h4 class="modal-title">{$MGLANG->T('modal','Delete All Entries')} <strong></strong></h4>
                            </div>
                            <div class="modal-loader" style="display:none;"></div>

                            <div class="modal-body">
                                <div class="modal-alerts">
                                    <div style="display:none;" data-prototype="error">
                                        <div class="note note-danger">
                                            <button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">&times;</span><span class="sr-only"></span></button>
                                            <strong></strong>
                                            <a style="display:none;" class="errorID" href=""></a>
                                        </div>
                                    </div>
                                    <div style="display:none;" data-prototype="success">
                                        <div class="note note-success">
                                            <button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">&times;</span><span class="sr-only"></span></button>
                                            <strong></strong>
                                        </div>
                                    </div>
                                </div>
                                <div style="margin: 30px; text-align: center;">

                                    <div>{$MGLANG->T('Are you sure you want to delete all entries from logs list?')} </div>
                                </div>
                            </div>


                            <div class="modal-footer">
                                <button type="button" class="btn btn-danger" data-modal-action="deleteAllLogs" id="pm-modal-addip-button-add">{$MGLANG->T('modal','delete')}</button>
                                <button type="button" class="btn btn-default" data-dismiss="modal">{$MGLANG->T('modal','close')}</button>
                            </div>
                        </div>
                    </div>
                </div>

    {*Modal delete-log*}
                <div class="modal fade bs-example-modal-lg" id="mg-modal-delete-log" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                                <h4 class="modal-title">{$MGLANG->T('modal','Delete Log')} <strong></strong></h4>
                            </div>
                            <div class="modal-loader" style="display:none;"></div>

                            <div class="modal-body">
                                <input type="hidden" name="id" data-target="id" value="">
                                <div class="modal-alerts">
                                    <div style="display:none;" data-prototype="error">
                                        <div class="note note-danger">
                                            <button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">&times;</span><span class="sr-only"></span></button>
                                            <strong></strong>
                                            <a style="display:none;" class="errorID" href=""></a>
                                        </div>
                                    </div>
                                    <div style="display:none;" data-prototype="success">
                                        <div class="note note-success">
                                            <button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">&times;</span><span class="sr-only"></span></button>
                                            <strong></strong>
                                        </div>
                                    </div>
                                </div>
                                <div style="margin: 30px; text-align: center;">

                                    <div>{$MGLANG->T('Are you sure you want to delete this entry from logs list?')} </div>
                                </div>
                            </div>


                            <div class="modal-footer">
                                <button type="button" class="btn btn-danger" data-modal-action="delete" id="pm-modal-addip-button-add">{$MGLANG->T('modal','delete')}</button>
                                <button type="button" class="btn btn-default" data-dismiss="modal">{$MGLANG->T('modal','close')}</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
{literal}
    <script type="text/javascript">
        var mgDataTable;

        jQuery(document).ready(function () {
            mgDataTable = $('#mg-data-list').dataTable({
                processing: false,
                searching: true,
                autoWidth: false,
                "serverSide": false,
                "order": [[0, "desc"]],
                ajax: function (data, callback, settings) {
                    var filter = {
                        //    serverID: $('#pm-filters-server').val(),
                    };
                    JSONParser.request(
                            'list'
                            , {
                                filter: filter
                                , limit: data.length
                                , offset: data.start
                                , order: data.order
                                , search: data.search
                            }
                    , function (data) {
                        callback(data);
                    }
                    );
                },
                'columns': [
                            , null
                            , null
                            , {orderable: false}

                ],
                'aoColumnDefs': [{
                        'bSortable': false,
                        'aTargets': ['nosort']
                    }],
                language: {
                    "zeroRecords": "{/literal}{$MGLANG->T('Nothing to display')}{literal}",
                    "infoEmpty": "",
                    "search": "{/literal}{$MGLANG->T('search')}{literal}",
                    "paginate": {
                        "previous": "{/literal}{$MGLANG->T('previous')}{literal}"
                        , "next": "{/literal}{$MGLANG->T('next')}{literal}"
                    }
                }
            });
            
            jQuery('#mg-logs-content').MGModalActions();
            
            
            $('#mg-modal-delete-all-entries, #mg-modal-delete-log').on('hidden.bs.modal', function () {
                var api = mgDataTable.api();
                  api.ajax.reload(function() {
                  }, false);
            });
            
        });
    </script>
{/literal}
