<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class UserGroupModel extends CI_Model {
    public function insertGroup($groupName)
    {
        $query = "
                INSERT INTO `Group`(`GroupName`)
                
                VALUES ?
            ";
            
        $this->db->query($query, [$groupName]);
    }
    
    public function deleteGroup($idGroup)
    {
        $query = "
                DELETE FROM `Group` 
                
                WHERE `IdGroup` = ?
            ";
        
        $this->db->query($query, [$idGroup]);
        
        $query2 = "
                DELETE FROM `UserGroup`
                
                 WHERE `IdGroup` = ?
            ";
        
        $this->db->query($query2, [$idGroup]);
    }
    public function getGroups()
    {
        $query = " 
                SELECT  `GroupName`,
                        `IdGroup`

                FROM    `Group`
            ";
        
            $result = $this->db->query($query)->result_array();
            
            $data = array();
            
            if(!empty($result))
            {
                $i=0;
                foreach($result as $row)
                {
                    $data['Group'][$i++] = $row;
                }
            }
            
            return $data;
    }
    public function getGroupAndUsersInIt()
    {
            $query = " 
                    SELECT	`GroupName`,
                                `IdGroup`,
				`UserName`,
                                `IdUser`,
				`UserGroupStatusAdmin` AS `Admin`

			FROM 	`User`

			JOIN	`UserGroup`
			USING	(`IdUser`)

			JOIN	`Group` AS `g`
			USING	(`IdGroup`)
            
                        ORDER BY `IdGroup`
            ";
            
            $result = $this->db->query($query)->result_array();
            
            $data = array();
            
            if(!empty($result))
            {
                $i=0;
                foreach($result as $row)
                {
                    $data['UserGroup'][$i++] = $row;
                }
            }
            
            return $data;
    }
    
    public function removeAdmin($idUser, $idGroup) {
        $query = "
                UPDATE      `UserGroup`
                SET         `UserGroupStatusAdmin` = 0
                WHERE       `IdGroup` = ? 
                AND         `IdUser` = ?
                ";
        
        $this->db->query($query, [$idGroup, $idUser]);
    }
}



