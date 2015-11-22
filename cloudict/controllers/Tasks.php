<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Tasks extends Backend_Controller
{

    /**
     * THIS FILE SHOULD BE EDITED ONLY FROM tasks-feature BRANCH
     * git checkout tasks-feature
     */

    private $userId;
    private $Username;
    private $base_url;
    private $Groups;
    private $menu;

    /**
     * Tasks constructor.
     */
    public function __construct()
    {
        parent::__construct();

        $this->load->helper('url');

        //TODO:Implement proper redirect on not logged in

        if($this->isLogged()){
            $this->userId = $this->session->userid;
            $this->Username = $this->session->username;

            $this->load->model('TaskModel');
            $this->load->model('UserModel');
            $this->load->model('MenuModel');

            $this->base_url = base_url();

            $this->Groups = array();
            $this->Groups = $this->UserModel->getAllUsersInGroupsWithId($this->userId);
            $this->menu = $this->MenuModel->getMenuOfApplication(5);
        }
        else redirect(base_url());
    }


    /**
     * Shows all Tasks for current user
     */
    public function index()
    {
        $data['assigned'] = $this->TaskModel->getAssignedTaks($this->userId);
        $data['given'] = $this->TaskModel->getGivenTasks($this->userId);
        $data['base_url'] = $this->base_url;
        $data['title'] = "ICT Cloud | Admin | Tasks";

        $data['menu'] = "";

        foreach($this->menu['Menu'] as $m)
        {
            $data['menu'] .= '<li>';
            $data['menu'] .= '<a href="'.$m['AppMenuLink'].'"><i class="fa '.$m['AppMenuIcon'].' fa-fw"></i> '.$m['AppMenuName'].'</a>';
            $data['menu'] .= '</li>';
        }

        $this->load_view("Task/ShowAllTasks", $data);
}

    /**
     * shows view for creating new task
     */
    public function create()
    {
        if(empty($this->Groups)) print_r($this->Groups);
        $adminGroups = array();
        foreach($this->Groups as $group => $users){
            if($users[$this->Username]['Status'] == true){
                $adminGroups[$group] = $users;
            }
        }
        $data['title'] = "Tasks";
        $data['Groups'] = $adminGroups;
        $data['base_url'] = $this->base_url;

        $data['menu'] = "";

        foreach($this->menu['Menu'] as $m)
        {
            $data['menu'] .= '<li>';
            $data['menu'] .= '<a href="'.$m['AppMenuLink'].'"><i class="fa '.$m['AppMenuIcon'].' fa-fw"></i> '.$m['AppMenuName'].'</a>';
            $data['menu'] .= '</li>';
        }

        $this->load_view("Task/Create", $data);

    }

    /**
     * Target for create method, stores created task in database
     */
    public function store()
    {
        $this->load->helper('form');
        $taskName = $this->input->post('TaskName');
        $taskDescription = $this->input->post('TaskDescription');
        $timeToExecute = 0;
        $executeType = $this->input->post('isGroupTask') == null ? false : true;
        $assignedUserIDs = array_unique(explode(",", $this->input->post('Users')));

        $this->TaskModel->storeTask($this->userId, $taskName, $taskDescription,
            $timeToExecute, $executeType, $assignedUserIDs);
        redirect('Tasks/');
    }

    /**
     * Shows current task, i.e no. of people who finished the selected task
     * TODO: Find a way to limit the access to task to people who are on the task
     */
    public function show($taskID)
    {
        $task = $this->TaskModel->getTask($taskID);
        if($this->UserCanSeeTask($taskID) && !empty($task)){
            $data['Task'] = $task;
            $data['base_url'] = base_url();
            $data['count'] = 5;
            $this->load->view("Task/Details", $data);
        }
        else{
            $data["Error"] = "You do not have permision to see this Task";
            $data['base_url'] = base_url();
            $data['count'] = 5;
            $this->load->view("header", $data);
            $this->load->view("menu", $data);
            $this->load->view("Task/Error", $data);
        }
    }

    /**
     * Show view for editing selected task
     */
    public function edit()
    {
        $this->load->view('Task/Edit');
    }

    /**
     * Method for updating selected task
     */
    public function update()
    {

    }

    /**
     * Method for deleting selected tasks
     */
    public function destroy($taskID)
    {
        if($this->UserCanEditTask($taskID)) {
            $this->TaskModel->removeTask($taskID);
            redirect("Tasks/Index");
        }
        else {
            $data['Error'] = "You do not have premission to delete this task";
            $data['base_url'] = base_url();
            $data['count'] = 5;
            $this->load->view("header", $data);
            $this->load->view("menu", $data);
            $this->load->view("Task/Error", $data);
        }
    }

    private function UserCanSeeTask($IdTask)
    {
        $task = $this->TaskModel->getTask($IdTask);
        if(empty($task)) redirect("Tasks/error");
        $assignedTasks = $this->TaskModel->getAssignedTaks($IdTask);
        if($task[0]['IdUser'] == $this->userId){
            return true;
        }
        else {
            foreach($assignedTasks as $task){
                if($task[0]['AssignedUser'] == $this->userId)
                    return true;
            }
        }
        return false;
    }

    private function UserCanEditTask($IdTask)
    {
        echo "Hello World";
        $task = $this->TaskModel->getTask($IdTask);
        if(empty($task)) redirect("Tasks/error");
        if(!empty($task)) {
            echo "Hello World";
            if ($task[0]['IdUser'] == $this->userId)
                return true;
        }
        return false;
    }
}