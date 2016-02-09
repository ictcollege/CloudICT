<?php //if(!isset($tasks)) redirect("Tasks/");
        include("Create.php") ?>
<div id="page-wrapper">
    <div class="row">
        <div class="col-lg-12 route">
            <div class="pull-left">
                <i class="fa fa-home fa-2x homeicon"></i>
            </div>
            <div class="pull-right">
                <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#mNewUser">New Task</button>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h4><i class="fa fa-files-o fa-fw"></i><?php echo $taskTitle; ?></h4>
            </div>
            <div class="panel-body">
                <div class="col-lg-12 table-responsive">
                    <table class="table table-striped tablefiles table-hover">

                        <thead>
                        <th></th>
                        <th>Task Title</th>
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
                        foreach($tasks as $task){?>
                            <tr>
                                <td style="width:25px"></td>
                                <td style="width:50px" class="filename"><?php echo $task["TaskName"] ?></td>
                                <td style="width:250px"><?php echo $task["TaskDescription"] ?></td>
                                <td style="width:100px"><?php echo date("d/m/Y", $task["TaskTimeCreated"]); ?></td>
                                <td style="width:100px"><?php echo date("d/m/Y", $task["TaskTimeToExecute"]); ?></td>
                                <td style="width:25px"></td>
                                <td style="width:50px">
                                    <a href="<?php echo site_url("Tasks/show")."/".$task["IdTask"]; ?>">
                                        <button class="btn btn-default">Details</button>
                                    </a>
                                </td>
                                <td style="width:50px">
                                    <?php if($finish) { ?>
                                    <a href="<?php echo site_url("Tasks/finish")."/".$task["IdTask"]; ?>">
                                    <button class="btn btn-default">Finish Task</button>
                                    </a>
                                    <?php } ?>
                                </td>
                                <td style="width:50px">
                                    <?php if($delete){ ?>
                                    <a href="<?php echo site_url("Tasks/destroy")."/".$task['IdTask'] ?>">
                                        <button class="btn btn-default">Delete Task</button>
                                    </a>
                                    <?php } ?>
                                </td>
                                <td style="width:50px"></td>
                            </tr>
                        <?php } ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    </div>

</div>