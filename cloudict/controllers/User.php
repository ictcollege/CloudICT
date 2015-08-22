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
        $this->load_view('login', $data);
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
    
    public function logout() {
        session_destroy();
        header("location:".base_url()."User");
        exit;
        //redirect(base_url()); //CI_Version

    }
}
