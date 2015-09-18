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
    
   /**
    * files shared with user 
    * @param type $IdUser
    * @return  array
    */
   public function sharedWithUser($IdUser){
       $query = "SELECT 
            share.IdUser,
            share.IdFile,
            share.ShareCreated,
            share.ShareFullName,
            share.SharePrivilege,
            user.UserName as Owner,
            file.IdUser as OwnerId,
            file.FilePath,
            file.IdFolder,
            file.FileExtension,
            file.FileSize,
            file.FileLastModified, 
			file.IdFileType,
			filetype.FileTypeMime 
            FROM `share` 
            INNER JOIN file ON share.IdFile = file.IdFile 
            JOIN user ON file.IdUser = user.IdUser 
			JOIN filetype ON file.IdFileType = filetype.IdFileType 
            WHERE share.IdUser=?";
       $result = $this->db->query($query,[$IdUser])->result_array();
       return $result;
   }
   /**
    * Files shared by user
    * @param type $IdUser
    * @return array
    */
   public function sharedByUser($IdUser){
       $query = "SELECT 
            file.IdUser,
            share.IdFile, 
            share.IdUser as SharedUser, 
            user.UserName as SharedUsername, 
            share.ShareCreated,
            share.ShareFullName,
            share.SharePrivilege,
            file.FilePath,
            file.IdFolder,
            file.FileExtension,
            file.FileSize,
            file.FileLastModified, 
            filetype.FileTypeMime 
            FROM file 
            JOIN share ON file.IdFile = share.IdFile 
            JOIN user ON share.IdUser = user.IdUser 
            JOIN filetype ON file.IdFileType = filetype.IdFileType 
            WHERE file.IdFile IN 
            (SELECT IdFile FROM share WHERE share.IdFile=file.IdFile) AND file.IdUser = ?";
       $result = $this->db->query($query,[$IdUser])->result_array();
       return $result;
   }
   /**
    * This method is much faster than if we use some in_array() function to check if user can download
    * file or not.
    * @param type $IdUser
    * @param type $IdFile
    * @return boolean
    */
   public function canDownload($IdUser,$IdFile) {
       $query = "SELECT IdUser,IdFile FROM share WHERE IdUser= ? AND IdFile = ? LIMIT 1";
       $result = $this->db->query($query,[$IdUser,$IdFile]);
       if(!empty($result->row())){
           return TRUE;
       }
       return FALSE;
   }
    
}
