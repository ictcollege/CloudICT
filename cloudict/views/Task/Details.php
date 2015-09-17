<div id="page-wrapper">
    <div class="row">
        <div class="col-lg-12">
            <div class="route pull-left">
                <i class="fa fa-home fa-2x homeicon"></i>
                <i class="fa fa-angle-right fa-2x separatoricon"></i>
                <button type="button" class="btn btn-default routebutton"><i class="fa fa-upload  fa-1x"></i></button>
                <button type="button" class="btn btn-default routebutton">New</button>
                <?php $task = $Task[0]; ?>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h4><i class="fa fa-files-o fa-fw"></i>Task Details</h4>
            </div>
            <div class="panel-body">
                <div class="col-lg-12 table-responsive">
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
                            <td style="width: 50px"><?php echo $task["TaskName"] ?></td>
                            <td style="width: 50px"></td>
                        </tr>

                        <tr>
                            <td style="width: 50px"></td>
                            <td style="width: 50px">Task Description: </td>
                            <td style="width: 50px"><?php echo $task["TaskDescription"] ?></td>
                            <td style="width: 50px"></td>
                        </tr>


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
