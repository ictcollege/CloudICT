<div id="page-wrapper">
    <div class="row">
        <div class="col-lg-12">
            <div class="route pull-left">
                <i class="fa fa-home fa-2x homeicon"></i>
                <i class="fa fa-angle-right fa-2x separatoricon"></i>
                <a href="<?php echo site_url("Tasks/index") ?>" <button type="button" class="btn btn-default routebutton">Show All Tasks</button></a>

            </div>
        </div>
    </div>

    <div class="row">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h4><i class="fa fa-files-o fa-fw"></i>Create new Task</h4>
            </div>
            <div class="panel-body">
                <div class="col-lg-12 table-responsive">
                    <?php echo form_open("Tasks/store"); ?>
                    <table class="table table-striped tablefiles table-hover">
                        <thead>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th></th>
                        </thead>

                        <tbody>
                        <tr>
                            <td style="width: 50px"></td>
                            <td style="width: 50px">Task Name: </td>
                            <td style="width: 50px"><?php echo form_input("TaskName") ?></td>
                            <td style="width: 50px"></td>
                        </tr>

                        <tr>
                            <td style="width: 50px"></td>
                            <td style="width: 50px">Task Description: </td>
                            <td style="width: 50px"><?php echo form_textarea("TaskDescription") ?></td>
                            <td style="width: 50px"></td>
                        </tr>
                        <tr>
                            <td style="width: 50px"></td>
                            <td style="width: 50px">End Date: </td>
                            <td style="width: 50px"><?php echo form_input($edate) ?></td>
                            <td style="width: 50px"></td>
                        </tr>
                        <tr>
                            <td style="width: 50px"></td>
                            <td style="width: 50px">Assign as group task:  </td>
                            <td style="width: 50px"><?php echo form_checkbox("isGroupTask") ?></td>
                            <td style="width: 50px"></td>
                        </tr>
                        <tr>
                            <td style="width: 50px"></td>
                            <td style="width: 100px">Select users for the task:  </td>
                            <td style="width: 50px">
                                <?php
                                if(empty($Groups)) echo "You do not have permision to assign tasks to anyone.";
                                foreach($Groups as $Group => $Users){
                                    foreach($Users as $User => $UserData){
                                        echo "Username: $User"."<input type='checkbox' name='Users[]' value='{$UserData['UserId']}' /> <br />";
                                    }
                                    echo "<br />";
                                }
                                ?>
                            </td>
                            <td style="width: 50px"></td>
                        </tr>
                        <tr>
                            <td style="width: 50px"></td>
                            <td style="width: 50px"></td>
                            <td style="width: 50px"><?php echo form_submit("submit", "Create Task") ?></td>
                            <td style="width: 50px"></td>
                        </tr>
                        </tbody>

                    </table>
                    <?php echo form_close() ?>
                </div>
            </div>
        </div>

    </div>

</div>
<!-- /#page-wrapper -->

</div>

</body>

</html>