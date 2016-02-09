<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Description of Notifications_controller
 *
 * @author Matic
 */
class Notifications extends MY_Controller{
    
    public function __construct() {
        parent::__construct();
        $this->load->model('NotificationModel');
        
    }
    
    public function index() {
        $data['base_url']=  base_url();
       
		
		$data['notifications']=  $this->NotificationModel->getInitialNotifications($this->session->userdata('userid'));
		$count=0;
		foreach($data['notifications'] as $red){
			if($red['UserNotificationTimeExpires']==0) $count++;
		}
		$data['count']=$count;
		
        $this->load->view('welcome_notif',$data);
    }
	
	
	
//	public function allNotifications() {
//        $data['base_url']=  base_url();
//       
//		$data['allnotifications']=  $this->NotificationModel->getAllNotifications($this->session->userdata('userid'));
//		
//        $this->load->view('allnotifications',$data);
//    }
	
	public function updateExpire(){
		
		$this->NotificationModel->updateNotificationExpireTime($this->session->userdata('userid'));
		
	}
	
	public function updateNotifications(){
	
	
		$result=$this->NotificationModel->getRealTimeNotifications($this->session->userdata('userid'));
			echo json_encode($result);
		
		
	}
	
	
	
	
    
    
}