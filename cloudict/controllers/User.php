<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Logins
 *
 * @author Jericho
 */
class User extends MY_Controller { //MY_Controller jer on nema zastitu za logovane user-e
    public function __construct() {
        parent::__construct();
    }
    //put your code here
    public function index(){
        //helpers
        $this->load->helper('form');
        
        //variables
        $base_url = base_url();
            
            //form elements
            $form_attr = array(
                "id" => "formLogin",
                "role" => "form"  
            );
            
            $username_attr = array(
                "class" => "form-control tbUsername" ,
                "placeholder" => "Username" ,
                "name" => "username" ,
                "autofocus" => "autofocus"
            );
        
            $password_attr = array(
                "class" => "form-control tbPassword" ,
                "placeholder" => "Password" ,
                "name" => "password" 
            );
        
        //data to view
        $data['base_url']= $base_url;
        $data['title'] = "ICT Cloud | Login";
            //data for form
            $data['form_attr'] = $form_attr;
            $data['username_attr'] = $username_attr;
            $data['password_attr'] = $password_attr;
        
        //views
        $this->load->view('header', $data);
        $this->load->view('login', $data);
        $this->load->view('footer', $data);
    }
    
    public function login()
    {
        $username = $this->input->post('Username');
        $password = $this->input->post('Password');
        
        $this->load->model('UserModel');
        
        $user = $this->UserModel->getUser($username, md5($password));
        
        if(count($user["User"]) != 0)
        {
            $idrole = $user["User"]["IdRole"];
            $username = $user["User"]["User"];
            $userId = $user["User"]["Id"]; 
            $groups = $this->UserModel->getAllUsersInGroups($user["User"]["Id"]);
            
            $session= array(
                'userid' =>$userId,
                'username' => $username,
                'role' => $idrole,
                'group' => $groups
            );
            
            $this->session->set_userdata($session);
        }
        
        echo json_encode($session);
    }
    
    public function applications()
    {
        //helpers
        $this->load->helper('url');
        $this->load->helper('form');
        
        //variables
        $base_url = base_url();
        
        //model
        $this->load->model('ApplicationModel');
        
        $applications = $this->ApplicationModel->getApplications(0);
        
        $data['applications'] = "";
        $data['applications'] .= ' <div class="row">';  
        $i= 0;
        foreach($applications['Application'] as $a)
        {
            if($i%3==0)
            {
               $data['applications'] .= ' <div class="row">';  
            }
            $data['applications'] .= '<div class="col-sm-4 text-center">';
            $data['applications'] .= '<a href="'.base_url().$a['AppLink'].'"><div class="app app'.($i+1).'">';
            $data['applications'] .= '<h2><i class="fa '.$a['AppIcon'].' fa-fw"></i></h2>';
            $data['applications'] .= '<h3 class="app-name">'.$a['AppName'].'</h3>';
            $data['applications'] .= '</div></a>';
            $data['applications'] .= ' </div>';
            $i++;
            if($i%3==0)
            {
                $data['applications'] .= '</div>'; 
            }
        }
        
        //data to view
        $data['base_url']= $base_url;
        $data['title'] = "ICT Cloud | Admin | Applications";
        $data['admin'] = true;
            //data for form
            
        
        //views
        $this->load->view('header', $data);
        $this->load->view('applications', $data);
    }
    
    public function logout() {
        session_destroy();
        header("location:".base_url()."User/applications");
        exit;
        //redirect(base_url()); //CI_Version

    }
}
