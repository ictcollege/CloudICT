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
        
        if(isset($groups['Group']))
        {
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
                
                if(isset($usergroups['UserGroup']))
                {
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
                }
                $data['usergroups'] .= '</div>';
                $data['usergroups'] .= '<hr/>';

                $data['usergroups'] .= '<div class="group-user">';
                if(isset($usergroups['UserGroup']))
                {
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
        }
        $data['deltemodal'] = "";
        $data['editmodal'] = "";
        
        if(isset($groups['Group']))
        {
            foreach($groups['Group'] as $g)
            { 
                $data['deltemodal'] .= '<div class="modal fade mDeleteGroup" id="mDeleteGroup'.$g['IdGroup'].'" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">';
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

                $data['editmodal'] .= '<div class="modal fade '.$g['IdGroup'].'" id="mEditGroup'.$g['IdGroup'].'" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">';
                $data['editmodal'] .= '<div class="modal-dialog" role="document">';
                $data['editmodal'] .= '<div class="modal-content">';
                $data['editmodal'] .= '<div class="modal-header">';
                $data['editmodal'] .= '<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>';
                $data['editmodal'] .= '<h4 class="modal-title" id="myModalLabel">Edit Group</h4>';
                $data['editmodal'] .= '</div>';
                $data['editmodal'] .= '<div class="modal-body">';
                $data['editmodal'] .= '<div class="form-group">';
                $data['editmodal'] .= '<label>Group Name</label>';
                $data['editmodal'] .= '<input class="form-control tbGroupName" placeholder="'.$g['GroupName'].'" id="'.$g['IdGroup'].'"/>';
                $data['editmodal'] .= '<button type="button" class="btn btn-success pull-right btnChangeGroupName" id="'.$g['IdGroup'].'">Change Name</button>';
                $data['editmodal'] .= '</div>';
                $data['editmodal'] .= '<div class="form-group">';
                $data['editmodal'] .= '<label>Admins</label>';

                $data['editmodal'] .= '<div class="admins '.$g['IdGroup'].'">';
                
                if(isset($usergroups['UserGroup']))
                {
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
                }
                $data['editmodal'] .= '</div>';

                $data['editmodal'] .= '</div>';
                $data['editmodal'] .= '<div class="form-group">';
                $data['editmodal'] .= '<label>Members</label>';

                $data['editmodal'] .= '<div class="users '.$g['IdGroup'].'">';
                if(isset($usergroups['UserGroup']))
                {
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
                }
                
                $data['editmodal'] .= '</div>';
                $data['editmodal'] .= '<div class="form-group">';
                $data['editmodal'] .= '<label>New Memers</label>';
                $data['editmodal'] .= '<input class="form-control tbSearchNewUser" placeholder="search..." id="'.$g["IdGroup"].'" />';
                $data['editmodal'] .= '</div">';
                $data['editmodal'] .= '<div class="newusers '.$g['IdGroup'].'">';

                $usersnotingroup = $this->UserGroupModel->getUsersThatAreNotInTheGroup($g['IdGroup']);
                
                if(isset($usersnotingroup['UsersNotInGroup']))
                {
                    foreach($usersnotingroup['UsersNotInGroup'] as $unig)
                    {
                        $data['editmodal'] .= '<button type="button" class="btn btn-default btn-user">'.$unig['UserName'].'<input type="hidden" id="'.$g['IdGroup'].'" class="hdIdGroup"/></i> <i class="fa fa-plus icon-add-newuser" id="'.$unig['IdUser'].'"><input type="hidden" id="'.$g['IdGroup'].'" class="hdIdGroup"/></i></button>';
                    }
                }

                $data['editmodal'] .= '</div>';
                $data['editmodal'] .= '</div>';
                $data['editmodal'] .= '</div>';
                $data['editmodal'] .= '</div>';
                $data['editmodal'] .= '<div class="modal-footer">';
                $data['editmodal'] .= '</div>';
                $data['editmodal'] .= '</div>';
                $data['editmodal'] .= '</div>';
                $data['editmodal'] .= '</div>';
            }
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
        
        if(isset($searchusers["SearchUsers"]))
        {
            foreach($searchusers["SearchUsers"] as $su)
            {
                $response .= '<button type="button" class="btn btn-default btn-user">'.$su['UserName'].'<input type="hidden" id="'.$idgroup.'" class="hdIdGroup"/></i> <i class="fa fa-plus icon-add-newuser" id="'.$su['IdUser'].'"><input type="hidden" id="'.$idgroup.'" class="hdIdGroup"/></i></button>';
            }
        }
        else
        {
            $response .= '<button type="button" class="btn btn-default btn-user">No such user</button>';
        }
        
        echo json_encode($response);
    }
    
    public function createNewGroup()
    {
        $groupname = $this->input->post('GroupName');
        
        $this->load->model("UserGroupModel");
        
        echo json_encode($this->UserGroupModel->createNewGroup($groupname));
    }
    
    public function getGroups()
    {
        $this->load->model("UserGroupModel");
        
        $groups = $this->UserGroupModel->getGroups();
        
        $resposne = array();
        
        $i=0;
        foreach($groups["Group"] as $g)
        {
            $response[$i++] = $g["GroupName"];
        }
        
        echo json_encode($response);
    }
    
    public function changeGroupName(){
        $groupnewname = $this->input->post('NewName');
        $idgroup = $this->input->post('IdGroup');
        
        $this->load->model("UserGroupModel");
        
        $this->UserGroupModel->changeGroupName($idgroup, $groupnewname);
        
        echo json_encode(true);
    }
    
    public function getUsers()
    {
        $idgroup = $this->input->post('IdGroup');
        
        $this->load->model("UserGroupModel");
        
        $users = $this->UserGroupModel->getUsers();
        
        $response = "";
        
        foreach($users['Users'] as $u)
        {
            $response .= '<button type="button" class="btn btn-default btn-user">'.$u['UserName'].'<input type="hidden" id="'.$idgroup.'" class="hdIdGroup"/></i> <i class="fa fa-plus icon-add-newuser" id="'.$u['IdUser'].'"><input type="hidden" id="'.$idgroup.'" class="hdIdGroup"/></i></button>';
        }
        
        echo json_encode($response);
    }
    
    public function deleteGroup()
    {
        $idgroup = $this->input->post('IdGroup');
        
        $this->load->model("UserGroupModel");
        
        $this->UserGroupModel->deleteGroup($idgroup);
        
        echo json_encode(true);
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
        $this->load_view('users', $data);
    }
    
    public function getAllUsers()
    {
        $this->load->model("UserModel");
        
        $users = $this->UserModel->getAllUsers();
        
        $data['data'] = $users;
        
       
    }
    
    public function checkIfEmailExists()
    {
        $email = $this->input->post('Email');
        
        $this->load->model("UserModel");
        
        $response = $this->UserModel->checkIfEmailExists($email);
        
        echo json_encode($response);
    }
    
    public function insertUser()
    {
        $email = $this->input->post('Email');
        
        $this->load->model("UserModel");
        
        $id = $this->UserModel->insertUser($email);
        $key = $this->UserModel->getUserKey($id);
        
        $i=0;
        foreach($key["Key"] as $k)
        {
            $response[$i++] = $k["UserKey"];
        }
        
        echo json_encode($response);
    }
}
