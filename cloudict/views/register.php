<div class="container">
        <div class="row">
            <div class="col-md-4 col-md-offset-4">
                <div class="login-panel panel panel-success">
                    <div class="panel-heading">
                        <h3 class="panel-title text-center">ICT Cloud Register</h3>
                    </div>
                    <div class="panel-body" >
                            <?php 
                                if($exists) 
                                {
                            ?>
                            <fieldset>
                                <div class="form-group">
                                    <input type="text" name="username" value="" class="form-control tbUsername" placeholder="Username" autofocus="autofocus">
                                </div>
                                <div class="form-group">
                                    <input type="password" name="password" value="" class="form-control tbPassword" placeholder="Password">
                                </div>
                                <div class="form-group">
                                    <input type="password" name="password" value="" class="form-control tbConfirmPassword" placeholder="Confirm Password">
                                </div>
                                
                                <!-- Change this to a button or input when using this as a form -->
                                <a  class="btn btn-lg btn-success btn-block btnRegister">Register</a>
                                
                            <?php 
                                }
                                else 
                                {
                                ?>
                                
                                <h3 class="text-center">Key Already Used Or Expiered <br/> <br/>Contact Administartor</h3>
                            <?php 
                                }
                           ?>
                                
                            </fieldset>
                        
                        <div class="error">
                        </div>
                    </div>
                </div>
            </div>
        </div>
</div>
