<div id="page-wrapper">
    <?php if(isset($msg)){ echo $msg;}?>

    <form id="fileupload" action="" method="POST" enctype="multipart/form-data">
    <div class="row">
        <div class="col-lg-12">
            <noscript><input type="hidden" name="redirect" value=""></noscript>
            <div class="row fileupload-buttonbar">
                <div class="col-lg-7">
                    <ol class="breadcrumb">
                        <li><a href="<?php echo base_url(); ?>Files/shared_with_you"><i class="fa fa-share-alt fa-2x homeicon"></i><i class="fa fa-angle-right fa-2x separatoricon"></i> </a></li>
                        <?php
                        if (isset($breadcrumbs)) {
                            $end = count($breadcrumbs);
                            $i = 0;
                            $delimiter = '/';

                            $link = base_url() . 'Files/shared_with_you';
                            foreach ($breadcrumbs as $bread) {
                                $li = "<li><a href='";
                                $link.=$delimiter . $bread;
                                $li.=$link;
                                $li.="'>" . $bread . "</a></li>";
                                echo $li;
                            }
                        }
                        ?>
                    </ol>
                </div>
                <?php if($can_upload) :?>
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
<!--                    <button type="button" class="btn btn-danger deleteAll routebutton">
                        <i class="glyphicon glyphicon-trash"></i>
                        <span>Delete</span>
                    </button>-->
<!--                    <input type="checkbox" class="toggle">-->
                    <!--nije implementirano-->
<!--                    <div class="btn-group">
                        <button type="button" class="btn btn-default routebutton dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            New <span class="caret"></span>
                        </button>
                        <ul class="dropdown-menu">
                            <li><a href="#" id="newFolder">Folder</a></li>
                            <li><a href="#" id="newFile">File</a></li>
                        </ul>
                    </div> -->
                    <div>
                        <p class="text-info">You can upload maximum up to:<?php echo $max_upload_size;?></p>
                        <p>All files will be stored to owner directory,</br> and shared only with you and with privilege to delete them.</p>
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
                
                <?php  endif; ?>
                <div class="col-lg-12 text-danger" id="errorMsg">

                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-12">
            <table class="table table-striped table-hover" id="myTable">
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
                <tbody class="files">

                </tbody>
            </table>
        </div>
        <input type="hidden" id="current_dir" value="<?php echo $current_dir; ?>"/>
    </div>
    </form>
       
</div><!-- /#page-wrapper -->
<div class="clearfix"></div>
<!-- The template to display files available for upload -->
<script id="template-upload" type="text/x-tmpl">
{% for (var i=0, file; file=o.files[i]; i++) { %}
    <tr class="template-upload fade">
        <td>
            <input type="hidden" name="IdFolder[]" value="<?php echo $current_dir;?>"/>
            <input type="hidden" name="Shared[]" value="<?php echo $shared_dir;?>"/>
            <span class="preview"></span>
        </td>
        <td>
            <p class="name">{%=file.name%}</p>
            <strong class="error text-danger"></strong>
        </td>
        <td colspan="3">
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
                <td colspan="7">
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
            apifunc:"sharedWithYou/",
            current_dir:$("#current_dir").val(),
            current_path:"",
            test:"",
            datatable:"#myTable",
            form:"#fileupload",
            maxChunkSize:2000000,
            datasrc:"files",
            maxFileSize:
            <?php 
            if(isset($maxFileSize)):
                echo $maxFileSize.",";
            else:
                echo "undefined,";
            endif;
            ?>
            formData:{
                IdFolder:current_dir,
                Mask:"",
                Shared:<?php echo $shared_dir;?>
            }
            
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
