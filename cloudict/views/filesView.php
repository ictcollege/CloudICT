<!--

          
        <div class="row placeholders">
            <div class="form-group-sm">
                <button type="button" id="inputButton" class="btn btn-default"><i class="glyphicon glyphicon-plus"></i>Add files</button>
                <input type="file" name="files[]" id="inputFile" style="display: none;" multiple>
                <button type="submit" id="upload-button" class="btn btn-primary"><i class="glyphicon glyphicon-upload"></i>Upload files</button>
            </div>
            <div class="form-group-sm">

            </div>
        </div>
          <div class="table-responsive">
            <table class="table table-striped" id="filetable">
              <thead>
                <tr>
                  <th></th>
                  <th>File name</th>
                  <th></th>
                  <th>Size</th>
                  <th>Modified</th>
                </tr>
              </thead>
              <tbody>
              
                <tr>
                    
                </tr>
                <tr>
                    
                </tr>
                
              </tbody>
            </table>
          </div>
           -->
       



<div id="page-wrapper">
            <div class="row">
                <div class="col-lg-12">
                    <div class="route pull-left">
                    <form id="form-upload" action="<?php echo base_url() . 'Files/'; ?>" method="POST" enctype="multipart/form-data">   
                    <i class="fa fa-home fa-2x homeicon"></i>
                    <i class="fa fa-angle-right fa-2x separatoricon"></i>

                    <button type="button" class="btn btn-default routebutton" id="inputButton"><i class="fa fa-upload  fa-1x"></i></button>
                    <input type="file" name="files[]" id="inputFile" style="display: none;" multiple>
                    <input type="hidden" id="current_dir" name="current_dir" value="0"/>
                    <div class="btn-group">
                      <button type="button" class="btn btn-default routebutton dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        New <span class="caret"></span>
                      </button>
                      <ul class="dropdown-menu">
                          <li><a href="#" id="newFolder">Folder</a></li>
                        <li><a href="#">File</a></li>
                      </ul>
                    </div>
                    <button type="submit" id="upload-button" class="btn btn-primary routebutton"><i class="glyphicon glyphicon-upload"></i>Upload files</button>
                    </form>    
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h4><i class="fa fa-files-o fa-fw"></i> All files</h4>
                    </div>
                    <div class="panel-body">
                         <div class="col-lg-12 table-responsive">
                    <table class="table table-striped tablefiles table-hover" id="file-table">
                        <thead>
                            <th></th>
                            <th><input type="checkbox" value=""></th>
                            <th>Name</th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th>Size</th>
                            <th>Modified</th>
                        </thead>
                        <tbody>
                            <tr>
                                <td style="width:50px" align="left"><i class="fa  fa-star-o fa-fw fileiconhide"></td>
                                <td style="width:50px"></td>
                                <td style="width:50px" class="filename">Test.txt</td>
                                <td style="width:50px" align="left"><i class="fa  fa-pencil fa-fw fileiconhide"></i></td>
                                <td style="width:50px"><i class="fa  fa-cloud-download fa-fw fileiconhide"></i></td>
                                <td style="width:50px"><i class="fa  fa-share-alt fa-fw fileiconhide"></i></td>
                                <td style="width:50px"><i class="fa  fa-trash-o fa-fw fileiconhide"></i></td>
                                <td style="width:50px">8 kB</td>
                                <td style="width:50px">last year</td>
                            <tr>
                        </tbody>
                    </table>
                </div>
                    </div>
                </div>
               
            </div>
                    <div class="row">
            <div class="col-lg-12">
<p class="footer">Page rendered in <strong>{elapsed_time}</strong> seconds. <?php echo  (ENVIRONMENT === 'development') ?  'CodeIgniter Version <strong>' . CI_VERSION . '</strong>' : '' ?></p>
            </div>
        </div>
            
        </div>
        <!-- /#page-wrapper -->

    </div>
    
    <?php
     $this->load->view('scripts.php');
    ?>
    <script type="text/javascript">
        //set user current_dir
        function setCurrentDir(){
            
        }
        //get user current_dir
        function getCurrentDir(){
            
        }
        //FILEUPLOAD JS
//(function (e){
//            $("#inputButton").on("click",function (){
//                $("#inputFile").click();
//            });
//            
//            allFilesUrl = "<?php echo base_url();?>Files/?all_files";
//            $.getJSON( allFilesUrl, function( data ) {
//            var items = [];
//            $.each( data, function( key, val ) {
//              items.push("<tr>");
//              items.push("<td style='width:50px' align='left'><i class='fa  fa-star-o fa-fw fileiconhide'></td>");
//              items.push("<td style='width:50px'></td>");
//              items.push("<td style='width:50px' class='filename'>"+val.FileName+"</td>");
//              items.push("<td style='width:50px' align='left'><i class='fa  fa-pencil fa-fw fileiconhide'></i></td>");
//              items.push("<td style='width:50px'><i class='fa  fa-cloud-download fa-fw fileiconhide'></i></td>");
//              items.push(' <td style="width:50px"><i class="fa  fa-share-alt fa-fw fileiconhide"></i></td>');
//              items.push(' <td style="width:50px"><i class="fa  fa-trash-o fa-fw fileiconhide"></i></td>');
//              items.push('<td style="width:50px">'+Math.round(val.FileSize/1024)+'KB</td>');
//              items.push('<td style="width:50px">'+val.FileLastModified+'</td>' );
//              items.push("</tr>");
//            });
//
//            $(items.join("")).appendTo("#file-table");
//            
//          });
//           
//        })();

        

        $(document).ready(function (e){
            
//            $("#inputButton").click(function (){
//                $("#inputFile").click();
//            });
        });
        
    </script>
</body>

</html>

