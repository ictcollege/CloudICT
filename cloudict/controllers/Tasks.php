<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Tasks extends Backend_Controller
{

    private $userId;
    private $Username;
    private $base_url;
    private $Groups;
    private $menu;


    public function __construct()
    {
        parent::__construct();

        $this->load->helper('url');

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

    public function index()
    {
        $data['tasks'] = $this->TaskModel->getAssignedTasks($this->userId);
        $data['taskTitle'] = "Tasks assigned to you";
        $data['base_url'] = $this->base_url;
        $data['title'] = "ICT Cloud | Admin | Tasks";

        $data['finish'] = true;
        $data['delete'] = false;

        $data['menu'] = "";

        foreach($this->menu['Menu'] as $m)
        {
            $data['menu'] .= '<li>';
            $data['menu'] .= '<a href="'.$m['AppMenuLink'].'"><i class="fa '.$m['AppMenuIcon'].' fa-fw"></i> '.$m['AppMenuName'].'</a>';
            $data['menu'] .= '</li>';
        }

        $this->load_view("Task/TaskList", $data);
    }

    public function assigned()
    {
        $data['tasks'] = $this->TaskModel->getCreatedTasks($this->userId);
        $data['taskTitle'] = "Tasks you Assigned";
        $data['base_url'] = $this->base_url;
        $data['title'] = "ICT Cloud | Admin | Tasks";

        $data['finish'] = false;
        $data['delete'] = true;

        $data['menu'] = "";

        foreach($this->menu['Menu'] as $m)
        {
            $data['menu'] .= '<li>';
            $data['menu'] .= '<a href="'.$m['AppMenuLink'].'"><i class="fa '.$m['AppMenuIcon'].' fa-fw"></i> '.$m['AppMenuName'].'</a>';
            $data['menu'] .= '</li>';
        }

        $this->load_view("Task/TaskList", $data);
    }

    public function finished(){
        $data['tasks'] = $this->TaskModel->getFinishedTasks($this->userId);
        $data['taskTitle'] = "Finished Tasks";
        $data['base_url'] = $this->base_url;
        $data['title'] = "ICT Cloud | Admin | Tasks";

        $data['finish'] = false;
        $data['delete'] = false;

        $data['menu'] = "";

        foreach($this->menu['Menu'] as $m)
        {
            $data['menu'] .= '<li>';
            $data['menu'] .= '<a href="'.$m['AppMenuLink'].'"><i class="fa '.$m['AppMenuIcon'].' fa-fw"></i> '.$m['AppMenuName'].'</a>';
            $data['menu'] .= '</li>';
        }

        $this->load_view("Task/TaskList", $data);
    }

    public function finish($taskId)
    {
        if($this->UserCanSeeTask($taskId)){
            $this->TaskModel->finishTask($taskId);
        }
        redirect("Tasks/");
    }

    public function create()
    {
        $adminGroups = array();
        foreach($this->Groups as $group => $users){
            if($users[$this->Username]['Status'] == true){
                $adminGroups[$group] = $users;
            }
        }
        $data['title'] = "Tasks";
        $data['Groups'] = $adminGroups;
        $data['base_url'] = $this->base_url;

        $data["edate"] = array(
            "type" => "date",
            "name" => "edate"
        );

        $data['menu'] = "";

        foreach($this->menu['Menu'] as $m)
        {
            $data['menu'] .= '<li>';
            $data['menu'] .= '<a href="'.$m['AppMenuLink'].'"><i class="fa '.$m['AppMenuIcon'].' fa-fw"></i> '.$m['AppMenuName'].'</a>';
            $data['menu'] .= '</li>';
        }

        $this->load_view("Task/Create", $data);

    }

    public function store()
    {
        $submitted = $this->input->post("submit");
        if(isset($submitted)) {
            $this->load->helper('form');
            $taskName = $this->input->post('TaskName');
            $taskDescription = $this->input->post('TaskDescription');
            $timeToExecute = $this->input->post("edate");;
            $executeType = $this->input->post('isGroupTask') == null ? false : true;
            $assignedUserIDs = array_unique($this->input->post('Users'));
            $assignedUsers = array();
            foreach ($assignedUserIDs as $id) {
                $user = $this->UserModel->getUserById($id);
                array_push($assignedUsers, array(
                    "id" => $id,
                    "FullName" => $user[0]["UserFullname"]
                ));
            }
            print_r($this->input->post('Users'));
            $this->TaskModel->storeTask($this->userId, $taskName, $taskDescription,
                $timeToExecute, $executeType, $assignedUsers);
            redirect('Tasks/');
        }
        else{
            $this->showError("There was an error while trying to create the task.");
        }
    }

    public function showError($message)
    {
        $data['menu'] = "";

        foreach($this->menu['Menu'] as $m)
        {
            $data['menu'] .= '<li>';
            $data['menu'] .= '<a href="'.$m['AppMenuLink'].'"><i class="fa '.$m['AppMenuIcon'].' fa-fw"></i> '.$m['AppMenuName'].'</a>';
            $data['menu'] .= '</li>';
        }

        $data['Error'] = $message;
        $data['base_url'] = base_url();
        $data['count'] = 5;
        $this->load_view("Task/Error", $data);
    }

    public function show($taskID)
    {
        $task = $this->TaskModel->getTask($taskID);
        if($this->UserCanSeeTask($taskID)){
            $data['Task'] = $task;
            $data['base_url'] = base_url();
            $data['count'] = 5;

            $data['menu'] = "";

            foreach($this->menu['Menu'] as $m)
            {
                $data['menu'] .= '<li>';
                $data['menu'] .= '<a href="'.$m['AppMenuLink'].'"><i class="fa '.$m['AppMenuIcon'].' fa-fw"></i> '.$m['AppMenuName'].'</a>';
                $data['menu'] .= '</li>';
            }

            $this->load_view("Task/Details", $data);
        }
        else{
            if(!empty($task))$this->showError("You do not have permision to see this Task!");
            else $this->showError("Task does not exist!");

        }
    }

    public function destroy($taskId)
    {
        if($this->UserCanEditTask($taskId)) {
            $this->TaskModel->removeTask($taskId);
            redirect("Tasks/Index");
        }
        else {
            $this->showError("You do not have premission to delete this task");
        }
    }

    private function UserCanSeeTask($taskId)
    {
        $task = $this->TaskModel->getTask($taskId);
        if(empty($task)) return false;
        $assignedTasks = $this->TaskModel->getCreatedTasks($this->userId);
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

    private function UserCanEditTask($taskId)
    {
        echo "Hello World";
        $task = $this->TaskModel->getTask($taskId);
        if(empty($task)) return false;
        if(!empty($task)) {
            echo "Hello World";
            if ($task[0]['IdUser'] == $this->userId)
                return true;
        }
        return false;
    }
}