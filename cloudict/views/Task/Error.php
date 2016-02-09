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
                <h4><i class="fa fa-files-o fa-fw"></i>There was an error while processing your request!</h4>
            </div>
            <div class="panel-body">
                <div class="col-lg-12 table-responsive">
                    <table class="table table-striped tablefiles table-hover">
                        <thead>
<!--                        Check to see if there are multiple errors ie. ErrorList is not empty-->
                        <th><?php isset($ErrorList) == true ? $s = $Error : $s = "Error Message"; print($s); ?></th>
                        <tbody>
                            <tr>
<!--                                if $ErrorList is not empty show errors inside it otherwise show $Error-->
                                <td><?php
                                    if(isset($ErrorList)){
                                        foreach($ErrorList as $e){
                                            echo "<p>$e</p>";
                                        }
                                    }
                                    else echo $Error;
                                    ?></td>
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
