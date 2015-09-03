<script>
$(document).ready(function () {
			
			
		//	waitForNotification(); /* Start the inital request */
			
			var toggleStatus=true;
			
			
			$('#toggle-notification').click(function(){
				if(toggleStatus) {
					//alert("spusteno");
					toggleStatus=false;
					$('#ntf_counter').text("0");
				$.ajax({
                type:'POST',
                url:'notifications/updateExpire',
                dataType:'json',
                data:{'id':66},
                success:function(func){
              alert('radi');     
          }
            });
					
					}
				else toggleStatus=true;
				
			});
        });
		function addNotification(type, data){
	   
        var json=jQuery.parseJSON(data);
		var count=Object.keys(json).length;
		if(type=="new"){
		for(var i=0; i<count; i++){
			var months = ['Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec'];
			var date = new Date((parseInt(json[i].UserNotificationCreated))*1000);
			var hour = date.getHours();
			var minute = date.getMinutes();
			var day = date.getDate();
			var month = months[date.getMonth()];
			
			
            $("#menu1").prepend("<li><a href='http://localhost/CloudICT2/"+json[i].AppLink+"HandleNotification/"+json[i].IdEvent+"'><span class='image' style='margin-right:10px'><i class='"+json[i].NotificationTypeIcon+"'></i></span><span><span>"+json[i].NotificationTypeName+"</span><span class='time'>"+day+"-"+month+" "+hour+":"+minute+"</span></span><br/><span>from "+json[i].UserFullname+"</span><span class='message'>"+json[i].UserNotificationDescription+"</span></a></li>");
			
			$('#ntf_counter').text(parseInt($('#ntf_counter').text())+1);
			}
		}
		
		
		
		else alert("ne radi");
       
    }
	
	function waitForNotification(){
		//alert("radi");
        $.ajax({
            type: "GET",
            url: "notifications/updateNotifications",
            async: true,
            cache: false,
            timeout:50000, 

            success: function(data){// alert("radi success");
                addNotification("new", data);
                setTimeout(
                    waitForNotification, 
                    5000 
                );
            },
            error: function(XMLHttpRequest, textStatus, errorThrown){
                addNotification("error", textStatus + " (" + errorThrown + ")");
                setTimeout(
                    waitForNotification,
                    15000);
            }
        });
    };
	
</script>
    <div id="wrapper">
        <!-- Navigation -->
        <nav class="navbar navbar-default navbar-static-top" role="navigation" style="margin-bottom: 0">
            <div class="navbar-header">
                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a class="navbar-brand" href="admin/"><img src="public/img/ict.png" class="logo"/> <span class="cloud">Cloud</span></a>
            </div>
            <!-- /.navbar-header -->

            <ul class="nav navbar-top-links navbar-right">
                <li class="dropdown">
                    <a class="dropdown-toggle" data-toggle="dropdown" href="#">
                        <i class="fa fa-user fa-fw"></i>  <i class="fa fa-caret-down"></i>
                    </a>
                    <ul class="dropdown-menu dropdown-messages">
                        <li><a href="#"><i class="fa fa-user fa-fw"></i> User Profile</a>
                        </li>
                        <li><a href="#"><i class="fa fa-gear fa-fw"></i> Settings</a>
                        </li>
                        <li class="divider"></li>
                        <li><a href="login.html"><i class="fa fa-sign-out fa-fw"></i> Logout</a>
                        </li>
                    </ul>
                    <!-- /.dropdown-user -->
                </li>
                <li class="dropdown">
                    <a id="toggle-notification" class="dropdown-toggle" data-toggle="dropdown" href="#" aria-expanded="false">
                        <i class="fa fa-bell fa-fw"></i>
						<span id="ntf_counter" class="badge bg-green"><?php echo $count;  ?></span>
						<i class="fa fa-caret-down"></i>
                    </a>
                    <ul class="dropdown-menu dropdown-messages" id="menu1">
                        
								<?php
		if($count==0){
		echo "<li><div class='text-center'><strong></strong></div></li>";}
		
			foreach($notifications as $red){
				echo "<li>";
				echo"<a href='".base_url().$red['AppLink']."HandleNotification/".$red['IdEvent']."'>";
				echo"<span class='image' style='margin-right:10px'>";
				echo"<i class='".$red['NotificationTypeIcon']."'></i>";
				echo"</span>";
				echo"<span>";
				echo"<span>".$red['NotificationTypeName']."</span>";
				echo"<span class='time'>".gmdate("d-M H:i", $red['UserNotificationCreated']+2*60*60)."</span>";
				echo"</span><br/>";
				echo"<span>from ".$red['UserFullname']."</span>";
				echo"<span class='message'>";
				echo $red['UserNotificationDescription'];
				echo"</span>";
				echo"</a>";
				echo"</li>";
				
		}
		
		?>
								
                                   
                                   
                                   
                                    <li>
                                        <div class="text-center">
                                            <a>
                                                <strong><a href="<?php echo base_url(); ?>Notifications/allNotifications">See All Alerts</strong>
                                                <i class="fa fa-angle-right"></i>
                                            </a>
                                        </div>
                                    </li>
                      <!--  <li class="divider"></li>
                        <li>
                            <a href="#">
                                <div>
                                    <i class="fa fa-upload fa-fw"></i> Server Rebooted
                                    <span class="pull-right text-muted small">4 minutes ago</span>
                                </div>
                            </a>
                        </li>
                        <li class="divider"></li>
                        <li>
                            <a class="text-center" href="#">
                                <strong>See All Alerts</strong>
                                <i class="fa fa-angle-right"></i>
                            </a>
                        </li> -->
                    </ul>
                    <!-- /.dropdown-alerts -->
                </li>
                <!-- /.dropdown -->
            </ul>
            <!-- /.navbar-top-links -->

            <div class="navbar-default sidebar" role="navigation">
                <div class="sidebar-nav navbar-collapse">
                    <ul class="nav" id="side-menu">
                        <li class="sidebar-search">
                            <div class="input-group custom-search-form">
                                <input type="text" class="form-control" placeholder="Search...">
                                <span class="input-group-btn">
                                <button class="btn btn-default" type="button">
                                    <i class="fa fa-search"></i>
                                </button>
                            </span>
                            </div>
                            <!-- /input-group -->
                        </li>
                        <?php
                            echo $menu;
                        ?>
                    </ul>
                </div>
                <!-- /.sidebar-collapse -->
            </div>
            <!-- /.navbar-static-side -->
        </nav>
