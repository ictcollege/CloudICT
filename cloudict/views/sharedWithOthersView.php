<script type="text/javascript" src="<?php echo base_url();?>public/js/jquery.dataTables.min.js"></script>
<script type="text/javascript" src="<?php echo base_url();?>public/js/dataTables.bootstrap.js"></script>
<script type="text/javascript">
    var table;
    var IdFolder = $("#current_dir").val();
    function myFunction(){
        IdFolder = $("#current_dir").val();
        table.ajax.reload( 
                function (){
                    attachListeners();
                }
                ); // user paging is not reset on reload
    }
    $(document).ready(function (){
        table=$("#myTable").DataTable({
            "ajax": {
                "url":"<?php echo base_url();?>ApiFiles/sharedWithOthers/",
                "data": {
                    "id_folder": $("#current_dir").val(),
                    "id_shared": $("#id_shared").val()
                }
              },
            "fnInitComplete": function(oSettings){
                attachListeners();
            }
        });
        

    });
    function attachListeners(){
        $('.unshare').bind('click',function (e){
            e.preventDefault();
            var Unshare=new Object();
            Unshare.Id=$(this).data('id'); //id file folder
            Unshare.Type=$(this).data('type'); //type folder or file
            Unshare.IdShared = $(this).data('idshared'); //shareduser
            Unshare.IdShare = $(this).data('idshare'); // id share
            var json = JSON.stringify(Unshare);
            var control = $(this);
            $.ajax({
                    url: "<?php echo base_url();?>ApiFiles/unshareFilesFolders",
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
                            <li><a href="<?php echo base_url();?>Files/shared_with_others"><i class="fa fa-share-alt fa-2x homeicon">&nbsp;</i><i class="fa fa-angle-right fa-2x separatoricon"></i> </a></li>
                          
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
                                        <th>Shared User</th>
                                        <th>Shared on</th>
                                        <th>File name</th>
                                        <th>Privilege</th>
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
        <input type="hidden" id="current_dir" value="<?php echo $current_dir;?>"/>    
        <input type="hidden" id="id_shared" value="<?php echo $id_shared;?>"/>          
            </div>
       
</div><!-- /#page-wrapper -->
<div class="clearfix"></div>

<script type="text/javascript">
    
</script>

