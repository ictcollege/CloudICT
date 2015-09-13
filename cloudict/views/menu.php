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
                <a class="navbar-brand" href="<?php if($this->session->userdata('role')==3) echo "admin/"; else echo"user/applications";?>"><img src="public/img/ict.png" class="logo"/> <span class="cloud">Cloud</span></a>
            </div>
            <!-- /.navbar-header -->

            <ul class="nav navbar-top-links navbar-right">
                
                <li class="dropdown">
                    <a class="dropdown-toggle" data-toggle="dropdown" href="#">
                        <i class="fa fa-question-circle fa-fw"></i>  <i class="fa fa-caret-down"></i>
                    </a>
                    <ul class="dropdown-menu dropdown-messages">
                        <li><a href="user/aboutus"><i class="fa fa-graduation-cap fa-fw"></i> About Us</a>
                        </li>
                        </li>
                        <li class="divider"></li>
                        <li><a href="user/aboutictcloud"><i class="fa fa-cloud fa-fw"></i> About ICT Cloud</a>
                        </li>
                    </ul>
                    <!-- /.dropdown-user -->
                </li>
                <li class="dropdown">
                    <a class="dropdown-toggle" data-toggle="dropdown" href="#">
                        <i class="fa fa-user fa-fw"></i>  <i class="fa fa-caret-down"></i>
                    </a>
                    <ul class="dropdown-menu dropdown-messages">
                        <li><a href=<?php echo $base_url;?>user/profile><i class="fa fa-user fa-fw"></i> User Profile</a>
                        </li>
                        </li>
                        <li class="divider"></li>
                        <li><a href=<?php echo $base_url;?>user/logout><i class="fa fa-sign-out fa-fw"></i> Logout</a>
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
				echo"<span class='image' style='margin-right:35px'>";
				echo"<i class='".$red['NotificationTypeIcon']."'></i>";
				echo"</span>";
				echo"<span>";
				echo"<span>".$red['NotificationTypeName']."</span>";
				echo"</span><br/>";
				echo"</a>";
				echo"</li>";
				echo "<i class='divider'></i>";
                                
		}
		?>
								
                                   
                                   
                                   
                                    <li>
                                        <div class="text-center">
                                            <a>
                                                <strong><a href="<?php echo base_url(); ?>user/allnotifications">See All Alerts</strong>
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
            <?php
                if(isset($menu))
                {
                    echo '<div class="navbar-default sidebar" role="navigation">';
                    echo '<div class="sidebar-nav navbar-collapse">';
                    echo '<ul class="nav" id="side-menu">';
                    echo '<li class="sidebar-search">';
                    echo '<div class="input-group custom-search-form">';       
                    echo '<input type="text" class="form-control" placeholder="Search...">';                
                    echo ' <span class="input-group-btn">';                  
                    echo '<button class="btn btn-default" type="button">';                   
                    echo '<i class="fa fa-search"></i>';                       
                    echo '</button>';                   
                    echo ' </span>';             
                    echo '</div>';              
                    echo '<!-- /input-group -->';             
                    echo '</li>';         

                    echo $menu;

                    echo '</ul>';   
                    echo '</div>';
                    echo '<!-- /.sidebar-collapse -->'; 
                    echo '</div>';
                }
            ?>
            <!-- /.navbar-static-side -->
        </nav>
