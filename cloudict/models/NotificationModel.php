<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class NotificationModel extends CI_Model {
	
	public function __construct() {
        $this->load->database();
    }

	
	public function getInitialNotifications($IdUser)
	{
		$query = "
			SELECT	`IdUserNotification`,
					`NotificationTypeName`,
					`NotificationTypeIcon`,
					`AppLink`,
					`AppIcon`,
					`IdEvent`,
					`UserFullname`,
					`UserNotificationDescription`,
					`UserNotificationCreated`,
					`UserNotificationTimeExpires`
					
			FROM 	`usernotification`
			
			JOIN `app`
			USING(`IdApp`)
			
			JOIN `notificationtype`
			USING(`IdNotificationType`)
			
			WHERE `IdUser`=?
			ORDER BY `UserNotificationCreated` DESC
			LIMIT 6
		";
		

		$result = $this->db->query($query, [$IdUser])->result_array();
		
		$query2 = "
			call update_notification_status(?)
		";
		$result2 = $this->db->query($query2, [$IdUser]);
		
		return $result;

		
	}
	
	public function getRealTimeNotifications($IdUser)
	{
		$query = "
			SELECT	`IdUserNotification`,
					`NotificationTypeName`,
					`NotificationTypeIcon`,
					`AppLink`,
					`AppIcon`,
					`IdEvent`,
					`UserFullname`,
					`UserNotificationDescription`,
					`UserNotificationCreated`,
					`UserNotificationTimeExpires`
					
			FROM 	`usernotification`
			
			JOIN `app`
			USING(`IdApp`)
			
			JOIN `notificationtype`
			USING(`IdNotificationType`)
			
			WHERE `UserNotificationStatus` = 1 AND `IdUser`=?
		";
		

		$result = $this->db->query($query, [$IdUser])->result_array();
		
		$query2 = "
			call update_notification_status(?)
		";
		$result2 = $this->db->query($query2, [$IdUser]);
		
		return $result;
		
	}
	
	public function updateNotificationExpireTime($IdUser){
		
		$query2 = "
			call update_notification_expire_time(?)
		";
		$result2 = $this->db->query($query2, [$IdUser]);
		
		
	}
	
	public function getAllNotifications($IdUser){
		$query = "
			SELECT	`IdUserNotification`,
					`NotificationTypeName`,
					`NotificationTypeIcon`,
					`AppLink`,
					`AppIcon`,
					`IdEvent`,
					`UserFullname`,
					`UserNotificationDescription`,
					`UserNotificationCreated`,
					`UserNotificationTimeExpires`
					
			FROM 	`usernotification`
			
			JOIN `app`
			USING(`IdApp`)
			
			JOIN `notificationtype`
			USING(`IdNotificationType`)
			
			WHERE `IdUser`=?
			
			ORDER BY `UserNotificationCreated` DESC
		";
		

		$result = $this->db->query($query, [$IdUser])->result_array();
		
		return $result;
		
	}
	
}
?>

	