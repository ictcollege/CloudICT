<div id="page-wrapper">
    <?php if(isset($msg)){ echo $msg;}?>
    <?php echo $this->session->flashdata('alert');?>
    <form id="fileupload" action="" method="POST" enctype="multipart/form-data">
    <div class="row">
        <div class="col-lg-12">
                <noscript><input type="hidden" name="redirect" value=""></noscript>
                <!-- The fileupload-buttonbar contains buttons to add/delete files and start/cancel the upload -->
                    <div class="row fileupload-buttonbar">
                        <div class="col-lg-7">
                        <ol class="breadcrumb">
                          <li><a href="<?php echo base_url();?>Files/"><i class="fa fa-home fa-2x homeicon"></i><i class="fa fa-angle-right fa-2x separatoricon"></i> </a></li>
                          <?php
                          if(isset($breadcrumbs)){
                              $end = count($breadcrumbs);
                              $i =0;
                              $delimiter = '/';
                              
                              $link = base_url().'Files/index';
                              foreach($breadcrumbs as $bread){
                                  $li = "<li><a href='";
                                  $link.=$delimiter.$bread;
                                  $li.=$link;
                                  $li.="'>".$bread."</a></li>";
                                  echo $li;
                              }
                              
                              
                          }
                          
                          ?>
                        </ol>
                        </div>
                        <div class="col-lg-7">
                            <!-- The fileinput-button span is used to style the file input field as button -->
                            <span class="btn btn-default routebutton fileinput-button">
                                <i class="fa fa-upload  fa-1x"></i>
                                <input type="file" name="files[]" multiple>
                            </span>
                            <button type="submit" class="btn btn-primary start routebutton">
                                <i class="glyphicon glyphicon-upload"></i>
                                <span>Start upload</span>
                            </button>
                            <button type="reset" class="btn btn-warning cancel routebutton">
                                <i class="glyphicon glyphicon-ban-circle"></i>
                                <span>Cancel upload</span>
                            </button>
                            <button type="button" class="btn btn-danger deleteAll routebutton">
                                <i class="glyphicon glyphicon-trash"></i>
                                <span>Delete</span>
                            </button>
                            <input type="checkbox" class="toggle">
                            <div class="btn-group">
                                <button type="button" class="btn btn-default routebutton dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    New <span class="caret"></span>
                                </button>
                                <ul class="dropdown-menu">
                                    <li><a href="#" id="newFolder">Folder</a></li>
                                    <li><a href="#" id="newFile">File</a></li>
                                </ul>
                            </div> 
                            <!-- The global file processing state -->
                            <span class="fileupload-process"></span>
                            <br/>
                        </div>
                        <!-- The global progress state -->
                        <div class="col-lg-5 fileupload-progress fade">
                            <!-- The global progress bar -->
                            <div class="progress progress-striped active" role="progressbar" aria-valuemin="0" aria-valuemax="100">
                                <div class="progress-bar progress-bar-success" style="width:0%;"></div>
                            </div>
                            <!-- The extended global progress state -->
                            <div class="progress-extended">&nbsp;</div>
                        </div>
                        <div class="col-lg-12 text-danger" id="errorMsg">
                            
                        </div>
                    </div>
        </div>
        <div class="clearfix"></div>
        <div class="col-lg-12">
            <table class="table table-striped table-hover" id="myTable">
                <thead>
                    <th>Preview</th>
                    <th></th>
                    <th>Name</th>
                    <th>Manage</th>
                    <th>Size</th>
                    <th>Modified</th>
                </thead>
                <tbody class="files">

                </tbody>
            </table>
            
            <input type="hidden" id="current_dir" name="current_dir" value="<?php echo $current_dir;?>"/>
            <input type="hidden" id="current_path" name="current_path" value="<?php echo $current_path;?>"/>
        </div>                                  
    </div>
    </form>
    
    <div class="row">
        <div class="col-lg-6">
            <p class="text-success text-uppercase">Quota info</p>
            <p class="text-info">Disk Quota:<?php echo $diskquota;?></p>
            <p class="text-info">Disk Used:<?php echo $diskused;?></p>
            <p class="text-warning">Disk Remain:<?php echo $diskremain;?></p>
            
            <div class="progress">
                <div class="progress-bar" role="progressbar" aria-valuenow="<?php echo $percentage;?>" aria-valuemin="0" aria-valuemax="100" style="width: <?php echo $percentage;?>%;">
                  <?php echo $percentage;?>%
                </div>
            </div>
        </div>
    </div>
<!-- /.modal -->
<!-- Modal -->
<div class="modal fade in" id="ShareModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="false">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
                <h4 class="modal-title" id="myModalLabel">Share</h4>

            </div>
            <div class="modal-body">
                <h4>Share file by link</h4>
                <form id="formSharedByLink">
                <div class="input-group">
                     <span class="input-group-addon">
                        <input type="checkbox" name="sharedByLink" id="sharedByLink">
                      </span>
                    <input type="text" class="form-control" id="directLink" readonly="">
                </div>
                </form>
                <form method="post" id="shareForm" action="<?php echo base_url();?>ApiFiles/shareFilesFolders">
                <div class="btn-group" data-toggle="buttons">
                    <label class="btn active">
                        <input type="radio" value="1" name="SharePrivilege"  autocomplete="off" checked> Can read
                    </label>
                    <label class="btn">
                        <input type="radio" value="2" name="SharePrivilege"  autocomplete="off"> Can edit
                    </label>
                    <label class="btn">
                        <input type="radio" value="3" name="SharePrivilege"  autocomplete="off"> Can Delete
                    </label>
                </div>
                <div class="panel-group" id="accordion">
                    <input type="hidden" id='inputIdToShare' name="inputIdToShare"/>
                    <input type='hidden' id='inputTypeToShare' name="inputTypeToShare"/>
                    <?php
                    $i = 0;
                    foreach ($user_groups as $group) {
                        ?>
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <h4 class="panel-title">
                                    <?php if ($group->UserGroupStatusAdmin == 1) { ?>
                                        <input type="checkbox" class="chbGroupShare" data-no='<?php echo $i; ?>' value="<?php echo $group->IdGroup ?>">
    <?php } ?>
                                    <a data-toggle="collapse" data-parent="#accordion" href="#collapse<?php echo $i; ?>" aria-expanded="false" class="collapsed"><?php echo $group->GroupName; ?></a>

                                </h4>
                            </div>
                            <div id="collapse<?php echo $i; ?>" class="panel-collapse collapse" aria-expanded="false" style="display: block; height: 0px;">
                                <div class="panel-body">
                                    <ul class="list-inline">
                                        <?php
                                        foreach ($group->Users as $members) {
                                            if ($this->session->userdata('userid') != $members['IdUser']) {
                                                ?>
<!--                                                <li><input type="checkbox" class="chbUserGroup" name="input_chb_<?php echo $i; ?>[]" value="<?php echo $members['IdUser']; ?>" onclick=""><?php echo $members["UserName"]; ?> </li>-->
                                        <li><input type="checkbox" class="chbUserGroup chbUserGroupId_<?php echo $i;?>" name="chbUsers[]" value="<?php echo $members["IdUser"];?>"><?php echo $members["UserName"];?></li>
                                                <?php
                                            }
                                        }
                                        ?>
                                    </ul>
                                </div>
                            </div>
                        </div> 
                        <?php
                        $i++;
                    }
                    ?>
                    <input type="submit" name="btnShare" class="btn btn-primary" value="Share">
                </div>
                </form>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>   
<!--Move modal-->
<div class="modal fade in" id="MoveModal" tabindex="-1" role="dialog"  aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
                <h4 class="modal-title" id="myModalLabel">Move</h4>
                <input type="hidden" id="inputIdToMove">
                <input type="hidden" id="inputTypeToMove">
                <p class="help-block">Choose some folders from list</p>

            </div>
            <div class="modal-body">
                <div class="btn-group-vertical" data-toggle="buttons" id="folderList">

                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" id="btnMove">Move</button>                                </div>
        </div>
    </div>
</div>   
</div><!-- /#page-wrapper -->
<div class="clearfix"></div>
<!-- The template to display files available for upload -->
<script id="template-upload" type="text/x-tmpl">
{% for (var i=0, file; file=o.files[i]; i++) { %}
    <tr class="template-upload fade">
        <td>
            <input name="Mask[]" type="hidden" value="<?php echo $current_path;?>"/>
            <input type="hidden" name="IdFolder[]" value="<?php echo $current_dir;?>"/>
            <span class="preview"></span>
        </td>
        <td>
            <p class="name">{%=file.name%}</p>
            <strong class="error text-danger"></strong>
        </td>
        <td colspan="2">
            <p class="size">Processing...</p>
            <div class="progress progress-striped active" role="progressbar" aria-valuemin="0" aria-valuemax="100" aria-valuenow="0"><div class="progress-bar progress-bar-success" style="width:0%;"></div></div>
        </td>
        <td colspan="2">
            {% if (!i && !o.options.autoUpload) { %}
                <button class="btn btn-primary start" disabled>
                    <i class="glyphicon glyphicon-upload"></i>
                    <span>Start</span>
                </button>
            {% } %}
            {% if (!i) { %}
                <button class="btn btn-warning cancel">
                    <i class="glyphicon glyphicon-ban-circle"></i>
                    <span>Cancel</span>
                </button>
            {% } %}
        </td>
    </tr>
{% } %}
</script>
<!-- The template to display files available for download -->
<script id="template-download" type="text/x-tmpl">
{% for (var i=0, file; file=o.files[i]; i++) { %}
    <tr class="template-download fade">
        {% if (file.error) { %}
                <td colspan="6">
                <div><span class="label label-danger">Error</span> {%=file.error%}</div>
                </td>
        {% }else{ reload();  } %}           
    </p>
    
{% } %}
</script>
<div class="clearfix"></div>
<!-- The blueimp Gallery widget -->
<div id="blueimp-gallery" class="blueimp-gallery blueimp-gallery-controls">
    <div class="slides"></div>
    <h3 class="title"></h3>
    <a class="prev">‹</a>
    <a class="next">›</a>
    <a class="close">×</a>
    <a class="play-pause"></a>
    <ol class="indicator"></ol>
</div>
<script src="<?php echo base_url();?>public/js/vendor/jquery.ui.widget.js"></script>
<!-- The Templates plugin is included to render the upload/download listings -->
<script src="<?php echo base_url();?>public/js/tmpl.min.js"></script>
<!-- The Load Image plugin is included for the preview images and image resizing functionality -->
<script src="<?php echo base_url();?>public/js/load-image.all.min.js"></script>
<!-- The Canvas to Blob plugin is included for image resizing functionality -->
<script src="<?php echo base_url();?>public/js/canvas-to-blob.min.js"></script>
<!-- Bootstrap JS is not required, but included for the responsive demo navigation -->
<!--<script src="//netdna.bootstrapcdn.com/bootstrap/3.2.0/js/bootstrap.min.js"></script>-->
<!-- blueimp Gallery script -->
<script src="<?php echo base_url();?>public/js/jquery.blueimp-gallery.min.js"></script>
<!-- The Iframe Transport is required for browsers without support for XHR file uploads -->
<script src="<?php echo base_url();?>public/js/jquery.iframe-transport.js"></script>
<!-- The basic File Upload plugin -->
<script src="<?php echo base_url();?>public/js/jquery.fileupload.js"></script>
<!-- The File Upload processing plugin -->
<script src="<?php echo base_url();?>public/js/jquery.fileupload-process.js"></script>
<!-- The File Upload image preview & resize plugin -->
<script src="<?php echo base_url();?>public/js/jquery.fileupload-image.js"></script>
<!-- The File Upload audio preview plugin -->
<script src="<?php echo base_url();?>public/js/jquery.fileupload-audio.js"></script>
<!-- The File Upload video preview plugin -->
<script src="<?php echo base_url();?>public/js/jquery.fileupload-video.js"></script>
<!-- The File Upload validation plugin -->
<script src="<?php echo base_url();?>public/js/jquery.fileupload-validate.js"></script>
<!-- The File Upload user interface plugin -->
<script src="<?php echo base_url();?>public/js/jquery.fileupload-ui.js"></script>
<!-- The main application script -->
<script src="<?php echo base_url();?>public/js/main.js"></script>
<!-- The XDomainRequest Transport is included for cross-domain file deletion for IE 8 and IE 9 -->
<!--[if (gte IE 8)&(lt IE 10)]>
<script src="<?php echo base_url();?>public/js/cors/jquery.xdr-transport.js"></script>
<![endif]-->
<script src="<?php echo base_url();?>public/js/bootstrap-switch.min.js"></script>
<script type="text/javascript" src="<?php echo base_url();?>public/js/jquery.dataTables.min.js"></script>
<script type="text/javascript" src="<?php echo base_url();?>public/js/dataTables.bootstrap.js"></script>
<script type="text/javascript" src="<?php echo base_url();?>public/js/filesScript.js"></script>
<script type="text/javascript">
var options = {
            apiurl:"<?php echo base_url();?>ApiFiles/",
            scripturl:"<?php echo base_url();?>Files/",
            apifunc:"",
            current_dir:$("#current_dir").val(),
            current_path:$("#current_path").val(),
            datatable:"#myTable",
            form:"#fileupload",
            maxChunkSize:2000000,
            datasrc:"files",
            formData:{
                IdFolder:current_dir,
                Mask:current_path,
                Shared:0
            },
            table:undefined
            
};
var filesScript = $(document).filesScript(options);
filesScript.base_url("<?php echo base_url();?>");
function reload(){
    filesScript._reload();
}
$(document).ready(function (){
    filesScript._init();
});
</script>
