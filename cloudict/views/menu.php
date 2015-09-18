<script>

	
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
