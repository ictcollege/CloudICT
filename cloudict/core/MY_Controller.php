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
        date_default_timezone_set('Europe/Belgrade');
        parent::__construct();
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
    
    protected function formatBytes($size, $precision = 2)
    {
// second algorytm
//        $base = log($size, 1024);
//        $suffixes = array('B', 'KB', 'MB', 'GB', 'TB');   
//
//        return round(pow(1024, $base - floor($base)), $precision) . $suffixes[floor($base)];
        if ($size >= 1073741824) {
            $size = number_format($size / 1073741824, 2) . ' GB';
        } elseif ($size >= 1048576) {
            $size = number_format($size / 1048576, 2) . ' MB';
        } elseif ($size >= 1024) {
            $size = number_format($size / 1024, 2) . ' KB';
        } elseif ($size > 1) {
            $size = $size . ' bytes';
        } elseif ($size == 1) {
            $size = $size . ' byte';
        } else {
            $size = '0 bytes';
        }

        return $size;
    }
}
