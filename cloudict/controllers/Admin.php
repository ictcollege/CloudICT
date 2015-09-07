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
        
        //data to view
        $data['base_url']= $base_url;
        $data['title'] = "ICT Cloud | Admin | Applications";
        $data['admin'] = true;
            //data for form
            
        
        //views
        $this->load_view('applications', $data);
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
        
        $data = array();
        
        $i = 0;
        foreach($users as $user)
        {
            $data['data'][$i][] = $user["IdUser"];
            $data['data'][$i][] = $user["IdRole"];
            $data['data'][$i][] = $user["UserName"];
            $data['data'][$i][] = $user["UserPassword"];
            $data['data'][$i][] = $user["UserFullname"];
            $data['data'][$i][] = $user["UserEmail"];
            $data['data'][$i][] = $user["UserDiskQuota"];
            $data['data'][$i][] = $user["UserDiskUsed"];
            $data['data'][$i][] = $user["UserStatus"];
            $data['data'][$i][] = $user["UserKey"];
            $data['data'][$i][] = $user["UserKeyExpires"];
            
            $i++;
        }
        
        header("Content-Type:application/json");
        
        echo json_encode($data);
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
    
    public function privileges()
    {
        //helpers
        $this->load->helper('url');
        $this->load->helper('form');
        
        //variables
        $base_url = base_url();
        
        //model
        $this->load->model('MenuModel');
        $this->load->model('GroupApplicationModel');
        $this->load->model('UserGroupModel');
        $this->load->model('ApplicationModel');
       
        $menu = $this->MenuModel->getMenuOfApplication(3);
        
        $data['menu'] = "";
        
        foreach($menu['Menu'] as $m)
        {
            $data['menu'] .= '<li>';
            $data['menu'] .= '<a href="'.$m['AppMenuLink'].'"><i class="fa '.$m['AppMenuIcon'].' fa-fw"></i> '.$m['AppMenuName'].'</a>';
            $data['menu'] .= '</li>';
        }
        
        $groupapplications = $this->GroupApplicationModel->getGroupApplications();
        $groups = $this->UserGroupModel->getGroups();
        $applications = $this->ApplicationModel->getAllApplications();
        
        $data['groupsapplications'] = "";
        $data['editmodal'] = "";
        
        $i = 0;
        if(isset($groups['Group']))
        {
            foreach($groups['Group'] as $g)
            {  
                if($i%3==0)
                {
                   $data['groupsapplications'] .= ' <div class="row">';  
                }
                $data['groupsapplications'] .= '<div class="col-lg-4">';
                $data['groupsapplications'] .= '<div id="'.$g['IdGroup'].'" class="panel panel-primary">';
                $data['groupsapplications'] .= '<div class="panel-heading">';
                $data['groupsapplications'] .= $g['GroupName'];
                $data['groupsapplications'] .= '</div>';
                $data['groupsapplications'] .= '<div class="panel-body">';
                
                if(isset($groupapplications['GroupApplications']))
                {
                    foreach($groupapplications['GroupApplications'] as $ga)
                    {
                        if($ga["IdGroup"] == $g["IdGroup"])
                        {
                            $data['groupsapplications'] .= '<button type="button" id="'.$ga['IdApp'].'" class="btn btn-no-hover btn-admin" style="background-color: '.$ga["AppColor"].'"><i class="fa '.$ga['AppIcon'].' fa-fw"></i> '.$ga['AppName'].'</button>';
                        }
                    }
                }
                
                $data['groupsapplications'] .= '</div>';
                $data['groupsapplications'] .= '<div class="panel-footer group-panel-footer">';
                $data['groupsapplications'] .= '<button type="button" class="btn btn-outline btn-primary btn-xs pull-left" data-toggle="modal" data-target="#mEditGroupApplication'.$g['IdGroup'].'">Edit</button>';
                
                $data['groupsapplications'] .= '</div>';
                $data['groupsapplications'] .= '</div>';
                $data['groupsapplications'] .= '</div>';

                $i++;
                if($i%3==0)
                {
                    $data['groupsapplications'] .= '</div>'; 
                }
                
               
                $data['editmodal'] .= '<div class="modal fade mEditGroupApplication" id="mEditGroupApplication'.$g['IdGroup'].'" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">';
                $data['editmodal'] .= '<div class="modal-dialog" role="document">';
                $data['editmodal'] .= '<div class="modal-content">';
                $data['editmodal'] .= '<div class="modal-header">';
                $data['editmodal'] .= '<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>';
                $data['editmodal'] .= '<h4 class="modal-title" id="myModalLabel">Edit Privileges</h4>';
                $data['editmodal'] .= '</div>';
                $data['editmodal'] .= '<div class="modal-body text-center">';
                
                if(isset($applications['Applications']))
                {
                    foreach($applications['Applications'] as $a)
                    {
                        $data['editmodal'] .= '<button for="'.$a['IdApp'].'" type="button" id="'.$a['IdApp'].'" class="btn btn-no-hover btn-application" style="background-color: '.$a["AppColor"].'"><i class="fa '.$a['AppIcon'].' fa-fw"></i> '.$a['AppName'].'<input type="checkbox" class="chbAppGroup" ';
                        
                        if(isset($groupapplications['GroupApplications']))
                        {
                            foreach($groupapplications['GroupApplications'] as $ga)
                            {
                                if($a["IdApp"] == $ga["IdApp"] && $g["IdGroup"] == $ga["IdGroup"])
                                {
                                    $data['editmodal'] .= 'checked="checked"' ;
                                }
                            }
                        }
                        
                        $data['editmodal'] .= 'id="'.$a['IdApp'].'"/><input type="hidden" id="'.$g["IdGroup"].'" class="hdIdGroup"/></button>';
                    }
                }
                
                $data['editmodal'] .= '</div>';
                $data['editmodal'] .= '<div class="modal-footer text-center">';
                $data['editmodal'] .= '<input type="hidden" id="'.$g['IdGroup'].'" class="hdId"/>';
                $data['editmodal'] .= '</div>';
                $data['editmodal'] .= '</div>';
                $data['editmodal'] .= '</div>';
                $data['editmodal'] .= '</div>';
            }
        }
        
        
        //data to view
        $data['base_url']= $base_url;
        $data['title'] = "ICT Cloud | Admin | Privileges";
            
        //views
        $this->load_view('privileges', $data);
    }
    
    public function removeApplicationForGroup()
    {
        $idgroup = $this->input->post('IdGroup');
        $idapp = $this->input->post('IdApp');
        
        $this->load->model("GroupApplicationModel");
        
        $this->GroupApplicationModel->removeApplicationForGroup($idapp, $idgroup);
    }
    
    public function addApplicationForGroup()
    {
        $idgroup = $this->input->post('IdGroup');
        $idapp = $this->input->post('IdApp');
        
        $this->load->model("GroupApplicationModel");
        
        
        $this->GroupApplicationModel->addApplicationForGroup($idapp, $idgroup);
    }
    
     public function applications()
    {
        //helpers
        $this->load->helper('url');
        $this->load->helper('form');
        
        //variables
        $base_url = base_url();
        
        //model
        $this->load->model('MenuModel');
        $this->load->model('ApplicationModel');
        
        $menu = $this->MenuModel->getMenuOfApplication(4);
        
        $data['menu'] = "";
        
        foreach($menu['Menu'] as $m)
        {
            $data['menu'] .= '<li>';
            $data['menu'] .= '<a href="'.$m['AppMenuLink'].'"><i class="fa '.$m['AppMenuIcon'].' fa-fw"></i> '.$m['AppMenuName'].'</a>';
            $data['menu'] .= '</li>';
        }
        
        $applications = $this->ApplicationModel->getAllApplications();
        
        $data['applications'] = "";
        $data['editmodal'] = "";
        
        $i= 0;
        foreach($applications['Applications'] as $a)
        {
            if($i%3==0)
            {
               $data['applications'] .= ' <div class="row">';  
            }
            
            $data['applications'] .= '<a data-toggle="modal" data-target="#mEditApplication'.$a['IdApp'].'">';
            $data['applications'] .= '<div class="col-sm-4 text-center">';
            $data['applications'] .= '<div class="app app'.($i+1).'" style="background-color: '.$a['AppColor'].'">';
            $data['applications'] .= '<h2><i class="fa '.$a['AppIcon'].' fa-fw"></i></h2>';
            $data['applications'] .= '<h3 class="app-name2">'.$a['AppName'].'</h3>';
            $data['applications'] .= '</div>';
            $data['applications'] .= ' </div>';
            $data['applications'] .= ' </a>';
            
            $i++;
            if($i%3==0)
            {
                $data['applications'] .= '</div>'; 
            }
            
             $data['editmodal'] .= '<div class="modal fade mEditApplication" id="mEditApplication'.$a['IdApp'].'" tabindex="-1" role="dialog" aria-labelledby="'.$a['IdApp'].'">';
                $data['editmodal'] .= '<div class="modal-dialog" role="document">';
                $data['editmodal'] .= '<div class="modal-content">';
                $data['editmodal'] .= '<div class="modal-header">';
                $data['editmodal'] .= '<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>';
                $data['editmodal'] .= '<h4 class="modal-title" id="myModalLabel">Edit "'.$a['AppName'].'" Application</h4>';
                $data['editmodal'] .= '</div>';
                $data['editmodal'] .= '<div class="modal-body">';
                
                $data['editmodal'] .= '<div class="form-group">';
                $data['editmodal'] .= '<label>Application Name</label>';
                $data['editmodal'] .= '<input class="form-control tbApplicationName" placeholder="'.$a['AppName'].'"> </div><div class="form-group"><label>Link</label>';
                $data['editmodal'] .= '<input class="form-control tbLink" placeholder="'.$a['AppLink'].'">';
                $data['editmodal'] .= '</div>';
                $data['editmodal'] .= '<div class="form-group"><label>Color</label><div class="input-group demo2">';
                $data['editmodal'] .= '<input type="text" value="'.$a['AppColor'].'" class="form-control tbColor" />';
                $data['editmodal'] .= '<span class="input-group-addon"><i></i></span></div></div><div class="form-group"><label>Icon</label><div class="input-group iconpicker-container">';
                $data['editmodal'] .= '<input data-placement="bottomRight" class="form-control icp icp-auto iconpicker-element iconpicker-input tbIcon" value="'.$a['AppIcon'].'" type="text">';
                $data['editmodal'] .= '<span class="input-group-addon"><i class="fa fa-archive"></i></span></div></div>';
                $data['editmodal'] .= '</div>';
                $data['editmodal'] .= '<div class="modal-footer text-center">';
                $data['editmodal'] .= '<div class="form-group input-group pull-left">';
                $data['editmodal'] .= '<button class="btn btn-danger bntDeleteApplication" type="button"><i class="fa  fa-trash-o" id="'.$a['IdApp'].'"></i> Delete Application</button>';
                $data['editmodal'] .= '<button class="btn btn-primary btnEditApplication pull-right" type="button"><i class="fa  fa-trash-o" id="'.$a['IdApp'].'"></i>Save</button>';
                $data['editmodal'] .= '</div>';
                $data['editmodal'] .= '</div>';
                $data['editmodal'] .= '</div>';
                $data['editmodal'] .= '</div>';
                $data['editmodal'] .= '</div>';
        }
        
        //data to view
        $data['base_url']= $base_url;
        $data['title'] = "ICT Cloud | Admin | System";
            
        //views
        $this->load_view('systemapplications', $data);
    }
    
    public function addNewApplication()
    {
        $appname = $this->input->post('Name');
        $applink = $this->input->post('Link');
        $appicon = $this->input->post('Icon');
        $appcolor = $this->input->post('Color');
        
        $this->load->model("ApplicationModel");
        
        $this->ApplicationModel->addNewApplication($appname, $applink, $appicon, $appcolor);
        
        $applications = $this->ApplicationModel->getAllApplications();
        
        $data['applications'] = "";
        $data['editmodal'] = "";
        
        $i= 0;
        foreach($applications['Applications'] as $a)
        {
            if($i%3==0)
            {
               $data['applications'] .= ' <div class="row">';  
            }
            
            $data['applications'] .= '<a data-toggle="modal" data-target="#mEditApplication'.$a['IdApp'].'">';
            $data['applications'] .= '<div class="col-sm-4 text-center">';
            $data['applications'] .= '<div class="app app'.($i+1).'" style="background-color: '.$a['AppColor'].'">';
            $data['applications'] .= '<h2><i class="fa '.$a['AppIcon'].' fa-fw"></i></h2>';
            $data['applications'] .= '<h3 class="app-name2">'.$a['AppName'].'</h3>';
            $data['applications'] .= '</div>';
            $data['applications'] .= ' </div>';
            $data['applications'] .= ' </a>';
            
            $i++;
            if($i%3==0)
            {
                $data['applications'] .= '</div>'; 
            }
            
             $data['editmodal'] .= '<div class="modal fade mEditApplication" id="mEditApplication'.$a['IdApp'].'" tabindex="-1" role="dialog" aria-labelledby="'.$a['IdApp'].'">';
                $data['editmodal'] .= '<div class="modal-dialog" role="document">';
                $data['editmodal'] .= '<div class="modal-content">';
                $data['editmodal'] .= '<div class="modal-header">';
                $data['editmodal'] .= '<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>';
                $data['editmodal'] .= '<h4 class="modal-title" id="myModalLabel">Edit "'.$a['AppName'].'" Application</h4>';
                $data['editmodal'] .= '</div>';
                $data['editmodal'] .= '<div class="modal-body">';
                
                $data['editmodal'] .= '<div class="form-group">';
                $data['editmodal'] .= '<label>Application Name</label>';
                $data['editmodal'] .= '<input class="form-control tbApplicationName" placeholder="'.$a['AppName'].'"> </div><div class="form-group"><label>Link</label>';
                $data['editmodal'] .= '<input class="form-control tbLink" placeholder="'.$a['AppLink'].'">';
                $data['editmodal'] .= '</div>';
                $data['editmodal'] .= '<div class="form-group"><label>Color</label><div class="input-group demo2">';
                $data['editmodal'] .= '<input type="text" value="'.$a['AppColor'].'" class="form-control tbColor" />';
                $data['editmodal'] .= '<span class="input-group-addon"><i></i></span></div></div><div class="form-group"><label>Icon</label><div class="input-group iconpicker-container">';
                $data['editmodal'] .= '<input data-placement="bottomRight" class="form-control icp icp-auto iconpicker-element iconpicker-input tbIcon" value="'.$a['AppIcon'].'" type="text">';
                $data['editmodal'] .= '<span class="input-group-addon"><i class="fa fa-archive"></i></span></div></div>';
                $data['editmodal'] .= '</div>';
                $data['editmodal'] .= '<div class="modal-footer text-center">';
                $data['editmodal'] .= '<div class="form-group input-group pull-left">';
                $data['editmodal'] .= '<button class="btn btn-danger bntDeleteApplication" type="button"><i class="fa  fa-trash-o" id="'.$a['IdApp'].'"></i> Delete Application</button>';
                $data['editmodal'] .= '<button class="btn btn-primary btnEditApplication pull-right" type="button"><i class="fa  fa-trash-o" id="'.$a['IdApp'].'"></i>Save</button>';
                $data['editmodal'] .= '</div>';
                $data['editmodal'] .= '</div>';
                $data['editmodal'] .= '</div>';
                $data['editmodal'] .= '</div>';
                $data['editmodal'] .= '</div>';
        }
        
        echo json_encode($data);
    }
    
    public function editApplication()
    {
        $idapp = $this->input->post('IdApp');
        $appname = $this->input->post('Name');
        $applink = $this->input->post('Link');
        $appicon = $this->input->post('Icon');
        $appcolor = $this->input->post('Color');
        
        $this->load->model("ApplicationModel");
        
        $this->ApplicationModel->editApplication($appname, $applink, $appicon, $appcolor, $idapp);
        
        $applications = $this->ApplicationModel->getAllApplications();
        
        $data['applications'] = "";
        $data['editmodal'] = "";
        
        $i= 0;
        foreach($applications['Applications'] as $a)
        {
            if($i%3==0)
            {
               $data['applications'] .= ' <div class="row">';  
            }
            
            $data['applications'] .= '<a data-toggle="modal" data-target="#mEditApplication'.$a['IdApp'].'">';
            $data['applications'] .= '<div class="col-sm-4 text-center">';
            $data['applications'] .= '<div class="app app'.($i+1).'" style="background-color: '.$a['AppColor'].'">';
            $data['applications'] .= '<h2><i class="fa '.$a['AppIcon'].' fa-fw"></i></h2>';
            $data['applications'] .= '<h3 class="app-name2">'.$a['AppName'].'</h3>';
            $data['applications'] .= '</div>';
            $data['applications'] .= ' </div>';
            $data['applications'] .= ' </a>';
            
            $i++;
            if($i%3==0)
            {
                $data['applications'] .= '</div>'; 
            }
            
             $data['editmodal'] .= '<div class="modal fade mEditApplication" id="mEditApplication'.$a['IdApp'].'" tabindex="-1" role="dialog" aria-labelledby="'.$a['IdApp'].'">';
                $data['editmodal'] .= '<div class="modal-dialog" role="document">';
                $data['editmodal'] .= '<div class="modal-content">';
                $data['editmodal'] .= '<div class="modal-header">';
                $data['editmodal'] .= '<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>';
                $data['editmodal'] .= '<h4 class="modal-title" id="myModalLabel">Edit "'.$a['AppName'].'" Application</h4>';
                $data['editmodal'] .= '</div>';
                $data['editmodal'] .= '<div class="modal-body">';
                
                $data['editmodal'] .= '<div class="form-group">';
                $data['editmodal'] .= '<label>Application Name</label>';
                $data['editmodal'] .= '<input class="form-control tbApplicationName" placeholder="'.$a['AppName'].'"> </div><div class="form-group"><label>Link</label>';
                $data['editmodal'] .= '<input class="form-control tbLink" placeholder="'.$a['AppLink'].'">';
                $data['editmodal'] .= '</div>';
                $data['editmodal'] .= '<div class="form-group"><label>Color</label><div class="input-group demo2">';
                $data['editmodal'] .= '<input type="text" value="'.$a['AppColor'].'" class="form-control tbColor" />';
                $data['editmodal'] .= '<span class="input-group-addon"><i></i></span></div></div><div class="form-group"><label>Icon</label><div class="input-group iconpicker-container">';
                $data['editmodal'] .= '<input data-placement="bottomRight" class="form-control icp icp-auto iconpicker-element iconpicker-input tbIcon" value="'.$a['AppIcon'].'" type="text">';
                $data['editmodal'] .= '<span class="input-group-addon"><i class="fa fa-archive"></i></span></div></div>';
                $data['editmodal'] .= '</div>';
                $data['editmodal'] .= '<div class="modal-footer text-center">';
                $data['editmodal'] .= '<div class="form-group input-group pull-left">';
                $data['editmodal'] .= '<button class="btn btn-danger bntDeleteApplication" type="button"><i class="fa  fa-trash-o" id="'.$a['IdApp'].'"></i> Delete Application</button>';
                $data['editmodal'] .= '<button class="btn btn-primary btnEditApplication pull-right" type="button"><i class="fa  fa-trash-o" id="'.$a['IdApp'].'"></i>Save</button>';
                $data['editmodal'] .= '</div>';
                $data['editmodal'] .= '</div>';
                $data['editmodal'] .= '</div>';
                $data['editmodal'] .= '</div>';
                $data['editmodal'] .= '</div>';
        }
        
        echo json_encode($data);
    }
    
    public function deleteApplication()
    {
        $idapp = $this->input->post('IdApp');
        
        $this->load->model("ApplicationModel");
        
        $this->ApplicationModel->deleteApplication($idapp);
        
        $applications = $this->ApplicationModel->getAllApplications();
        
        $data['applications'] = "";
        $data['editmodal'] = "";
        
        $i= 0;
        foreach($applications['Applications'] as $a)
        {
            if($i%3==0)
            {
               $data['applications'] .= ' <div class="row">';  
            }
            
            $data['applications'] .= '<a data-toggle="modal" data-target="#mEditApplication'.$a['IdApp'].'">';
            $data['applications'] .= '<div class="col-sm-4 text-center">';
            $data['applications'] .= '<div class="app app'.($i+1).'" style="background-color: '.$a['AppColor'].'">';
            $data['applications'] .= '<h2><i class="fa '.$a['AppIcon'].' fa-fw"></i></h2>';
            $data['applications'] .= '<h3 class="app-name2">'.$a['AppName'].'</h3>';
            $data['applications'] .= '</div>';
            $data['applications'] .= ' </div>';
            $data['applications'] .= ' </a>';
            
            $i++;
            if($i%3==0)
            {
                $data['applications'] .= '</div>'; 
            }
            
             $data['editmodal'] .= '<div class="modal fade mEditApplication" id="mEditApplication'.$a['IdApp'].'" tabindex="-1" role="dialog" aria-labelledby="'.$a['IdApp'].'">';
                $data['editmodal'] .= '<div class="modal-dialog" role="document">';
                $data['editmodal'] .= '<div class="modal-content">';
                $data['editmodal'] .= '<div class="modal-header">';
                $data['editmodal'] .= '<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>';
                $data['editmodal'] .= '<h4 class="modal-title" id="myModalLabel">Edit "'.$a['AppName'].'" Application</h4>';
                $data['editmodal'] .= '</div>';
                $data['editmodal'] .= '<div class="modal-body">';
                
                $data['editmodal'] .= '<div class="form-group">';
                $data['editmodal'] .= '<label>Application Name</label>';
                $data['editmodal'] .= '<input class="form-control tbApplicationName" placeholder="'.$a['AppName'].'"> </div><div class="form-group"><label>Link</label>';
                $data['editmodal'] .= '<input class="form-control tbLink" placeholder="'.$a['AppLink'].'">';
                $data['editmodal'] .= '</div>';
                $data['editmodal'] .= '<div class="form-group"><label>Color</label><div class="input-group demo2">';
                $data['editmodal'] .= '<input type="text" value="'.$a['AppColor'].'" class="form-control tbColor" />';
                $data['editmodal'] .= '<span class="input-group-addon"><i></i></span></div></div><div class="form-group"><label>Icon</label><div class="input-group iconpicker-container">';
                $data['editmodal'] .= '<input data-placement="bottomRight" class="form-control icp icp-auto iconpicker-element iconpicker-input tbIcon" value="'.$a['AppIcon'].'" type="text">';
                $data['editmodal'] .= '<span class="input-group-addon"><i class="fa fa-archive"></i></span></div></div>';
                $data['editmodal'] .= '</div>';
                $data['editmodal'] .= '<div class="modal-footer text-center">';
                $data['editmodal'] .= '<div class="form-group input-group pull-left">';
                $data['editmodal'] .= '<button class="btn btn-danger bntDeleteApplication" type="button"><i class="fa  fa-trash-o" id="'.$a['IdApp'].'"></i> Delete Application</button>';
                $data['editmodal'] .= '<button class="btn btn-primary btnEditApplication pull-right" type="button"><i class="fa  fa-trash-o" id="'.$a['IdApp'].'"></i>Save</button>';
                $data['editmodal'] .= '</div>';
                $data['editmodal'] .= '</div>';
                $data['editmodal'] .= '</div>';
                $data['editmodal'] .= '</div>';
                $data['editmodal'] .= '</div>';
        }
        
        echo json_encode($data);
    }
}
