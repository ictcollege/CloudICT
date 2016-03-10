
<div id="page-wrapper">
    
    <div class="row">
        <div class="col-lg-12">
            <?php if(isset($msg)){ echo $msg;}?>
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
                            <table class="table table-striped table-hover" id="myTable">
                                <thead>
                                    <tr>
                                        <th>Favourite</th>
                                        <th>File name</th>
                                        <th>Size</th>
                                        <th>Last Modified</th>
                                        <th>Manage</th>
                                    </tr>
                                </thead>
                                <tbody>
                                   
                                </tbody>
                            </table>
                        </div>
                    </div>
                        
            </div>
       
</div><!-- /#page-wrapper -->
<div class="clearfix"></div>
<script type="text/javascript" src="<?php echo base_url();?>public/js/jquery.dataTables.min.js"></script>
<script type="text/javascript" src="<?php echo base_url();?>public/js/dataTables.bootstrap.js"></script>
<script type="text/javascript" src="<?php echo base_url();?>public/js/filesScript.js"></script>
<script type="text/javascript">
    var options = {
            apiurl:"<?php echo base_url();?>ApiFiles/",
            scripturl:"<?php echo base_url();?>Files/",
            apifunc:"favourites/",
            current_dir:$("#current_dir").val(),
            current_path:"",
            datatable:"#myTable",
            datasrc:"files"
            
};
var filesScript = $(document).filesScript(options);
filesScript.base_url("<?php echo base_url();?>");
$(document).ready(function (){
    filesScript._initdatatables();
});
</script>