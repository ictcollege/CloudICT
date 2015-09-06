$(document).ready(function() {
	
	//Animacija na aplikacionoj stranici 
	
	$(".app-name").hide(0);
	$(".app").hover(function() {
		$(this).find(".app-name").stop(true,true).fadeIn(500); 
	}, function() {
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
        
        //generate new user, insert new user in db
        $(".user-exists").hide();
        
        $(".btnGenerateKey").click(function () {
            var _this = $(this),
                email = $(".tbEmail"),
                regEmail = /^([\w-]+(?:\.[\w-]+)*)@((?:[\w-]+\.)*\w[\w-]{0,66})\.([a-z]{2,6}(?:\.[a-z]{2})?)$/i,
                error = false;
                
            if(email.val().match(regEmail)) {
                email.parent().addClass("has-success");
                email.parent().removeClass("has-error");
                error = false;
            } else {
                email.parent().removeClass("has-success");
                email.parent().addClass("has-error");
                error = true;
            }
            
            if(!error) {
                $.ajax({
                    url: 'admin/checkIfEmailExists',
                    type: 'post',
                    dataType: 'json',
                    data:{Email:email.val()},
                    success: function(response) {
                        if(response == 0) {
                            $(".user-exists").hide(400);
                            $.ajax({
                                url: 'admin/insertUser',
                                type: 'post',
                                dataType: 'json',
                                data:{Email:email.val()},
                                success: function(response) {
                                    $(".tbKey").attr("placeholder", response);
                                    $(".btnSendKey").removeClass("disabled");
                                    $(".btnGenerateKey").hide(400);
                                }
                            });
                        } else {
                            email.parent().removeClass("has-success");
                            email.parent().addClass("has-error");
                            $(".user-exists").show(400);
                        }
                    }
                });
            }
        });
        
        //user bootstrap datatables
        var url = window.location.href,
            pop = url.split("/"),
            controller = pop[pop.length-1];
        if(controller == "users"){
            
            $(".tableusers").DataTable( {
                "ajax": 'admin/getAllUsers',
                responsive: true
            });
        }
        
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
        
        //colorpicker
        $(function(){
            $('.demo2').colorpicker();
        });
        
        //iconpicker
        
        $(".iconpicker-element").iconpicker({title: false, // Popover title (optional) only if specified in the template
    selected: false, // use this value as the current item and ignore the original
    defaultValue: false, // use this value as the current item if input or element value is empty
    placement: 'bottom', // (has some issues with auto and CSS). auto, top, bottom, left, right
    collision: 'none', // If true, the popover will be repositioned to another position when collapses with the window borders
    animation: true, // fade in/out on show/hide ?
    //hide iconpicker automatically when a value is picked. it is ignored if mustAccept is not false and the accept button is visible
    hideOnSelect: false,
    showFooter: false,
    searchInFooter: false, // If true, the search will be added to the footer instead of the title
    mustAccept: false, // only applicable when there's an iconpicker-btn-accept button in the popover footer
    selectedCustomClass: 'bg-primary', // Appends this class when to the selected item
    icons: [], // list of icon classes (declared at the bottom of this script for maintainability)
    fullClassFormatter: function(val) {
        return 'fa ' + val;
    },
    input: 'input,.iconpicker-input', // children input selector
    inputSearch: false, // use the input as a search box too?
    container: false, //  Appends the popover to a specific element. If not set, the selected element or element parent is used
    component: '.input-group-addon,.iconpicker-component', // children component jQuery selector or object, relative to the container element
    // Plugin templates:
    templates: {
        popover: '<div class="iconpicker-popover popover"><div class="arrow"></div>' +
            '<div class="popover-title"></div><div class="popover-content"></div></div>',
        footer: '<div class="popover-footer"></div>',
        buttons: '<button class="iconpicker-btn iconpicker-btn-cancel btn btn-default btn-sm">Cancel</button>' +
            ' <button class="iconpicker-btn iconpicker-btn-accept btn btn-primary btn-sm">Accept</button>',
        search: '<input type="search" class="form-control iconpicker-search" placeholder="Type to filter" />',
        iconpicker: '<div class="iconpicker"><div class="iconpicker-items"></div></div>',
        iconpickerItem: '<i class="iconpicker-item></i>',
    }});
        
});
