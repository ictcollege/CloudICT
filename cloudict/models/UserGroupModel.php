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

			JOIN	`Group`
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
    
    public function getUsersThatAreNotInTheGroup($idGroup)
    {
        $query = " 
                    SELECT	`UserName`,
                                `IdUser`
			
                        FROM 	`User`

			WHERE `IdUser` NOT IN   (
                                                        SELECT  `IdUser`
                                                        
                                                        FROM    `UserGroup`
                                                        
                                                        WHERE `IdGroup` = ?
                                                )
            ";
            
            $result = $this->db->query($query, [$idGroup])->result_array();
            
            $data = array();
            
            if(!empty($result))
            {
                $i=0;
                foreach($result as $row)
                {
                    $data['UsersNotInGroup'][$i++] = $row;
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
    
    public function addAdmin($idUser, $idGroup) {
        $query = "
                UPDATE      `UserGroup`
                SET         `UserGroupStatusAdmin` = 1
                WHERE       `IdGroup` = ? 
                AND         `IdUser` = ?
                ";
        
        $this->db->query($query, [$idGroup, $idUser]);
    }
    
    public function removeUserFromGroup($idUser, $idGroup) {
        $query = "
                DELETE      
                FROM        `UserGroup` 
                WHERE       `IdGroup` = ? 
                AND         `IdUser` = ?
                ";
        
        $this->db->query($query, [$idGroup, $idUser]);
    }
    
    public function addNewUserToGroup($idUser, $idGroup)
    {
        $query = "
                INSERT INTO `UserGroup`(`IdUser`, `IdGroup`, `UserGroupStatusAdmin`)
                
                VALUES (?, ?, 0);
                ";
        
        $this->db->query($query, [$idUser, $idGroup]);
    }
    
    public function searchNewUser($username, $idGroup)
    {
        $query = 'SELECT	`UserName`,
                                `IdUser`
			
                        FROM 	`User`

			WHERE `IdUser` NOT IN   (
                                                        SELECT  `IdUser`
                                                        
                                                        FROM    `UserGroup`
                                                        
                                                        WHERE `IdGroup` = ?
                                                ) 
                        AND `UserName` LIKE "%?%"
                ';
        
        $result = $this->db->query($query, [$idGroup, $username])->result_array();
            
        $data = array();

        if(!empty($result))
        {
            $i=0;
            foreach($result as $row)
            {
                $data['SearchUsers'][$i++] = $row;
            }
        }

        return $data;
    }   
    
     public function getUsersThatAreInTheGroup($idGroup)
    {
        $query = " 
                    SELECT	`UserName`,
                                `IdUser`
			
                        FROM 	`User`

			WHERE `IdUser` NOT IN   (
                                                        SELECT  `IdUser`
                                                        
                                                        FROM    `UserGroup`
                                                        
                                                        WHERE `IdGroup` = ?
                                                )
            ";
            
            $result = $this->db->query($query, [$idGroup])->result_array();
            
            $data = array();
            
            if(!empty($result))
            {
                $i=0;
                foreach($result as $row)
                {
                    $data['UsersInGroup'][$i++] = $row;
                }
            }
            
            return $data;
    }
}



