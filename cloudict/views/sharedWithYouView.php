
<div id="page-wrapper">
    <div class="row">
        <div class="col-lg-12">
            
                        <div class="col-lg-7">
                        <ol class="breadcrumb">
                            <li><a href="<?php echo base_url();?>Files/shared_with_you"><i class="fa fa-share-alt fa-2x homeicon">&nbsp;</i><i class="fa fa-angle-right fa-2x separatoricon"></i> </a></li>
                          
                        </ol>
                        </div>
                        <div class="col-lg-7"> 
                            <br/>
                            <span id="errorMsg" class="text-danger"></span>
                        </div>
                        <div class="clearfix"></div>
                        <div class="col-lg-12">
                            <table class="table table-striped table-hover">
                                <thead>
                                    <tr>
                                        <th>Owner</th>
                                        <th>Shared on</th>
                                        <th>File name</th>
                                        <th>Privilege</th>
                                        <th>Size</th>
                                        <th>Last Modified</th>
                                        <th>Manage</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    foreach($shared_files as $file){
                                        echo "<tr>";
                                        echo "<td>".$file["Owner"]."</td>";
                                        echo "<td>".$file["ShareCreated"]."</td>";
                                        echo "<td>".$file["ShareFullName"]."</td>";
                                        echo "<td>".$file['SharePrivilege']."</td>";
                                        echo "<td>".$file['FileSize']."</td>";
                                        echo "<td>".$file['FileLastModified']."</td>";
                                        echo "<td>"."modifiy"."</td>";
                                        echo "</tr>";
                                    }
                                    
                                    ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                        
                    
            </div>
       
</div><!-- /#page-wrapper -->
<div class="clearfix"></div>

<script type="text/javascript">
    
</script>