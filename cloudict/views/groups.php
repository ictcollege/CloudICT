<div id="page-wrapper">
            <div class="row row-padding-top">
                <div class="panel panel-default main-panel">
                    <div class="panel-heading">
                        <div class="pull-left">
                            <h4><i class="fa fa-users fa-fw"></i></h4>
                        </div>

                        <div class="pull-right">
                            <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#mNewGroup">New Group</button>  
                        </div>
                    </div>
                    <div class="panel-body">
                        <div class="col-lg-4">
                            <div class="panel panel-primary">
                                <div class="panel-heading">
                                    Group #1
                                </div>
                            <div class="panel-body">
                                <div class="group-admin">
                                    <button type="button" class="btn btn-outline btn-default btn-xs">Admin</button>
                                </div>
                                <hr/>
                                <ul class="list-inline">
                                    <li>User 1,</li>
                                    <li>User 2,</li>
                                    <li>User 3,</li>
                                    <li>User 4,</li>
                                    <li>User 5,</li>
                                    <li>User 1,</li>
                                    <li>User 2,</li>
                                    <li>User 3,</li>
                                    <li>User 4,</li>
                                    <li>User 5,</li>
                                    <li>User 1,</li>
                                    <li>User 2,</li>
                                    <li>User 3,</li>
                                    <li>User 4,</li>
                                    <li>User 5,</li>
                                </ul>
                            </div>
                            <div class="panel-footer">
                                <button type="button" class="btn btn-primary btn-xs" data-toggle="modal" data-target="#mEditGroup">Edit</button> 
                            </div>
                        </div>
                    </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade" id="mNewGroup" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="myModalLabel">New Group</h4>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label>Group Name</label>
                        <input class="form-control">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary btnNewGroup">Save</button>
                </div>
            </div>
        </div>
    </div>
    <!-- /#modal -->

    <!--modal-->
    <div class="modal fade" id="mEditGroup" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="myModalLabel">Edit Group</h4>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label>Group Name</label>
                        <input class="form-control">
                    </div>
                    <div class="form-group">
                        <label>Members</label>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary btnNewGroup">Save</button>
                </div>
            </div>
        </div>
    </div>
    <!-- /#modal -->