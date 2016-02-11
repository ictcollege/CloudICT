<<<<<<< HEAD
<script type="text/javascript">
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
        
        $(".btnGenerateKey").click(function() {
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
                                    $(".tbKey").attr("placeholder", response.key);
                                    $(".btnSendKey").removeClass("disabled");
                                    $(".btnGenerateKey").hide(400);
                                    
                                    $(".editmodals").append(response.editmodal);
                                    $(".deletemodals").append(response.deltemodal);
                                    
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
        
        // send key to email
        
        $(".btnSendKey").click(function() {
            var key = $(".tbKey").attr("placeholder"),
                email = $(".tbEmail").val();
            
            $.ajax({
                url: 'admin/sendKey',
                type: 'post',
                dataType: 'json',
                data:{Key:key,Email:email},
                success: function(response) {
                }
            });
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
=======
<script type="text/javascript">
function editUser(control){
    var id = $(control).data('id');
    $.ajax({
            url: 'admin/editUser',
            type: 'post',
            dataType: 'json',
            data:{IdUser:id},
            success: function(response) {
                if(typeof response === 'object'){
                    fillEditForm(response);
                    
                }
                else{
                    alert(response.toString());
                }
            }
   });
    
}
function makeRandomKey(size)
{
    var text = "";
    var possible = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789";

    for( var i=0; i < size; i++ )
        text += possible.charAt(Math.floor(Math.random() * possible.length));

    return text;
}
function sendKey(key,email){
    $.ajax({
                url: 'admin/sendKey',
                type: 'post',
                dataType: 'json',
                data:{Key:key,Email:email},
                success: function(response) {
                }
    });
}
function fillEditForm(User){
    $("#tbEditIdUser").val(User.IdUser);
    $("#selEditIdRole").val(User.IdRole);
    $("#tbEditUserName").val(User.UserName);
    $("#tbEditUserPassword").val(User.UserPassword);
    $("#tbEditOldPassword").val(User.UserPassword);
    $("#tbEditUserFullname").val(User.UserFullname);
    $("#tbEditUserEmail").val(User.UserEmail);
    $("#tbEditUserDiskQuota").val(User.UserDiskQuota);
    $("#tbEditUserDiskUsed").val(User.UserDiskUsed);
    $("#selEditUserStatus").val(User.UserStatus);
    $("#tbEditUserKey").val(User.UserKey);
    if(User.UserKeyExpires == "0"){
       $("#chbEditUserKeyExpires").prop('checked','checked');
    }
    $("#fldEditUser").removeAttr('disabled');
    $(".btnSaveChanges").removeAttr('disabled');
}
function deleteUser(control){
    var id = $(control).data('id');
    $("#hiddenIdUserDelete").val(id);
}
$(document).ready(function(){
    $(".btnEditUser").click(function(){
        alert($(this).data('id'));
    });
     var datatable =$(".tableusers").DataTable( {
                        "ajax": 'admin/getAllUsers',
                        "scrollX" : true, 
                        "deferRender" : true
                    });
    
    //delete user         
    $(document).on("click", ".btnDeleteUserYes", function() {
        var id = $("#hiddenIdUserDelete").val();

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
            var username = $("#tbEditUserName"),
                password = $("#tbEditUserPassword"),
                userfullname = $("#tbEditUserFullname"),
                email = $("#tbEditUserEmail"),
                diskquota = $("#tbEditUserDiskQuota"),
                userstatus = $("#selEditUserStatus"), 
                userkey = $("#tbEditUserKey"),
                keyexpires  = $("#chbEditUserKeyExpires"),
                idrole = $("#selEditIdRole"),
                iduser = $("#tbEditIdUser");
            
            var User = new Object();
            User.IdUser = iduser.val();
            User.IdRole = idrole.val();
            User.UserName = username.val();
            User.UserPassword = password.val();
            User.UserOldPassword = $("#tbEditOldPassword").val();
            User.UserFullname = userfullname.val();
            User.UserEmail = email.val();
            User.UserDiskQuota = diskquota.val();
            User.UserStatus = userstatus.val();
            User.UserKey = userkey.val();
            if($(keyexpires).is(':checked')){
                User.UserKeyExpires = keyexpires.val();
            }
            else{
                User.UserKeyExpires = <?php echo time() + (7 * 24 * 60 * 60);?>;
            }
            
            
            var json = JSON.stringify(User);
            $.ajax({
                url: 'admin/editUser',
                type: 'post',
                dataType: 'json',
                data:{json:json},
                success: function(response) {
                    if(response==true){
                        $(".modal").modal('hide');
                        $(".btnSaveChanges").attr('disabled','disabled');
                    }
                
                    datatable.ajax.reload();
                }
            });
        });
        
        $(".btnGenerateKey").click(function() {
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
                                    $(".tbKey").attr("placeholder", response.key);
                                    $(".btnSendKey").removeClass("disabled");
                                    $(".btnGenerateKey").hide(400);
                                    
                                    $(".editmodals").append(response.editmodal);
                                    $(".deletemodals").append(response.deltemodal);
                                    
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
        
        // send key to email
        
        $(".btnSendKey").click(function() {
            var key = $(".tbKey").attr("placeholder"),
                email = $(".tbEmail").val();
            
            sendKey(key,email);
        });
        
        $(".selUserDiskQuota").change(function(){
            var size = $(this).val();
            if(size == "0"){
                $("#tbEditUserDiskQuota").removeAttr('disabled');
            }
            else{
                $("#tbEditUserDiskQuota").attr('disabled','disabled');
                $("#tbEditUserDiskQuota").val(size);
            }
        });
        
        $("#btnEditGenerate").click(function (){
            var key = makeRandomKey(8);
            $("#tbEditUserKey").val(key);
        });
        
        $("#btnEditSendKey").click(function (){
            var email = $("#tbEditUserEmail").val();
            var key = $("#tbEditUserKey").val();
            sendKey(key,email);
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

    <!--modal editModal-->
        <div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
            <div class="modal-dialog" role="document">';
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title" id="myModalLabel">Edit User</h4>
                    </div>
                    <div class="modal-body">
                        <fieldset id="fldEditUser" disabled="">
<div class="form-group">
<label>#USER ID</label>
<input class="form-control" disabled id="tbEditIdUser"/>
</div>
<div class="form-group">
<label>#ROLE ID</label>
<select id="selEditIdRole" class="form-control">
    <option>Select..</option>
    <option value="1">User</option>
    <option value="2">Group Administrator</option>
    <option value="3">Administrator</option>
</select>
</div>
<div class="form-group">
<label>Username</label>
<input class="form-control" id="tbEditUserName"/>
</div>
<div class="form-group">
<label>Password md5</label>
<input class="form-control" id="tbEditUserPassword"/>
<input type='hidden' id='tbEditOldPassword' value=""/>
</div>
<div class="form-group">
<label>User Full Name</label>
<input class="form-control" id="tbEditUserFullname"/>
</div>
<div class="form-group">
<label>User Email</label>
<input class="form-control" id="tbEditUserEmail"/>
</div>
<div class="form-group">
<label>Disk Quota</label>
<select class="form-control selUserDiskQuota">
<option value="0">select</option>
<option value="262144000"><256 MB</option>
<option value="524288000"><512 MB</option>
<option value="1000000000"><1 GB</option>
<option value="5000000000"><5 GB</option>
</select>
<label>Custom size in byte's</label>
<input class="form-control" disabled id="tbEditUserDiskQuota"/>
</div>
<div class="form-group">
<label>Disk Used %</label>
<input class="form-control" disabled="" id="tbEditUserDiskUsed"/>
</div>
<div class="form-group">
<label>User Status</label>
<select id="selEditUserStatus" class="form-control">
    <option value="-1">Select..</option>
    <option value="0">Banned</option>
    <option value="1">Active</option>
</select>
</div>
<div class="form-group">
<label>User Key</label>
<input class="form-control" disabled="" id="tbEditUserKey"/>
<button class="btn btn-primary" id="btnEditGenerate">Generate</button>
<button class="btn btn-link" id="btnEditSendKey">Send</button>
</div>
<div class="form-group">
<label>Key Expires</label>
<input type="checkbox" id="chbEditUserKeyExpires" value="0"/>
</div>
                        </fieldset>
</div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-primary pull-right btnSaveChanges">Save Changes</button>
                    </div>
                </div>
            </div>
        </div>
    <!-- modal deleteModal -->
        <div class="modal fade" id="deleteModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
            <div class="modal-dialog" role="document">';
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title" id="myModalLabel">Delete User</h4>
                    </div>
                    <div class="modal-body text-center">
                        Are you sure?
                        <input type='hidden' id='hiddenIdUserDelete' value=""/>
                    </div>
                    <div class="modal-footer text-center">
                        
                        <button type="button" class="btn btn-primary btnDeleteUserYes">Yes</button>
                        <button type="button" class="btn btn-danger" data-dismiss="modal">No</button>
                    </div>
                </div>
            </div>
        </div>
    <!-- /#modal -->
>>>>>>> master
