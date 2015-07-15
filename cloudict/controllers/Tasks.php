<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Tasks extends CI_Controller {

    /**
     * THIS FILE SHOULD BE EDITED ONLY FROM tasks-feature BRANCH
     * git checkout tasks-feature
     */

    private $userID;

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


        $this->userID = 2;
        $this->load->model('TaskModel');
        $this->load->model('UserModel');
    }


    /**
     * Shows all Tasks for current user
     */
    public function index(){
        $tasks['assigned'] = $this->TaskModel->getAssignedTaks($this->userID);
        $tasks['given'] = $this->TaskModel->getGivenTasks($this->userID);
        $tasks['url_helper'] = base_url();
        $this->load->view("Task/Show", $tasks);
    }

    /**
     * shows view for creating new task
     */
    public function create(){
        $this->load->view("Task/Create");
    }

    /**
 * Target for create method, stores created task in database
 */
    public function store(){
        $taskName = $this->input->post('taskName');
        $taskDescription = $this->input->post('taskDescription');
        $timeToExecute = $this->input->post('timeToExecute');
        $executeType = $this->input->post('executeType');
        $assignedUserIDs = $this->input->post('assignedUserIDs');

        $this->TaskModel->storeTask($this->userID, $taskName, $taskDescription,
                                    $timeToExecute, $executeType, $assignedUserIDs);
        redirect('Task/Index');
    }

    /**
     * Shows current task, i.e no. of people who finished the selected task
     * TODO: Find a way to limit the access to task to people who are on the task
     */
    public function show($taskID){
        //$taskID = $this->input->post('taskID');
        $task['task'] = $this->TaskModel->getTask($taskID);
        $this->load->view("TaskInfo", $task);
    }

    /**
     * Show view for editing selected task
     */
    public function edit(){

    }

    /**
     * Method for updating selected task
     */
    public function update(){

    }

    /**
     * Method for deleting selected tasks
     */
    public function destroy($taskID){
        //$taskID = $this->input->post('taskID');
        $this->TaskModel->removeTask($taskID);
    }
}