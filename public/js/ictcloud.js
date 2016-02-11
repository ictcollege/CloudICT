<<<<<<< HEAD
$(document).ready(function() {
	
	//Animacija na aplikacionoj stranici 
	
	$(".app-name").hide();
	
        $(document).on("mouseenter", ".app", function(){
            $(this).find(".app-name").stop(true,true).fadeIn(500); 
        });
        
        $(document).on("mouseleave", ".app", function(){
            $(this).find(".app-name").stop(true,true).fadeOut(500);
        });

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
        });	
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
                    $(".users."+idgroup).append('<button type="button" class="btn btn-default btn-user">'+username+'<i class="fa fa-trash-o icon-remove-user" id="'+iduser+'"><input type="hidden" id="'+idgroup+'" class="hdIdGroup"/></i> <i class="fa fa-plus icon-add-admin" id="'+iduser+'"><input type="hidden" id="'+idgroup+'" class="hdIdGroup"/></i></button>');
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
                    $(".admins."+idgroup).append('<button type="button" class="btn btn-primary btn-admin">'+username+'<i class="fa fa-minus icon-remove-admin" id="'+iduser+'"><input type="hidden" id="'+idgroup+'" class="hdIdGroup"/></i></button>').show(500);
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
                    $(".newusers."+idgroup).append('<button type="button" class="btn btn-default btn-admin">'+username+'<i class="fa fa-plus icon-add-newuser" id="'+iduser+'"><input type="hidden" id="'+idgroup+'" class="hdIdGroup"/></i></button>').show(500);
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
                    $(".users."+idgroup).append('<button type="button" class="btn btn-default btn-user">'+username+'   <i class="fa fa-trash-o icon-remove-user" id="'+iduser+'"><input type="hidden" id="'+idgroup+'" class="hdIdGroup"/></i> <i class="fa fa-plus icon-add-admin" id="'+iduser+'"><input type="hidden" id="'+idgroup+'" class="hdIdGroup"/></i></button>').show(500);
                    $(".panel#"+idgroup).find(".group-user").append('<button type="button" id="'+iduser+'" class="btn btn-info btn-xs btn-no-hover btn-admin">'+username+'</button>');
                } 
            });
        });
        
        $(document).on("keyup", ".tbSearchNewUser", function() {
            var idgroup = $(this).attr("id"),
                username = $(this).val(),
                idnewcreatedgroup;
            
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
                    
                    $.ajax({
                            url: 'admin/getUsers',
                            type: 'post',
                            dataType: 'json',
                            data:{IdGroup:response},
                            success: function(users) {
                       $('#mNewGroup').find('modal-body').append('<div class="text-center"><h3>Group '+groupname.val()+' added</h3></div>');
                     
                        var numberofcolums = $(".main-panel").find(".panel-body").find(".row .col-lg-4").length,
                            newgroupcontent = "",
                            newgroupeditmodal = "",
                            newgroupdeletemodal = "",
                            idnewcreatedgroup = response;
                    
                        if(numberofcolums%3 == 0) {
                            newgroupcontent += '<div class="row">';
                        }
                        newgroupcontent += '<div class="col-lg-4">';
                        newgroupcontent += '<div id="'+idnewcreatedgroup+'" class="panel panel-primary">';
                        newgroupcontent += ' <div class="panel-heading">'+groupname.val()+'</div>';
                        newgroupcontent += '<div class="panel-body">';
                        newgroupcontent += '<div class="group-admin">';
                        newgroupcontent += '</div><hr>';
                        newgroupcontent += '<div class="group-user">';
                        newgroupcontent += '</div>';
                        newgroupcontent += '</div>';
                        newgroupcontent += '<div class="panel-footer group-panel-footer">';
                        newgroupcontent += ' <button type="button" class="btn btn-outline btn-primary btn-xs pull-left" data-toggle="modal" data-target="#mEditGroup'+idnewcreatedgroup+'">Edit</button>';
                        newgroupcontent += '<button type="button" class="btn btn-outline btn-danger btn-xs pull-right" data-toggle="modal" data-target="#mDeleteGroup'+idnewcreatedgroup+'">Delete</button>';
                        newgroupcontent += '</div></div>';
                        
                        if(numberofcolums%3 == 0) {
                            newgroupcontent += '</div>';
                        }
                        
                        if(numberofcolums%3 == 0){
                            $(".panel-groups").append(newgroupcontent);
                        } else {
                            $(".main-panel").find(".row:last-child").append(newgroupcontent);
                        }
                        
                        newgroupeditmodal += '<div class="modal fade '+idnewcreatedgroup+'" id="mEditGroup'+idnewcreatedgroup+'" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">';
                        newgroupeditmodal += '<div class="modal-dialog" role="document">';
                        newgroupeditmodal += '<div class="modal-content">';
                        newgroupeditmodal += '<div class="modal-header">';
                        newgroupeditmodal += '<button type="button" class="close" data-dismiss="modal" aria-label="Close">';
                        newgroupeditmodal += '<span aria-hidden="true">×</span></button>';
                        newgroupeditmodal += '<h4 class="modal-title" id="myModalLabel">Edit Group</h4></div>';
                        newgroupeditmodal += '<div class="modal-body"><div class="form-group"><label>Group Name</label>';
                        newgroupeditmodal += '<input class="form-control tbGroupName" placeholder="'+groupname.val()+'" id="'+idnewcreatedgroup+'" ><button type="button" class="btn btn-success pull-right btnChangeGroupName" id="'+idnewcreatedgroup+'">Change Name</button></div>';
                        newgroupeditmodal += '<div class="form-group"><label>Admins</label><div class="admins '+idnewcreatedgroup+'">';
                        newgroupeditmodal += '</div></div>';
                        newgroupeditmodal += '<div class="form-group"><label>Members</label><div class="users '+idnewcreatedgroup+'">';
                        newgroupeditmodal += '</div>';
                        newgroupeditmodal += '<div class="form-group"><label>New Memers</label><input class="form-control tbSearchNewUser" placeholder="search..." id="'+idnewcreatedgroup+'"><div class="newusers '+idnewcreatedgroup+'">';
                        
                         
                        newgroupeditmodal += users;
                        
                        newgroupeditmodal += '</div></div></div></div><div class="modal-footer">';
                        newgroupeditmodal += '</div></div></div></div>';
                        
                        $(".editmodals").append(newgroupeditmodal);
                        
                        
                        newgroupdeletemodal += '<div class="modal fade mDeleteGroup" id="mDeleteGroup'+idnewcreatedgroup+'" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">';
                        newgroupdeletemodal += '<div class="modal-dialog" role="document">';
                        newgroupdeletemodal += '<div class="modal-content">';
                        newgroupdeletemodal += '<div class="modal-header">';
                        newgroupdeletemodal += '<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>';
                        newgroupdeletemodal += '<h4 class="modal-title" id="myModalLabel">Delete Group</h4>';
                        newgroupdeletemodal += '</div>';
                        newgroupdeletemodal += '<div class="modal-body text-center">';
                        newgroupdeletemodal += 'Delete group '+groupname.val()+'?';
                        newgroupdeletemodal += '</div>';
                        newgroupdeletemodal += '<div class="modal-footer text-center">';
                        newgroupdeletemodal += '<button type="button" class="btn btn-primary btnDeleteGroupYes">Yes</button>';
                        newgroupdeletemodal += '<button type="button" class="btn btn-danger" data-dismiss="modal">No</button>';
                        newgroupdeletemodal += '<input type="hidden" id="'+idnewcreatedgroup+'" class="hdId"/>';
                        
                        $(".deletemodals").append(newgroupdeletemodal);
                        
                        setTimeout(function() {
                            $('#mNewGroup').modal('hide');
                        }, 200);
                        
                        } 
                    });
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
        
        //promena imena grupe
        
        $(document).on("click", ".btnChangeGroupName", function() {
            var _this = $(this),
                idgroup = $(this).attr("id"),
                newgroupname = $(this).parent().find(".tbGroupName").val(),
                groupexists = false,
                error = false;
                
            if(newgroupname == ""){
                error = true;
                _this.parent().removeClass("has-success");
                _this.parent().addClass("has-error");
            } else {
                error = false;
                _this.parent().addClass("has-success");
                _this.parent().removeClass("has-error");
            }
                
            $.ajax({
                url: 'admin/getGroups',
                type: 'post',
                success: function(response) {
                    var groupNames = jQuery.parseJSON(response),
                        i = 0;
                    for(i=0; i<groupNames.length;i++){
                        if(groupNames[i] == newgroupname){
                            groupexists = true;
                            break;
                        }
                    }
                    
                    if(!groupexists&&!error)
                    {
                        $.ajax({
                            url: 'admin/changeGroupName',
                            type: 'post',
                            dataType: 'json',
                            data:{NewName:newgroupname,IdGroup:idgroup},
                            success: function(response){
                               $(".panel#"+idgroup).find(".panel-heading").text(" ");
                               $(".panel#"+idgroup).find(".panel-heading").append(newgroupname);
                               
                               _this.parent().addClass("has-success");
                               _this.parent().removeClass("has-error");
                               _this.parent().find(".tbGroupName").attr("placeholder", newgroupname); 
                             }
                        });
                    } else {
                        _this.parent().find(".help-block").append("Group already exits with this name");
                        _this.parent().removeClass("has-success");
			_this.parent().addClass("has-error");
                    }
                }
            });
        });
        
        //modal close event 
        
        $(document).on(".modal", "hidden.bs.modal", function(){
            $(".tbGroupName").val("");
                $(".tbGroupName").parent().removeClass("has-error");
                $(".tbGroupName").parent().removeClass("has-success");
        });
        
        $('.modal').on('hidden.bs.modal', function () {
                $(".tbGroupName").val("");
                $(".tbGroupName").parent().removeClass("has-error");
                $(".tbGroupName").parent().removeClass("has-success");
        });
        
        $('#mNewUser').on('hidden.bs.modal', function () {
                $(".tbEmail").val("");
                $(".tbEmail").parent().removeClass("has-error");
                $(".tbEmail").parent().removeClass("has-success");
                $(".tbKey ").attr("placeholder", " ");
                $(".btnGenerateKey").show();
        });
        
        //generate new user, insert new user in db
        $(".user-exists").hide();
        
        $(".chbAppGroup").click(function() {
           var idapp = $(this).attr("id"),
               idgroup = $(this).parent().find(".hdIdGroup").attr("id");
           
           if($(this).prop('checked')) {
               $.ajax({
                    url: 'admin/addApplicationForGroup',
                    type: 'post',
                    dataType: 'json',
                    data:{IdApp:idapp,IdGroup:idgroup},
                    success: function(response) {
                    }
                });
            } else {
                $.ajax({
                    url: 'admin/removeApplicationForGroup',
                    type: 'post',
                    dataType: 'json',
                    data:{IdApp:idapp,IdGroup:idgroup},
                    success: function(response) {
                    }
                });
            }
        });
        
        //check or uncheck on btn click
        
        $(".btn-application").click(function() {
            var chb = $(this).find(".chbAppGroup").prop("checked"),
                idapp = $(this).find(".chbAppGroup").attr("id"),
                idgroup = $(this).parent().find(".hdIdGroup").attr("id");
            
            if(chb) {
                $(this).find(".chbAppGroup").prop("checked", false);
                $.ajax({
                    url: 'admin/removeApplicationForGroup',
                    type: 'post',
                    dataType: 'json',
                    data:{IdApp:idapp,IdGroup:idgroup},
                    success: function(response) {
                    }
                });
            } else {
                $(this).find(".chbAppGroup").prop("checked", true);
                $.ajax({
                    url: 'admin/addApplicationForGroup',
                    type: 'post',
                    dataType: 'json',
                    data:{IdApp:idapp,IdGroup:idgroup},
                    success: function(response) {
                    }
                });
            }
        });
        
        //refresh on close modal 
        
        $('.mEditGroupApplication').on('hidden.bs.modal', function () {
            window.location.href = "admin/privileges";
        });
        
        $('.mEditApplication').on('hidden.bs.modal', function () {
            $(".tbApplicationName").val("");
            $(".tbLink").val("");
            
            numofnewmenuapplications=0;
        });
        
        //colorpicker
        if($('.demo2').length>0){
            $(function(){
                $('.demo2').colorpicker();
            });
        }
        //iconpicker
        if($(".iconpicker-element").length>0){
        $(".iconpicker-element").iconpicker();
        }
        // creating application 
        $(document).on("click", ".btnCreateApplication", function() {
            var name = $(".tbNewApplicationName"),
                color = $(".tbNewColor"),
                icon = $(".tbNewIcon"),
                link = $(".tbLink"),
                error = false;
            
            $.ajax({
                url: 'admin/getApplications',
                type: 'post',
                dataType: 'json',
                success: function(resposne) {
                    var i;
                    
                    for(i=0;i<resposne.length;i++) {
                        if(name.val()==resposne[i]) {
                            name.parent().removeClass("has-success");
                            name.parent().addClass("has-error");
                            error = true;
                        } else {
                            name.parent().addClass("has-success");
                            name.parent().removeClass("has-error");
                        }
                    }
                }
            });
            
            if(name.val() == "") {
                name.parent().removeClass("has-success");
                name.parent().addClass("has-error");
                error = true;
            } else {
                name.parent().addClass("has-success");
                name.parent().removeClass("has-error");
            }
            
            if(color.val() == "") {
                color.parent().removeClass("has-success");
                color.parent().addClass("has-error");
                error = true;
            } else {
                color.parent().addClass("has-success");
                color.parent().removeClass("has-error");
            }
            
            if(link.val() == "") {
                link.parent().removeClass("has-success");
                link.parent().addClass("has-error");
                error = true;
            } else {
                link.parent().addClass("has-success");
                link.parent().removeClass("has-error");
            }
            
            if(icon.val() == "") {
                icon.parent().removeClass("has-success");
                icon.parent().addClass("has-error");
                error = true;
            } else {
                icon.parent().addClass("has-success");
                icon.parent().removeClass("has-error");
            }
            
            if(!error)
            {
                $.ajax({
                    url: 'admin/addNewApplication',
                    type: 'post',
                    dataType: 'json',
                    data:{Name:name.val(),Link:link.val(),Color:color.val(),Icon:icon.val()},
                    success: function(resposne) {
                        $('#mNewApplication').modal('hide');
                        name.val(" ");
                        link.val(" ");
                        color.val(" ");
                        icon.val(" ");
                        
                        $(".panel-body.panel-applications").text(" ");
                        $(".panel-body.panel-applications").append(resposne.applications);
                        
                        $(".editmodals").text(" ");
                        $(".editmodals").append(resposne.editmodal);
                        
                        $(".iconpicker-element").iconpicker();
                        
                        $(function(){
                            $('.demo2').colorpicker();
                        });
                    }
                });
            }
        });
        
        $(document).on('click' , '.btnEditApplication', function () {
            var id = $(this).parent().parent().parent().parent().parent().attr("aria-labelledby"),
                name = $(this).parent().parent().parent().find(".tbApplicationName"),
                link = $(this).parent().parent().parent().find(".tbLink"),
                color = $(this).parent().parent().parent().find(".tbNewColor").val(),
                icon = $(this).parent().parent().parent().find(".tbNewIcon").val(),
                data;
            
            if(name.val() == "") {
                name = name.attr("placeholder");
            } else {
                name = name.val();
            }
            
            if(link.val() == "") {
                link = link.attr("placeholder");
            } else{
                link = link.val();
            }
            
            var appmenus = [],
            eachinputfield = $(this).parent().parent().parent().find(".appmenu").find(".row").find(".form-group").find("input.form-control:not(.iconpicker-search)"),
            i = 0;

            
            eachinputfield.each(function() {
               
                if($(this).val()=="") {
                    data = $(this).attr("placeholder");
                } else {
                    data = $(this).val();
                }
                
                appmenus.push(data);
            });
                
            $.ajax({
                url: 'admin/editApplication',
                type: 'post',
                dataType: 'json',
                data:{IdApp:id,Name:name,Link:link,Color:color,Icon:icon,AppMenus:appmenus},
                success: function(resposne) {
                    $('#mEditApplication'+id).modal('hide');
                    
                    setTimeout(function() {
                        $(".panel-body.panel-applications").text(" ");
                        $(".panel-body.panel-applications").append(resposne.applications);

                        $(".editmodals").text(" ");
                        $(".editmodals").append(resposne.editmodal);
                        
                        $(".iconpicker-element").iconpicker();
                        
                        $(function(){
                            $('.demo2').colorpicker();
                        });
                    }, 300);
                }
            });
        });
        
        
        $(document).on("click", ".bntDeleteApplication", function() {
            var id = $(this).find("i").attr("id");
            
            $.ajax({
                url: 'admin/deleteApplication',
                type: 'post',
                dataType: 'json',
                data:{IdApp:id},
                success: function(resposne) {
                    $('#mEditApplication'+id).modal('hide');
                    
                    setTimeout(function() {
                        $(".panel-body.panel-applications").text(" ");
                        $(".panel-body.panel-applications").append(resposne.applications);

                        $(".editmodals").text(" ");
                        $(".editmodals").append(resposne.editmodal);
                        
                        $(".iconpicker-element").iconpicker();
                        
                        $(function(){
                            $('.demo2').colorpicker();
                        });
                    }, 300);
                }
            });
            
        });
        
        $('.modal').on('hidden.bs.modal', function () {
                $(".tbNewApplicationName").val("");
                $(".tbLink").val("");
                
                $(".tbNewApplicationName").parent().removeClass("has-error");
                $(".tbNewApplicationName").parent().removeClass("has-success");
                
                $(".tbLink").parent().removeClass("has-error");
                $(".tbLink").parent().removeClass("has-success");
                
                $(".tbNewColor").parent().removeClass("has-error");
                $(".tbNewColor").parent().removeClass("has-success");
                
                $(".tbNewIcon").parent().removeClass("has-error");
                $(".tbNewIcon").parent().removeClass("has-success");
                
        });
        
        
        
        
        
        
       
        //user profile all
        $(".pNewOldPassword").hide();
        $(".pConfirmPassword").hide();
        
        $(".btnChangePasswordYes").click(function() {
           var id =$(this).attr("id"),
               oldpassword = $(".tbOldPassword"),
               newpassword = $(".tbNewPassword"),
               confirmpassword = $(".tbConfirmPassword"),
               error = 0;
               
               if(oldpassword.val() == ""){
                   oldpassword.parent().addClass("has-error");
                   oldpassword.parent().removeClass("has-success");
                   
                   error++;
               } else {
                   $.ajax({
                        url: 'user/checkIfPasswordExists',
                        type: 'post',
                        dataType: 'json',
                        data:{Password:oldpassword.val(),IdUser:id},
                        success: function(resposne) {
                            if(resposne) {
                                oldpassword.parent().removeClass("has-error");
                                oldpassword.parent().addClass("has-success");
                                
                                $(".pNewOldPassword").slideUp(400);
                                
                                if(newpassword.val() == "" || confirmpassword.val() == "") {
                                    newpassword.parent().addClass("has-error");
                                    newpassword.parent().removeClass("has-success");
                                    
                                    confirmpassword.parent().addClass("has-error");
                                    confirmpassword.parent().removeClass("has-success");
                                    
                                    error ++;
                                } else {
                                   if(newpassword.val() == confirmpassword.val()) {
                                        $(".pConfirmPassword").slideUp(400);
                                       
                                        newpassword.parent().addClass("has-success");
                                        newpassword.parent().removeClass("has-error");

                                        confirmpassword.parent().addClass("has-success");
                                        confirmpassword.parent().removeClass("has-error");
                                   } else {
                                        $(".pConfirmPassword").slideDown(400);
                                       
                                        newpassword.parent().addClass("has-error");
                                        newpassword.parent().removeClass("has-success");

                                        confirmpassword.parent().addClass("has-error");
                                        confirmpassword.parent().removeClass("has-success");
                                        
                                        error ++;
                                   }
                                }
                            } else {
                                oldpassword.parent().addClass("has-error");
                                oldpassword.parent().removeClass("has-success");
                                
                                 $(".pNewOldPassword").slideDown(400);
                                 
                                 error++;
                            }
                            
                            if(error == 0){
                                $.ajax({
                                    url: 'user/changePassword',
                                    type: 'post',
                                    dataType: 'json',
                                    data:{Password:newpassword.val(),IdUser:id},
                                    success: function(resposne) {
                                        if(resposne) {
                                            setTimeout(function() {
                                                $(".mChangePassword").modal('hide');
                                            }, 400);
                                        }
                                    }
                                });
                            }
                        }
                    });
                }
        });
        
        
        //register validation 
        
        $(".btnRegister").click(function() {
            var username = $(".tbUsername"), 
                password = $(".tbPassword"),
                confirmpassword = $(".tbConfirmPassword"),
                pop = window.location.href.split("/"),
                key = pop[pop.length-1],
                error = false;
            
            if(username.val()== ""){
            username.parent().removeClass('has-success');
            username.parent().addClass('has-error');
            error = true;
            } else {
                username.parent().removeClass('has-error');
                username.parent().addClass('has-success');
            }

            if(password.val()== ""){
                password.parent().removeClass('has-success');
                password.parent().addClass('has-error');
                error = true;
            } else {
                password.parent().removeClass('has-error');
                password.parent().addClass('has-success');
            }
            
            if(confirmpassword.val()== ""){
                confirmpassword.parent().removeClass('has-success');
                confirmpassword.parent().addClass('has-error');
                error = true;
            } else {
                confirmpassword.parent().removeClass('has-error');
                confirmpassword.parent().addClass('has-success');
            } 
            
            if(password.val() != confirmpassword.val()){
                $(".error").append("<p>Password And Confirm Password Do Not Match");
                $(".error").show(400);
                
                password.parent().removeClass('has-success');
                password.parent().addClass('has-error');
                confirmpassword.parent().removeClass('has-success');
                confirmpassword.parent().addClass('has-error');
                
                error = true;
            } else {
                $(".error").hide(400);
                $(".error").text(" ");
            }
            
            if(!error){
                $.ajax({
                    url: 'user/registerUser',
                    type: 'post',
                    dataType: 'json',
                    data:{Username:username.val(),Password:password.val(),Key:key},
                    success: function(response) {
                        $(".login-panel ").find(".panel-body").text(" ");
                        $(".login-panel ").find(".panel-body").append('<h3 class="text-center">Profile Created <br/><br/><a href="user/" class="btn btn-lg btn-success btn-block">Procede To Cloud</a></h3>');
                        
                } 
            });
            }
        });
        
        $(".btnUserProfileSaveChanges").click(function() {
            var username = $(".tbEditUsername"),
                fullname = $(".tbFullName"),
                email = $(".tbUserEmail"),
                regEmail = /^([\w-]+(?:\.[\w-]+)*)@((?:[\w-]+\.)*\w[\w-]{0,66})\.([a-z]{2,6}(?:\.[a-z]{2})?)$/i,
                error = false;
                
                if(username.val() == "") {
                    username = username.attr("placeholder");
                } else {
                    username = username.val();
                }
                
                if(fullname.val() == "") {
                    fullname = fullname.attr("placeholder");
                } else {
                    fullname = fullname.val();
                }
                
                if(email.val() == "") {
                    email = email.attr("placeholder");
                    $(".error").hide(400);
                } else {
                    if(email.val().match(regEmail)) {
                        $(".error").hide(400);
                    } else {
                        $(".error").show(400);
                        
                        error = true;
                    }
                }
                
                if(!error) {
                    $.ajax({
                        url: 'user/updateUser',
                        type: 'post',
                        dataType: 'json',
                        data:{Username:username,Fullname:fullname,Email:email},
                        success: function(resposne) {
                            $(".update-success").hide();
                            $(".update-success").text(" ");
                            $(".update-success").append("<h3 class='green'>Profile Updated Successfully</h3>");
                            $(".update-success").slideDown(400);
                            
                            setTimeout(function(){
                                $(".update-success").slideUp(400);
                            }, 3000);
                            
                            $(".tbEditUsername").attr("placeholder", username);
                            $(".tbFullName").attr("placeholder", fullname);
                            $(".tbUserEmail").attr("placeholder", email);
                            
                            $(".tbEditUsername").val("");
                            $(".tbFullName").val("");
                            $(".tbUserEmail").val("");
                        }
                    });
                }
        });
        
        
        //addind new app menu item
        
        var numofnewmenuapplications = 0;
        $(document).on("click", ".btnAddNewAppMenu", function() {
            var content = '',
                appmenuname = "New "+numofnewmenuapplications,
                appmenulink = "Link "+numofnewmenuapplications,
                appmenuicon = "fa-archive",
                idapp = $(this).parent().parent().parent().parent().parent().attr("aria-labelledby"),
                _this = $(this);
                
            $.ajax({
                url: 'admin/insertApplicationMenu',
                type: 'post',
                dataType: 'json',
                data:{IdApp:idapp,AppMenuName:appmenuname,AppMenuLink:appmenulink,AppMenuIcon:appmenuicon},
                success: function(response) {
                    content += "<div class='row row"+numofnewmenuapplications+"'>";
                    content += "<div class='col-xs-3'>";
                    content += " <div class='form-group'>";
                    content += '<button class="btn btn-danger float-right btnDeleteAppMenu" type="button"><i class="fa  fa-minus" id="N_'+numofnewmenuapplications+'"></i></button>';
                    content += "</div></div>";
                    content += "<div class='col-xs-3'>";
                    content += " <div class='form-group'>";
                    content += '<input class="form-control" id="hdIdAppMenu" placeholder="'+response+'" type="hidden" />';
                    content += '<input class="form-control" placeholder="New '+numofnewmenuapplications+'" type="text" />';
                    content += "</div></div>";
                    content += "<div class='col-xs-3'>";
                    content += "<div class='form-group'>";
                    content += '<input class="form-control" placeholder="Link '+numofnewmenuapplications+'" type="text" />';
                    content += "</div></div>";
                    content += "<div class='col-xs-3'>";
                    content += "<div class='form-group'>";
                    content += '<div class="input-group iconpicker-container">';
                    content += '<input data-placement="bottomRight" class="form-control icp icp-auto iconpicker-element iconpicker-input tbNewIcon" value="fa-archive" type="text">';
                    content += '<span class="input-group-addon">';
                    content += '<i class="fa fa-archive"></i></span>';
                    content += "</div></div></div></div>";
                    
                    _this.hide();
            
                    _this.parent().append(content);
                    _this.parent().append('<button class="btn btn-primary float-right btnAddNewAppMenu" type="button"><i class="fa  fa-plus"></i></button>');

                    _this.remove();

                    $(".row"+numofnewmenuapplications).hide(0);

                    $(".row"+numofnewmenuapplications).slideDown(1000);

                    $(".iconpicker-element").iconpicker();
                }
            });
            
            numofnewmenuapplications++;
        });
        
        $(document).on("click", ".btnDeleteAppMenu", function() {
            var id = $(this).find("i").attr("id");
            
            $(this).parent().parent().parent().hide(400);
            setTimeout(function() {
                $(this).parent().parent().parent().remove();
            }, 800);
            
            $.ajax({
                url: 'admin/deleteApplicationMenu',
                type: 'post',
                dataType: 'json',
                data:{IdAppMenu:id},
                success: function(response) {
                }
            });
        });
        
        //about us animation
        $(".more").hide();
        $(".more2").hide();
        
        if($(window).width()>992){
            

            $(".jumbotron").hover(function() {
                var _this = $(this);

                $(this).find(".img-circle").stop(true,true).animate({
                  marginRight: '+450px' 
               }, function () {
                   _this.find(".more").stop(true,true).slideDown(400);
               });
            }, function() {
                 var _this = $(this);
                $(this).find(".more").stop(true,true).slideUp(500);

                setTimeout(function() {
                     _this.find(".img-circle").stop(true,true).animate({
                        marginRight: '0px' 
                    }, function(){
                    });
                }, 500);
            });
        } else {
            $(".jumbotron").click(function() {
                $(this).find(".more2").toggle(400);
            });
            
        }
});
=======
$(document).ready(function() {
	
	//Animacija na aplikacionoj stranici 
	
	$(".app-name").hide();
	
        $(document).on("mouseenter", ".app", function(){
            $(this).find(".app-name").stop(true,true).fadeIn(500); 
        });
        
        $(document).on("mouseleave", ".app", function(){
            $(this).find(".app-name").stop(true,true).fadeOut(500);
        });

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
        });	
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
                    $(".users."+idgroup).append('<button type="button" class="btn btn-default btn-user">'+username+'<i class="fa fa-trash-o icon-remove-user" id="'+iduser+'"><input type="hidden" id="'+idgroup+'" class="hdIdGroup"/></i> <i class="fa fa-plus icon-add-admin" id="'+iduser+'"><input type="hidden" id="'+idgroup+'" class="hdIdGroup"/></i></button>');
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
                    $(".admins."+idgroup).append('<button type="button" class="btn btn-primary btn-admin">'+username+'<i class="fa fa-minus icon-remove-admin" id="'+iduser+'"><input type="hidden" id="'+idgroup+'" class="hdIdGroup"/></i></button>').show(500);
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
                    $(".newusers."+idgroup).append('<button type="button" class="btn btn-default btn-admin">'+username+'<i class="fa fa-plus icon-add-newuser" id="'+iduser+'"><input type="hidden" id="'+idgroup+'" class="hdIdGroup"/></i></button>').show(500);
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
                    $(".users."+idgroup).append('<button type="button" class="btn btn-default btn-user">'+username+'   <i class="fa fa-trash-o icon-remove-user" id="'+iduser+'"><input type="hidden" id="'+idgroup+'" class="hdIdGroup"/></i> <i class="fa fa-plus icon-add-admin" id="'+iduser+'"><input type="hidden" id="'+idgroup+'" class="hdIdGroup"/></i></button>').show(500);
                    $(".panel#"+idgroup).find(".group-user").append('<button type="button" id="'+iduser+'" class="btn btn-info btn-xs btn-no-hover btn-admin">'+username+'</button>');
                } 
            });
        });
        
        $(document).on("keyup", ".tbSearchNewUser", function() {
            var idgroup = $(this).attr("id"),
                username = $(this).val(),
                idnewcreatedgroup;
            
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
                    
                    $.ajax({
                            url: 'admin/getUsers',
                            type: 'post',
                            dataType: 'json',
                            data:{IdGroup:response},
                            success: function(users) {
                       $('#mNewGroup').find('modal-body').append('<div class="text-center"><h3>Group '+groupname.val()+' added</h3></div>');
                     
                        var numberofcolums = $(".main-panel").find(".panel-body").find(".row .col-lg-4").length,
                            newgroupcontent = "",
                            newgroupeditmodal = "",
                            newgroupdeletemodal = "",
                            idnewcreatedgroup = response;
                    
                        if(numberofcolums%3 == 0) {
                            newgroupcontent += '<div class="row">';
                        }
                        newgroupcontent += '<div class="col-lg-4">';
                        newgroupcontent += '<div id="'+idnewcreatedgroup+'" class="panel panel-primary">';
                        newgroupcontent += ' <div class="panel-heading">'+groupname.val()+'</div>';
                        newgroupcontent += '<div class="panel-body">';
                        newgroupcontent += '<div class="group-admin">';
                        newgroupcontent += '</div><hr>';
                        newgroupcontent += '<div class="group-user">';
                        newgroupcontent += '</div>';
                        newgroupcontent += '</div>';
                        newgroupcontent += '<div class="panel-footer group-panel-footer">';
                        newgroupcontent += ' <button type="button" class="btn btn-outline btn-primary btn-xs pull-left" data-toggle="modal" data-target="#mEditGroup'+idnewcreatedgroup+'">Edit</button>';
                        newgroupcontent += '<button type="button" class="btn btn-outline btn-danger btn-xs pull-right" data-toggle="modal" data-target="#mDeleteGroup'+idnewcreatedgroup+'">Delete</button>';
                        newgroupcontent += '</div></div>';
                        
                        if(numberofcolums%3 == 0) {
                            newgroupcontent += '</div>';
                        }
                        
                        if(numberofcolums%3 == 0){
                            $(".panel-groups").append(newgroupcontent);
                        } else {
                            $(".main-panel").find(".row:last-child").append(newgroupcontent);
                        }
                        
                        newgroupeditmodal += '<div class="modal fade '+idnewcreatedgroup+'" id="mEditGroup'+idnewcreatedgroup+'" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">';
                        newgroupeditmodal += '<div class="modal-dialog" role="document">';
                        newgroupeditmodal += '<div class="modal-content">';
                        newgroupeditmodal += '<div class="modal-header">';
                        newgroupeditmodal += '<button type="button" class="close" data-dismiss="modal" aria-label="Close">';
                        newgroupeditmodal += '<span aria-hidden="true">×</span></button>';
                        newgroupeditmodal += '<h4 class="modal-title" id="myModalLabel">Edit Group</h4></div>';
                        newgroupeditmodal += '<div class="modal-body"><div class="form-group"><label>Group Name</label>';
                        newgroupeditmodal += '<input class="form-control tbGroupName" placeholder="'+groupname.val()+'" id="'+idnewcreatedgroup+'" ><button type="button" class="btn btn-success pull-right btnChangeGroupName" id="'+idnewcreatedgroup+'">Change Name</button></div>';
                        newgroupeditmodal += '<div class="form-group"><label>Admins</label><div class="admins '+idnewcreatedgroup+'">';
                        newgroupeditmodal += '</div></div>';
                        newgroupeditmodal += '<div class="form-group"><label>Members</label><div class="users '+idnewcreatedgroup+'">';
                        newgroupeditmodal += '</div>';
                        newgroupeditmodal += '<div class="form-group"><label>New Memers</label><input class="form-control tbSearchNewUser" placeholder="search..." id="'+idnewcreatedgroup+'"><div class="newusers '+idnewcreatedgroup+'">';
                        
                         
                        newgroupeditmodal += users;
                        
                        newgroupeditmodal += '</div></div></div></div><div class="modal-footer">';
                        newgroupeditmodal += '</div></div></div></div>';
                        
                        $(".editmodals").append(newgroupeditmodal);
                        
                        
                        newgroupdeletemodal += '<div class="modal fade mDeleteGroup" id="mDeleteGroup'+idnewcreatedgroup+'" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">';
                        newgroupdeletemodal += '<div class="modal-dialog" role="document">';
                        newgroupdeletemodal += '<div class="modal-content">';
                        newgroupdeletemodal += '<div class="modal-header">';
                        newgroupdeletemodal += '<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>';
                        newgroupdeletemodal += '<h4 class="modal-title" id="myModalLabel">Delete Group</h4>';
                        newgroupdeletemodal += '</div>';
                        newgroupdeletemodal += '<div class="modal-body text-center">';
                        newgroupdeletemodal += 'Delete group '+groupname.val()+'?';
                        newgroupdeletemodal += '</div>';
                        newgroupdeletemodal += '<div class="modal-footer text-center">';
                        newgroupdeletemodal += '<button type="button" class="btn btn-primary btnDeleteGroupYes">Yes</button>';
                        newgroupdeletemodal += '<button type="button" class="btn btn-danger" data-dismiss="modal">No</button>';
                        newgroupdeletemodal += '<input type="hidden" id="'+idnewcreatedgroup+'" class="hdId"/>';
                        
                        $(".deletemodals").append(newgroupdeletemodal);
                        
                        setTimeout(function() {
                            $('#mNewGroup').modal('hide');
                        }, 200);
                        
                        } 
                    });
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
        
        //promena imena grupe
        
        $(document).on("click", ".btnChangeGroupName", function() {
            var _this = $(this),
                idgroup = $(this).attr("id"),
                newgroupname = $(this).parent().find(".tbGroupName").val(),
                groupexists = false,
                error = false;
                
            if(newgroupname == ""){
                error = true;
                _this.parent().removeClass("has-success");
                _this.parent().addClass("has-error");
            } else {
                error = false;
                _this.parent().addClass("has-success");
                _this.parent().removeClass("has-error");
            }
                
            $.ajax({
                url: 'admin/getGroups',
                type: 'post',
                success: function(response) {
                    var groupNames = jQuery.parseJSON(response),
                        i = 0;
                    for(i=0; i<groupNames.length;i++){
                        if(groupNames[i] == newgroupname){
                            groupexists = true;
                            break;
                        }
                    }
                    
                    if(!groupexists&&!error)
                    {
                        $.ajax({
                            url: 'admin/changeGroupName',
                            type: 'post',
                            dataType: 'json',
                            data:{NewName:newgroupname,IdGroup:idgroup},
                            success: function(response){
                               $(".panel#"+idgroup).find(".panel-heading").text(" ");
                               $(".panel#"+idgroup).find(".panel-heading").append(newgroupname);
                               
                               _this.parent().addClass("has-success");
                               _this.parent().removeClass("has-error");
                               _this.parent().find(".tbGroupName").attr("placeholder", newgroupname); 
                             }
                        });
                    } else {
                        _this.parent().find(".help-block").append("Group already exits with this name");
                        _this.parent().removeClass("has-success");
			_this.parent().addClass("has-error");
                    }
                }
            });
        });
        
        //modal close event 
        
        $(document).on(".modal", "hidden.bs.modal", function(){
            $(".tbGroupName").val("");
                $(".tbGroupName").parent().removeClass("has-error");
                $(".tbGroupName").parent().removeClass("has-success");
        });
        
        $('.modal').on('hidden.bs.modal', function () {
                $(".tbGroupName").val("");
                $(".tbGroupName").parent().removeClass("has-error");
                $(".tbGroupName").parent().removeClass("has-success");
        });
        
        $('#mNewUser').on('hidden.bs.modal', function () {
                $(".tbEmail").val("");
                $(".tbEmail").parent().removeClass("has-error");
                $(".tbEmail").parent().removeClass("has-success");
                $(".tbKey ").attr("placeholder", " ");
                $(".btnGenerateKey").show();
        });
        
        //generate new user, insert new user in db
        $(".user-exists").hide();
        
        $(".chbAppGroup").click(function() {
           var idapp = $(this).attr("id"),
               idgroup = $(this).parent().find(".hdIdGroup").attr("id");
           
           if($(this).prop('checked')) {
               $.ajax({
                    url: 'admin/addApplicationForGroup',
                    type: 'post',
                    dataType: 'json',
                    data:{IdApp:idapp,IdGroup:idgroup},
                    success: function(response) {
                    }
                });
            } else {
                $.ajax({
                    url: 'admin/removeApplicationForGroup',
                    type: 'post',
                    dataType: 'json',
                    data:{IdApp:idapp,IdGroup:idgroup},
                    success: function(response) {
                    }
                });
            }
        });
        
        //check or uncheck on btn click
        
        $(".btn-application").click(function() {
            var chb = $(this).find(".chbAppGroup").prop("checked"),
                idapp = $(this).find(".chbAppGroup").attr("id"),
                idgroup = $(this).parent().find(".hdIdGroup").attr("id");
            
            if(chb) {
                $(this).find(".chbAppGroup").prop("checked", false);
                $.ajax({
                    url: 'admin/removeApplicationForGroup',
                    type: 'post',
                    dataType: 'json',
                    data:{IdApp:idapp,IdGroup:idgroup},
                    success: function(response) {
                    }
                });
            } else {
                $(this).find(".chbAppGroup").prop("checked", true);
                $.ajax({
                    url: 'admin/addApplicationForGroup',
                    type: 'post',
                    dataType: 'json',
                    data:{IdApp:idapp,IdGroup:idgroup},
                    success: function(response) {
                    }
                });
            }
        });
        
        //refresh on close modal 
        
        $('.mEditGroupApplication').on('hidden.bs.modal', function () {
            window.location.href = "admin/privileges";
        });
        
        $('.mEditApplication').on('hidden.bs.modal', function () {
            $(".tbApplicationName").val("");
            $(".tbLink").val("");
            
            numofnewmenuapplications=0;
        });
        
        //colorpicker
        if($('.demo2').length>0){
            $(function(){
                $('.demo2').colorpicker();
            });
        }
        //iconpicker
        if($(".iconpicker-element").length>0){
        $(".iconpicker-element").iconpicker();
        }
        // creating application 
        $(document).on("click", ".btnCreateApplication", function() {
            var name = $(".tbNewApplicationName"),
                color = $(".tbNewColor"),
                icon = $(".tbNewIcon"),
                link = $(".tbLink"),
                error = false;
            
            $.ajax({
                url: 'admin/getApplications',
                type: 'post',
                dataType: 'json',
                success: function(resposne) {
                    var i;
                    
                    for(i=0;i<resposne.length;i++) {
                        if(name.val()==resposne[i]) {
                            name.parent().removeClass("has-success");
                            name.parent().addClass("has-error");
                            error = true;
                        } else {
                            name.parent().addClass("has-success");
                            name.parent().removeClass("has-error");
                        }
                    }
                }
            });
            
            if(name.val() == "") {
                name.parent().removeClass("has-success");
                name.parent().addClass("has-error");
                error = true;
            } else {
                name.parent().addClass("has-success");
                name.parent().removeClass("has-error");
            }
            
            if(color.val() == "") {
                color.parent().removeClass("has-success");
                color.parent().addClass("has-error");
                error = true;
            } else {
                color.parent().addClass("has-success");
                color.parent().removeClass("has-error");
            }
            
            if(link.val() == "") {
                link.parent().removeClass("has-success");
                link.parent().addClass("has-error");
                error = true;
            } else {
                link.parent().addClass("has-success");
                link.parent().removeClass("has-error");
            }
            
            if(icon.val() == "") {
                icon.parent().removeClass("has-success");
                icon.parent().addClass("has-error");
                error = true;
            } else {
                icon.parent().addClass("has-success");
                icon.parent().removeClass("has-error");
            }
            
            if(!error)
            {
                $.ajax({
                    url: 'admin/addNewApplication',
                    type: 'post',
                    dataType: 'json',
                    data:{Name:name.val(),Link:link.val(),Color:color.val(),Icon:icon.val()},
                    success: function(resposne) {
                        $('#mNewApplication').modal('hide');
                        name.val(" ");
                        link.val(" ");
                        color.val(" ");
                        icon.val(" ");
                        
                        $(".panel-body.panel-applications").text(" ");
                        $(".panel-body.panel-applications").append(resposne.applications);
                        
                        $(".editmodals").text(" ");
                        $(".editmodals").append(resposne.editmodal);
                        
                        $(".iconpicker-element").iconpicker();
                        
                        $(function(){
                            $('.demo2').colorpicker();
                        });
                    }
                });
            }
        });
        
        $(document).on('click' , '.btnEditApplication', function () {
            var id = $(this).parent().parent().parent().parent().parent().attr("aria-labelledby"),
                name = $(this).parent().parent().parent().find(".tbApplicationName"),
                link = $(this).parent().parent().parent().find(".tbLink"),
                color = $(this).parent().parent().parent().find(".tbNewColor").val(),
                icon = $(this).parent().parent().parent().find(".tbNewIcon").val(),
                data;
            
            if(name.val() == "") {
                name = name.attr("placeholder");
            } else {
                name = name.val();
            }
            
            if(link.val() == "") {
                link = link.attr("placeholder");
            } else{
                link = link.val();
            }
            
            var appmenus = [],
            eachinputfield = $(this).parent().parent().parent().find(".appmenu").find(".row").find(".form-group").find("input.form-control:not(.iconpicker-search)"),
            i = 0;

            
            eachinputfield.each(function() {
               
                if($(this).val()=="") {
                    data = $(this).attr("placeholder");
                } else {
                    data = $(this).val();
                }
                
                appmenus.push(data);
            });
                
            $.ajax({
                url: 'admin/editApplication',
                type: 'post',
                dataType: 'json',
                data:{IdApp:id,Name:name,Link:link,Color:color,Icon:icon,AppMenus:appmenus},
                success: function(resposne) {
                    $('#mEditApplication'+id).modal('hide');
                    
                    setTimeout(function() {
                        $(".panel-body.panel-applications").text(" ");
                        $(".panel-body.panel-applications").append(resposne.applications);

                        $(".editmodals").text(" ");
                        $(".editmodals").append(resposne.editmodal);
                        
                        $(".iconpicker-element").iconpicker();
                        
                        $(function(){
                            $('.demo2').colorpicker();
                        });
                    }, 300);
                }
            });
        });
        
        
        $(document).on("click", ".bntDeleteApplication", function() {
            var id = $(this).find("i").attr("id");
            
            $.ajax({
                url: 'admin/deleteApplication',
                type: 'post',
                dataType: 'json',
                data:{IdApp:id},
                success: function(resposne) {
                    $('#mEditApplication'+id).modal('hide');
                    
                    setTimeout(function() {
                        $(".panel-body.panel-applications").text(" ");
                        $(".panel-body.panel-applications").append(resposne.applications);

                        $(".editmodals").text(" ");
                        $(".editmodals").append(resposne.editmodal);
                        
                        $(".iconpicker-element").iconpicker();
                        
                        $(function(){
                            $('.demo2').colorpicker();
                        });
                    }, 300);
                }
            });
            
        });
        
        $('.modal').on('hidden.bs.modal', function () {
                $(".tbNewApplicationName").val("");
                $(".tbLink").val("");
                
                $(".tbNewApplicationName").parent().removeClass("has-error");
                $(".tbNewApplicationName").parent().removeClass("has-success");
                
                $(".tbLink").parent().removeClass("has-error");
                $(".tbLink").parent().removeClass("has-success");
                
                $(".tbNewColor").parent().removeClass("has-error");
                $(".tbNewColor").parent().removeClass("has-success");
                
                $(".tbNewIcon").parent().removeClass("has-error");
                $(".tbNewIcon").parent().removeClass("has-success");
                
        });
        
        
        
        
        
        
       
        //user profile all
        $(".pNewOldPassword").hide();
        $(".pConfirmPassword").hide();
        
        $(".btnChangePasswordYes").click(function() {
           var id =$(this).attr("id"),
               oldpassword = $(".tbOldPassword"),
               newpassword = $(".tbNewPassword"),
               confirmpassword = $(".tbConfirmPassword"),
               error = 0;
               
               if(oldpassword.val() == ""){
                   oldpassword.parent().addClass("has-error");
                   oldpassword.parent().removeClass("has-success");
                   
                   error++;
               } else {
                   $.ajax({
                        url: 'user/checkIfPasswordExists',
                        type: 'post',
                        dataType: 'json',
                        data:{Password:oldpassword.val(),IdUser:id},
                        success: function(resposne) {
                            if(resposne) {
                                oldpassword.parent().removeClass("has-error");
                                oldpassword.parent().addClass("has-success");
                                
                                $(".pNewOldPassword").slideUp(400);
                                
                                if(newpassword.val() == "" || confirmpassword.val() == "") {
                                    newpassword.parent().addClass("has-error");
                                    newpassword.parent().removeClass("has-success");
                                    
                                    confirmpassword.parent().addClass("has-error");
                                    confirmpassword.parent().removeClass("has-success");
                                    
                                    error ++;
                                } else {
                                   if(newpassword.val() == confirmpassword.val()) {
                                        $(".pConfirmPassword").slideUp(400);
                                       
                                        newpassword.parent().addClass("has-success");
                                        newpassword.parent().removeClass("has-error");

                                        confirmpassword.parent().addClass("has-success");
                                        confirmpassword.parent().removeClass("has-error");
                                   } else {
                                        $(".pConfirmPassword").slideDown(400);
                                       
                                        newpassword.parent().addClass("has-error");
                                        newpassword.parent().removeClass("has-success");

                                        confirmpassword.parent().addClass("has-error");
                                        confirmpassword.parent().removeClass("has-success");
                                        
                                        error ++;
                                   }
                                }
                            } else {
                                oldpassword.parent().addClass("has-error");
                                oldpassword.parent().removeClass("has-success");
                                
                                 $(".pNewOldPassword").slideDown(400);
                                 
                                 error++;
                            }
                            
                            if(error == 0){
                                $.ajax({
                                    url: 'user/changePassword',
                                    type: 'post',
                                    dataType: 'json',
                                    data:{Password:newpassword.val(),IdUser:id},
                                    success: function(resposne) {
                                        if(resposne) {
                                            setTimeout(function() {
                                                $(".mChangePassword").modal('hide');
                                            }, 400);
                                        }
                                    }
                                });
                            }
                        }
                    });
                }
        });
        
        
        //register validation 
        
        $(".btnRegister").click(function() {
            var username = $(".tbUsername"), 
                password = $(".tbPassword"),
                confirmpassword = $(".tbConfirmPassword"),
                pop = window.location.href.split("/"),
                key = pop[pop.length-1],
                error = false;
            
            if(username.val()== ""){
            username.parent().removeClass('has-success');
            username.parent().addClass('has-error');
            error = true;
            } else {
                username.parent().removeClass('has-error');
                username.parent().addClass('has-success');
            }

            if(password.val()== ""){
                password.parent().removeClass('has-success');
                password.parent().addClass('has-error');
                error = true;
            } else {
                password.parent().removeClass('has-error');
                password.parent().addClass('has-success');
            }
            
            if(confirmpassword.val()== ""){
                confirmpassword.parent().removeClass('has-success');
                confirmpassword.parent().addClass('has-error');
                error = true;
            } else {
                confirmpassword.parent().removeClass('has-error');
                confirmpassword.parent().addClass('has-success');
            } 
            
            if(password.val() != confirmpassword.val()){
                $(".error").append("<p>Password And Confirm Password Do Not Match");
                $(".error").show(400);
                
                password.parent().removeClass('has-success');
                password.parent().addClass('has-error');
                confirmpassword.parent().removeClass('has-success');
                confirmpassword.parent().addClass('has-error');
                
                error = true;
            } else {
                $(".error").hide(400);
                $(".error").text(" ");
            }
            
            if(!error){
                $.ajax({
                    url: 'user/registerUser',
                    type: 'post',
                    dataType: 'json',
                    data:{Username:username.val(),Password:password.val(),Key:key},
                    success: function(response) {
                        $(".login-panel ").find(".panel-body").text(" ");
                        $(".login-panel ").find(".panel-body").append('<h3 class="text-center">Profile Created <br/><br/><a href="user/" class="btn btn-lg btn-success btn-block">Procede To Cloud</a></h3>');
                        
                } 
            });
            }
        });
        
        $(".btnUserProfileSaveChanges").click(function() {
            var username = $(".tbEditUsername"),
                fullname = $(".tbFullName"),
                email = $(".tbUserEmail"),
                regEmail = /^([\w-]+(?:\.[\w-]+)*)@((?:[\w-]+\.)*\w[\w-]{0,66})\.([a-z]{2,6}(?:\.[a-z]{2})?)$/i,
                error = false;
                
                if(username.val() == "") {
                    username = username.attr("placeholder");
                } else {
                    username = username.val();
                }
                
                if(fullname.val() == "") {
                    fullname = fullname.attr("placeholder");
                } else {
                    fullname = fullname.val();
                }
                
                if(email.val() == "") {
                    email = email.attr("placeholder");
                    $(".error").hide(400);
                } else {
                    if(email.val().match(regEmail)) {
                        $(".error").hide(400);
                    } else {
                        $(".error").show(400);
                        
                        error = true;
                    }
                }
                
                if(!error) {
                    $.ajax({
                        url: 'user/updateUser',
                        type: 'post',
                        dataType: 'json',
                        data:{Username:username,Fullname:fullname,Email:email},
                        success: function(resposne) {
                            $(".update-success").hide();
                            $(".update-success").text(" ");
                            $(".update-success").append("<h3 class='green'>Profile Updated Successfully</h3>");
                            $(".update-success").slideDown(400);
                            
                            setTimeout(function(){
                                $(".update-success").slideUp(400);
                            }, 3000);
                            
                            $(".tbEditUsername").attr("placeholder", username);
                            $(".tbFullName").attr("placeholder", fullname);
                            $(".tbUserEmail").attr("placeholder", email);
                            
                            $(".tbEditUsername").val("");
                            $(".tbFullName").val("");
                            $(".tbUserEmail").val("");
                        }
                    });
                }
        });
        
        
        //addind new app menu item
        
        var numofnewmenuapplications = 0;
        $(document).on("click", ".btnAddNewAppMenu", function() {
            var content = '',
                appmenuname = "New "+numofnewmenuapplications,
                appmenulink = "Link "+numofnewmenuapplications,
                appmenuicon = "fa-archive",
                idapp = $(this).parent().parent().parent().parent().parent().attr("aria-labelledby"),
                _this = $(this);
                
            $.ajax({
                url: 'admin/insertApplicationMenu',
                type: 'post',
                dataType: 'json',
                data:{IdApp:idapp,AppMenuName:appmenuname,AppMenuLink:appmenulink,AppMenuIcon:appmenuicon},
                success: function(response) {
                    content += "<div class='row row"+numofnewmenuapplications+"'>";
                    content += "<div class='col-xs-3'>";
                    content += " <div class='form-group'>";
                    content += '<button class="btn btn-danger float-right btnDeleteAppMenu" type="button"><i class="fa  fa-minus" id="N_'+numofnewmenuapplications+'"></i></button>';
                    content += "</div></div>";
                    content += "<div class='col-xs-3'>";
                    content += " <div class='form-group'>";
                    content += '<input class="form-control" id="hdIdAppMenu" placeholder="'+response+'" type="hidden" />';
                    content += '<input class="form-control" placeholder="New '+numofnewmenuapplications+'" type="text" />';
                    content += "</div></div>";
                    content += "<div class='col-xs-3'>";
                    content += "<div class='form-group'>";
                    content += '<input class="form-control" placeholder="Link '+numofnewmenuapplications+'" type="text" />';
                    content += "</div></div>";
                    content += "<div class='col-xs-3'>";
                    content += "<div class='form-group'>";
                    content += '<div class="input-group iconpicker-container">';
                    content += '<input data-placement="bottomRight" class="form-control icp icp-auto iconpicker-element iconpicker-input tbNewIcon" value="fa-archive" type="text">';
                    content += '<span class="input-group-addon">';
                    content += '<i class="fa fa-archive"></i></span>';
                    content += "</div></div></div></div>";
                    
                    _this.hide();
            
                    _this.parent().append(content);
                    _this.parent().append('<button class="btn btn-primary float-right btnAddNewAppMenu" type="button"><i class="fa  fa-plus"></i></button>');

                    _this.remove();

                    $(".row"+numofnewmenuapplications).hide(0);

                    $(".row"+numofnewmenuapplications).slideDown(1000);

                    $(".iconpicker-element").iconpicker();
                }
            });
            
            numofnewmenuapplications++;
        });
        
        $(document).on("click", ".btnDeleteAppMenu", function() {
            var id = $(this).find("i").attr("id");
            
            $(this).parent().parent().parent().hide(400);
            setTimeout(function() {
                $(this).parent().parent().parent().remove();
            }, 800);
            
            $.ajax({
                url: 'admin/deleteApplicationMenu',
                type: 'post',
                dataType: 'json',
                data:{IdAppMenu:id},
                success: function(response) {
                }
            });
        });
        
        //about us animation
        $(".more").hide();
        $(".more2").hide();
        
        if($(window).width()>992){
            

            $(".jumbotron").hover(function() {
                var _this = $(this);

                $(this).find(".img-circle").stop(true,true).animate({
                  marginRight: '+450px' 
               }, function () {
                   _this.find(".more").stop(true,true).slideDown(400);
               });
            }, function() {
                 var _this = $(this);
                $(this).find(".more").stop(true,true).slideUp(500);

                setTimeout(function() {
                     _this.find(".img-circle").stop(true,true).animate({
                        marginRight: '0px' 
                    }, function(){
                    });
                }, 500);
            });
        } else {
            $(".jumbotron").click(function() {
                $(this).find(".more2").toggle(400);
            });
            
        }
});
>>>>>>> master
