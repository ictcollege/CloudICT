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
class Admin extends Backend_Controller {
    public function __construct() {
        parent::__construct();
    }
    //put your code here
    public function index(){
           
        //helpers
        $this->load->helper('url');
        $this->load->helper('form');
        
        //variables
        $base_url = base_url();
        
        //model
        $this->load->model('ApplicationModel');
        
        $applications = $this->ApplicationModel->getAllApplications();
        
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
    
    public function allfiles()
    {
        //helpers
        $this->load->helper('url');
        $this->load->helper('form');
        
        //variables
        $base_url = base_url();
        
        //data to view
        $data['base_url']= $base_url;
        $data['title'] = "ICT Cloud | Admin | All Files";
        
        //views
        $this->load->view('header', $data);
        $this->load->view('menu', $data);
        $this->load->view('maincontent', $data);
    }
    
    public function newuser()
    {
        //helpers
        $this->load->helper('url');
        $this->load->helper('form');
        
        //variables
        $base_url = base_url();
            //form elements
            $form_attr = array(
                "id" => "formRegister",
                "role" => "form"  
            );
            
            $email_attr = array(
                "class" => "form-control tbEmail" ,
                "placeholder" => "Email" ,
                "name" => "Email" ,
                "autofocus" => "autofocus"
            );
        
            $key_attr = array(
                "class" => "form-control tbKey disabled" ,
                "placeholder" => "Generated Key" ,
                "name" => "key",
                "readonly" => "true"
            );
        
        //data to view
        $data['base_url']= $base_url;
        $data['title'] = "ICT Cloud | Admin | New User";
             //data for form
            $data['form_attr'] = $form_attr;
            $data['email_attr'] = $email_attr;
            $data['key_attr'] = $key_attr;
        
        //views
        $this->load->view('header', $data);
        $this->load->view('menu', $data);
        $this->load->view('newuser', $data);
    }
    
    public function groups()
    {
        //helpers
        $this->load->helper('url');
        $this->load->helper('form');
        
        //variables
        $base_url = base_url();
        
        //model
        $this->load->model('MenuModel');
        $this->load->model('UserGroupModel');
        $this->load->model('UserModel');
        
        $menu = $this->MenuModel->getMenuOfApplication(3);
        
        $data['menu'] = "";
        
        foreach($menu['Menu'] as $m)
        {
            $data['menu'] .= '<li>';
            $data['menu'] .= '<a href="'.$m['AppMenuLink'].'"><i class="fa '.$m['AppMenuIcon'].' fa-fw"></i> '.$m['AppMenuName'].'</a>';
            $data['menu'] .= '</li>';
        }
        
        $groups = $this->UserGroupModel->getGroups();
        $usergroups = $this->UserGroupModel->getGroupAndUsersInIt();
        
        $data['usergroups'] = "";
        $i=0;
        
        foreach($groups['Group'] as $g)
        {  
            if($i%3==0)
            {
               $data['usergroups'] .= ' <div class="row">';  
            }
            $data['usergroups'] .= '<div class="col-lg-4">';
            $data['usergroups'] .= '<div id="'.$g['IdGroup'].'" class="panel panel-primary">';
            $data['usergroups'] .= '<div class="panel-heading">';
            $data['usergroups'] .= $g['GroupName'];
            $data['usergroups'] .= '</div>';
            $data['usergroups'] .= '<div class="panel-body">';
            $data['usergroups'] .= '<div class="group-admin">';
            
            foreach($usergroups['UserGroup'] as $ug)
            {
                if($ug['IdGroup'] == $g['IdGroup'])
                {
                   if($ug['Admin']==1)
                   {
                       $data['usergroups'] .= '<button type="button" id="'.$ug['IdUser'].'" class="btn btn-primary btn-xs btn-no-hover btn-admin">'.$ug['UserName'].'</button>';
                   }
                }
            }
            
            $data['usergroups'] .= '</div>';
            $data['usergroups'] .= '<hr/>';
            
            $data['usergroups'] .= '<div class="group-user">';
            foreach($usergroups['UserGroup'] as $ug)
            {
                if($ug['IdGroup'] == $g['IdGroup'])
                {
                   if($ug['Admin']==0)
                   {
                       $data['usergroups'] .= '<button type="button" id="'.$ug['IdUser'].'" class="btn btn-info btn-xs btn-no-hover btn-admin">'.$ug['UserName'].'</button>';
                   }
                }
                
                
            }
            
            $data['usergroups'] .= '</div>';
            $data['usergroups'] .= '</div>';
            $data['usergroups'] .= '<div class="panel-footer group-panel-footer">';
            $data['usergroups'] .= '<button type="button" class="btn btn-outline btn-primary btn-xs pull-left" data-toggle="modal" data-target="#mEditGroup'.$g['IdGroup'].'">Edit</button>';
            $data['usergroups'] .= '<button type="button" class="btn btn-outline btn-danger btn-xs pull-right" data-toggle="modal" data-target="#mDeleteGroup'.$g['IdGroup'].'">Delete</button>';
            $data['usergroups'] .= '</div>';
            $data['usergroups'] .= '</div>';
            $data['usergroups'] .= '</div>';
            
            $i++;
            if($i%3==0)
            {
                $data['usergroups'] .= '</div>'; 
            }
        }
        
        $data['deltemodal'] = "";
        $data['editmodal'] = "";
        
        foreach($groups['Group'] as $g)
        { 
            $data['deltemodal'] .= '<div class="modal fade" id="mDeleteGroup'.$g['IdGroup'].'" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">';
            $data['deltemodal'] .= '<div class="modal-dialog" role="document">';
            $data['deltemodal'] .= '<div class="modal-content">';
            $data['deltemodal'] .= '<div class="modal-header">';
            $data['deltemodal'] .= '<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>';
            $data['deltemodal'] .= '<h4 class="modal-title" id="myModalLabel">Delete Group</h4>';
            $data['deltemodal'] .= '</div>';
            $data['deltemodal'] .= '<div class="modal-body text-center">';
            $data['deltemodal'] .= 'Delete group '.$g['GroupName'].'?';
            $data['deltemodal'] .= '</div>';
            $data['deltemodal'] .= '<div class="modal-footer text-center">';
            $data['deltemodal'] .= '<button type="button" class="btn btn-primary btnDeleteGroupYes">Yes</button>';
            $data['deltemodal'] .= '<button type="button" class="btn btn-danger" data-dismiss="modal">No</button>';
            $data['deltemodal'] .= '<input type="hidden" id="'.$g['IdGroup'].'" class="hdId"/>';
            $data['deltemodal'] .= '</div>';
            $data['deltemodal'] .= '</div>';
            $data['deltemodal'] .= '</div>';
            $data['deltemodal'] .= '</div>';
            
            $data['editmodal'] .= '<div class="modal fade" id="mEditGroup'.$g['IdGroup'].'" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">';
            $data['editmodal'] .= '<div class="modal-dialog" role="document">';
            $data['editmodal'] .= '<div class="modal-content">';
            $data['editmodal'] .= '<div class="modal-header">';
            $data['editmodal'] .= '<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>';
            $data['editmodal'] .= '<h4 class="modal-title" id="myModalLabel">Edit Group</h4>';
            $data['editmodal'] .= '</div>';
            $data['editmodal'] .= '<div class="modal-body">';
            $data['editmodal'] .= '<div class="form-group">';
            $data['editmodal'] .= '<label>Group Name</label>';
            $data['editmodal'] .= '<input class="form-control" placeholder="'.$g['GroupName'].'" />';
            $data['editmodal'] .= '</div>';
            $data['editmodal'] .= '<div class="form-group">';
            $data['editmodal'] .= '<label>Admins</label>';
            
            $data['editmodal'] .= '<div class="admins">';
            foreach($usergroups['UserGroup'] as $ug)
            {
                if($ug['IdGroup'] == $g['IdGroup'])
                {
                   if($ug['Admin']==1)
                   {
                       $data['editmodal'] .= '<button type="button" class="btn btn-primary btn-admin">'.$ug['UserName'].'   <i class="fa fa-minus icon-remove-admin" id="'.$ug['IdUser'].'"><input type="hidden" id="'.$g['IdGroup'].'" class="hdIdGroup"/></i></button>';
                   }
                }
            }
            $data['editmodal'] .= '</div>';
            
            $data['editmodal'] .= '</div>';
            $data['editmodal'] .= '<div class="form-group">';
            $data['editmodal'] .= '<label>Members</label>';
            
            $data['editmodal'] .= '<div class="users">';
            foreach($usergroups['UserGroup'] as $ug)
            {
                if($ug['IdGroup'] == $g['IdGroup'])
                {
                   if($ug['Admin']==0)
                   {
                       $data['editmodal'] .= '<button type="button" class="btn btn-default btn-user">'.$ug['UserName'].'   <i class="fa fa-trash-o icon-remove-user" id="'.$ug['IdUser'].'"><input type="hidden" id="'.$g['IdGroup'].'" class="hdIdGroup"/></i> <i class="fa fa-plus icon-add-admin" id="'.$ug['IdUser'].'"><input type="hidden" id="'.$g['IdGroup'].'" class="hdIdGroup"/></i></button>';
                   }
                }
            }
            $data['editmodal'] .= '</div>';
            $data['editmodal'] .= '<div class="form-group">';
            $data['editmodal'] .= '<label>New Memers</label>';
            $data['editmodal'] .= '<input class="form-control tbSearchNewUser" placeholder="search..." id="'.$g["IdGroup"].'" />';
            $data['editmodal'] .= '</div">';
            $data['editmodal'] .= '<div class="newusers">';
            
            $usersnotingroup = $this->UserGroupModel->getUsersThatAreNotInTheGroup($g['IdGroup']);
            
            foreach($usersnotingroup['UsersNotInGroup'] as $unig)
            {
                $data['editmodal'] .= '<button type="button" class="btn btn-default btn-user">'.$unig['UserName'].'<input type="hidden" id="'.$g['IdGroup'].'" class="hdIdGroup"/></i> <i class="fa fa-plus icon-add-newuser" id="'.$unig['IdUser'].'"><input type="hidden" id="'.$g['IdGroup'].'" class="hdIdGroup"/></i></button>';
            }
            
            
            $data['editmodal'] .= '</div>';
            $data['editmodal'] .= '</div>';
            $data['editmodal'] .= '</div>';
            $data['editmodal'] .= '</div>';
            $data['editmodal'] .= '<div class="modal-footer">';
            $data['editmodal'] .= '<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>';
            $data['editmodal'] .= '<button type="button" class="btn btn-primary btnNewGroup">Save</button>';
            $data['editmodal'] .= '</div>';
            $data['editmodal'] .= '</div>';
            $data['editmodal'] .= '</div>';
            $data['editmodal'] .= '</div>';
        }
        
        //data to view
        $data['base_url']= $base_url;
        $data['title'] = "ICT Cloud | Admin | Groups";
        
        //views
        $this->load_view('groups', $data);
    }
    
    public function removeAdmin()
    {
        $iduser = $this->input->post('IdUser');
        $idgroup = $this->input->post('IdGroup');
        
        $this->load->model("UserGroupModel");
        
        $this->UserGroupModel->removeAdmin($iduser, $idgroup);
        
        echo json_encode(true);
    }
    
    public function addAdmin()
    {
        $iduser = $this->input->post('IdUser');
        $idgroup = $this->input->post('IdGroup');
        
        $this->load->model("UserGroupModel");
        
        $this->UserGroupModel->addAdmin($iduser, $idgroup);
        
        echo json_encode(true);
    }
    
    public function removeUserFromGroup()
    {
        $iduser = $this->input->post('IdUser');
        $idgroup = $this->input->post('IdGroup');
        
        $this->load->model("UserGroupModel");
        
        $this->UserGroupModel->removeUserFromGroup($iduser, $idgroup);
        
        echo json_encode(true);
    }
    
    public function addNewUserToGroup()
    {
        $iduser = $this->input->post('IdUser');
        $idgroup = $this->input->post('IdGroup');
        
        $this->load->model("UserGroupModel");
        
        $this->UserGroupModel->addNewUserToGroup($iduser, $idgroup);
        
        echo json_encode(true);
    }
    
    public function searchNewUser()
    {
        $username = $this->input->post('Username');
        $idgroup = $this->input->post('IdGroup');
        
        $this->load->model("UserGroupModel");
        
        $searchusers = $this->UserGroupModel->searchNewUser($username, $idgroup);
        
        $response = "";
        
        foreach($searchusers["SearchUsers"] as $su)
        {
            $response .= $su["UserName"];
        }
        
        echo json_encode($response);
    }
    
    public function insertGroup()
    {
        
    }
    
    public function deleteGroup()
    {
        $this->input->post($idGroup);
        $this->load->model("UserGroupModel");
        
        $this->UserGroupModel->deleteGroup($idGroup);
    }
    
    public function users()
    {
        //helpers
        $this->load->helper('url');
        $this->load->helper('form');
        
        //variables
        $base_url = base_url();
        
        //model
        $this->load->model('MenuModel');
        
        $menu = $this->MenuModel->getMenuOfApplication(3);
        
        $data['menu'] = "";
        
        foreach($menu['Menu'] as $m)
        {
            $data['menu'] .= '<li>';
            $data['menu'] .= '<a href="'.$m['AppMenuLink'].'"><i class="fa '.$m['AppMenuIcon'].' fa-fw"></i> '.$m['AppMenuName'].'</a>';
            $data['menu'] .= '</li>';
        }
        
         //data to view
        $data['base_url']= $base_url;
        $data['title'] = "ICT Cloud | Admin | Groups";
        
        //views
        $this->load_view('groups', $data);
    }
}
