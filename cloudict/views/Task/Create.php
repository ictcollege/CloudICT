<!--<script type="text/javascript">-->
<!--    function formValidate(){-->
<!--        var taskName = document.getElementById("TaskName");-->
<!--        var taskDescription = document.getElementById("TaskDescription");-->
<!---->
<!--        var edate = document.getElementById("edate");-->
<!--        var endDate = new Date(edate.value);-->
<!--        var today = new Date();-->
<!---->
<!--        var selectedUsers = document.getElementsByName('Users');-->
<!--        var errorDiv = document.getElementById("errorDiv");-->
<!---->
<!--        var form = document.getElementById("createTask");-->
<!---->
<!--        var errors = new Array();-->
<!---->
<!--        if(taskName.value.trim() == ""){-->
<!--            errors.push("Task Name is required.");-->
<!--        }-->
<!---->
<!--        if(taskDescription.value.trim() == ""){-->
<!--            errors.push("Task Description is required.");-->
<!--        }-->
<!---->
<!--        if(selectedUsers.length == 0){-->
<!--            errors.push("you must select at least one user for the task.");-->
<!--        }-->
<!---->
<!--        if(endDate > today){-->
<!--            errors.push("Task expiration date must be in the future.")-->
<!--        }-->
<!---->
<!--        if(errors.length > 0){-->
<!--            var s = "";-->
<!--            for(var i = 0; i < errors.length; i++)-->
<!--                s += "<div><p>errors[i]</p></div>";-->
<!---->
<!--            errorDiv.innerHTML = s;-->
<!--            return false;-->
<!--        }-->
<!--        else {-->
<!--            return true;-->
<!--        }-->
<!--    }-->
<!--</script>-->

    <div class="modal fade" id="mNewUser" role="dialog">
        <div class="modal-dialog">

            <div class="modal-content">

                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Create Task</h4>
                </div>

                <div class="modal-body">
                    <div class="col-lg-12 table-responsive">
                        <?php echo form_open("Tasks/store", $formOptions); ?>
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
                                <td style="width: 50px"><?php echo form_input($formTaskName) ?></td>
                                <td style="width: 50px"></td>
                            </tr>

                            <tr>
                                <td style="width: 50px"></td>
                                <td style="width: 50px">Task Description: </td>
                                <td style="width: 50px"><?php echo form_textarea($formTaskDescription) ?></td>
                                <td style="width: 50px"></td>
                            </tr>
                            <tr>
                                <td style="width: 50px"></td>
                                <td style="width: 50px">End Date: </td>
                                <td style="width: 50px"><?php echo form_input($formEndDate) ?></td>
                                <td style="width: 50px"></td>
                            </tr>
                            <tr>
                                <td style="width: 50px"></td>
                                <td style="width: 50px">Assign as group task:  </td>
                                <td style="width: 50px"><input type="checkbox" name="isGroupTask" value="1" /> </td>
                                <td style="width: 50px"></td>
                            </tr>
                            <tr>
                                <td style="width: 50px"></td>
                                <td style="width: 100px">Select users for the task:  </td>
                                <td style="width: 50px">
                                    <?php
                                    if(empty($Groups)) echo "You do not have permision to assign tasks to anyone.";
                                    foreach($Groups as $Group => $Users){
                                        echo "<table class='table table-condensed'>";

                                        echo "<tr style='background-color: #061317; color: white'>
                                            <th>Group Name: </th><td>$Group</td>
                                          </tr>";

                                        echo "<tr>";
                                        echo "<td>Username: </td>";
                                        echo "<td>Assign: </td>";
                                        echo "</tr>";

                                        foreach($Users as $User => $UserData){
                                            echo "<tr>";
                                            echo "<td>$User</td>";
                                            echo "<td><input type='checkbox' name='Users[]' value='{$UserData['UserId']}' /></td>";
                                            echo "</tr>";
                                        }
                                        echo "</table>";
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

                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

<!--    <div class="row">-->
<!--        <div class="panel panel-default">-->
<!--            <div class="panel-heading">-->
<!--                <h4><i class="fa fa-files-o fa-fw"></i>Create new Task</h4>-->
<!--            </div>-->
<!--            <div class="panel-body">-->
<!--                <div class="col-lg-12 table-responsive">-->
<!--                    --><?php //echo form_open("Tasks/store", $formOptions); ?>
<!--                    <table class="table table-striped tablefiles table-hover">-->
<!--                        <thead>-->
<!--                        <th></th>-->
<!--                        <th></th>-->
<!--                        <th></th>-->
<!--                        <th></th>-->
<!--                        </thead>-->
<!---->
<!--                        <tbody>-->
<!--                        <tr>-->
<!--                            <td style="width: 50px"></td>-->
<!--                            <td style="width: 50px">Task Name: </td>-->
<!--                            <td style="width: 50px">--><?php //echo form_input($formTaskName) ?><!--</td>-->
<!--                            <td style="width: 50px"></td>-->
<!--                        </tr>-->
<!---->
<!--                        <tr>-->
<!--                            <td style="width: 50px"></td>-->
<!--                            <td style="width: 50px">Task Description: </td>-->
<!--                            <td style="width: 50px">--><?php //echo form_textarea($formTaskDescription) ?><!--</td>-->
<!--                            <td style="width: 50px"></td>-->
<!--                        </tr>-->
<!--                        <tr>-->
<!--                            <td style="width: 50px"></td>-->
<!--                            <td style="width: 50px">End Date: </td>-->
<!--                            <td style="width: 50px">--><?php //echo form_input($formEndDate) ?><!--</td>-->
<!--                            <td style="width: 50px"></td>-->
<!--                        </tr>-->
<!--                        <tr>-->
<!--                            <td style="width: 50px"></td>-->
<!--                            <td style="width: 50px">Assign as group task:  </td>-->
<!--                            <td style="width: 50px"><input type="checkbox" name="isGroupTask" value="1" /> </td>-->
<!--                            <td style="width: 50px"></td>-->
<!--                        </tr>-->
<!--                        <tr>-->
<!--                            <td style="width: 50px"></td>-->
<!--                            <td style="width: 100px">Select users for the task:  </td>-->
<!--                            <td style="width: 50px">-->
<!--                                --><?php
//                                if(empty($Groups)) echo "You do not have permision to assign tasks to anyone.";
//                                foreach($Groups as $Group => $Users){
//                                    echo "<table class='table table-condensed'>";
//
//                                    echo "<tr style='background-color: #061317; color: white'>
//                                            <th>Group Name: </th><td>$Group</td>
//                                          </tr>";
//
//                                    echo "<tr>";
//                                    echo "<td>Username: </td>";
//                                    echo "<td>Assign: </td>";
//                                    echo "</tr>";
//
//                                    foreach($Users as $User => $UserData){
//                                        echo "<tr>";
//                                        echo "<td>$User</td>";
//                                        echo "<td><input type='checkbox' name='Users[]' value='{$UserData['UserId']}' /></td>";
//                                        echo "</tr>";
//                                    }
//                                    echo "</table>";
//                                    echo "<br />";
//                                }
//                                ?>
<!--                            </td>-->
<!--                            <td style="width: 50px"></td>-->
<!--                        </tr>-->
<!--                        <tr>-->
<!--                            <td style="width: 50px"></td>-->
<!--                            <td style="width: 50px"></td>-->
<!--                            <td style="width: 50px">--><?php //echo form_submit("submit", "Create Task") ?><!--</td>-->
<!--                            <td style="width: 50px"></td>-->
<!--                        </tr>-->
<!--                        </tbody>-->
<!---->
<!--                    </table>-->
<!--                    --><?php //echo form_close() ?>
<!--                </div>-->
<!--            </div>-->
<!--        </div>-->
<!---->
<!--    </div>-->
<!---->
<!--</div>-->
<!--<!-- /#page-wrapper -->-->
<!---->
<!--</div>-->
<!---->
<!--</body>-->
<!---->
<!--</html>-->