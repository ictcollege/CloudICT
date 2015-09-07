<div class="container">
        <div class="row">
            <div class="col-md-4 col-md-offset-4">
                <div class="login-panel panel panel-success">
                    <div class="panel-heading">
                        <h3 class="panel-title text-center">Welcome To ICT Cloud </h3>
                    </div>
                    <div class="panel-body" >
                        <?php echo form_open('logins/login', $form_attr);?>
                            <fieldset>
                                <div class="form-group">
                                    <?php echo form_input($username_attr) ?>
                                </div>
                                <div class="form-group">
                                    <?php echo form_password($password_attr) ?>
                                </div>
                                
                                <!-- Change this to a button or input when using this as a form -->
                                <a  class="btn btn-lg btn-success btn-block btnLogin">Login</a>
                            </fieldset>
                        </form>
                        
                        <div class="error">
                        </div>
                    </div>
                </div>
            </div>
        </div>
</div>
