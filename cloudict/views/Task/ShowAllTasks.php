<div id="page-wrapper">
    <div class="row">
        <div class="col-lg-12">
            <div class="route pull-left">
                <i class="fa fa-home fa-2x homeicon"></i>
                <i class="fa fa-angle-right fa-2x separatoricon"></i>
                <a href="<?php echo site_url("Tasks/create") ?>" <button type="button" class="btn btn-default routebutton">Create Task</button></a>

            </div>
        </div>
    </div>

    <div class="row">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h4><i class="fa fa-files-o fa-fw"></i>Tasks assigned to you</h4>
            </div>
            <div class="panel-body">
                <div class="col-lg-12 table-responsive">
                    <table class="table table-striped tablefiles table-hover">

                        <thead>
                        <th></th>
                        <th>Task Name</th>
                        <th>Task Description</th>
                        <th>Time Created</th>
                        <th>Time to Execute</th>
                        <th></th>
                        <th></th>
                        <th>Options</th>
                        <th></th>
                        </thead>
                        <tbody>
                            <?php
                            foreach($assigned as $task){?>
                                <tr>
                                    <td style="width:25px"></td>
                                    <td style="width:50px" class="filename"><?php echo $task["TaskName"] ?></td>
                                    <td style="width:250px"><?php echo $task["TaskDescription"] ?></td>
                                    <td style="width:100px"><?php echo date("d/m/Y", $task["TaskTimeCreated"]); ?></td>
                                    <td style="width:100px"><?php echo date("d/m/Y", $task["TaskTimeToExecute"]); ?></td>
                                    <td style="width:25px"></td>
                                    <td style="width:50px"><button class="btn btn-default">Details</button></td>
                                    <td style="width:50px"><button class="btn btn-default">Finish</button></td>
                                    <td style="width:50px"></td>
                                </tr>
                            <?php } ?>
<!--                        <tr>-->
<!--                            <td style="width:25px"></td>-->
<!--                            <td style="width:50px" class="filename">Finish Homework</td>-->
<!--                            <td style="width:250px">Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, </td>-->
<!--                            <td style="width:100px">15-04-2015</td>-->
<!--                            <td style="width:100px">20-04-2015</td>-->
<!--                            <td style="width:25px"></td>-->
<!--                            <td style="width:50px"><button class="btn btn-default">Details</button></td>-->
<!--                            <td style="width:50px"><button class="btn btn-default">Edit</button></td>-->
<!--                            <td style="width:50px"><button class="btn btn-default">Finish</button></td>-->
<!--                        </tr>-->
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    </div>

    <div class="row">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h4><i class="fa fa-files-o fa-fw"></i>Tasks you assigned</h4>
            </div>
            <div class="panel-body">
                <div class="col-lg-12 table-responsive">
                    <table class="table table-striped tablefiles table-hover">

                        <thead>
                        <th></th>
                        <th>Task Name</th>
                        <th>Task Description</th>
                        <th>Time Created</th>
                        <th>Time to Execute</th>
                        <th></th>
                        <th></th>
                        <th>Options</th>
                        <th></th>
                        </thead>
                        <tbody>
                        <?php
                        foreach($given as $task){?>
                            <tr>
                                <td style="width:25px"></td>
                                <td style="width:50px" class="filename"><?php echo $task["TaskName"] ?></td>
                                <td style="width:250px"><?php echo $task["TaskDescription"] ?></td>
                                <td style="width:100px">15-04-2015</td>
                                <td style="width:100px">20-04-2015</td>
                                <td style="width:25px"></td>
                                <td style="width:50px"><button class="btn btn-default">Details</button></td>
                                <td style="width:50px">
                                    <a href="<?php echo site_url("Tasks/destroy")."/".$task['IdTask'] ?>">
                                        <button class="btn btn-default">Delete</button>
                                    </a>
                                </td>
                                <td style="width:50px"><button class="btn btn-default">Finish</button></td>
                            </tr>
                        <?php } ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    </div>

</div>
<!-- /#page-wrapper -->

</div>

</body>

</html>
