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
        
        //Ajax za delete grupe
        $(".btnDeleteGroupYes").click(function() {
            var id = $(this).parent().find(".hdId").val();
            $.ajax({
                url: '',
                type: '',
                dataType: '',
                data: '',
                success: function(response) {
                    
                }
            });
        });
        
        //group modal, remove admin
        
        $(".icon-remove-admin").hide();
        
        $(".icon-remove-admin").hover(function() {
            $(this).css({"color":"red", "opacity":"0.6"});
        }, function() {
            $(this).css({"color":"white", "opacity":"0.6"});
        });
        
        $(".btn-admin").hover(function() {
            $(this).find(".icon-remove-admin").stop(true,true).show(500);
        }, function() {
            $(this).find(".icon-remove-admin").stop(true,true).hide(500);
        });
        
        $(".icon-remove-admin").click(function() {
            var _this = $(this),
                iduser = $(this).attr("id"),
                idgroup = $(this).find(".hdIdGroup").attr("id");
            
            $.ajax({
                url: 'admin/removeAdmin',
                type: 'post',
                dataType: 'json',
                data:{IdUser:iduser,IdGroup:idgroup},
                success: function(response) {
                    _this.parent().hide(500);
                } 
            });
            
            
        });

});