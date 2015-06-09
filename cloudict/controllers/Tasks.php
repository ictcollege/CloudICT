<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Tasks extends CI_Controller {

    /**
     * THIS FILE SHOULD BE EDITED ONLY FROM tasks-feature BRANCH
     * git checkout tasks-feature
     */

    private $userID;

    /**
     * None of these methods are final
     */
    /**
     * Tasks constructor.
     */
    public function __construct()
    {
        parent::_construct();
        $this->load->helper('url');

        //TODO:Implement proper redirect
        if($_SESSION['LoggedIn'] == false){
            redirect('/tasks');
        }

        $this->userID = $this->session->userID;
        $this->load->model('TasksModel');
    }


    /**
     * Shows all Tasks for current user
     */
    public function index(){
        $tasks['assigned'] = $this->TasksModel->getAssignedTaks($this->userID);
        $tasks['given'] = $this->TaskModel->getgivenTasks($this->userID);
        $this->load->view("TaskViewData", $tasks);
    }

    /**
     * shows view for creating new task
     */
    public function create(){
        $this->load->view("createTask");
    }

    /**
 * Target for create method, stores created task in database
 */
    public function store(){
        $taskName = $this->input->post('taskName');
        $taskDescription = $this->input->post('taskDescription');
        $timeToExecute = $this->input->post('timeToExecute');
        $executeType = $this->input->post('executeType');
        $assignedUserID = $this->input->post('assignedUserIDs');

        $this->TaskModel->storeTask($taskName, $taskDescription, $timeToExecute, $executeType, $assignedUserID);

        redirect('/tasks');
    }

    /**
     * Shows current task, i.e no. of people who finished the selected task
     * TODO: Find a way to limit the access to task to people who are on the task
     */
    public function show(){
        
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
    public function destroy(){

    }
}