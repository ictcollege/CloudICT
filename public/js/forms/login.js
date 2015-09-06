$(document).ready(function(){
   //LOGIN VALIDATION
   
    var btn = $(".btnLogin"),
        password = $(".tbPassword"),
        username = $(".tbUsername"),
        error = false;
        
        
    $(".error").hide();
    
    btn.click(function() {
        
        if(username.val()== ""){
            username.parent().removeClass('has-success');
            username.parent().addClass('has-error');
            error = true;
        } else {
            username.parent().removeClass('has-error');
            username.parent().addClass('has-success');
            error = false;
        }
        
        if(password.val()== ""){
            password.parent().removeClass('has-success');
            password.parent().addClass('has-error');
            error = true;
        } else {
            password.parent().removeClass('has-error');
            password.parent().addClass('has-success');
            error = false;
        }
        
        if(!error) {
            $.ajax({
                url: 'user/login',
                type: 'post',
                dataType: 'json',
                data:{Username:username.val(),Password:password.val()},
                success: function(response) {
                    if(response["role"] == 3) {
                        window.location = "Admin";
                    } else if(response == 0) {
                        $(".error").text(" ");
                        $(".error").append("<p class='login-error'>Invalid user</p>");
                        $(".error").show(400);
                    } else if(response == -1) {
                       $(".panel-title.text-center").hide();
                       $(".panel-title.text-center").text(" ");
                       $(".panel-title.text-center").append("ICT Cloud Initial Login </br> Change Password");
                       $(".panel-title.text-center").show(400);
                       
                       $(".panel-body").children().remove();
                       $(".panel-body").hide();
                       $(".panel-body").append('<div class="form-group"><input type="password" class="form-control tbPassword" placeholder="New Password" name="password" /></div>');
                       $(".panel-body").append('<div class="form-group"><input type="password" class="form-control tbConfirmPassword" placeholder="Confirm Password" name="password" /></div>');
                       $(".panel-body").append('<a  class="btn btn-lg btn-success btn-block btnChangePassword">Change Password</a>');
                       $(".panel-body").append('<div class="login-error"></div>');
                       
                       $(".panel-body").slideDown(800);
                    }
                    else {
                        window.location = "User/applications"
                    }
                } 
            });
        }
    });
   
    username.blur(function() {
       if(username.val()== ""){
            username.parent().removeClass('has-success');
            username.parent().addClass('has-error');
        } else {
            username.parent().removeClass('has-error');
            username.parent().addClass('has-success');
        }
    });
   
    password.blur(function() {
        if(password.val()== ""){
            password.parent().removeClass('has-success');
            password.parent().addClass('has-error');
        } else {
            password.parent().removeClass('has-error');
            password.parent().addClass('has-success');
        }
    });
    
    $(document).on("click", ".btnChangePassword", function() {
        var newpassword = $(".tbPassword"),
            newpasswordconfirm = $(".tbConfirmPassword"),
            error = false;
            
        if(newpassword.val()== ""){
            newpassword.parent().removeClass('has-success');
            newpassword.parent().addClass('has-error');
            error = true;
        } else {
            newpassword.parent().removeClass('has-error');
            newpassword.parent().addClass('has-success');
            error = false;
        }
        
        if(newpasswordconfirm.val()== ""){
            newpasswordconfirm.parent().removeClass('has-success');
            newpasswordconfirm.parent().addClass('has-error');
            error = true;
        } else {
            newpasswordconfirm.parent().removeClass('has-error');
            newpasswordconfirm.parent().addClass('has-success');
            error = false;
        }
        
        
        
        if(!error) {
            if(newpassword.val() != newpasswordconfirm.val()) {
                newpassword.parent().removeClass('has-success');
                newpassword.parent().addClass('has-error');
                newpasswordconfirm.parent().removeClass('has-success');
                newpasswordconfirm.parent().addClass('has-error');
                
                $(".login-error").hide();
                $(".login-error").text(" ");
                $(".login-error").append("<p>Password And Confirm Password Do Not Match</p>");
                $(".login-error").show(400);
            } else if(newpassword.val() == "admin" && newpasswordconfirm.val() == "admin") {
                newpassword.parent().removeClass('has-success');
                newpassword.parent().addClass('has-error');
                newpasswordconfirm.parent().removeClass('has-success');
                newpasswordconfirm.parent().addClass('has-error');
                
                $(".login-error").hide();
                $(".login-error").text(" ");
                $(".login-error").append("<p>Password Can Not Be 'admin'</p>");
                $(".login-error").show(400);
            } else {
                $(".login-error").hide(400);
                
                $.ajax({
                    url: 'user/initialPasswordChange',
                    type: 'post',
                    dataType: 'json',
                    data:{NewPassword:newpassword.val()},
                    success: function(response) {
                        $(".panel-body").children().remove();
                        $(".panel-body").hide();
                        $(".panel-body").append('<h3>Passwrod Change Successfully</h3>');
                        $(".panel-body").append('<a  href="admin/" class="btn btn-lg btn-success btn-block btnChangePassword">Proceed To Cloud</a>');
                        $(".panel-body").slideDown(800);
                    }
                }); 
            }
            
            
        }
    });
});