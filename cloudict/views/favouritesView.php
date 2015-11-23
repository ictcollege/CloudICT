<script type="text/javascript" src="<?php echo base_url();?>public/js/jquery.dataTables.min.js"></script>
<script type="text/javascript" src="<?php echo base_url();?>public/js/dataTables.bootstrap.js"></script>
<script type="text/javascript">
    $(document).ready(function (){
        $("#myTable").DataTable({
            "ajax":"<?php echo base_url();?>share/?action=sharedWithYou"
        });
        
        $(document).ajaxComplete(function (){
            $('.viewFolder').click(function (e){
                e.preventDefault();
                var idfile = $(this).data('idfile');
                alert(idfile);
            });
            $('.unshare').click(function (e){
                e.preventDefault();
                var control = $(this);
                var tr = $(this).parent('td').parent('tr');
                var Share = new Object();
                Share.users = [];
                Share.IdFile = $(this).data('idfile');
                Share.unshare=[];

                Share.unshare.push("<?php echo $this->session->userdata('userid');?>");
                var json = JSON.stringify(Share);
                $.ajax({
                    url: "<?php echo base_url();?>Share/shareFile",
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
                       tr.remove();
                    }


                });
            });
            
        });
    });
</script>
<div id="page-wrapper">
    
    <div class="row">
        <div class="col-lg-12">
            <?php if(isset($msg)){ echo $msg;}?>
                        <div class="col-lg-7">
                        <ol class="breadcrumb">
                            <li><a href="<?php echo base_url();?>Files/favourites"><i class="fa fa-star-o fa-2x homeicon">&nbsp;</i><i class="fa fa-angle-right fa-2x separatoricon"></i> </a></li>
                          
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

<script type="text/javascript">
    
</script>