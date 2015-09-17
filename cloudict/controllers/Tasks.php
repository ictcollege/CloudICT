<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Tasks extends CI_Controller
{

    /**
     * THIS FILE SHOULD BE EDITED ONLY FROM tasks-feature BRANCH
     * git checkout tasks-feature
     */

    private $userID;
    private $Username;
    private $base_url;
    private $Groups;

    /**
     * Tasks constructor.
     */
    public function __construct()
    {
        parent::__construct();

        $this->load->helper('url');

        //TODO:Implement proper redirect on not logged in
        //TODO: Update Users Controller to include idUser in session
//        if($_SESSION['LoggedIn'] == true){
//            $this->userID = $this->session->userData("UserID");
//        }
//        else redirect('/User');


        $this->userID = 1;
        $this->Username = "admin";

        $this->load->model('TaskModel');
        $this->load->model('UserModel');

        $this->base_url = base_url();

        $this->Groups = $this->UserModel->getAllUsersInGroups($this->userID);
    }


    /**
     * Shows all Tasks for current user
     */
    public function index()
    {
        $tasks['assigned'] = $this->TaskModel->getAssignedTaks($this->userID);
        $tasks['given'] = $this->TaskModel->getGivenTasks($this->userID);
        $tasks['base_url'] = $this->base_url;
        $tasks['title'] = "Tasks";
        $tasks['count'] = 5;
        $tasks['notifications'] = array();
        $tasks['base_url'] = base_url();


        $this->load->view("header", $tasks);
        $this->load->view("menu", $tasks);
        $this->load->view("Task/ShowAllTasks", $tasks);
}

    /**
     * shows view for creating new task
     */
    public function create()
    {
        $adminGroups = array();
        foreach($this->Groups as $Group => $users){
            if($users[$this->Username]['Status'] == true){
                $adminGroups[$Group] = $users;
            }
        }
        $data['title'] = "Tasks";
        $data['count'] = 5;
        $data['Groups'] = $adminGroups;
        $data['base_url'] = base_url();
        $this->load->view("header", $data);
        $this->load->view("menu", $data);
        $this->load->view("Task/Create", $data);

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

        $this->TaskModel->storeTask($this->userID, $taskName, $taskDescription,
            $timeToExecute, $executeType, $assignedUserIDs);
        redirect('Tasks/Index');
    }

    /**
     * Shows current task, i.e no. of people who finished the selected task
     * TODO: Find a way to limit the access to task to people who are on the task
     */
    public function show($taskID)
    {
        if($this->UserCanSeeTask($taskID)){
            $data['Task'] = $this->TaskModel->getTask($taskID);
            $data['base_url'] = base_url();
            $data['count'] = 5;
            $this->load->view("header", $data);
            $this->load->view("menu", $data);
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
        $assignedTasks = $this->TaskModel->getAssignedTaks($IdTask);
        if($task[0]['IdUser'] == $this->userID){
            return true;
        }
        else {
            foreach($assignedTasks as $task){
                if($task[0]['AssignedUser'] == $this->userID)
                    return true;
            }
        }
        return false;
    }

    private function UserCanEditTask($IdTask)
    {
        echo "Hello World";
        $task = $this->TaskModel->getTask($IdTask);
        if(!empty($task)) {
            echo "Hello World";
            if ($task[0]['IdUser'] == $this->userID)
                return true;
        }
        return false;
    }
}