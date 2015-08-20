<div id="page-wrapper">
            <div class="row row-padding-top">
                <div class="col-lg-6 col-lg-offset-3">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <h4><i class="fa  fa-user fa-fw"></i>Generate New User</h4>
                        </div>
                        <div class="panel-body">
                             <?php echo form_open('logins/login', $form_attr);?>
                                    <div class="form-group input-group">
                                        <span class="input-group-addon">@</span>
                                         <?php echo form_input($email_attr) ?>
                                    </div>
                                    <div class="form-group input-group">
                                        <span class="input-group-addon"><i class="fa  fa-key"></i>
                                        </span>
                                         <?php echo form_input($key_attr) ?>
                                    </div>
                                    <div class="form-group input-group pull-right">
                                        <button class="btn btn-primary" type="button"><i class="fa  fa-gears"></i> Generate</button>
                                    </div>
                                    <div class="form-group input-group pull-left">
                                        <button class="btn btn-primary disabled" type="button"><i class="fa fa-send-o"></i> Send Key</button>
                                    </div>
                                </form>
                        </div>
                    </div>   
                </div>
            </div>
            
            
        </div>
        <!-- /#page-wrapper -->

    </div>
    <!-- /#wrapper -->

   <?php
     $data['base_url'] = $base_url;
     $this->load->view('scripts.php', $data);
    ?>

</body>

</html>