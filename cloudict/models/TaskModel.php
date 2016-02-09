<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class TaskModel extends CI_Model
{


    /**
     * TaskModel constructor.
     */
    public function __construct()
    {
        parent::__construct();

        $this->load->model("UserModel");
    }

    /**
     * Returns the task with Id $IdTask from the database
     * @param $IdTask
     * @return mixed
     */
    public function getTask($IdTask)
    {
        $query = "
            SELECT *
            FROM Task
            WHERE IdTask = ?
        ";

        $data = $this->db->query($query, array($IdTask))->row_array();

        $query2 = "
            SELECT IdTask, IdUser, TaskUserFullName, TaskUserAssigned, TaskUserTimeExecuted
            FROM TaskUser
            WHERE IdTask = ?
        ";

        $data2 = $this->db->query($query2, array($IdTask))->result_array();
        $data["users"] = $data2;


        return $data;
    }


    /**
     * Get Tasks assigned to you
     * @param $userId
     * @return mixed
     */
    public function getAssignedTasks($userId)
    {

        $query = "
            SELECT t.IdTask, TaskName, TaskDescription, TaskTimeCreated,
                    TaskTimeToExecute, TaskExecuteType, tu.IdUser AS AssignedUser, TaskUserTimeExecuted as Finished
            FROM Task AS  t JOIN TaskUser AS tu ON t.IdTask = tu.IdTask
            WHERE tu.idUser = ? AND TaskUserTimeExecuted IS NULL
        ";


        $result = $this->db->query($query, array($userId))->result_array();


        $data = $result;

        return $data;


    }

    /**
     * Returns all finished tasks that belong to user $userId
     * @param $userId
     * @return mixed
     */
    public function getFinishedTasks($userId)
    {
        $query = "
            SELECT t.IdTask, TaskName, TaskDescription, TaskTimeCreated,
                        TaskTimeToExecute, TaskExecuteType, tu.IdUser AS AssignedUser, TaskUserTimeExecuted as Finished
            FROM Task AS  t JOIN TaskUser AS tu ON t.IdTask = tu.IdTask
            WHERE tu.idUser = ? AND TaskUserTimeExecuted IS NOT NULL
        ";

        $result = $this->db->query($query, array($userId))->result_array();
        $data = $result;

        return $data;
    }

    public function finishTask($taskId, $userId)
    {
        $isGroupTask = $this->isGroupTask($taskId);

        if($isGroupTask == "1") {
            $query = "
                UPDATE TaskUser
                SET TaskUserTimeExecuted = NOW()
                WHERE IdTask = ?
            ";
            $result = $this->db->query($query, array($taskId));
        }
        else {
            $query = "
                UPDATE TaskUser
                SET TaskUserTimeExecuted = NOW()
                WHERE IdTask = ? AND IdUser = ?
            ";
            $result = $this->db->query($query, array($taskId, $userId));
        }


        return $result;
    }

    public function isGroupTask($taskId)
    {
        $query = "
            SELECT TaskExecuteType
            FROM Task
            WHERE IdTask = ?
        ";

        $result = $this->db->query($query, array($taskId))->row_array();
        $data = $result['TaskExecuteType'];
        return $data;
    }

    /**
     * Get tasks that you assigned
     * @param $currentUserID (int)
     * @return mixed
     */
    public function getCreatedTasks($currentUserID)
    {
        $query = "
            SELECT IdTask, TaskName, TaskDescription, TaskTimeCreated, TaskTimeToExecute, TaskExecuteType
            FROM Task
            WHERE idUser = ?
        ";


        $result = $this->db->query($query, array($currentUserID))->result_array();


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
    public function storeTask($userID, $taskName, $taskDescription, $timeToExecute, $executeType, $assignedUsers)
    {
        $sdate = strtotime(date("Y-m-d"));
        $edate = strtotime($timeToExecute);
        $data = array(
            'IdUser' => $userID,
            'TaskName' => $taskName,
            'TaskDescription' => $taskDescription,
            'TaskTimeCreated' => $sdate,
            'TaskTimeToExecute' => $edate,
            'TaskExecuteType' => $executeType
        );

        $this->db->insert('Task', $data);
        $insertId = $this->db->insert_id();

        if (isset($insertId)) {

            if (!empty($assignedUsers)) {
                $taskUserData = array();
                foreach ($assignedUsers as $user) {
                    array_push($taskUserData, array(
                        'IdTask' => $insertId,
                        'IdUser' => $user['id'],
                        'TaskUserFullname' => $user['FullName'],
                        'TaskUserAssigned' => $sdate,
                    ));
                }

                $this->db->insert_batch('TaskUser', $taskUserData);
            }
        }
    }


    public function RemoveTask($IdTask)
    {
        $query = "
            DELETE FROM Task
            WHERE Idtask = ?
        ";

        $this->db->query($query, array($IdTask));

        $query2 = "
            DELETE FROM TaskUser
            WHERE IdTask = ?
        ";

        $this->db->query($query2, array($IdTask));
    }
}
