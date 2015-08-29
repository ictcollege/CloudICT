<?php

/*
 *  @author Darko Lesendric <dlesendric at https://github.com/ @Darko_Lesendric at https://twitter.com/>
 */

/**
 * Description of ShareModel
 *
 * @author Darko
 */
class ShareModel extends CI_Model{
    const READ = 1;
    const WRITE = 2;
    const EXECUTE = 3;
    
    public function __construct() {
        parent::__construct();
    }
    
    public function getAllSharedFiles($IdUser){
        $query = "SELECT * FROM `share` WHERE `IdUser` = ?";
        $result = $this->db->query($query,[$IdUser]);
        return $result->result_array();
    }
    
    public function newShare($IdFile,$IdUser,$ShareFullName,$FilePath,$SharePrivilege){
        $checkQuery = "SELECT `IdUser`,`IdFile` FROM share WHERE `IdUser` = ? AND  `IdFile` = ? LIMIT 1";
        $checkResult = $this->db->query($checkQuery,[$IdUser,$IdFile])->result();
        if(empty($checkResult)){
            $query = "INSERT INTO `share` (
                                    `IdFile`,
                                    `IdUser`,
                                    `ShareCreated`,
                                    `ShareFullName`,
                                    `FilePath`,
                                    `SharePrivilege`
                                    ) VALUES (?,?,?,?,?,?)
                                    ";
            $result = $this->db->query($query,[$IdFile,$IdUser,time(),$ShareFullName,$FilePath,$SharePrivilege]);  
        }
    }
    
    public function removeShare($IdFile,$IdUser){
        $query = "DELETE FROM `share` WHERE `IdFile` = ? AND `IdUser` = ?";
        $result = $this->db->query($query,[$IdFile,$IdUser]);

    }
    
   public function shareWithGroup($IdGroup,$IdFile,$Share,$SharePrivilege=self::READ){
       $this->load->model("UserGroupModel");
       $UsersInGroup = $this->UserGroupModel->getUsersThatAreInTheGroup($IdGroup);
       //if true than share with group
       if($Share){
            $this->load->model("FileModel");
            $File = $this->FileModel->getFileById($IdFile);
           foreach($UsersInGroup as $User){
               $this->newShare($File->IdFile, $User["IdUser"], $File->FileName, $File->FilePath, $SharePrivilege);
           }
       }
       else{ //than unshare with group
           foreach($UsersInGroup as $User){
               $this->removeShare($IdFile, $User["IdUser"]);
           }
       }
       
   }
   public function shareWithUser($IdUser,$IdFile,$Share,$SharePrivilege=self::READ){
       //if true than share file
       if($Share){
            $this->load->model("FileModel");
            $File = $this->FileModel->getFileById($IdFile);
            $this->newShare($File->IdFile, $IdUser, $File->FileName, $File->FilePath, $SharePrivilege);
           
       }
       else{ //than unshare file
           $this->removeShare($IdFile, $IdUser);
           
       }
       
   }
   
   public function getAllSharedUsersForFile($IdFile){
       $query = "SELECT IdUser FROM share WHERE IdFile = ?";
       return array_values($this->db->query($query,[$IdFile])->result_array());
   }
    
    
    
    
    
}
