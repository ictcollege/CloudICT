<script>
$(document).ready(function(){
     $(".tableusers").DataTable( {
                "ajax": 'admin/getAllUsers',
                "scrollX" : true, 
                "deferRender" : true
            });
         
});
</script>

<div id="page-wrapper">
            <div class="row row-padding-top">
                <div class="panel panel-default main-panel">
                    <div class="panel-heading">
                        <div class="pull-left">
                            <h4><i class="fa fa-user fa-fw"></i></h4>
                        </div>

                        <div class="pull-right">
                            <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#mNewUser">New User</button>  
                        </div>
                    </div>
                    <div class="panel-body panel-users">
                        <div class="table-responsiv">
                            
                            <table class="table-responsive tableusers table table-striped table-bordered nowrap">
                                <thead>
                                        <th>#USER ID</th>
                                        <th>#ROLE ID</th>
                                        <th>Username</th>
                                        <th>Password md5</th>
                                        <th>Email</th>
                                        <th>Disk Quota</th>
                                        <th>Disk Used %</th>
                                        <th>User Status</th>
                                        <th>User Key</th>
                                        <th>Key Expires</th>
                                </thead>
                                <tbody>
                                    
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade" id="mNewUser" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="myModalLabel">New User</h4>
                </div>
                <div class="modal-body">
                    <form id="formRegister" role="form" method="post" accept-charset="utf-8">
                        <div class="form-group input-group">
                            <span class="input-group-addon">@</span>
                             <input type="text" name="Email" value="" class="form-control tbEmail" placeholder="Email" autofocus="autofocus">
                            
                        </div>
                        <p class="help-block user-exists">Email is already in database.</p>
                        <div class="form-group input-group">
                            <span class="input-group-addon"><i class="fa  fa-key"></i>
                            </span>
                             <input type="text" name="key" value="" class="form-control tbKey disabled" placeholder="" readonly="true">
                        </div>
                        
                    </form>
                </div>
                <div class="modal-footer">
                    <div class="form-group input-group pull-right">
                            <button class="btn btn-primary btnGenerateKey" type="button"><i class="fa  fa-gears"></i> Generate</button>
                        </div>
                        <div class="form-group input-group pull-left">
                            <button class="btn btn-primary disabled btnSendKey" type="button"><i class="fa fa-send-o"></i> Send Key</button>
                        </div>
                </div>
            </div>
        </div>
    </div>
    <!-- /#modal -->

    <!--modal-->
    <div class="editmodals">
        <?php
            // echo $editmodal;
        ?>
    </div>
    <!-- /#modal -->
    <div class="deletemodals">
        <?php
            // echo $deltemodal;
        ?>
    </div>
    <!-- /#modal -->