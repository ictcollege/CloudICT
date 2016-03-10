<script type="text/javascript" src="<?php echo base_url();?>public/js/jquery.dataTables.min.js"></script>
<script type="text/javascript" src="<?php echo base_url();?>public/js/dataTables.bootstrap.js"></script>

<div id="page-wrapper">
    <div class="row">
        <div class="col-lg-12">
            
                        <div class="col-lg-7">
                        <ol class="breadcrumb">
                            <li><a href="<?php echo base_url();?>Files/shared_by_link"><i class="fa fa-link fa-2x homeicon">&nbsp;</i><i class="fa fa-angle-right fa-2x separatoricon"></i> </a></li>
                          
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
                                        <th>Type</th>
                                        <th>File/Folder name</th>
                                        <th>Shared on</th>
                                        <th>Link</th>
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
<script type="text/javascript" src="<?php echo base_url();?>public/js/filesScript.js"></script>
<script type="text/javascript">
var options = {
    apiurl:"<?php echo base_url();?>ApiFiles/",
    scripturl:"<?php echo base_url();?>Files/",
    apifunc:"sharedByLink/",
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

