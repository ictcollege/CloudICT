<script>
$(document).ready(function(){
     var datatable =$(".tableusers").DataTable( {
                        "ajax": 'admin/getAllUsers',
                        "scrollX" : true, 
                        "deferRender" : true
                    });
    
    //delete user         
    $(document).on("click", ".btnDeleteUserYes", function() {
        var id = $(this).attr("id");

        $.ajax({
            url: 'admin/deleteUser',
            type: 'post',
            dataType: 'json',
            data:{IdUser:id},
            success: function(resposne) {

                setTimeout(function() {
                    $(".modal").modal('hide');
                }, 200);
                
                datatable.ajax.reload();
            }
         });
     });
     
     //save changes, edit modal
        $(document).on("click", ".btnSaveChanges", function() {
            var id = $(this).attr("id"),
                username = $(this).parent().parent().find(".tbEditUsername"),
                password = $(this).parent().parent().find(".tbEditPassword"),
                userfullname = $(this).parent().parent().find(".tbUserFullName"),
                email = $(this).parent().parent().find(".tbUserEmail"),
                diskquota = $(this).parent().parent().find(".tbUserDiskQuota"),
                diskused = $(this).parent().parent().find(".tbUserDiskUsed"),
                userstatus = $(this).parent().parent().find(".tbUserStatus"), 
                userkey = $(this).parent().parent().find(".tbUserKey"),
                keyexpires  = $(this).parent().parent().find(".tbUserKeyExpires");
                
            if(username.val() == "") {
                username = username.attr("placeholder");
            } else {
                username = username.val();
            }
            
            if(password.val() == "") {
                password = password.attr("placeholder");
            } else {
                password = password.val();
            }
            
            if(userfullname.val() == "") {
                userfullname = userfullname.attr("placeholder");
            } else {
                userfullname = userfullname.val();
            }
            
            if(email.val() == "") {
                email = email.attr("placeholder");
            } else {
                email = email.val();
            }
            
            if(diskquota.val() == "") {
                diskquota = diskquota.attr("placeholder");
            } else {
                diskquota = diskquota.val();
            }
            
            if(diskused.val() == "") {
                diskused = diskused.attr("placeholder");
            } else {
                diskused = diskused.val();
            }
            
            if(userstatus.val() == "") {
                userstatus = userstatus.attr("placeholder");
            } else {
                userstatus = userstatus.val();
            }
            
            if(userkey.val() == "") {
                userkey = userkey.attr("placeholder");
            } else {
                userkey = userkey.val();
            }
            
            if(keyexpires.val() == "") {
                keyexpires = keyexpires.attr("placeholder");
            } else {
                keyexpires = keyexpires.val();
            }
            
            $.ajax({
                url: 'admin/editUser',
                type: 'post',
                dataType: 'json',
                data:{IdUser:id,Username:username,Password:password,Userfullname:userfullname,Email:email,Diskquota:diskquota,Diskused:diskused,Userstatus:userstatus,Userkey:userkey,KeyExpires:keyexpires},
                success: function(resposne) {
                    setTimeout(function() {
                        $(".modal").modal('hide');
                    }, 200);
                
                    datatable.ajax.reload();
                }
            });
        });
        
        $(".btnGenerateKey").click(function () {
            var _this = $(this),
                email = $(".tbEmail"),
                regEmail = /^([\w-]+(?:\.[\w-]+)*)@((?:[\w-]+\.)*\w[\w-]{0,66})\.([a-z]{2,6}(?:\.[a-z]{2})?)$/i,
                error = false;
                
            if(email.val().match(regEmail)) {
                email.parent().addClass("has-success");
                email.parent().removeClass("has-error");
                error = false;
            } else {
                email.parent().removeClass("has-success");
                email.parent().addClass("has-error");
                error = true;
            }
            
            if(!error) {
                $.ajax({
                    url: 'admin/checkIfEmailExists',
                    type: 'post',
                    dataType: 'json',
                    data:{Email:email.val()},
                    success: function(response) {
                        if(response == 0) {
                            $(".user-exists").hide(400);
                            $.ajax({
                                url: 'admin/insertUser',
                                type: 'post',
                                dataType: 'json',
                                data:{Email:email.val()},
                                success: function(response) {
                                    $(".tbKey").attr("placeholder", response);
                                    $(".btnSendKey").removeClass("disabled");
                                    $(".btnGenerateKey").hide(400);
                                    
                                    datatable.ajax.reload();
                                }
                            });
                        } else {
                            email.parent().removeClass("has-success");
                            email.parent().addClass("has-error");
                            $(".user-exists").show(400);
                        }
                    }
                });
            }
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
                                        <th>User Full Name</th>
                                        <th>Email</th>
                                        <th>Disk Quota</th>
                                        <th>Disk Used %</th>
                                        <th>User Status</th>
                                        <th>User Key</th>
                                        <th>Key Expires</th>
                                        <th>Options</th>
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