$(document).ready(function() {
	
	//Animacija na aplikacionoj stranici 
	//
	// TO DO!!!!!!!!!!!!!!!!!!!!!!!!!!
	//
	$(".app-name").hide(0);
	$(".app").hover(function() {
		$(this).find(".app-name").stop(true,true).fadeIn(500); 
	}, function() {
		$(this).find(".app-name").stop(true,true).fadeOut(500);
	});

	//favorited
	//
	// TO DO!!!!!!!!!!!!!!!!!!!!!!!!!!
	//
	
	$(".fa-star").click(function() {
		if($(this).hasClass("favorited")) {
			$(this).removeClass("favorited");
			$(this).addClass("fileiconhide");
		} else {
			$(this).addClass("favorited");
			$(this).removeClass("fileiconhide");
			$(this).css({"dispaly": "block"});
		}
			
	});

	//animacija
	$("td .fa-pencil").hover(function() {
		$(this).addClass("rotate360");
		$(this).addClass("transition_3");
	}, function() {
		$(this).removeClass("rotate360");
		$(this).addClass("rotate-negative");
	});

	$("td .fa-star").hover(function() {
		$(this).addClass("rotate360");
		$(this).addClass("transition_3");
	}, function() {
		$(this).removeClass("rotate360");
		$(this).addClass("rotate-negative");
	});

	$("td .fa-cloud-download").hover(function() {

	});
	
	$("td .fa-share-alt").hover(function() {
		$(this).removeClass("fa-share-alt");
		$(this).addClass("fa-link");
	}, function() {
		$(this).removeClass("fa-link");
		$(this).addClass("fa-share-alt");
	});

	$("td .fa-share-alt").click(function() {
		$(this).removeClass("fa-share-alt");
		$(this).addClass("fa-link");
	});


			
	//new file/folder

	$(".new-folder-name").hide();

	$(".rbFile").click(function() {
		$(".new-folder-name").hide();
		$(".new-file-name").show();	
		$(".new-file-content").show();
	});

	$(".rbFolder").click(function() {
		$(".new-folder-name").show();
		$(".new-file-name").hide();
		$(".new-file-content").hide();
	});


	//new file validation

	$(".btnSaveNewFile").click(function() {
		var filename = $(".tbFileName"),
			foldername = $(".tbFolderName"),
			filecontent = $(".taFileContent"),
			folder = $(".rbNew");

		if(filename.val() == "") { 
			filename.parent().addClass("has-error");
			filename.parent().removeClass("has-success");
		} else {
			filename.parent().addClass("has-success");
			filename.parent().removeClass("has-error");
		}
	
		if(foldername.val() == "") {
			foldername.parent().addClass("has-error");
			foldername.parent().removeClass("has-success");
		} else {
			foldername.parent().addClass("has-success");
			foldername.parent().removeClass("has-error");
		}
		
		
		/*if(filecontent.val() == "") {
			filecontent.parent().addClass("has-error");
		} else {
			filecontent.parent().removeClass("has-error");
		}*/
	});

	//Group select

	$(".chbGroupShare").click(function() {
		var id = $(this).attr("id").split("_")[1];
		
		if(this.checked) {
			$("input:checkbox.chbUserGroup"+id).each(function () {
				this.checked = true;
			});
		} else {
			$("input:checkbox.chbUserGroup"+id).each(function () {
				this.checked = false;
			});
		}
	})

	/*$('.check').click(function() {
		var mark = 0;
		$('input:checkbox.check').each(function () {
   			if(this.checked == true) {
   				mark++;
   			}
		});

		if(mark>1) {
			$(".multiple-select").show();
		} else {
			$(".multiple-select").hide();
		}
	});*/

	//multiple select dugmici i ostalo

	$(".multiple-select").hide();

	$(".check-all").click(function () {
		if(this.checked) {
			$('input:checkbox.check').each(function () {
       			this.checked = true;
       			$(".multiple-select").show(500);
  			});
		} else {
			$('input:checkbox.check').each(function () {
       			this.checked = false;
       			$(".multiple-select").hide(500);
  			});
		}
	});

	$('.check').click(function() {
		var mark = 0,
			count =0;
		$('input:checkbox.check').each(function () {
   			if(this.checked == true) {
   				mark++;
   			}

   			count++;
		});

		if(mark>1) {
			$(".multiple-select").show(500);
		} else {
			$(".multiple-select").hide(500);
		}

		if(count==mark) {
			$('input:checkbox.check-all').checked = true;
		}

		if(count>mark) {
			$('input:checkbox.check-all').checked = false;
		}
	});

	/*$('input:checkbox.class').each(function () {
       var sThisVal = (this.checked ? $(this).val() : "");
  	});*/

	// Opcije u tabeli
	if($(window).width()>768){
		$(".tablefiles").find('tr').find('td').find('i').addClass('fileiconhide');

		$(".tablefiles").find('tr').on({
			mouseenter: function () {
	        	$(this).find('td').find('.fileiconhide').removeClass('fileiconhide');
	    	},
	    	mouseleave: function () {
				$(this).find('td').find('i').addClass('fileiconhide');
	        }
		});
	} else {
		$(".tablefiles").find('tr').find('td').find('i').removeClass('fileiconhide');
	}

	//test 
	//$("#test").DataTable();
        
        //group modal, remove admin
        $(document).on("mouseenter", ".icon-remove-admin", function(){
            $(this).css({"color":"red", "opacity":"0.6"});
        });
        
        $(document).on("mouseleave", ".icon-remove-admin", function(){
            $(this).css({"color":"white", "opacity":"0.6"});
        });
        
        $(document).on("click", ".icon-remove-admin", function(){
            var _this = $(this),
                iduser = $(this).attr("id"),
                idgroup = $(this).find(".hdIdGroup").attr("id"),
                username = $(this).parent().text();;
            
            $.ajax({
                url: 'admin/removeAdmin',
                type: 'post',
                dataType: 'json',
                data:{IdUser:iduser,IdGroup:idgroup},
                success: function(response) {
                    _this.parent().hide(500);
                    $(".users").append('<button type="button" class="btn btn-default btn-user">'+username+'<i class="fa fa-trash-o icon-remove-user" id="'+iduser+'"><input type="hidden" id="'+idgroup+'" class="hdIdGroup"/></i> <i class="fa fa-plus icon-add-admin" id="'+iduser+'"><input type="hidden" id="'+idgroup+'" class="hdIdGroup"/></i></button>');
                    $(".panel#"+idgroup).find(".group-admin").find("button#"+iduser).remove();
                    $(".panel#"+idgroup).find(".group-user").append('<button type="button" id="'+iduser+'" class="btn btn-info btn-xs btn-no-hover btn-admin">'+username+'</button>');
                } 
            });
        });
        //group modal, remove user, add admin
        
        
        $(document).on("mouseenter", ".icon-remove-user", function(){
           $(this).css({"color":"red", "opacity":"0.6"});
        });
        
        $(document).on("mouseleave", ".icon-remove-user", function(){
             $(this).css({"color":"#357ebd", "opacity":"0.6"});
        });
        
        $(".icon-add-admin").hover(function() {
            $(this).css({"color":"red", "opacity":"0.6"});
        }, function() {
            $(this).css({"color":"#357ebd", "opacity":"0.6"});
        });
        
        $(document).on("mouseenter", ".icon-add-admin", function(){
           $(this).css({"color":"red", "opacity":"0.6"});
        });
        
        $(document).on("mouseleave", ".icon-add-admin", function(){
             $(this).css({"color":"#357ebd", "opacity":"0.6"});
        });
        
        $(document).on("click", ".icon-add-admin", function() {
            var _this = $(this),
                iduser = $(this).attr("id"),
                idgroup = $(this).find(".hdIdGroup").attr("id"),
                username = $(this).parent().text();
            
            $.ajax({
                url: 'admin/addAdmin',
                type: 'post',
                dataType: 'json',
                data:{IdUser:iduser,IdGroup:idgroup},
                success: function(response) {
                    _this.parent().hide(500);
                    $(".admins").append('<button type="button" class="btn btn-primary btn-admin">'+username+'<i class="fa fa-minus icon-remove-admin" id="'+iduser+'"><input type="hidden" id="'+idgroup+'" class="hdIdGroup"/></i></button>').show(500);
                    $(".panel#"+idgroup).find(".group-user").find("button#"+iduser).remove();
                    $(".panel#"+idgroup).find(".group-admin").append('<button type="button" id="'+iduser+'" class="btn btn-primary btn-xs btn-no-hover btn-admin">'+username+'</button>');
                } 
            });
        });
        
        $(document).on("click", ".icon-remove-user", function() {
            var _this = $(this),
                iduser = $(this).attr("id"),
                idgroup = $(this).find(".hdIdGroup").attr("id"),
                username = $(this).parent().text();
             
            $.ajax({
                url: 'admin/removeUserFromGroup',
                type: 'post',
                dataType: 'json',
                data:{IdUser:iduser,IdGroup:idgroup},
                success: function(response) {
                    _this.parent().hide(500);
                    $(".newusers").append('<button type="button" class="btn btn-default btn-admin">'+username+'<i class="fa fa-plus icon-add-newuser" id="'+iduser+'"><input type="hidden" id="'+idgroup+'" class="hdIdGroup"/></i></button>').show(500);
                    $(".panel#"+idgroup).find(".group-user").find("button#"+iduser).remove();
                } 
            });
        });
        
        // group modal, add new user
        $(document).on("mouseenter", ".icon-add-newuser", function(){
           $(this).css({"color":"red", "opacity":"0.6"});
        });
        
        $(document).on("mouseleave", ".icon-add-newuser", function(){
             $(this).css({"color":"#357ebd", "opacity":"0.6"});
        });
        
        $(document).on("click", ".icon-add-newuser", function() {
            var _this = $(this),
                iduser = $(this).attr("id"),
                idgroup = $(this).find(".hdIdGroup").attr("id"),
                username = $(this).parent().text();
            
            $.ajax({
                url: 'admin/addNewUserToGroup',
                type: 'post',
                dataType: 'json',
                data:{IdUser:iduser,IdGroup:idgroup},
                success: function(response) {
                    _this.parent().hide(500);
                    $(".users").append('<button type="button" class="btn btn-default btn-user">'+username+'   <i class="fa fa-trash-o icon-remove-user" id="'+iduser+'"><input type="hidden" id="'+idgroup+'" class="hdIdGroup"/></i> <i class="fa fa-plus icon-add-admin" id="'+iduser+'"><input type="hidden" id="'+idgroup+'" class="hdIdGroup"/></i></button>').show(500);
                    $(".panel#"+idgroup).find(".group-user").append('<button type="button" id="'+iduser+'" class="btn btn-info btn-xs btn-no-hover btn-admin">'+username+'</button>');
                } 
            });
        });
        
        $(document).on("keyup", ".tbSearchNewUser", function() {
            var username = $(".tbSearchNewUser").val(),
                idgroup = $(this).attr("id");
                
            $.ajax({
                url: 'admin/searchNewUser',
                type: 'post',
                dataType: 'json',
                data:{Username:username,IdGroup:idgroup},
                success: function(response) {
                    $(".modal."+idgroup).find(".newusers").text("");
                     $(".modal."+idgroup).find(".newusers").append(response);
                } 
            });
        });
        
        //dodavanje nove grupe 
        
        $(".btnNewGroup").click(function () {
            var groupname = $(".tbNewGroupName"),
                error = false,
                groupExists = false;
            
            if(groupname.val() == "") {
                error = true;
                groupname.parent().addClass("has-error");
                groupname.parent().removeClass("has-success");
            } else {
                error = false;
                groupname.parent().removeClass("has-error");
                groupname.parent().addClass("has-success");
            }
            
            if(!error)
            {
                $.ajax({
                    url: 'admin/getGroups',
                    type: 'post',
                    success: function(response) {
                        var groupNames = jQuery.parseJSON(response),
                            i = 0;

                        for(i=0; i<groupNames.length;i++){
                            if(groupNames[i] == groupname.val()){
                                groupExists = true;
                                break;
                            }
                        }
                    }
                });
                if(!groupExists) {
                $.ajax({
                    url: 'admin/createNewGroup',
                    type: 'post',
                    dataType: 'json',
                    data:{GroupName:groupname.val()},
                    success: function(response) {
                       $('#mNewGroup').find('modal-body').append('<div class="text-center"><h3>Group '+groupname.val()+' added</h3></div>');
                     
                        var numberofcolums = $(".main-panel").find(".panel-body").find(".row .col-lg-4").length,
                            newgroupcontent = "",
                            newgroupeditmodal = "",
                            newgroupdeletemodal = "";
                    
                        if(numberofcolums%3 == 0) {
                            newgroupcontent += '<div class="row">';
                        }
                        newgroupcontent += '<div class="col-lg-4">';
                        newgroupcontent += '<div id="'+response+'" class="panel panel-primary">';
                        newgroupcontent += ' <div class="panel-heading">'+groupname.val()+'</div>';
                        newgroupcontent += '<div class="panel-body">';
                        newgroupcontent += '<div class="group-admin">';
                        newgroupcontent += '</div><hr>';
                        newgroupcontent += '<div class="group-user">';
                        newgroupcontent += '</div>';
                        newgroupcontent += '</div>';
                        newgroupcontent += '<div class="panel-footer group-panel-footer">';
                        newgroupcontent += ' <button type="button" class="btn btn-outline btn-primary btn-xs pull-left" data-toggle="modal" data-target="#mEditGroup'+response+'">Edit</button>';
                        newgroupcontent += '<button type="button" class="btn btn-outline btn-danger btn-xs pull-right" data-toggle="modal" data-target="#mDeleteGroup'+response+'">Delete</button>';
                        newgroupcontent += '</div></div>';
                        
                        if(numberofcolums%3 == 0) {
                            newgroupcontent += '</div>';
                        }
                        
                        if(numberofcolums%3 == 0){
                            $(".panel-groups").append(newgroupcontent);
                        } else {
                            $(".main-panel").find(".row:last-child").append(newgroupcontent);
                        }
                        
                       
                        
                        newgroupeditmodal += '<div class="modal fade '+response+'" id="mEditGroup'+response+'" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">';
                        newgroupeditmodal += '<div class="modal-dialog" role="document">';
                        newgroupeditmodal += '<div class="modal-content">';
                        newgroupeditmodal += '<div class="modal-header">';
                        newgroupeditmodal += '<button type="button" class="close" data-dismiss="modal" aria-label="Close">';
                        newgroupeditmodal += '<span aria-hidden="true">×</span></button>';
                        newgroupeditmodal += '<h4 class="modal-title" id="myModalLabel">Edit Group</h4></div>';
                        newgroupeditmodal += '<div class="modal-body"><div class="form-group"><label>Group Name</label>';
                        newgroupeditmodal += '<input class="form-control" placeholder="admins"></div>';
                        newgroupeditmodal += '<div class="form-group"><label>Admins</label><div class="admins">';
                        newgroupeditmodal += '</div></div>';
                        newgroupeditmodal += '<div class="form-group"><label>Members</label><div class="users">';
                        newgroupeditmodal += '</div>';
                        newgroupeditmodal += '<div class="form-group"><label>New Memers</label><input class="form-control tbSearchNewUser" placeholder="search..." id="1"><div class="newusers">';
                        newgroupeditmodal += '</div></div></div></div><div class="modal-footer">';
                        newgroupeditmodal += '<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>';
                        newgroupeditmodal += '<button type="button" class="btn btn-primary btnNewGroup">Save</button></div></div></div></div>';
                        
                        
                        $(".editmodals").append(newgroupeditmodal);
                        
                        setTimeout(function() {
                            $('#mNewGroup').modal('hide');
                        }, 200);
                        
                    } 
                });
                } else {
                    $('#mNewGroup').find('modal-body').append('<div class="text-center"><h3>Group already exists!</h3></div>');
                }
            }
        });
        
        //brisanje grupe
        
        $(document).on("click", ".btnDeleteGroupYes", function(){
           var idgroup = $(this).parent().find(".hdId").attr("id");
           
           $.ajax({
                url: 'admin/deleteGroup',
                type: 'post',
                dataType: 'json',
                data:{IdGroup:idgroup},
                success: function(response) {
                    $('#mDeleteGroup'+idgroup).find('modal-body').append('<div class="text-center"><h3>Group deleted!</h3></div>');
                    $("#"+idgroup+".panel.panel-primary").parent().remove();
                    setTimeout(function() {
                        $('.mDeleteGroup').modal('hide');
                    }, 200);
                }
            });
           
        });
});