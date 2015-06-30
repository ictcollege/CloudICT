<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class TaskModel extends CI_Model {

    /**
     * @param $currentUserID
     * @return mixed
     */
    public function getAssignedTaks($currentUserID){
        return $this->db->from('TaskUser')->where("IdUser", $currentUserID);
    }

    public function getGivenTasks($currentUserID){
        return $this->db->from('Task')->where("IdUser", $currentUserID);
    }

    public function storeTask($userID, $taskName, $taskDescription, $timeToExecute, $executeType, $assignedUserIDs){
        $data = array(
            'IdUser' => $userID,
            'TaskName' => $taskName,
            'TaskDescription' => $taskDescription,
            'TimeCreated' => date("Y-m-d H;i:s"),
            'TimeToExecute' => $timeToExecute,
            'ExecuteType' => $executeType
        );
        $insertID = $this->db->insert('Task', $data)->insert_id();
        if($insertID != null){
            $taskUserData = array();
            foreach($assignedUserIDs as $Id){
                array_push($taskUserData, array(
                    'IdTask' => $insertID,
                    'IdUser' => $Id
                ));
            }
            if($this->db->insert_batch('TaskUser', $taskUserData) != null)
                return true;
        }
        return false;
    }
}