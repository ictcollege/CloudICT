<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Tasks extends Frontend_Controller
{

    private $userId;
    private $Username;
    private $base_url;
    private $Groups;
    private $menu;
    //Modal form Data
    private $formTaskName;
    private $formOptions;
    private $formSubmit;
    private $formTaskDescription;
    private $formEndDate;

    private $adminGroups;


    /**
     * Tasks constructor
     * Initializes all global Models and Libraries
     */
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

            $this->adminGroups = array();
            foreach($this->Groups as $group => $users){
                if($users[$this->Username]['Status'] == true){
                    $this->adminGroups[$group] = $users;
                }
            }

            $this->formTaskName = array(
                "name" => "TaskName",
                "id" => "Taskname",
                "required" => "required"
            );

            $this->formOptions = array(
                "onSubmit" => "return formValidate()"
            );

            $this->formSubmit = array(
                "type" => "button",
                "name" => "submit",
                "value" => "Create Task",
            );

            $this->formTaskDescription = array(
                "name" => "TaskDescription",
                "id" => "TaskDescription",
                "required" => "required"
            );

            $this->formEndDate = array(
                "name" => "edate",
                "id" => "edate",
                "type" => "date",
                "min" => date("Y-m-d", strtotime('tomorrow')),
                "value" => date("Y-m-d", strtotime('tomorrow'))
            );
        }
        else redirect(base_url());
    }




    /**
     * Displays all tasks assigned to you
     */
    public function index()
    {
        $this->session->set_userdata('referred_from', current_url());
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

        $data['formTaskName'] = $this->formTaskName;
        $data['formOptions'] = $this->formOptions;
        $data['formSubmit'] = $this->formSubmit;
        $data['formTaskDescription'] = $this->formTaskDescription;
        $data['formEndDate'] = $this->formEndDate;

        $data['Groups'] = $this->adminGroups;


        $this->load_view("Task/TaskList", $data);
    }




    /**
     * Displays all tasks you created
     *
     */
    public function assigned()
    {
        $this->session->set_userdata('referred_from', current_url());
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

        $data['formTaskName'] = $this->formTaskName;
        $data['formOptions'] = $this->formOptions;
        $data['formSubmit'] = $this->formSubmit;
        $data['formTaskDescription'] = $this->formTaskDescription;
        $data['formEndDate'] = $this->formEndDate;

        $data['Groups'] = $this->adminGroups;

        $this->load_view("Task/TaskList", $data);
    }


    /**
     * Displays all finished tasks
     */
    public function finished(){
        $this->session->set_userdata('referred_from', current_url());
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

        $data['formTaskName'] = $this->formTaskName;
        $data['formOptions'] = $this->formOptions;
        $data['formSubmit'] = $this->formSubmit;
        $data['formTaskDescription'] = $this->formTaskDescription;
        $data['formEndDate'] = $this->formEndDate;

        $data['Groups'] = $this->adminGroups;

        $this->load_view("Task/TaskList", $data);
    }



    /**
     * Method that finishes tasks with $taskId
     * @param $taskId
     */
    public function finish($taskId)
    {
        if($this->UserCanSeeTask($taskId)){
            $this->TaskModel->finishTask($taskId, $this->userId);
        }
        redirect("Tasks/");
    }

    /**
     * Method that stores created task into the database
     */
    public function store()
    {
        $submitted = $this->input->post("submit");
        if(isset($submitted)) {
            $this->load->helper('form');
            $errors = array();

            $taskName = trim($this->input->post('TaskName'));
            $taskDescription = trim($this->input->post('TaskDescription'));
            $timeToExecute = $this->input->post("edate");
            $executeType = $this->input->post('isGroupTask') == 1 ? true : false;
            if(is_array($this->input->post('Users')))
                $assignedUserIDs = array_unique($this->input->post('Users'));
            $assignedUsers = array();

            $n = strlen($taskName);
            if($n < 5){
                array_push($errors, "Task name must be longer than 5 characters.");
            }

            $n = strlen($taskDescription);
            if($n < 10){
                array_push($errors, "Task description must be longer than 10 characters.");
            }

            $dateCheck = strtotime($timeToExecute);
            $time = time();
            if(is_nan($dateCheck)){
                array_push($errors, "End date must be a valid date format.");
            }
            else if($dateCheck < $time){
                array_push($errors, "Task end date must be a valid date in the future.");
            }


            if(is_array($assignedUserIDs)) {
                foreach ($assignedUserIDs as $id) {
                    $user = $this->UserModel->getUserById($id);
                    array_push($assignedUsers, array(
                        "id" => $id,
                        "FullName" => $user[0]["UserFullname"]
                    ));
                }
            }

            if(count($assignedUsers) == 0){
                array_push($errors, "You must assign at least on user for the task");
            }

            if(count($errors) > 0){
                $this->showError($errors);
            }
            else{
                $this->TaskModel->storeTask($this->userId, $taskName, $taskDescription,
                    $timeToExecute, $executeType, $assignedUsers);
                $referred_from = $this->session->userdata('referred_from');
                redirect($referred_from, 'refresh');
            }
        }
        else{
            $this->showError("There was an error while trying to create the task.");
        }
    }

    /**
     * Helper method that displays an error message within $message parameter
     * @param $message
     */
    public function showError($message)
    {
        $data['menu'] = "";

        foreach($this->menu['Menu'] as $m)
        {
            $data['menu'] .= '<li>';
            $data['menu'] .= '<a href="'.$m['AppMenuLink'].'"><i class="fa '.$m['AppMenuIcon'].' fa-fw"></i> '.$m['AppMenuName'].'</a>';
            $data['menu'] .= '</li>';
        }

        if(is_array($message)){
            $data['ErrorList'] = $message;
            $data['Error'] = "There where multiple errors while trying to execute your task";
        }
        else if(isset($message)){
            $data['Error'] = $message;
        }
        else{
            $data['Error'] = "There was an error while trying to execute your task";
        }

        $data['base_url'] = base_url();
        $this->load_view("Task/Error", $data);
    }

    /**
     * Mehod that displays the Task with id $taskID
     * @param $taskId
     */
    public function show($taskId)
    {
        $this->currentURL = current_url();
        $task = $this->TaskModel->getTask($taskId);
        if($this->UserCanSeeTask($taskId)){
            $data['task'] = $task;
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


    /**
     * Method that deletes the task with id $taskId
     * @param $taskId
     */
    public function destroy($taskId)
    {
        if($this->UserCanEditTask($taskId)) {
            $this->TaskModel->removeTask($taskId);
            $referred_from = $this->session->userdata('referred_from');
            redirect($referred_from, 'refresh');
        }
        else {
            $this->showError("You do not have premission to delete this task");
        }
    }


    /**
     * Method that returns whether the current user can access the task with id $taskId
     * @param $taskId
     * @return bool
     */
    private function UserCanSeeTask($taskId)
    {
        $task = $this->TaskModel->getTask($taskId);
        if(empty($task)) return false;
        $assignedTasks = $this->TaskModel->getCreatedTasks($this->userId);
        if($task['IdUser'] == $this->userId){
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


    /**
     * Method that returns whether the current user can edit the task with id $taskId
     * @param $taskId
     * @return bool
     */
    private function UserCanEditTask($taskId)
    {
        $task = $this->TaskModel->getTask($taskId);
        if(!empty($task['users'])) {
            if ($task['IdUser'] == $this->userId)
                return true;
        }
        return false;
    }
}