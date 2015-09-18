
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
