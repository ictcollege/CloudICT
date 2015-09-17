<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class TaskModel extends CI_Model
{


    public function getTask($IdTask)
    {
        $this->db->where("IdTask", $IdTask);
        return $this->db->get("Task")->result_array();
    }

    /**
     * Get Tasks assigned to you
     * @param $currentUserID
     * @return mixed
     */
    public function getAssignedTaks($currentUserID)
    {

        $query = "
            SELECT t.IdTask, TaskName, TaskDescription, TaskTimeCreated, TaskTimeToExecute, TaskExecuteType, tu.IdUser AS AssignedUser
            FROM Task AS  t JOIN TaskUser AS tu ON t.IdTask = tu.IdTask
            WHERE tu.idUser = ?
        ";


        $result = $this->db->query($query, [$currentUserID])->result_array();


        $data = $result;

        return $data;


    }

    /**
     * Get tasks that you assigned
     * @param $currentUserID (int)
     * @return mixed
     */
    public function getGivenTasks($currentUserID)
    {
        $query = "
            SELECT IdTask, TaskName, TaskDescription, TaskTimeCreated, TaskTimeToExecute, TaskExecuteType
            FROM Task
            WHERE idUser = ?
        ";


        $result = $this->db->query($query, [$currentUserID])->result_array();


        $data = $result;

        return $data;
    }


    /**
     * @param $userID (int), Id of user who is creating task
     * @param $taskName (string), Name of the task being created
     * @param $taskDescription (string), Description of the task being created
     * @param $timeToExecute (date), Date when the task expires
     * @param $executeType (boolean), type of the task, the Task can be group or singular
     * @param $assignedUser (array), Contains user data of users assigned to the Task
     * @return bool
     */
    public function storeTask($userID, $taskName, $taskDescription, $timeToExecute, $executeType, $assignedUser)
    {
        $data = array(
            'IdUser' => $userID,
            'TaskName' => $taskName,
            'TaskDescription' => $taskDescription,
            //'TimeCreated' => date("Y-m-d H:i:s"),
            'TaskTimeCreated' => 1,
            'TaskTimeToExecute' => $timeToExecute,
            'TaskExecuteType' => $executeType
        );

        $this->db->insert('Task', $data);
        $insertID = $this->db->insert_id();

        //if ($insertID != null) {

            //if (!empty($assignedUser)) {
                $taskUserData = array();
                foreach ($assignedUser as $user) {
                    array_push($taskUserData, array(
                        'IdTask' => $insertID,
                        'IdUser' => $user['idUser'],
                        'TaskUserFullname' => $user['username'],
                        'TaskUserAssigned' => date("Y-m-d H:i:s"),
                    ));
                }
                return $this->db->insert_batch('TaskUser', $taskUserData);
            //}

            return true;
        //}

        //return false;
    }


    public function RemoveTask($IdTask)
    {
        $query = "
            DELETE FROM Task
            WHERE Idtask = ?
        ";

        $result = $this->db->query($query, [$IdTask]);
        return $result;
    }
}