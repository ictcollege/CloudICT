<<<<<<< HEAD

<div id="page-wrapper">
    <div class="row">
        <div class="col-lg-12">
            <form id="fileupload" action="//jquery-file-upload.appspot.com/" method="POST" enctype="multipart/form-data">
                <!-- Redirect browsers with JavaScript disabled to the origin page -->
                <noscript><input type="hidden" name="redirect" value="https://blueimp.github.io/jQuery-File-Upload/"></noscript>
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
                            <button type="button" class="btn btn-danger delete routebutton">
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
                            <span id="errorMsg" class="text-danger"></span>
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
                    </div>
                    
                        <!-- The table listing the files available for upload/download -->
                        <table id="dropzone" role="presentation" class="table table-striped">
                            <thead>
                                <th>Preview</th>
                                <th></th>
                                <th>Name</th>
                                <th>Manage</th>
                                <th>Size</th>
                                <th>Modified</th>
                                
                            </thead>
                            <tbody class="files"></tbody>
                        </table>
                        
                        <input type="hidden" id="current_dir" name="current_dir" value="<?php echo $current_dir;?>"/>
                        <input type="hidden" id="current_path" name="current_path" value="<?php echo $current_path;?>"/>
                </form>
                    
            </div>
        </div>


<!-- Modal -->
<div class="modal fade in" id="ShareModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="false">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
                        <h4 class="modal-title" id="myModalLabel">Share</h4>
                        
                    </div>
                    <div class="modal-body">
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
                            <input type="hidden" id='inputIdFileShare' />
                            <input type='hidden' id='inputFileTypeMimeShare' />
                            <?php
                            $i = 0;
                            foreach($user_groups as $group){ ?>
                                <div class="panel panel-default">
                                    <div class="panel-heading">
                                        <h4 class="panel-title">
                                            <?php if($group->UserGroupStatusAdmin==1){?>
                                            <input type="checkbox" class="chbGroupShare" data-no='<?= $i;?>' value="<?php echo $group->IdGroup ?>">
                                            <?php }?>
                                            <a data-toggle="collapse" data-parent="#accordion" href="#collapse<?=$i;?>" aria-expanded="false" class="collapsed"><?php echo $group->GroupName ;?></a>
                                            
                                        </h4>
                                    </div>
                                    <div id="collapse<?=$i;?>" class="panel-collapse collapse" aria-expanded="false" style="display: block; height: 0px;">
                                        <div class="panel-body">
                                            <ul class="list-inline">
                                                <?php 
                                                foreach ($group->Users as $members){
                                                    if($this->session->userdata('userid')!=$members['IdUser']){
                                                ?>
                                                <li><input type="checkbox" class="chbUserGroup" name="input_chb_<?= $i; ?>[]" value="<?php echo $members['IdUser'];?>" onclick=""><?php echo $members["UserName"];?> </li>
                                                <?php 
                                                    }
                                                }?>
                                            </ul>
                                        </div>
                                    </div>
                                </div> 
                            <?php 
                            $i++;
                            }
                            ?>

                            </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-primary" id="btnShareFile">Share</button>                    </div>
                </div>
            </div>
        </div>
</div><!-- /#page-wrapper -->
<div class="clearfix"></div>
    <!-- The blueimp Gallery widget -->
<div id="blueimp-gallery" class="blueimp-gallery blueimp-gallery-controls" data-filter=":even">
    <div class="slides"></div>
    <h3 class="title"></h3>
    <a class="prev">‹</a>
    <a class="next">›</a>
    <a class="close">×</a>
    <a class="play-pause"></a>
    <ol class="indicator"></ol>
</div>

<script src="//code.jquery.com/jquery-1.11.3.min.js"></script>    
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
        <td>
            <p class="size">Processing...</p>
            <div class="progress progress-striped active" role="progressbar" aria-valuemin="0" aria-valuemax="100" aria-valuenow="0"><div class="progress-bar progress-bar-success" style="width:0%;"></div></div>
        </td>
        <td>
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
        <td>
            <span class="preview">
                {% if (file.thumbnailUrl) { %}
                    <a href="{%=file.url%}" title="{%=file.name%}" download="{%=file.name%}" data-gallery><img src="{%=file.thumbnailUrl%}"></a>
                {% } %}
            </span>
        </td>
<td>
            {% if (file.deleteUrl) { %}
                <input type="checkbox" name="delete" value="1" class="toggle">
            {% } else { %}
                <button class="btn btn-warning cancel">
                    <i class="glyphicon glyphicon-ban-circle"></i>
                    <span>Cancel</span>
                </button>
            {% } %}
        </td>
        <td>
            <p class="name">
                {% if(file.FileTypeMime=="DIR"){ %}
                    <a href="<?php echo base_url();?>files/index/{%=file.Mask+file.name%}">{%=file.name%}</a>
                {% } else if (file.url) { %}
                    <a href="{%=file.url%}" title="{%=file.name%}" download="{%=file.name%}" {%=file.thumbnailUrl?'data-gallery':''%}>{%=file.name%}</a>
                {% } else { %}
                    <span>{%=file.name%}</span>
                {% } %}
            </p>
            {% if (file.error) { %}
                <div><span class="label label-danger">Error</span> {%=file.error%}</div>
            {% } %}
        </td>
        <td>
          <div class="btn-group">
          <a href="#" data-toggle="dropdown" title="edit/rename"><i class="fa  fa-pencil fa-fw"></i></a>
          <ul class="dropdown-menu">
            <li><a href="#" data-idfile="{%=file.IdFile%}" data-filetypemime="{%=file.FileTypeMime%}" class="rename">Rename</a></li>
            <li><a href="#" data-idfile="{%=file.IdFile%}" data-filetypemime="{%=file.FileTypeMime%}" class="move">Move</a></li>
            {% if(file.FileTypeMime!="DIR"){ %}
            <li><a href="#" data-idfile="{%=file.IdFile%}" data-filetypemime="{%=file.FileTypeMime%}" class="edit">Edit</a></li>
            {% } %}
          </ul>
          {% if (file.deleteUrl) { %}
                <a href="#" class="delete" data-IdFile="{%=file.IdFile%}" data-type="{%=file.deleteType%}" data-url="{%=file.deleteUrl%}"{% if (file.deleteWithCredentials) { %} data-xhr-fields='{"withCredentials":true}'{% } %}>
                    <i class="glyphicon glyphicon-trash"></i>
                </a>
            {% } %}

          <a href="{%=file.url%}" class="download" data-idfile="{%=file.IdFile%}"  title="Download {%=file.name%}"><i class="fa  fa-cloud-download fa-fw"></i></a>
          
          <a href="#" class="share" data-toggle="modal" data-target="#ShareModal" data-idfile="{%=file.IdFile%}" data-filetypemime="{%=file.FileTypeMime%}" title="share" onclick="openModal(this)"><i class="fa  fa-share-alt fa-fw"></i></a>
          </div>
        </td>
        <td>
          {% if (file.FileTypeMime=="DIR") { %}
            <span class="size"><i class="fa fa-folder-open fa-fw"></i></span>
            {% } else { %}
           <span class="size">{%=o.formatFileSize(file.size)%}</span>
           {% } %}
        </td>
        <td>
            <p class="FileLastModified">{%=timeConverter(file.FileLastModified)||''%}</p>

        </td>
        

    </tr>
{% } %}
</script>
<!--<script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>-->
<!-- The jQuery UI widget factory, can be omitted if jQuery UI is already included -->
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
<script type="text/javascript" charset="utf-8">
    $(document).bind('dragover', function (e) {
        var dropZone = $('#dropzone'),
            timeout = window.dropZoneTimeout;
        if (!timeout) {
            dropZone.addClass('in');
        } else {
            clearTimeout(timeout);
        }
        var found = false,
            node = e.target;
        do {
            if (node === dropZone[0]) {
                found = true;
                break;
            }
            node = node.parentNode;
        } while (node != null);
        if (found) {
            dropZone.addClass('hover');
        } else {
            dropZone.removeClass('hover');
        }
        window.dropZoneTimeout = setTimeout(function () {
            window.dropZoneTimeout = null;
            dropZone.removeClass('in hover');
        }, 100);
    });
    $(function () {
    'use strict';

    // Initialize the jQuery File Upload widget:
    $('#fileupload').fileupload({
        // Uncomment the following to send cross-domain cookies:
        //xhrFields: {withCredentials: true},
        url: '<?php echo base_url();?>CloudFiles/index/'+$("#current_path").val(),
        maxChunkSize: 4000000, // 4 MB,
        dropZone : $("#dropzone"),
        formData: {IdFolder: $("#current_dir").val(),Mask:$("#current_path").val()}
    });
    $('#fileupload').fileupload({
        url: '<?php echo base_url();?>CloudFiles/index/'+$("#current_path").val(),
        maxChunkSize: 4000000 // 4 MB
    }).on('fileuploadsubmit', function (e, data) {
        data.formData = data.context.find(':input').serializeArray();
    });
    // Enable iframe cross-domain access via redirect option:
    $('#fileupload').fileupload(
        'option',
        'redirect',
        window.location.href.replace(
            /\/[^\/]*$/,
            '/cors/result.html?%s'
        )
    );

    if (window.location.hostname === 'blueimp.github.io') {
        // Demo settings:
        $('#fileupload').fileupload('option', {
            url: '//jquery-file-upload.appspot.com/',
            // Enable image resizing, except for Android and Opera,
            // which actually support image resizing, but fail to
            // send Blob objects via XHR requests:
            disableImageResize: /Android(?!.*Chrome)|Opera/
                .test(window.navigator.userAgent),
            maxFileSize: 5000000,
            acceptFileTypes: /(\.|\/)(gif|jpe?g|png)$/i
        });
        // Upload server status check for browsers with CORS support:
        if ($.support.cors) {
            $.ajax({
                url: '//jquery-file-upload.appspot.com/',
                type: 'HEAD'
            }).fail(function () {
                $('<div class="alert alert-danger"/>')
                    .text('Upload server currently unavailable - ' +
                            new Date())
                    .appendTo('#fileupload');
            });
        }
    } else {
        // Load existing files:
        $('#fileupload').addClass('fileupload-processing');
        $.ajax({
            // Uncomment the following to send cross-domain cookies:
            //xhrFields: {withCredentials: true},
            url: $('#fileupload').fileupload('option', 'url'),
            dataType: 'json',
            context: $('#fileupload')[0]
        }).always(function () {
            $(this).removeClass('fileupload-processing');
        }).done(function (result) {
            $(this).fileupload('option', 'done')
                .call(this, $.Event('done'), {result: result});
        });
    }
    $(document).ready(function(){
        //kreiranje novog file-a
        //new file
        $("#newFile").click(function (e){
            e.preventDefault();
            var IdFolder = $("#current_dir").val();
            var Mask = $("#current_path").val();
            var file = prompt("File name");
            if(file.length>0){
                $("#errorMsg").text('');
                $.ajax({
			url: "<?php echo base_url();?>CloudFiles/",
                        data:{action:"newFile",Mask:Mask,File:file,IdFolder : IdFolder},
			success: function(data) {
                           window.location.reload();
			}
			
		});
            }
            else{
              $("#errorMsg").text("Please give file some name!");  
            }
        });
        
        //kreiranje foldera
        //new folder 
        $("#newFolder").click(function (e){
            e.preventDefault();
            var IdFolder = $("#current_dir").val();
            var Mask = $("#current_path").val();
            var folder_name = prompt("Folder name:");
            if(folder_name.length>0){
                $("#errorMsg").text('');
                $.ajax({
			url: "<?php echo base_url();?>CloudFiles/",
                        data:{action:"newFolder",Mask:Mask,folderName:folder_name,IdFolder : IdFolder},
			success: function(data) {
                           window.location.reload();
			}
			
		});
            }
            else{
              $("#errorMsg").text("Please give folder some name!");  
            }
        });
        
        $(".chbGroupShare").click(function() {
            var no = $(this).data("no");
            var checkBoxes = $("input[name=input_chb_"+no+"\\[\\]]");
                checkBoxes.prop("checked", !checkBoxes.prop("checked"));
        });                 
        
        $("#btnShareFile").click(function (e){
            var Share = new Object();
            Share.users = [];
            Share.IdFile = $("#inputIdFileShare").val();
            Share.SharePrivilege = $("input[type='radio'][name='SharePrivilege']:checked").val();
            Share.unshare=[];
            $("input:checkbox.chbUserGroup").each(function () {
               if(this.checked){
                   Share.users.push($(this).val());
               }
               else{
                   Share.unshare.push($(this).val());
               }
            });
            var json = JSON.stringify(Share);
            $.ajax({
                url: "<?php echo base_url();?>Share/shareFile",
                type: "POST",
                dataType: "json",
                data:{json:json},
                beforeSend: function (xhr) {
                    $("#btnShareFile").append('<i class="fa fa-spinner fa-spin"></i>');
                },
                success: function(data) {
                   if($(".fa-spin").length>0){
                       $(".fa-spin").remove();
                   }
                }
                        

            });
            
        });
        

        $(document).ajaxComplete(function (){
            //pencil click
            $(".edit").click(function (e){
                e.preventDefault();
                var IdFile = $(this).data("idfile");
                var IdFolder = $("#current_dir").val();
                var Mask = $("#current_path").val();
            });
            $(".rename").click(function (e){
                e.preventDefault();
                var IdFile = $(this).data("idfile");
                var newName = prompt("New name");
                var tdHref=$(this).parents('tr').find('p.name').find('a');
                if(newName!=null&&newName.length>0){
                $("#errorMsg").text('');
                $.ajax({
			url: "<?php echo base_url();?>CloudFiles/",
                        data:{action:"renameFile",IdFile:IdFile,newName:newName},
			success: function(data) {
                           if(data){
                               window.location.reload();
                           }
                           else{
                               alert("Error!");
                           }
			}

                    });
                }
                else{
                  $("#errorMsg").text("Please give folder some name!");  
                }
            });
            //click on move link
            $(".move").click(function (e){
                e.preventDefault();
                var IdFile = $(this).data("idfile");
            });
        });
    });

});

function shareWithUser(control){
        var IdUser = $(control).val();
        var chb = $(control);
        var IdFile = $("#inputIdFileShare").val();
        var SharePrivilege = $("input[type='radio'][name='SharePrivilege']:checked").val();
        if(chb.checked){
            share = 1;
        }
        else{
            share = 0;
        }
        $.ajax({
        url: "<?php echo base_url();?>Share/shareFile",
        type: "POST",
        data:{IdFile:IdFile,IdUser:IdUser,Share:share,SharePrivilege:SharePrivilege},
        beforeSend: function (xhr) {
            $(chb).next().append('<i class="fa fa-spinner fa-spin"></i>');
        },
        error: function (){alert("Error...")},
        success: function(data) {
           if($(".fa-spin").length>0){
               $(".fa-spin").remove();
           }
        }


    });
}

function openModal(control){
    var IdFile = $(control).data("idfile");
    var FileTypeMime = $(control).data("filetypemime");
    $("#inputIdFileShare").val(IdFile);
    $("#inputFileTypeMimeShare").val(FileTypeMime);
     $.ajax({
            url: "<?php echo base_url(); ?>Share/",
            data:{action:"checkFileShare",IdFile:IdFile},
            success: function(data) {
                $.each(data,function(index,val){

                    $("input:checkbox.chbUserGroup").each(function () {
                       if($(this).val()==val.IdUser){
                           $(this).prop('checked',true);
                       }
                    });
                });

            }

    });
 }

</script>
=======
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
                            <span id="errorMsg" class="text-danger"></span>
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
        {% }else{ myFunction();  } %}           
    </p>
    
{% } %}
</script>
<div class="clearfix"></div>
<!-- The blueimp Gallery widget -->
<div id="blueimp-gallery" class="blueimp-gallery blueimp-gallery-controls" data-filter=":even">
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

<script type="text/javascript">

    function checkIsValidFile(filename){
        if(filename==null||!filename.length>2){
            return false;
        }
        if(/^[a-zA-Z0-9-_]{2,}[\.]{1}[a-zA-Z]{1,}$/.test(filename)){
            return true;
        }

        return false;
    }
    $(function () {
    'use strict';

    // Initialize the jQuery File Upload widget:
    $('#fileupload').fileupload({
        // Uncomment the following to send cross-domain cookies:
        //xhrFields: {withCredentials: true},
        url: '<?php echo base_url();?>ApiFiles/',
        maxChunkSize: 2000000, // 2 MB,
        dropZone : $("#myTable"),
        formData: {IdFolder: $("#current_dir").val(),Mask:$("#current_path").val()}
    });
    $('#fileupload').fileupload({
        url: '<?php echo base_url();?>ApiFiles/',
        maxChunkSize: 2000000 // 2 MB
    }).on('fileuploadsubmit', function (e, data) {
        data.formData = data.context.find(':input').serializeArray();
    });
    // Enable iframe cross-domain access via redirect option:
    $('#fileupload').fileupload(
        'option',
        'redirect',
        window.location.href.replace(
            /\/[^\/]*$/,
            '/cors/result.html?%s'
        )
    );

    if (window.location.hostname === 'blueimp.github.io') {
        // Demo settings:
        $('#fileupload').fileupload('option', {
            url: '//jquery-file-upload.appspot.com/',
            // Enable image resizing, except for Android and Opera,
            // which actually support image resizing, but fail to
            // send Blob objects via XHR requests:
            disableImageResize: /Android(?!.*Chrome)|Opera/
                .test(window.navigator.userAgent),
            maxFileSize: 5000000,
            acceptFileTypes: /(\.|\/)(gif|jpe?g|png)$/i
        });
        // Upload server status check for browsers with CORS support:
        if ($.support.cors) {
            $.ajax({
                url: '//jquery-file-upload.appspot.com/',
                type: 'HEAD'
            }).fail(function () {
                $('<div class="alert alert-danger"/>')
                    .text('Upload server currently unavailable - ' +
                            new Date())
                    .appendTo('#fileupload');
            });
        }
    } else {
        // Load existing files:
        $('#fileupload').addClass('fileupload-processing');
        $.ajax({
            // Uncomment the following to send cross-domain cookies:
            //xhrFields: {withCredentials: true},
            url: $('#fileupload').fileupload('option', 'url'),
            dataType: 'json',
            context: $('#fileupload')[0]
        }).always(function () {
            $(this).removeClass('fileupload-processing');
        }).done(function (result) {
            $(this).fileupload('option', 'done')
                .call(this, $.Event('done'), {result: result});
                
        });
    }
 

});



</script>
<script type="text/javascript">
    var table;
    var IdFolder = $("#current_dir").val();
    var Mask = $("#current_path").val();
    function myFunction(){
        IdFolder = $("#current_dir").val();
        Mask = $("#current_path").val();
        table.ajax.reload( 
                function (){
                    attachListeners();
                }
                ); // user paging is not reset on reload
    }
    
    $(document).ready(function (){
        table = $("#myTable").DataTable({
            "ajax": {
                "url":"<?php echo base_url();?>ApiFiles/",
                "dataSrc":"files",
                "data": {
                    "id_folder": $("#current_dir").val(),
                    "Mask":$("#current_path").val()
                }
              },
            "fnInitComplete": function(oSettings){
                attachListeners();
            },
            "responsive":true,
            "scrollX":true,
            "scroller":true,
            "deferRender" : true
        });
//        $(".deleteLink").bind("click",function (e){
//            e.preventDefault();
//            var id = $(this).data('id');
//            var type = $(this).data('type');
//            deleteFileFolder(id,type);
//        });
        $(".deleteAll").click(function (){
            deleteFromCheckBox();
        });
        $("input:checkbox.chbUserGroup").change(function (){
            var control = $(this);
            $("input:checkbox.chbUserGroup").each(function () {
               if($(this).val()==$(control).val()){
                   if($(control).is(':checked')){
                       this.checked = true;
                   }
                   else{
                       this.checked = false;
                   }
               }
            });
        });
//        $("#btnShare").click(function (e){
//            var Share = new Object();
//            Share.users = [];
//            Share.Id = $("#inputIdToShare").val();
//            Share.Type = $("#inputTypeToShare").val();
//            Share.SharePrivilege = $("input[type='radio'][name='SharePrivilege']:checked").val();
//            Share.unshare=[];
//            
//            $("input:checkbox.chbUserGroup").each(function () {
//               if(this.checked){
//                   Share.users.push($(this).val());
//               }
//               else{
//                   Share.unshare.push($(this).val());
//               }
//            });
//            var json = JSON.stringify(Share);
//            $.ajax({
//                url: "<?php echo base_url();?>ApiFiles/shareFileFolders/",
//                type: "POST",
//                dataType: "json",
//                data:{json:json},
//                beforeSend: function (xhr) {
//                    $("#btnShare").append('<i class="fa fa-spinner fa-spin"></i>');
//                },
//                success: function(data) {
//                   if($(".fa-spin").length>0){
//                       $(".fa-spin").remove();
//                   }
//                }
//                        
//
//            });
//            
//        });
       $('input[name="sharedByLink"]').change(function (){
         var Share=new Object();
         Share.Id = $("#inputIdToShare").val();
         Share.Type = $("#inputTypeToShare").val();
         Share.State = $(this).is(":checked");
         var json = JSON.stringify(Share);
         $.ajax({
                url: "<?php echo base_url();?>ApiFiles/directShare/",
                type:"POST",
                dataType:"json",
                data:{json:json},
                success:function(data){
                    if(data=="1"){
                        $("#directLink").val("");
                    }
                    else{
                        $("#directLink").val(data.directLink);
                    }
                }
         });
      });
      
      $("#btnMove").click(function(e){
        var moveto;
          $.each($('input[name="moveto"]'),function (){
              if($(this).is(':checked')){
                  moveto = $(this).val();
              }
          });
          var Move = new Object();
          Move.Id = $("#inputIdToMove").val();
          Move.Type = $("#inputTypeToMove").val();
          Move.MoveTo = moveto;
          var json = JSON.stringify(Move);
          $.ajax({
                url: "<?php echo base_url();?>ApiFiles/moveFile/",
                type:"POST",
                dataType:"json",
                data:{json:json},
                success:function(data){
                    if(data=="1"){
                        myFunction();
                        $('#MoveModal').modal('toggle');
                    }
                    else{
                        console.log(data);
                    }
                }
         });
      });
        //kreiranje novog file-a
        //new file
        $("#newFile").click(function (e){
            e.preventDefault();
            var File = new Object();
            File.IdFolder = $("#current_dir").val();
            File.FileName = prompt("File name");
            if(checkIsValidFile(File.FileName)){
                var json = JSON.stringify(File);
                $("#errorMsg").text('');
                $.ajax({
			url: "<?php echo base_url();?>ApiFiles/createFile/",
                        type:"POST",
                        dataType:"json",
                        data:{json:json},
			success: function(data) {
                           if(data == "1"){
                               myFunction();
                            }
                            else{
                               alert("Error:"+data); 
                            }
                           
			}
			
		});
            }
            else{
              $("#errorMsg").text("Please give file some name and extension!");  
            }
        });
        
        //kreiranje foldera
        //new folder 
        $("#newFolder").click(function (e){
            e.preventDefault();
            var Folder = new Object();
            Folder.IdFolder = $("#current_dir").val();
            Folder.Mask = $("#current_path").val();
            Folder.FolderName = prompt("Folder name:");
            var json = JSON.stringify(Folder);
            if(Folder.FolderName!=null&&Folder.FolderName.length>0){
                $("#errorMsg").text('');
                $.ajax({
			url: "<?php echo base_url();?>ApiFiles/newFolder/",
                        type: "POST",
                        dataType: "json",
                        data:{json:json},
			success: function(data) {
                           if(data=="1"){
                               myFunction();
                           }
                           else{
                               alert("Error:"+data);
                           }
			}
			
		});
            }
            else{
              $("#errorMsg").text("Please give folder some name!");  
            }
        });
        
        $(".chbGroupShare").change(function() {
            var no = $(this).data("no");
            var checkBoxes = $("input:checkbox.chbUserGroupId_"+no);
            //checkBoxes.prop("checked", !checkBoxes.prop("checked"));
            checkBoxes.trigger('click');
        });
    });
    
    //depricated
    //function to attachListeners on links in table 
    //all commented functions are depricated because is much faster onclick on link and can't have bugs
    function attachListeners(){
    
//        $(".download").bind("click",function (e){
//            
//        });
//        $(".deleteLink").bind("click",function (e){
//            e.preventDefault();
//            deleteFileFolder($(this));
//        });
//        $(".edit").bind('click',function (e){
//                e.preventDefault();
//                editFile($(this));
//                
//        });
//        $(".rename").bind("click",function (e){
//            e.preventDefault();
//            renameFileFolder($(this));
//        });
        
//        $(".setfav").bind("click",function (e){
//            e.preventDefault();
//            setFavourites($(this));
//        
//        });
//        $(".unsetfav").bind("click",function (e){
//            e.preventDefault();
//            setFavourites($(this));
//        });
        
//        $(".share").bind("click",function (e){
//            shareFileFolder($(this));
//        });
        
//        $(".move").bind("click",function(e){
//            moveFile($(this));
//        });
        
        
        
    }
    function editFile(control){
        var IdFile = $(control).data("idfile");
        window.open("<?php echo base_url();?>Files/edit/"+IdFile,"","width=400","height=800");
    }
    function moveFile(control){
            id = $(control).data('id');
            type = $(control).data('type');
            name = $(control).data('name');
            parent = $(control).data('parent');
            $("#inputIdToMove").val(id);
            $("#inputTypeToMove").val(type);
            $.ajax({
                url: "<?php echo base_url(); ?>ApiFiles/listFolders/",
                dataType: 'json',
                success: function(data) {
                    var folders = data.folders;
                    var disabled = ($("#current_dir").val()=='')? 'disabled' : '';
                    var tekst = '<label class="btn '+disabled+'"><input type="radio" name="moveto" autocomplete="off" value="0"><i class="fa fa-home"></i> /'+name+'</label>';
                    $.each(folders,function(index,val){
                        if(parent == val.IdFolder){
                            disabled="disabled";
                        }
                        else{
                            disabled="";
                        }
                        tekst+='<label class="btn '+disabled+'"><input type="radio" name="moveto" autocomplete="off" value="'+val.IdFolder+'"><i class="fa fa-home"></i>/'+val.FolderMask+val.FolderName+'/'+name+'</label>';
                    });
                    
                    $("#folderList").html(tekst);
                    
                   
                }

            });
    }
    function shareFileFolder(control){
            var type = $(control).data('type');
            var id = $(control).data('id');
            getSharedItem(id,type);
            $("#inputIdToShare").val(id);
            $("#inputTypeToShare").val(type);
            //ajax to check with who is already shared
            $.ajax({
                url: "<?php echo base_url(); ?>ApiFiles/checkShared/",
                type:"POST",
                data:{id:id,type:type},
                success: function(data) {
                    $.each(data,function(index,val){
                        $("input:checkbox.chbUserGroup").each(function () {
                           if($(this).val()==val.IdUser){
                               $(this).prop('checked',true);
                           }
                        });
                    });

                }

            });
    }
    //function to set file or folder favourites
    function setFavourites(control){
        var id = $(control).data("id");
        var type = $(control).data("type");
        var set = $(control).data("set");
        $.ajax({
                url: "<?php echo base_url();?>ApiFiles/setFavourites/"+id+"/"+type+"/"+set,
                success:function(data){
                    myFunction();
                }
        });
    }
    //function to check if file is direct shared already
    function getSharedItem(id,type){
        document.getElementById("sharedByLink").checked=false;
        $("#directLink").val("");
        $.ajax({
            url:"<?php echo base_url();?>ApiFiles/getSharedItem/",
            type:"POST",
            data:{id:id,type:type},
            success:function(data){
                var Item = JSON.parse(data);
                if(typeof Item.file!=="undefined"&&Item.file!=null){
                   if(Item.file.SharedByLink=="1"){
                       $("#directLink").val(Item.file.url);
                       //$("input[name='sharedByLink']").attr('checked',true);
                       document.getElementById("sharedByLink").checked=true;
                    }
                }
                if(typeof Item.folder!=="undefined"&&Item.folder!=null){
                    if(Item.folder.SharedByLink=="1"){
                       $("#directLink").val(Item.folder.url);
                       //$("input[name='sharedByLink']").attr('checked',true);
                       document.getElementById("sharedByLink").checked=true;
                    }
                }
            }
        });
        
    }
    function deleteFileFolder(control){
        var id = $(control).data("id");
        var type = $(control).data("type");
                $.ajax({
                url: "<?php echo base_url();?>ApiFiles/?"+$.param({"Id":id,"Type":type}),
                type: "DELETE",
                beforeSend: function (xhr) {
                    
                },
                success: function(data) {
                   if(data=="1"){
                       myFunction();
                   }
                   else{
                     $("#errorMsg").html(data);  
                    }
                }
                        

            });
    }
    
    function deleteFromCheckBox(){
       $("input:checkbox.chbDelete").each(function () {
               if(this.checked){
                   deleteFileFolder($(this));
               }
            });
    }
    function renameFileFolder(control){
        var id = $(control).data('id');
        var type = $(control).data('type');
        if(type == "folder"){
            renameFolder(id);
        }
        if(type == "file"){
            renameFile(id);
        }
    }
    function renameFolder(IdFolder){
        var Folder = new Object();
        Folder.IdFolder = IdFolder;
        Folder.Name = prompt("New name:");
        var json = JSON.stringify(Folder);
        if(Folder.Name!=null&&Folder.Name.length>0){
            $("#errorMsg").text('');
            $.ajax({
                url: "<?php echo base_url();?>ApiFiles/renameFolder/",
                type: "POST",
                dataType: "json",
                data:{json:json},
                success: function(data) {
                   if(data=="1"){
                       myFunction();
                   }
                   else{
                       $("#errorMsg").html(data);
                   }
                }

            });
        }
        else{
          $("#errorMsg").text("Please give folder some name!");  
        }
    }
    
    function renameFile(IdFile){
        var File = new Object();
        File.IdFile = IdFile;
        File.Name = prompt("New name:");
        var json = JSON.stringify(File);
        if(checkIsValidFile(File.Name)){
            $("#errorMsg").text('');
            $.ajax({
                url: "<?php echo base_url();?>ApiFiles/renameFile/",
                type: "POST",
                dataType: "json",
                data:{json:json},
                success: function(data) {
                   if(data=="1"){
                       myFunction();
                   }
                   else{
                       alert("Error!"+data);
                   }
                }

            });
        }
        else{
          $("#errorMsg").text("Please give file some name and extension!");  
        }
    }
    
        //drag and drop
    $(document).bind('dragover', function (e) {
        var dropZone = $('#myTable'),
            timeout = window.dropZoneTimeout;
        if (!timeout) {
            dropZone.addClass('in');
        } else {
            clearTimeout(timeout);
        }
        var found = false,
            node = e.target;
        do {
            if (node === dropZone[0]) {
                found = true;
                break;
            }
            node = node.parentNode;
        } while (node != null);
        if (found) {
            dropZone.addClass('hover');
        } else {
            dropZone.removeClass('hover');
        }
        window.dropZoneTimeout = setTimeout(function () {
            window.dropZoneTimeout = null;
            dropZone.removeClass('in hover');
        }, 100);
    });
</script>
>>>>>>> master
