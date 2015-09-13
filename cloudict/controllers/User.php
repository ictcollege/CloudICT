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
            
            if($username == "admin" && $user["User"]["Password"] == md5("admin"))
            {
                echo json_encode(-1);
            }
            else 
            {
                echo json_encode($session);
            }
        }
        else
        {
            echo json_encode(0);
        }
    }
    
    public function initialPasswordChange()
    {
        $newpassword = $this->input->post('NewPassword');
        
        $this->load->model("UserModel");
        
        $this->UserModel->initialPasswordChange($newpassword);
        
        echo json_encode(true);
    }
    
    public function applications()
    {
        //helpers
        
        $this->load->helper('form');
        
        //variables
        
        //model
        $this->load->model('ApplicationModel');
        
        $applications = $this->ApplicationModel->getApplicationsFromPrivileges($this->session->userdata('userid'));
        
        $data['applications'] = "";
        $data['applications'] .= ' <div class="row">';  
        $i= 0;
        if(isset($applications))
        {
            foreach($applications['Applications'] as $a)
            {
                if($i%3==0)
                {
                   $data['applications'] .= ' <div class="row">';  
                }
                $data['applications'] .= '<div class="col-sm-4 text-center">';
                $data['applications'] .= '<a href="'.base_url().$a['AppLink'].'"><div class="app app'.($i+1).'" style="background-color: '.$a['AppColor'].'">';
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
        } else {
            $data['applications'] .= ' <div class="row">';  
            $data['applications'] .= '<div class="col-sm-12 text-center">';
            $data['applications'] .= '<a><div class="app">';
            $data['applications'] .= '<h2></h2>';
            $data['applications'] .= '<h3 class="app-name-none">None Avalable Applications For This Account</br>Contact Administrator</h3>';
            $data['applications'] .= '</div></a>';
            $data['applications'] .= ' </div>';
            $data['applications'] .= '</div>';
        }
        
        //data to view
        $data['title'] = "ICT Cloud | User | Applications";
        $data['admin'] = true;
            //data for form
            
        
        //views
        $this->load_view('applications', $data);
    }
    
    public function profile()
    {
        if(!$this->isLogged())
        {
            header("location:".base_url());
            exit;
        }
        else 
        {
             //helpers
            
            
            $this->load->model('UserModel');
            
            $user = $this->UserModel->getUserById($this->session->userdata('userid'));
            
            
            $data['usereditform'] = "";
            $data['passwordchangemodal'] = "";
            
            foreach($user as $u)
            {
                $data['usereditform'] .= '<div class="form-group">';
                $data['usereditform'] .= '<label>Username</label>';
                $data['usereditform'] .= '<input class="form-control tbEditUsername" placeholder="'.$u['UserName'].'"/>';
                $data['usereditform'] .= '</div>';
                
                $data['usereditform'] .= '<div class="form-group">';
                $data['usereditform'] .= '<label></label>';
                $data['usereditform'] .= '<button type="button" class="btn btn-warning pull-left btnUserChangePassword" data-toggle="modal" data-target="#mChangePassword">Change Password</button>';
                $data['usereditform'] .= '</div>';
                
                $data['usereditform'] .= '<div class="form-group">';
                $data['usereditform'] .= '<label>Full Name</label>';
                $data['usereditform'] .= '<input class="form-control tbFullName" placeholder="'.$u['UserFullname'].'"/>';
                $data['usereditform'] .= '</div>';
                
                $data['usereditform'] .= '<div class="form-group">';
                $data['usereditform'] .= '<label>Email</label>';
                $data['usereditform'] .= '<input class="form-control tbUserEmail" placeholder="'.$u['UserEmail'].'"/>';
                $data['usereditform'] .= '<p class="error">Email Format Is Wrong!</p>';
                $data['usereditform'] .= '</div>';
                
                $data['usereditform'] .= '<div class="form-group">';
                $data['usereditform'] .= '<label>Disk Quota</label>';
                $data['usereditform'] .= '<input class="form-control tbUserDiskQuota" placeholder="'.$u['UserDiskQuota'].'" disabled/>';
                $data['usereditform'] .= '</div>';
                
                $data['usereditform'] .= '<div class="form-group">';
                $data['usereditform'] .= '<label>Disk Used %</label>';
                $data['usereditform'] .= '<input class="form-control tbUserDiskUsed" placeholder="'.$u['UserDiskUsed'].'" disabled/>';
                $data['usereditform'] .= '<button type="button" class="btn btn-primary pull-right btnUserProfileSaveChanges" id="'.$u['IdUser'].'">Save Changes</button>';
                $data['usereditform'] .= '</div>';
                $data['usereditform'] .= '<div class="update-success">';
                $data['usereditform'] .= '</div>';
                
                $data['passwordchangemodal'] .= '<div class="modal fade mChangePassword" id="mChangePassword" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">';
                $data['passwordchangemodal'] .= '<div class="modal-dialog" role="document">';
                $data['passwordchangemodal'] .= '<div class="modal-content">';
                $data['passwordchangemodal'] .= '<div class="modal-header">';
                $data['passwordchangemodal'] .= '<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>';
                $data['passwordchangemodal'] .= '<h4 class="modal-title" id="myModalLabel">Delete Group</h4>';
                $data['passwordchangemodal'] .= '</div>';
                $data['passwordchangemodal'] .= '<div class="modal-body text-center">';
                
                $data['passwordchangemodal'] .= '<div class="form-group">';
                $data['passwordchangemodal'] .= '<input type="password" class="form-control tbOldPassword" placeholder="Old Password" />';
                $data['passwordchangemodal'] .= '<p class="pNewOldPassword error-message pull-left">Old Password Does Not Match</p>';
                
                $data['passwordchangemodal'] .= '</div>';
                
                $data['passwordchangemodal'] .= '<div class="form-group">';
                $data['passwordchangemodal'] .= '<input type="password" class="form-control tbNewPassword" placeholder="New Password" />';
                $data['passwordchangemodal'] .= '</div>';
                
                $data['passwordchangemodal'] .= '<div class="form-group">';
                $data['passwordchangemodal'] .= '<input type="password" class="form-control tbConfirmPassword" placeholder="Confirm Password" />';
                $data['passwordchangemodal'] .= '</div>';
                
                $data['passwordchangemodal'] .= '<p class="pConfirmPassword error-message pull-left">Match them up!</p>';
                
                $data['passwordchangemodal'] .= '</div>';
                $data['passwordchangemodal'] .= '<div class="modal-footer text-center">';
                $data['passwordchangemodal'] .= '<button type="button" id="'.$u["IdUser"].'" class="btn btn-primary btnChangePasswordYes">Change</button>';
                $data['passwordchangemodal'] .= '<button type="button" class="btn btn-danger" data-dismiss="modal">Cancel</button>';
                $data['passwordchangemodal'] .= '</div>';
                $data['passwordchangemodal'] .= '</div>';
                $data['passwordchangemodal'] .= '</div>';
                $data['passwordchangemodal'] .= '</div>';
            }
        }
        
        $data['title'] = "ICT Cloud | Admin | User Profile";
        
        $this->load_view("profile", $data);
    }
    
    public function updateUser()
    {
        $username = $this->input->post('Username');
        $fullname = $this->input->post('Fullname');
        $email = $this->input->post('Email');
        $iduser = $this->session->userdata('userid');
        
        $this->load->model('UserModel');
        
        $this->UserModel->updateUser($username, $fullname, $email, $iduser);
        
        echo json_encode(true);
        
    }
    
    public function checkIfPasswordExists()
    {
        $password = $this->input->post('Password');
        $iduser = $this->input->post('IdUser');
        
        $this->load->model('UserModel');
        
        $response = $this->UserModel->checkIfPasswordExists(md5($password), $iduser);
        
        echo json_encode($response);
    }
    
    public function changePassword()
    {
        $password = $this->input->post('Password');
        $iduser = $this->input->post('IdUser');
        
        $this->load->model('UserModel');
        
        $response = $this->UserModel->changePassword(md5($password), $iduser);
        
        echo json_encode($response);
    }
    
    public function logout() {
        session_destroy();
        header("location:".base_url());
        exit;
        //redirect(base_url()); //CI_Version

    }
    
    public function register($key) {
        
        $this->load->model('UserModel');
        
        if($this->UserModel->checkIfKeyExists($key))
        {
            $data["exists"] = true;
        }
        else 
        {
            $data["exists"] = false;
        }
        
            $this->load->view("header");
            $this->load->view("register", $data);
    }
    
    public function registerUser() {
        $username = $this->input->post("Username");
        $password = $this->input->post("Password");
        $key = $this->input->post("Key");
        
        $this->load->model('UserModel');
        
        $this->UserModel->registerUser($username, $password, $key);
        
        echo json_encode(true);
    }
    
    public function aboutus()
    {
        $this->load->helper('url');
        $this->load->helper('form');
        
        //variables
        $base_url = base_url();
        
        $data['title'] = "ICT Cloud | User | About Us";
        //views
        $this->load_view('aboutus', $data);
    }
    
    public function aboutictcloud()
    {
        $this->load->helper('url');
        $this->load->helper('form');
        
        //variables
        $base_url = base_url();
        
        $data['title'] = "ICT Cloud | User | About ICT Cloud";
        //views
        $this->load_view('aboutictcloud', $data);
    }
    
    public function allnotifications()
    {
        $this->load->model('');
                
        $data['title'] = "ICT Cloud | User | User Notifications";
        
        $this->load->view("");
    }
}
