<<<<<<< HEAD
<div id="wrapper">
    <div class="container">
            <div class="row">
                <div class="col-md-8 col-md-offset-2">
                <div class="user-panel panel">

                    <div class="panel-body application-panel">
                        <?php
                            foreach($notifications as $red){
                                echo '<div class="panel ';
                                if($red['NotificationTypeName'] == "New Group")
                                {
                                    echo 'panel-primary">';
                                } else if($red['NotificationTypeName'] == "Information")
                                {
                                    echo 'panel-green">';
                                }
                                echo '<div class="panel-heading notification-panel-heading">';
                                echo '<div class="row">';
                                echo '<div class="col-xs-3"><i class="fa '.$red['NotificationTypeIcon'].' fa-5x"></i></div>';
                                echo '<div class="col-xs-9 text-left">';
                                echo '<div class="huge">'.$red['NotificationTypeName'].'</div>';
                                echo '<div>by '.$red['UserFullname'].'    <span class="small">  '.gmdate("d.m.Y H:i:s", $red['UserNotificationCreated']+2*60*60).'</span></div>';
                                echo '</div></div></div>';
                                echo '<div class="panel-footer">';
                                echo '<span class="pull-left">Mark As Read</span>';
                                echo '<span class="pull-right"><i class="fa fa-check-square mark-as-read"></i></span>';
                                echo '<div class="clearfix"></div>';
                                echo '</div>';
                                echo '</div>';
                            }
                            
                            if(count($notifications) == 0)
                            {
                                echo ' <div class="row">';  
                                echo '<div class="col-sm-12 text-center">';
                                echo '<a><div class="app">';
                                echo '<h2></h2>';
                                echo '<h3 class="app-name-none">No New Notifications</h3>';
                                echo '</div></a>';
                                echo ' </div>';
                                echo '</div>';
                            }
                        ?>
                    </div>
                </div>
            </div>
            </div>
</div>
</div>
=======
<div id="wrapper">
    <div class="container">
            <div class="row">
                <div class="col-md-6 col-md-offset-4">
                <div class="user-panel panel">

                    <div class="panel-body application-panel">
                        <?php
                        if(count($allnotifications)!=0)
                        {	
                            foreach($allnotifications as $red)
                            {
//sta je ovo???? (dlesendric)
//                                echo '<div class="panel '.$red['NotificationTypePanelStyle'].'">';
                               //zamena
                                echo '<div class="panel">';
                                //zamena (dlesendric)
                                echo '<div class="panel-heading">';
                                echo '<div class="row">';
                                echo '<div class="col-xs-3">';
                                echo '<i class="fa '.$red['NotificationTypeIcon'].' fa-2x"></i>';
                                echo '</div>';
                                echo '<div class="col-xs-9 text-right">';
                                echo '<div class="small">'.gmdate("d-m-Y H:i:s", $red['UserNotificationCreated']+2*60*60).'</div>';
                                echo '<div>by '.$red['UserFullname'].'</div>';
                                echo '</div>';
                                echo '</div>';
                                echo '</div>';;
                                echo '<div class="panel-footer">';
                                echo '<span class="pull-left" style="color: #333;">'.$red['UserNotificationDescription'].'</span>';
                                echo '<div class="clearfix"></div>';
                                echo '</div>';
                                echo '</div>';

                            }
                        }
                        else 
                        {
                            echo '<h3 class="text-center">No New Notification</h3>';
                        }
                        ?>
                        
                   </div>
            </div>
            </div>
</div>
</div>
>>>>>>> master
