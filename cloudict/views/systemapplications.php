<<<<<<< HEAD
<div id="page-wrapper">
            <div class="row row-padding-top">
                <div class="panel panel-default main-panel">
                    <div class="panel-heading">
                        <div class="pull-left">
                            <h4><i class="fa fa-puzzle-piece fa-fw"></i></h4>
                        </div>

                        <div class="pull-right">
                            <button type="button" class="btn btn-primary btnNewApplication" data-toggle="modal" data-target="#mNewApplication">New Application</button>  
                        </div>
                    </div>
                    <div class="panel-body panel-applications">
                        <?php
                            echo $applications;
                        ?>
                    </div>
                </div>
        </div>

        <div class="modal fade" id="mNewApplication" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="myModalLabel">New Application</h4>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label>Application Name</label>
                        <input class="form-control tbNewApplicationName" placeholder="Name">
                    </div>
                    <div class="form-group">
                        <label>Link</label>
                        <input class="form-control tbLink" placeholder="Link">
                    </div>
                    <div class="form-group">
                        <label>Color</label>
                        <div class="input-group demo2">
                            <input type="text" value="" class="form-control tbNewColor" />
                            <span class="input-group-addon"><i></i></span>
                        </div>
                    </div>
                    <div class="form-group">
                        <label>Icon</label>
                        <div class="input-group iconpicker-container">
                            <input data-placement="bottomRight" class="form-control icp icp-auto iconpicker-element iconpicker-input tbNewIcon" value="fa-archive" type="text">
                            <span class="input-group-addon"><i class="fa fa-archive"></i></span>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-danger btnCancel pull-left" type="button" data-dismiss="modal">Cancel</button>
                    <button class="btn btn-primary btnCreateApplication" type="button">Create</button>
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
            //echo $deltemodal;
        ?>
    </div>
=======
<div id="page-wrapper">
            <div class="row row-padding-top">
                <div class="panel panel-default main-panel">
                    <div class="panel-heading">
                        <div class="pull-left">
                            <h4><i class="fa fa-puzzle-piece fa-fw"></i></h4>
                        </div>

                        <div class="pull-right">
                            <button type="button" class="btn btn-primary btnNewApplication" data-toggle="modal" data-target="#mNewApplication">New Application</button>  
                        </div>
                    </div>
                    <div class="panel-body panel-applications">
                        <?php
                            echo $applications;
                        ?>
                    </div>
                </div>
        </div>

        <div class="modal fade" id="mNewApplication" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="myModalLabel">New Application</h4>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label>Application Name</label>
                        <input class="form-control tbNewApplicationName" placeholder="Name">
                    </div>
                    <div class="form-group">
                        <label>Link</label>
                        <input class="form-control tbLink" placeholder="Link">
                    </div>
                    <div class="form-group">
                        <label>Color</label>
                        <div class="input-group demo2">
                            <input type="text" value="" class="form-control tbNewColor" />
                            <span class="input-group-addon"><i></i></span>
                        </div>
                    </div>
                    <div class="form-group">
                        <label>Icon</label>
                        <div class="input-group iconpicker-container">
                            <input data-placement="bottomRight" class="form-control icp icp-auto iconpicker-element iconpicker-input tbNewIcon" value="fa-archive" type="text">
                            <span class="input-group-addon"><i class="fa fa-archive"></i></span>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-danger btnCancel pull-left" type="button" data-dismiss="modal">Cancel</button>
                    <button class="btn btn-primary btnCreateApplication" type="button">Create</button>
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
            //echo $deltemodal;
        ?>
    </div>
>>>>>>> master
    <!-- /#modal -->