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
                    <div class="panel-body panel-groups">
                        <?php
                            echo $usergroups;
                        ?>
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
                        <input class="form-control tbNewGroupName" placeholder="Name">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary btnNewGroup">Create</button>
                </div>
            </div>
        </div>
    </div>
    <!-- /#modal -->

    <!--modal-->
    <div class="editmodals">
        <?php
            echo $editmodal;
        ?>
    </div>
    <!-- /#modal -->
    <div class="deletemodals">
        <?php
            echo $deltemodal;
        ?>
    </div>
    <!-- /#modal -->