<div id="wrapper">
    <div class="container">
            <div class="row">
                <div class="col-md-6 col-md-offset-4">
                <div class="user-panel panel">

                    <div class="panel-body application-panel">
                        <?php
                        if(count($notifications)!=0)
                        {
                            foreach($notifications as $red)
                            {

                                echo '<div class="panel panel-primary">';
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