<<<<<<< HEAD
<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class UserGroupModel extends CI_Model {
    public function createNewGroup($groupName)
    {
        $query = "
                INSERT INTO `Group`(`GroupName`)
                
                VALUES (?)
            ";
            
        $this->db->query($query, [$groupName]);
        $IdGroup = $this->db->insert_id();
        
        return $IdGroup;
    }
    
    public function deleteGroup($idGroup)
    {
        $query = "
                DELETE 
                
                FROM `Group` 
                
                WHERE `IdGroup` = ?
            ";
        
        $this->db->query($query, [$idGroup]);
        
        $query2 = "
                DELETE 
                
                FROM `UserGroup`
                
                WHERE `IdGroup` = ?
            ";
        
        $this->db->query($query2, [$idGroup]);
        
        $query3 = "
                DELETE 
                
                FROM `GroupApp`
                
                WHERE `IdGroup` = ?
                ";
        
        $this->db->query($query3, [$idGroup]);
    }
    
    public function changeGroupName($idGroup, $newName)
    {
        $query = "
                UPDATE      `Group`
                SET         `GroupName` = ?
                WHERE       `IdGroup` = ?
                ";
        
        $this->db->query($query, [$newName, $idGroup]);
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
                                                        
                                                        WHERE `IdGroup` = '.$idGroup.'
                                                ) 
                        AND `UserName` LIKE "%'.$username.'%"
                ';
        
        $result = $this->db->query($query)->result_array();
            
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

			WHERE `IdUser` IN   (
                                                        SELECT  `IdUser`
                                                        
                                                        FROM    `UserGroup`
                                                        
                                                        WHERE `IdGroup` = ?
                                                )
            ";
            
        $result = $this->db->query($query, [$idGroup])->result_array();

        return $result;
    }
    
    public function getUsers()
    {
        $query = " 
                    SELECT	`UserName`,
                                `IdUser`
			
                        FROM 	`User`
                ";
            
        $result = $this->db->query($query)->result_array();

        $data = array();

        if(!empty($result))
        {
            $i=0;
            foreach($result as $row)
            {
                $data['Users'][$i++] = $row;
            }
        }

        return $data;
    }
}



=======
<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class UserGroupModel extends CI_Model {
    public function createNewGroup($groupName)
    {
        $query = "
                INSERT INTO `Group`(`GroupName`)
                
                VALUES (?)
            ";
            
        $this->db->query($query, array($groupName));
        $IdGroup = $this->db->insert_id();
        
        return $IdGroup;
    }
    
    public function deleteGroup($idGroup)
    {
        $query = "
                DELETE 
                
                FROM `Group` 
                
                WHERE `IdGroup` = ?
            ";
        
        $this->db->query($query, array($idGroup));
        
        $query2 = "
                DELETE 
                
                FROM `UserGroup`
                
                WHERE `IdGroup` = ?
            ";
        
        $this->db->query($query2, array($idGroup));
        
        $query3 = "
                DELETE 
                
                FROM `GroupApp`
                
                WHERE `IdGroup` = ?
                ";
        
        $this->db->query($query3, array($idGroup));
    }
    
    public function changeGroupName($idGroup, $newName)
    {
        $query = "
                UPDATE      `Group`
                SET         `GroupName` = ?
                WHERE       `IdGroup` = ?
                ";
        
        $this->db->query($query, array($newName, $idGroup));
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
            
            $result = $this->db->query($query, array($idGroup))->result_array();
            
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
        
        $this->db->query($query, array($idGroup, $idUser));
    }
    
    public function addAdmin($idUser, $idGroup) {
        $query = "
                UPDATE      `UserGroup`
                SET         `UserGroupStatusAdmin` = 1
                WHERE       `IdGroup` = ? 
                AND         `IdUser` = ?
                ";
        
        $this->db->query($query, array($idGroup, $idUser));
    }
    
    public function removeUserFromGroup($idUser, $idGroup) {
        $query = "
                DELETE      
                FROM        `UserGroup` 
                WHERE       `IdGroup` = ? 
                AND         `IdUser` = ?
                ";
        
        $this->db->query($query, array($idGroup, $idUser));
    }
    
    public function addNewUserToGroup($idUser, $idGroup)
    {
        $query = "
                INSERT INTO `UserGroup`(`IdUser`, `IdGroup`, `UserGroupStatusAdmin`)
                
                VALUES (?, ?, 0);
                ";
        
        $this->db->query($query, array($idUser, $idGroup));
    }
    
    public function searchNewUser($username, $idGroup)
    {
        $query = 'SELECT	`UserName`,
                                `IdUser`
			
                        FROM 	`User`

			WHERE `IdUser` NOT IN   (
                                                        SELECT  `IdUser`
                                                        
                                                        FROM    `UserGroup`
                                                        
                                                        WHERE `IdGroup` = '.$idGroup.'
                                                ) 
                        AND `UserName` LIKE "%'.$username.'%"
                ';
        
        $result = $this->db->query($query)->result_array();
            
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

			WHERE `IdUser` IN   (
                                                        SELECT  `IdUser`
                                                        
                                                        FROM    `UserGroup`
                                                        
                                                        WHERE `IdGroup` = ?
                                                )
            ";
            
        $result = $this->db->query($query, array($idGroup))->result_array();

        return $result;
    }
    
    public function getUsers()
    {
        $query = " 
                    SELECT	`UserName`,
                                `IdUser`
			
                        FROM 	`User`
                ";
            
        $result = $this->db->query($query)->result_array();

        $data = array();

        if(!empty($result))
        {
            $i=0;
            foreach($result as $row)
            {
                $data['Users'][$i++] = $row;
            }
        }

        return $data;
    }
}



>>>>>>> master
