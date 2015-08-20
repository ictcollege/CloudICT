$(document).ready(function(){
   //LOGIN VALIDATION
   
    var btn = $(".btnLogin"),
        password = $(".tbPassword"),
        username = $(".tbUsername"),
        error = false;
    
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
                    } else {
                        window.location = "User"
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
   
});