<script type="text/javascript" src="<?php echo base_url();?>public/js/jquery.dataTables.min.js"></script>
<script type="text/javascript" src="<?php echo base_url();?>public/js/dataTables.bootstrap.js"></script>
<script type="text/javascript">
    var table;
    function myFunction(){
        table.ajax.reload( 
                function (){
                    attachListeners();
                }
                ); // user paging is not reset on reload
    }
    $(document).ready(function (){
        table=$("#myTable").DataTable({
            "ajax": {
                "url":"<?php echo base_url();?>ApiFiles/sharedByLink/"
              },
            "fnInitComplete": function(oSettings){
                attachListeners();
            },
            "scrollX" : true, 
            "deferRender" : true
        });
        
    });
    function attachListeners(){
        $('.unshare').bind('click',function (e){
            e.preventDefault();
            var Unshare=new Object();
            Unshare.Id=$(this).data('id');
            Unshare.Type=$(this).data('type');
            Unshare.State=false;
            var json = JSON.stringify(Unshare);
            var control = $(this);
            $.ajax({
                    url: "<?php echo base_url();?>ApiFiles/directShare",
                    type: "POST",
                    dataType: "json",
                    data:{json:json},
                    beforeSend: function (xhr) {
                        $(control).append('<i class="fa fa-spinner fa-spin"></i>');
                    },
                    success: function(data) {
                       if($(".fa-spin").length>0){
                           $(".fa-spin").remove();
                       }
                       myFunction();

                    }
                });
        });
    }
</script>
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

<script type="text/javascript">
    
</script>

