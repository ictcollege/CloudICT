/* 
 *  @author Darko Lesendric <dlesendric at https://github.com/ @Darko_Lesendric at https://twitter.com/>
 */

FilesScript={
    base_url:function(url){
        if(url==undefined){
            if (!location.origin){
                url=location.origin = location.protocol + "//" + location.host+"/cloud_project";
            }
            else{
                url= location.origin+"/cloud_project/"; 
            }
        }
        return url;      
    },
    options:undefined,
    _construct : function(options){
        this.options = options;
        this.base_url(options.baseurl);
    },
    _init:function(){
        if(this.options==undefined){
            this.options = {
                apiurl:this.base_url()+"ApiFiles/",
                scripturl:this.base_url()+"Files/",
                apifunc:"", //require for datatable source
                current_dir:"",
                current_path:"",
                datatable:"#myTable",
                form:"#fileupload",
                maxChunkSize:2000000,
                datasrc:"files",
                maxFileSize:undefined,
                formData:{
                    IdFolder:"",
                    Mask:"",
                    Shared:0
                },
                table:undefined
            }; 
            
        }
        this.options.table=this._initdatatables();
        this._initfileupload();   
    },
    _initdatatables:function(opt){
        "use strict";
        var dt;
        if(opt==undefined){
            dt=$(this.options.datatable).DataTable({
                "ajax": {
                    "url":this.options.apiurl+this.options.apifunc,
                    "dataSrc":this.options.datasrc,
                    "data": {
                        "id_folder": this.options.current_dir,
                        "Mask":this.options.current_path
                    }
                  },
                "fnInitComplete": function(oSettings){

                },
                "responsive":true,
                "scrollX":true,
                "scroller":true,
                "deferRender" : true
            });  
        }
        else{
            dt = $(this.options.datatable).DataTable(opt);
        }
        this.options.table = dt;
        return dt;
    },
    _initfileupload:function () {
        'use strict';
        // Initialize the jQuery File Upload widget:
        $(this.options.form).fileupload({
            // Uncomment the following to send cross-domain cookies:
            //xhrFields: {withCredentials: true},
            url: this.options.apiurl,
            maxChunkSize: this.options.maxChunkSize,
            dropZone: this.options.datatable,
            formData: this.options.formData,
            maxFileSize:this.options.maxFileSize
        }).on('fileuploadsubmit', function (e, data) {
            data.formData = data.context.find(':input').serializeArray();
        });      

    },
    _reload:function(){
        var that = this;
        this.options.table.ajax.reload(); // user paging is not reset on reload
    },
    _send:function(type,apifunc,json){
        var delimiter = "/";
        if(json == undefined){
            delimiter = "";
        }
        var data={
            type:type,
            url:this.options.apiurl+apifunc+delimiter,
            dataType:'json',
            data:{json:json}
            
        };
        return $.ajax(data);
    },
    checkIsValidFile:function(filename){
        if(filename==null||!filename.length>2){
        return false;
        }
        if(/^[a-zA-Z0-9-_]{2,}[\.]{1}[a-z]{2,6}$/.test(filename)){
            return true;
        }

        return false;
    }     
};


jQuery.fn.filesScript = function(options){
    FilesScript._construct(options);
    return FilesScript;
};

        

function editFile(control){
    var IdFile = $(control).data("idfile");
    window.open(FilesScript.options.scripturl+"edit/"+IdFile,"","width=400","height=800");
}
function moveFile(control){
    id = $(control).data('id');
    type = $(control).data('type');
    name = $(control).data('name');
    parent = $(control).data('parent');
    $("#inputIdToMove").val(id);
    $("#inputTypeToMove").val(type);
    FilesScript._send('GET','listFolders')
            .done(function(data){
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
            })
            .fail(function (xhr,status,error){
                 $("#errorMsg").html(error+"<br/>"+xhr.responseText);
            });
    
}
function shareFileFolder(control){
        var type = $(control).data('type');
        var id = $(control).data('id');
        getSharedItem(id,type);
        $("#inputIdToShare").val(id);
        $("#inputTypeToShare").val(type);
        //ajax to check with who is already shared
//        var check = new Object();
//        check.id = id;
//        check.type = type;
//        var json = JSON.stringify(check);
//        FilesScript._send('POST','checkShared',json)
//                .done(function(data){
//                    $.each(data,function(index,val){
//                        $("input:checkbox.chbUserGroup").each(function () {
//                           if($(this).val()==val.IdUser){
//                               $(this).prop('checked',true);
//                           }
//                        });
//                    });
//                });
//                .fail(function (xhr,status,error){
//                 $("#errorMsg").html(error+"<br/>"+xhr.responseText);
//                });
}

//function to unshare file folders
function ushareFileFolder(control){
    var Unshare=new Object();
    Unshare.Id=$(control).data('id');//id file or folder
    Unshare.IdShare = $(control).data('idshare');//id share
    Unshare.IdShared  = $(control).data('idshared');
    Unshare.Type = $(control).data('type');
    var json = JSON.stringify(Unshare);
    $(control).append('<i class="fa fa-spinner fa-spin"></i>');
    FilesScript._send('POST','unshareFilesFolders',json)
            .done(function(data){
                if($(".fa-spin").length>0){
                           $(".fa-spin").remove();
                }
                FilesScript._reload();
            })
            .fail(function (xhr,status,error){
                 $("#errorMsg").html(error+"<br/>"+xhr.responseText);
            });    
   
}

//function to set file or folder favourites
function setFavourites(control){
    var fav = new Object();
    fav.id = $(control).data("id");
    fav.type = $(control).data("type");
    fav.set = $(control).data("set");
    var json = JSON.stringify(fav);
    FilesScript._send("POST","setFavourites",json)
            .done(function(){
                FilesScript._reload();
            })
            .fail(function (xhr,status,error){
                $("#errorMsg").html(error+"<br/>"+xhr.responseText);
            });
}
//function to check if file is direct shared already
function getSharedItem(id,type){
    document.getElementById("sharedByLink").checked=false;
    $("#directLink").val("");
    var file = new Object();
    file.id = id;
    file.type = type;
    var json = JSON.stringify(file);
    FilesScript._send('POST','getSharedItem',json)
            .done(function(data){
                var Item = data;
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
            })
            .fail(function(xhr,status,error){
                $("#errorMsg").html(error+"<br/>"+xhr.responseText);
            });

}
function deleteFileFolder(control){
    var id = $(control).data("id");
    var type = $(control).data("type");
    FilesScript._send("DELETE","?"+$.param({id:id,type:type}))
            .done(function (){
                FilesScript._reload();
            })
            .fail(function(xhr,status,error){
                $("#errorMsg").html(error+"<br/>"+xhr.responseText);
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
        FilesScript._send('POST','renameFolder',json)
                .done(function (){
                    FilesScript._reload();
                })
                .fail(function(xhr,status,error){
                    $("#errorMsg").html(error+"<br/>"+xhr.responseText);
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
    if(FilesScript.checkIsValidFile(File.Name)){
        $("#errorMsg").text('');
        FilesScript._send('POST','renameFile',json)
                .done(function (){
                    FilesScript._reload();
                })
                .fail(function(xhr,status,error){
                    $("#errorMsg").html(error+"<br/>"+xhr.responseText);
                });
        
    }
    else{
      $("#errorMsg").text("Please give file some name and extension!");  
    }
}

function unshareDirectShare(control){
    var Share=new Object();
    Share.id = $(control).data('id');
    Share.type = $(control).data('type');
    Share.state = false;
    var json = JSON.stringify(Share);
    $(control).append('<i class="fa fa-spinner fa-spin"></i>');
    FilesScript._send('POST','directShare',json)
            .done(function (response){
                if($(".fa-spin").length>0){
                           $(".fa-spin").remove();
                }
                FilesScript._reload();
            })
            .fail(function(xhr,status,error){
                alert("xhr:"+xhr);
                alert("status:"+status);
                alert("error:"+error);
            });
}
/////////////////////////////////////////////////////////////////////////////
//LISTENERS
////////////////////////////////////////////////////////////////////////////
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
//red button delete all
$(document).on('click','.deleteAll',function(){
    deleteFromCheckBox();
});
//on click group
$(document).on('change','input:checkbox.chbUserGroup',function(){
    var control = this;
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
//sharedbylink checkbox
$(document).on('change','input[name="sharedByLink"]',function(){
    var Share=new Object();
    Share.id = $("#inputIdToShare").val();
    Share.type = $("#inputTypeToShare").val();
    Share.state = $(this).is(":checked");
    var json = JSON.stringify(Share);
    FilesScript._send('POST','directShare',json)
            .done(function (response){
                if(response == "1" || response == "true"){
                    $("#directLink").val("");
                }
                else{
                    $("#directLink").val(response.directLink);
                }
            })
            .fail(function(xhr,status,error){
                alert("xhr:"+xhr);
                alert("status:"+status);
                alert("error:"+error);
            });
            
});

$(document).on('click','#btnMove',function(e){
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
    FilesScript._send('POST','moveFile',json)
            .done(function(msg){
                FilesScript._reload();
                $("#MoveModal").modal('toggle');
            })
            .fail();
    
    
});
$(document).on('click','#newFile',function(e){
    e.preventDefault();
    var File = new Object();
    File.IdFolder = $("#current_dir").val();
    File.FileName = prompt("File name");
    if(FilesScript.checkIsValidFile(File.FileName)){
        var json = JSON.stringify(File);
        $("#errorMsg").text('');
        FilesScript._send('POST','createFile',json)
                .done(function (msg){
                    FilesScript._reload();
                })
                .fail(function (xhr){
                    console.log(xhr);
                });
        
    }
    else{
      $("#errorMsg").text("Please give file some name and extension!");  
    }
});

$(document).on('click','#newFolder',function(e){
    e.preventDefault();
    var Folder = new Object();
    Folder.IdFolder = $("#current_dir").val();
    Folder.Mask = $("#current_path").val();
    Folder.FolderName = prompt("Folder name:");
    var json = JSON.stringify(Folder);
    if(Folder.FolderName!=null&&Folder.FolderName.length>0){
        $("#errorMsg").text('');
        FilesScript._send('POST','newFolder',json)
                .done(function(){
                    FilesScript._reload();
                })
                .fail(function (xhr){
                    console.log(xhr);
                });
       
    }
    else{
      $("#errorMsg").text("Please give folder some name!");  
    }
});

$(document).on('change','.chbGroupShare',function(){
    var no = $(this).data("no");
    var checkBoxes = $("input:checkbox.chbUserGroupId_"+no);
    checkBoxes.trigger('click');
});