<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/*
 *  @author Darko Lesendric <dlesendric at https://github.com/ @Darko_Lesendric at https://twitter.com/>
 */

/**
 * Description of MY_Controller
 * This class is for all common funciton in both controllers Frontend_Controller and Backend_Controller
 * @author Darko
 */
class MY_Controller extends CI_Controller{

    public function __construct() {
        parent::__construct();
        date_default_timezone_set("Europe/Belgrade");
    }
    
    /**
     * funcition to check if user is admin
     * @return boolean
     */
    public function isAdmin(){
        if($this->session->userdata('role')==3){
            return TRUE;
        }
        else{
            return FALSE;
        }
    }
    public function isUser(){
        if($this->session->userdata('role')==1){
            return TRUE;
        }
        else{
            return FALSE;
        }
    }
    public function isGroupAdmin(){
        if($this->session->userdata('role')==2){
            return TRUE;
        }
        else{
            return FALSE;
        }
    }
    /**
     * check if user is logged?
     */
    public function isLogged(){
        if($this->session->userdata('role')!=null){
            return TRUE;
        }
        else {
            return FALSE;
        }
    }
    /**
     * IF MENU IS TRUE, THIS WILL LOAD MENU
     * @param type $view
     * @param type $data
     * @param type $menu
     */
    public function load_view($view,$data=array()){
		if($this->session->userdata('userid')!=null){
			$data['userid']=$this->session->userdata('userid');
			$this->load->model('NotificationModel');
			$data['notifications']=  $this->NotificationModel->getInitialNotifications($data['userid']);
			$count=0;
			foreach($data['notifications'] as $red){
				if($red['UserNotificationTimeExpires']==0) $count++;
			}
			$data['count']=$count;
		}
                
        //helpers
            $this->load->helper('url');
        //variables   
        $data['base_url']= base_url();
		
        $this->load->view('header',$data);
        $this->load->view('menu',$data);
        $this->load->view($view, $data);
        $this->load->view('footer');
    }
}
