  <div>
<?php
               echo "<ul style='padding-top:15px; list-style-type:none;'>";
            foreach($allnotifications as $red){
				echo "<li style='padding:15px; background-color:#fff; margin-top:5px;'>";
				echo"<a href='".$base_url.$red['AppLink']."HandleNotification/".$red['IdEvent']."'>";
				echo"<span class='image' style='margin-right:10px'>";
				
				echo"<span class='time'>".gmdate("d-m-Y H:i:s", $red['UserNotificationCreated']+2*60*60)."</span>";
				echo"</span>";
				echo"<span>from ".$red['UserFullname']."</span>&nbsp&nbsp&nbsp";
				echo"<span class='message'>";
				echo $red['UserNotificationDescription'];
				echo"</span>";
				echo"</a>";
				echo"</li>";
				
		}
		echo"</ul>";
?>
        </div>