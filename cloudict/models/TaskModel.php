<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class TaskModel extends CI_Model {

    public function getAssignedTaks($id){
        
    }

    public function getgivenTasks($id){
        
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
            foreach($assignedUserIDs as $Id){
                $taskUser = array(

                );
                $this->db->insert('TaskUser', $taskUser);
            }
        }

    }
}