<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Tasks extends Frontend_Controller
{

    private $userId;
    private $Username;
    private $base_url;
    private $Groups;
    private $menu;


    /**
     * Tasks constructor
     * Initializes all global Models and Libraries
     */
    public function __construct()
    {
        parent::__construct();

        $this->load->helper('url');

        //if($this->isLogged()){
            $this->userId = $this->session->userid;
            $this->Username = $this->session->username;

            $this->load->model('TaskModel');
            $this->load->model('UserModel');
            $this->load->model('MenuModel');

            $this->base_url = base_url();

            $this->Groups = array();
            $this->Groups = $this->UserModel->getAllUsersInGroupsWithId($this->userId);
            $this->menu = $this->MenuModel->getMenuOfApplication(5);
        //}
       // else redirect(base_url());
    }




    /**
     * Displays all tasks assigned to you
     */
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




    /**
     * Displays all tasks you created
     *
     */
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


    /**
     * Displays all finished tasks
     */
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
     * Method that displays the form for creating tasks
     */
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

    /**
     * Method that stores created task into the database
     */
    public function store()
    {
        $submitted = $this->input->post("submit");
        if(isset($submitted)) {

            $this->load->helper('form');

            $taskName = $this->input->post('TaskName');
            $taskDescription = $this->input->post('TaskDescription');
            $timeToExecute = $this->input->post("edate");;
            $executeType = $this->input->post('isGroupTask') == 1 ? true : false;
            $assignedUserIDs = array_unique($this->input->post('Users'));
            $assignedUsers = array();

            print ($executeType);


            foreach ($assignedUserIDs as $id) {
                $user = $this->UserModel->getUserById($id);
                array_push($assignedUsers, array(
                    "id" => $id,
                    "FullName" => $user[0]["UserFullname"]
                ));
            }

            $this->TaskModel->storeTask($this->userId, $taskName, $taskDescription,
                $timeToExecute, $executeType, $assignedUsers);
            redirect('Tasks/');
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

        $data['Error'] = $message;
        $data['base_url'] = base_url();
        $data['count'] = 5;
        $this->load_view("Task/Error", $data);
    }

    /**
     * Mehod that displays the Task with id $taskID
     * @param $taskId
     */
    public function show($taskId)
    {
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
            redirect("Tasks/Index");
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
        print_r($task);
        if(!empty($task['users'])) {
            if ($task['IdUser'] == $this->userId)
                return true;
        }
        return false;
    }
}