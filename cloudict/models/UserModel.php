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
//	public function getAllUsersInGroups($IdUser)
//	{
//		$query = "
//			SELECT	`GroupName` AS `Group`,
//				`UserName` AS `User`,
//				`UserGroupStatusAdmin` AS `Admin`
//
//			FROM 	`User`
//
//			JOIN	`UserGroup`
//			USING	(`IdUser`)
//
//			JOIN	`Group` AS `g`
//			USING	(`IdGroup`)
//
//			WHERE	`g`.`IdGroup` IN (
//							SELECT	`IdGroup`
//							FROM	`UserGroup`
//							WHERE	`IdUser` = ?
//						 )
//		";
//
//		$result = $this->db->query($query, [$IdUser])->result_array();
//
//		$data = array();
//
//		if(!empty($result)){
//			foreach ($result as $row){
//                    $data[$row['Group']][$row['User']] = $row['Admin'];
//                        }
//                }
//
//		$query2 = "
//			SELECT	`GroupName` AS `Group`
//
//			FROM	`Group`
//
//			JOIN	`UserGroup`
//			USING	(`IdGroup`)
//
//			WHERE	`IdUser` = ?
//			AND	`UserGroupStatusAdmin` = 1
//		";
//
//		$result2 = $this->db->query($query2, [$IdUser])->result_array();
//
//		$data['GroupAdmin'] = array();
//
//		if(!empty($result2))
//			foreach ($result2 as $row) $data['GroupAdmin'][] = $row['Group'];
//
//		return $data;
//	}
	
        public function getAllUsersInGroups($IdUser){
            $query = "SELECT * FROM `usergroup` JOIN `group` ON usergroup.IdGroup = group.IdGroup WHERE usergroup.IdUser = ?";

		$result = $this->db->query($query, [$IdUser]);
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
		
		$result = $this->db->query($query, [$username,$password])->result_array();
		
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
		
		$result = $this->db->query($query, [$username])->result_array();
		
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
		
		$result = $this->db->query($query, [$email])->result_array();
		
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
		
		$result = $this->db->query($query, [$UserKey])->result_array();
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
		
		$result = $this->db->query($query, [$email,md5(time()+$email)]);
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
		$this->db->query($updateQuery, [$updateData,$updateData,$updateData,5,time(),$IdUser]);
		return $IdUser;
	}
	
	/**
	* Update User
	* 
	* Returns int
	* 
	* Structure of returned data:
	*      0: user was not updated
	*	   1: user was updated
	* 
	* @param int $IdUser
	* @param array($IdRole, $Username, $UserPassword, $UserFullname, $UserEmail, $UserStatus)
	* @return int
	*/
	public function updateUser($IdUser, $UserArray)
	{
		$IdRole = $UserArray[0];
		$Username = $UserArray[1];
		$UserPassword = $UserArray[2];
		$UserFullname = $UserArray[3];
		$UserEmail = $UserArray[4];
		$UserStatus = $UserArray[5];
		$updateQuery = "
			UPDATE `User` SET `IdRole` = ?,
				`UserName` = ?,
				`UserPassword` = ?,
				`UserFullname` = ?,
				`UserEmail` = ?,
				`UserStatus` = ?
			WHERE IdUser = ?;
		";
		$result = $this->db->query($updateQuery, [$IdRole,$Username,$UserPassword,$UserFullname,$UserEmail,$UserStatus,$IdUser]);
		return !empty($result)?1:0; 
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
		$result = $this->db->query($updateQuery, [$UserDiskUsed,$IdUser]);
		return !empty($result)?1:0; 
	}
        
        public function getAllUsers()
        {
            $query = "
                    SELECT * 
                    FROM `User`
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

/* End of file UserModel.php */
/* Location: ./ictcloud/models/UserModel.php */
