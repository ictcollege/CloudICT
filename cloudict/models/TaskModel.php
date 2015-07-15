<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class TaskModel extends CI_Model {

    /**
     * Get Tasks assigned to you
     * @param $currentUserID
     * @return mixed
     */
    public function getAssignedTaks($currentUserID){

        $query = "
            SELECT t.idTask, TaskName, TaskTimeCreated, TaskTimeToExecute, TaskExecuteType
            FROM Task AS  t JOIN TaskUser AS tu ON t.idTask = tu.idTask
            WHERE tu.idUser = ?
        ";


        $result =  $this->db->query($query, [$currentUserID])->result_array();


        $data = $result;

        return $data;


    }

    /**
     * Get tasks that you assigned
     * @param $currentUserID (int)
     * @return mixed
     */
    public function getGivenTasks($currentUserID){
        $query = "
            SELECT idTask, TaskName, TaskTimeCreated, TaskTimeToExecute, TaskExecuteType
            FROM Task
            WHERE idUser = ?
        ";


        $result =  $this->db->query($query, [$currentUserID])->result_array();


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
    public function storeTask($userID, $taskName, $taskDescription, $timeToExecute, $executeType, $assignedUser){
        $data = array(
            'IdUser' => $userID,
            'TaskName' => $taskName,
            'TaskDescription' => $taskDescription,
            'TimeCreated' => date("Y-m-d H:i:s"),
            'TimeToExecute' => $timeToExecute,
            'ExecuteType' => $executeType
        );

        $insertID = $this->db->insert('Task', $data)->insert_id();

        if($insertID != null){

            if(!empty($assignedUser)){
                $taskUserData = array();
                foreach($assignedUser as $user){
                    array_push($taskUserData, array(
                        'IdTask' => $insertID,
                        'IdUser' => $user['idUser'],
                        'TaskUserFullname' => $user['username'],
                        'TaskUserAssigned' => date("Y-m-d H:i:s"),
                    ));
                }
                return $this->db->insert_batch('TaskUser', $taskUserData);
            }

            return true;
        }

        return false;
    }


    public function RemoveTask($taskId){

    }
}