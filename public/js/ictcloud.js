///////////////////////////////////////////////////////////
/////////////// Files 
/////////////////////////////////////////////////////////
var base_url = "../index.php/Files/";
var data_url = "../data/admin/";
function initialize() {

    $("#inputButton").on("click", function () {
        $("#inputFile").click();
    });
    
    $("#newFolder").on("click",function (event){
        event.preventDefault();
        newFolder();
    });
    
    getAllFiles();
    jQuery.ready();
    
    




};
function getAllFiles(){
    allFilesUrl = base_url + "?all_files";
    $.getJSON(allFilesUrl, function (data) {
        var items = [];
        $.each(data, function (key, val) {
            items.push("<tr data-idfile='"+val.IdFile+"'>");
            items.push("<td style='width:50px' align='left'><i class='fa  fa-star-o fa-fw fileiconhide'></td>");
            items.push("<td style='width:50px'>"+thumbnails(val.FileTypeMime,val.FileName,val.FileExtension)+"</td>");
            items.push("<td style='width:50px' class='fileRename' data-extension='"+val.FileExtension+"'>"+val.FileName+"</td>");
            items.push("<td style='width:50px' align='left'><i class='fa  fa-pencil fa-fw fileiconhide'></i></td>");
            items.push("<td style='width:50px'><i class='fa  fa-cloud-download fa-fw fileiconhide'></i></td>");
            items.push(' <td style="width:50px"><i class="fa  fa-share-alt fa-fw fileiconhide"></i></td>');
            items.push(' <td style="width:50px"><a href="#" class="deletefile" data-idfile="' + val.IdFile + '" data-filename="'+val.FileName+'" data-mime="'+val.FileTypeMime+'" data-extension="'+val.FileExtension+'"><i class="fa  fa-trash-o fa-fw fileiconhide"></i></a></td>');
            items.push('<td style="width:50px">' + formatSizeUnits(val.FileSize) + '</td>');
            items.push('<td style="width:50px">' + timeConverter(val.FileLastModified) + '</td>');
            items.push("</tr>");
        });
        $(".tablefiles").html(items.join(""));
        
    });
}

// Format file size
function formatSizeUnits(size) {
    if (size == 0) { return "0.00 B"; }
    var i = Math.floor(Math.log(size) / Math.log(1024));
    return (size / Math.pow(1024, i)).toFixed(2) * 1 + ' ' + ['B', 'kB', 'MB', 'GB', 'TB'][i];
}

function deleteFile(IdFile,FileName,FileExtension) {
    $.ajax({
       url: base_url+"?"+$.param({IdFile:IdFile,FileName:FileName,FileExtension:FileExtension}),
       type: 'DELETE',
       success: function (){
           $(".tablefiles").html("");
            initialize();
       }
    });
    
}

function newFolder(){
    var currentDir = $("#current_dir").val();
    var foldername = prompt("Folder name:");
    if(foldername==""){
        foldername = "New folder";
    }
    $.ajax({
       url: base_url,
       type: 'POST',
       data: {current_dir:currentDir,newFolder:foldername},
       success: function (){
           $(".tablefiles").html("");
            initialize();
       }
    });
}
//prikazuje thumbnail
function thumbnails(mime,filename,extension){
    switch (mime){
        case "image/jpeg":
            return "<a href='"+data_url+filename+"."+extension+"' class='thumbnail' style='max-width:120px;'><img src='"+data_url+filename+"."+extension+"' alt='...'/></a>";
            break;
        case "DIR":
            return "<span class='glyphicon glyphicon-folder-open'> DIR </span>";
            break;
        
    }
}



function timeConverter(UNIX_timestamp){
  var a = new Date(UNIX_timestamp * 1000);
  var months = ['Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec'];
  var year = a.getFullYear();
  var month = months[a.getMonth()];
  var date = a.getDate();
  var hour = a.getHours();
  var min = a.getMinutes();
  var sec = a.getSeconds();
  var time = date + ' ' + month + ' ' + year + ' ' + hour + ':' + min + ':' + sec ;
  return time;
}



function renameFile(OldName,NewName,IdFile,FileExtension){
      var current_dir  = $("#current_dir").val();
      if(NewName.length>0){
        $.ajax({
            url: base_url,
            type: 'POST',
            data: {IdFile: IdFile, OldName: OldName,NewName:NewName,current_dir:current_dir,FileExtension:FileExtension}
        }); 
      }else{
          alert("Name must be some word");
          location.reload();
      }
    
}

function resetTableFiles(){
    $(".tablefiles").html("");
    initialize();
}

//jQuery.each(["put", "delete"], function (i, method) {
//    jQuery[ method ] = function (url, data, callback, type) {
//        if (jQuery.isFunction(data)) {
//            type = type || callback;
//            callback = data;
//            data = undefined;
//            
//        }
//        return jQuery.ajax({
//            url: url,
//            type: method,
//            dataType: type,
//            data: data,
//            success: callback
//        });
//    };
//});        
$(function () {

});




$(document).ready(function (e) {
        
        initialize();

});
$(document).ajaxComplete(function (){
    $(".tablefiles").find('tr').find('td').find('i').addClass('fileiconhide');

	$(".tablefiles").find('tr').on({
		mouseenter: function () {
        	$(this).find('td').find('.fileiconhide').removeClass('fileiconhide');
    	},
    	mouseleave: function () {
			$(this).find('td').find('i').addClass('fileiconhide');
    	}
	});
        
        //delete button click listener
        $(".deletefile").on('click',function (ex){
              ex.preventDefault();
              var IdFile = $(this).data('idfile');
              var FileName = $(this).data('filename');
              var FileExtension = $(this).data('extension');
              if(confirm("Are you sure?")){
                  deleteFile(IdFile,FileName,FileExtension);
              }
              
        });
        
        

    $(".fileRename").dblclick(function () {
        var OriginalContent = $(this).text();
        var IdFile = $(this).parent('tr').data('idfile');
        var extension =  $(this).data('extension');
        $(this).addClass("cellEditing");
        $(this).html("<input type='text' class='form-control' value='"+ OriginalContent +"' />");
        $(this).children().first().focus();
 
        $(this).children().first().keypress(function (e) {
            if (e.which == 13) {
                var newContent = $(this).val();
                $(this).parent().text(newContent);
                $(this).parent().removeClass("cellEditing");
                
                renameFile(OriginalContent,newContent,IdFile,extension);
            }
        });
 
    $(this).children().first().blur(function(){
        $(this).parent().text(OriginalContent);
        $(this).parent().removeClass("cellEditing");
    });
    });

});