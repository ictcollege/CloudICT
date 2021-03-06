<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class UserModel extends CI_Model {

    /**
     * Get All Users In Groups
     *
     * Returns all users in groups to which given user with $iduser belongs
     * Returns all groups in which given user with $iduser is group admin
     *
     * Structure of returned data:
     *      array (
     *          [GroupName] => array (
     *              [UserName] => [UserGroupStatusAdmin],
     *              ...
     *          ),
     *          ...
     *          ['GroupAdmin'] => array (
     *              [0] => [GroupName],
     *              ...
     *          )
     *      )
     *
     * @param int $IdUser
     * @return array
     */
    public function getAllUsersInGroupsWithId($IdUser)
    {
        $query = "
			SELECT
				`u`.`IdUser` AS `Id`,
				`GroupName` AS `Group`,
				`UserName` AS `User`,
				`UserGroupStatusAdmin` AS `Admin`

			FROM 	`User`

			JOIN	`UserGroup` AS `u`
			USING	(`IdUser`)

			JOIN	`Group` AS `g`
			USING	(`IdGroup`)

			WHERE	`g`.`IdGroup` IN (
							SELECT	`IdGroup`
							FROM	`UserGroup`
							WHERE	`IdUser` = ?
						 )
		";

        $result = $this->db->query($query, $IdUser)->result_array();

        $data = array();

        if (!empty($result)) {
            //Data Restructuring
            foreach ($result as $row) {
                $data[$row['Group']] = array();
                foreach ($result as $row2) {
                    if ($row2['Group'] == $row['Group']) {
                        $group = $row['Group'];
                        $user = $row2['User'];
                        $data[$group][$user] = array(
                            "UserId" => $row2["Id"],
                            "Status" => $row2['Admin']
                        );
                    }
                }
            }
        return $data;
        }
    }

    public function getAllUsersInGroups($IdUser){
        $query = "SELECT * FROM `usergroup` JOIN `group` ON usergroup.IdGroup = group.IdGroup WHERE usergroup.IdUser = ?";

        $result = $this->db->query($query, array($IdUser));
        $query = "SELECT usergroup.IdUser,usergroup.IdGroup,usergroup.UserGroupStatusAdmin,user.UserName FROM usergroup JOIN user ON usergroup.IdUser = user.IdUser  WHERE usergroup.IdGroup = ?";
        foreach($result->result() as $obj){
            $obj->Users = $this->db->query($query, $obj->IdGroup)->result_array();
        }
        return $result->result();
    }



    /**
     * Get User
     *
     * Returns user to which given $username and $password belongs
     *
     * Structure of returned data:
     *      array (
     *          [User] => array (
     *                          [Id] => [UserId],
     *			   [User] => [UserName],
     *			   [Password] => [UserPassword],
     *			   [FullName] => [UserFullname],
     *			   [Email] => [UserEmail],
     *			   [DiskQuota] => [UserDiskQuota],
     *			   [DiskUsed] => [UserDiskUsed],
     *			   [Status] => [UserStatus],
     *          ),
     *          )
     *
     * @param string $username
     * @param string $password
     * @return array
     */
    public function getUser($username, $password)
    {
        $query = "
			SELECT	`IdUser` AS `Id`,
                                `IdRole`,
				`UserName` AS `User`,
				`UserPassword` AS `Password`,
				`UserFullname` AS `FullName`,
				`UserEmail` AS `Email`,
				`UserDiskQuota` AS `DiskQuota`,
				`UserDiskUsed` AS `DiskUsed`,
				`UserStatus` AS `Status`				

			FROM 	`User`

			WHERE	`UserName` = ?
			AND `UserPassword` = ?
		";

        $result = $this->db->query($query, array($username,$password))->result_array();

        $data['User'] = array();

        if(!empty($result))
            foreach ($result as $row) $data['User'] = $row;
        return $data;
    }

    /**
     * Check If User Exists
     *
     * Returns int
     *
     * Structure of returned data:
     *      0: user doesnt exist
     *	   1: user exists
     *
     * @param string $username
     * @return int
     */
    public function checkIfUserExists($username)
    {
        $query = "
			SELECT	`IdUser` 				

			FROM 	`User`

			WHERE	`UserName` = ?
		";

        $result = $this->db->query($query, array($username))->result_array();

        return !empty($result)?1:0;
    }

    /**
     * Check If Email Exists
     *
     * Returns int
     *
     * Structure of returned data:
     *      0: user doesnt exist
     *	   1: user exists
     *
     * @param string $email
     * @return int
     */
    public function checkIfEmailExists($email)
    {
        $query = "
			SELECT	`IdUser` 				

			FROM 	`User`

			WHERE	`UserEmail` = ?
		";

        $result = $this->db->query($query, array($email))->result_array();

        return !empty($result)?1:0;
    }

    /**
     * Check If Key Expired
     *
     * Returns int
     *
     * Structure of returned data:
     *      0: key expired
     *	   1: key is not expired
     *
     * @param string $UserKey
     * @return int
     */
    public function checkIfKeyExpired($UserKey)
    {
        $query = "
			SELECT	`UserKeyExpires` 				

			FROM 	`User`

			WHERE	`UserKey` = ?
		";

        $result = $this->db->query($query, array($UserKey))->result_array();
        $key = "";

        if(!empty($result))
            foreach ($result as $row) $key = $row["UserKeyExpires"];

        return ($key > time())?1:0;
    }

    /**
     * Insert User
     *
     * Returns int
     *
     * Structure of returned data:
     *      $IdUser
     *
     * @param string $email
     * @return int
     */
    public function insertUser($email)
    {
        $query = "
			INSERT INTO `User`(`UserEmail`,`UserKey`)
			
			VALUES (?,?);
		";

        $result = $this->db->query($query, array($email,md5(time()+$email)));
        $IdUser = $this->db->insert_id();
        $updateQuery = "
			UPDATE `User` SET `UserName` = ?,
				`UserPassword` = ?,
				`UserFullname` = ?,
				`UserDiskQuota` = ?,
				`UserKeyExpires` = ?
			
			WHERE IdUser = ?;
		";
        $updateData = "user$IdUser";
        //dodato
        // ne moze time(); mora jos minimum 7 dana
        $nextWeek = time() + (7 * 24 * 60 * 60);
        $this->db->query($updateQuery, array($updateData,$updateData,$updateData,5,$nextWeek,$IdUser));
        return $IdUser;
    }

    /**
     * Update Used Space By User
     *
     * Returns int
     *
     * Structure of returned data:
     *      0: UserDiskUsed was not updated
     *	   1: UserDiskUsed was updated
     *
     * @param int $IdUser
     * @param int $UserDiskUsed
     * @return int
     */
    public function updateUsedSpaceByUser($IdUser, $UserDiskUsed)
    {
        $updateQuery = "
			UPDATE `User` SET `UserDiskUsed` = ?
			
			WHERE IdUser = ?;
		";
        $result = $this->db->query($updateQuery, array($UserDiskUsed,$IdUser));
        return !empty($result)?1:0;
    }

    public function getAllUsers()
    {
        $query = "
                        SELECT * 
                    
                        FROM `User`
                    ";

        $result = $this->db->query($query)->result_array();

        return $result;
    }

    public function getUserKey($idUser)
    {
        $query = "
                    SELECT      `UserKey`
                    
                    FROM        `User`
                    
                    WHERE `IdUser` = ?
                    ";

        $result = $this->db->query($query, array($idUser))->result_array();

        $data = array();

        if(!empty($result))
        {
            $i=0;
            foreach($result as $row)
            {
                $data['Key'][$i++] = $row;
            }
        }

        return $data;
    }

    public function initialPasswordChange($newPassword)
    {
        $query = "
                    UPDATE      `User`      
                    
                    SET         `UserPassword` = '".$newPassword."'        
                    
                    WHERE `IdUser` = 1
                    ";

        $result = $this->db->query($query);
    }
    //editovano, pogledati prethodne verzije
    public function editUser($IdUser, $IdRole, $UserName, $UserPassword, $UserFullname, $UserEmail,$UserDiskQuota,$UserStatus,$UserKey, $UserKeyExpires )
    {
        $query = "
                    UPDATE      `User`      
                    
                    SET         `IdRole` = ?,
                                `UserName` = ?,
                                `UserPassword` = ?,
                                `UserFullname` = ?,
                                `UserEmail` = ?,
                                `UserDiskQuota` = ?,
                                `UserStatus` = ?,
                                `UserKey` = ?,
                                `UserKeyExpires` = ?
                                
                                
                    WHERE `IdUser` = ?
                    ";
        $result = $this->db->query($query, array($IdRole, $UserName, $UserPassword, $UserFullname, $UserEmail,$UserDiskQuota, $UserStatus, $UserKey,$UserKeyExpires,$IdUser));
        return !empty($result)?1:0;
    }

    public function deleteUser($iduser)
    {
        $query = "
                    DELETE 
                    
                    FROM `User`
                    
                    WHERE `IdUser` = ?
                    ";
        $result = $this->db->query($query, array($iduser));
    }

    public function getUserById($iduser)
    {
        $query = "
                    SELECT * 
                    
                    FROM `User`
                    
                    WHERE `IdUser` = ?
                    ";
        $result = $this->db->query($query, array($iduser))->result_array();

        return $result;
    }

    public function checkIfPasswordExists($password, $idUser)
    {

        $query = "
                    SELECT * 
                    
                    FROM `User`
                    
                    WHERE `UserPassword` = '".$password."' AND `IdUser` = '".$idUser."'
                    ";

        $result = $this->db->query($query)->result_array();

        if(!empty($result))
        {
            return true;
        }
        else
        {
            return false;
        }
    }

    public function changePassword($password, $idUser) {

        $query = "
                    UPDATE `User`
                    
                    SET `UserPassword` = '".$password."'
                    
                    WHERE `IdUser` = ?
                    ";

        $result = $this->db->query($query, array($idUser));

        return $result;
    }

    public function checkIfKeyExists($key)
    {
        $query = "
			SELECT	* 				

			FROM 	`User`

			WHERE	`UserKey` = '".$key."' 
                           
                        AND `UserKeyExpires` != 0
		";

        $result = $this->db->query($query)->result_array();

        if(!empty($result))
        {
            return true;
        }
        else
        {
            return false;
        }
    }

    public function registerUser($username, $password, $key)
    {

        $query = "
			UPDATE `User` 
                        
                        SET     `UserName` = ?,
				`UserPassword` = ?,
                                `UserKeyExpires` = 0
                        
                        WHERE `UserKey` = ?;
		";
        $result = $this->db->query($query, array($username,md5($password),$key));


    }

    public function getUserByKey($key)
    {
        $query = "
			SELECT	* 				

			FROM 	`User`

			WHERE	`UserKey` = '".$key."' 
		";

        $result = $this->db->query($query)->result_array();

        $data = array();

        if(!empty($result))
        {
            $i=0;
            foreach($result as $row)
            {
                $data['User'][$i++] = $row;
            }
        }

        return $data;
    }

    public function updateUser($username, $fullname, $email, $iduser)
    {
        $query = "
			UPDATE  `User` 
                        
                        SET     
				`UserName` = ?,
				`UserFullname` = ?,
				`UserEmail` = ?
			WHERE IdUser = ?;
		";
        $result = $this->db->query($query, array($username, $fullname, $email, $iduser));
    }
    //ovaj metod sam napravio jer me smara da pravim objekat iz niza
    public function getUserByIdObject($IdUser){
        $query = "SELECT * FROM user WHERE user.IdUser=? LIMIT 1";
        $result = $this->db->query($query,array($IdUser));
        return $result->row();
    }
}

/* End of file UserModel.php */
/* Location: ./ictcloud/models/UserModel.php */
