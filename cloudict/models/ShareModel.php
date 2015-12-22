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
    
//    public function getAllSharedFiles($IdUser){
//        $query = "SELECT * FROM `share` WHERE `IdUser` = ?";
//        $result = $this->db->query($query,[$IdUser]);
//        return $result->result_array();
//    }
//    
//    public function shareFile($IdFile,$IdUser,$ShareFullName,$FilePath,$SharePrivilege){
//            $query = "INSERT INTO `share` (
//                                    `IdFile`,
//                                    `IdUser`,
//                                    `ShareCreated`,
//                                    `ShareFullName`,
//                                    `Path`,
//                                    `SharePrivilege`
//                                    ) VALUES (?,?,?,?,?,?)
//                                    ";
//            $result = $this->db->query($query,array($IdFile,$IdUser,time(),$ShareFullName,$FilePath,$SharePrivilege));
//            var_dump($result);
//        
//    }
//    
//    public function removeShare($IdUser,$IdFile=null,$IdFolder=null){
//        if(!is_null($IdFile)){
//           $query = "DELETE FROM `share` WHERE `IdFile` = ? AND `IdUser` = ?";
//           $this->db->query($query,array($IdFile,$IdUser)); 
//        }
//        if(!is_null($IdFolder)){
//            $query = "DELETE FROM `share` WHERE `IdFolder` = ? AND `IdUser` = ?";
//            $this->db->query($query,array($IdFolder,$IdUser));
//        }
//        else{
//            $query = "DELETE FROM `share` WHERE `IdUser` = ?";
//            $this->db->query($query,array($IdUser));
//        }
//        
//
//    }
//    
//   public function shareWithGroup($IdGroup,$IdFile,$Share,$SharePrivilege=self::READ){
//       $this->load->model("UserGroupModel");
//       $UsersInGroup = $this->UserGroupModel->getUsersThatAreInTheGroup($IdGroup);
//       //if true than share with group
//       if($Share){
//            $this->load->model("FileModel");
//            $File = $this->FileModel->getFileById($IdFile);
//           foreach($UsersInGroup as $User){
//               $this->shareFile($File->IdFile, $User["IdUser"], $File->FileName, $File->FilePath, $SharePrivilege);
//           }
//       }
//       else{ //than unshare with group
//           foreach($UsersInGroup as $User){
//               $this->removeShare($IdFile, $User["IdUser"]);
//           }
//       }
//       
//   }
//   public function shareFileWithUser($IdUser,$IdFile,$Share,$SharePrivilege=self::READ){
//       //if true than share file
//       if($Share){
//        $checkQuery = "SELECT `IdUser`,`IdFile` FROM share WHERE `IdUser` = ? AND  `IdFile` = ? LIMIT 1";
//        $checkResult = $this->db->query($checkQuery,array($IdUser,$IdFile))->result();
//        if(!$checkResult){
//            $this->load->model("FileModel");
//            $File = $this->FileModel->getFileById($IdFile);
//            $this->shareFile($File->IdFile, $IdUser, $File->FileName, $File->FilePath, $SharePrivilege);
//        }
//       }
//       else{ //than unshare file
//           $this->removeShare($IdUser,$IdFile,null);
//       }
//       
//   }
//   
//   public function shareFolderWithUser($IdUser,$IdFolder,$Share,$SharePrivilege = self::READ){
//       if($Share){
//            $this->load->model("FolderModel");
//            $Folder = $this->FolderModel->getFolderById($IdFolder);
//            $this->shareFolder($Folder->IdFolder, $IdUser, $Folder->FolderName, $Folder->FolderPath, $SharePrivilege);
//           
//       }
//       else{
//            $this->removeShare($IdUser, null, $IdFolder);
//        }
//   }
//   
//   public function shareFolder($IdFolder,$IdUser,$FolderName,$Path,$SharePrivilege){
//        $checkQuery = "SELECT `IdUser`,`IdFolder` FROM share WHERE `IdUser` = ? AND  `IdFolder` = ? LIMIT 1";
//        $checkResult = $this->db->query($checkQuery,array($IdUser,$IdFolder))->result();
//        if(empty($checkResult)){
//            $query = "INSERT INTO `share` (
//                                    `IdFolder`,
//                                    `IdUser`,
//                                    `ShareCreated`,
//                                    `ShareFullName`,
//                                    `Path`,
//                                    `SharePrivilege`
//                                    ) VALUES (?,?,?,?,?,?)
//                                    ";
//            $result = $this->db->query($query,array($IdFolder,$IdUser,time(),$FolderName,$Path,$SharePrivilege));  
//        }
//        
//   }
//   
//   public function getAllSharedUsersForFile($IdFile){
//       $query = "SELECT IdUser FROM share WHERE IdFile = ?";
//       return array_values($this->db->query($query,[$IdFile])->result_array());
//   }
//   
//   public function getAllSharedUsersForFolder($IdFolder){
//       $query = "SELECT IdUser FROM share WHERE IdFolder = ?";
//       return array_values($this->db->query($query,array($IdFolder))->result_array());
//   }
//   
//   
//   
//   /**
//    * files shared with user 
//    * @param type $IdUser
//    * @return  array
//    */
//   public function sharedFilesWithUser($IdUser,$IdFolder=0){
//       $query = "SELECT 
//            share.IdUser,
//            share.IdFile,
//            share.IdFolder,
//            share.ShareCreated,
//            share.ShareFullName,
//            share.SharePrivilege,
//            user.UserName as Owner,
//            file.IdUser as OwnerId,
//            file.FilePath,
//            file.IdFolder,
//            file.FileExtension,
//            file.FileSize,
//            file.FileLastModified, 
//			file.IdFileType,
//			filetype.FileTypeMime 
//            FROM `share` 
//            INNER JOIN file ON share.IdFile = file.IdFile 
//            JOIN user ON file.IdUser = user.IdUser 
//			JOIN filetype ON file.IdFileType = filetype.IdFileType 
//            WHERE share.IdUser=? AND share.IdFolder = ?";
//       $result = $this->db->query($query,array($IdUser,$IdFolder))->result_array();
//       return $result;
//   }
//   
//   public function sharedFoldersWithUser($IdUser,$IdFolder=0){
//       
//   }
//   /**
//    * Files shared by user
//    * @param type $IdUser
//    * @return array
//    */
//   public function sharedByUser($IdUser){
//       $query = "SELECT 
//            file.IdUser,
//            share.IdFile, 
//            share.IdUser as SharedUser, 
//            user.UserName as SharedUsername, 
//            share.ShareCreated,
//            share.ShareFullName,
//            share.SharePrivilege,
//            file.FilePath,
//            file.IdFolder,
//            file.FileExtension,
//            file.FileSize,
//            file.FileLastModified, 
//            filetype.FileTypeMime 
//            FROM file 
//            JOIN share ON file.IdFile = share.IdFile 
//            JOIN user ON share.IdUser = user.IdUser 
//            JOIN filetype ON file.IdFileType = filetype.IdFileType 
//            WHERE file.IdFile IN 
//            (SELECT IdFile FROM share WHERE share.IdFile=file.IdFile) AND file.IdUser = ?";
//       $result = $this->db->query($query,[$IdUser])->result_array();
//       return $result;
//   }
   /**
    * This method is much faster than if we use some in_array() function to check if user can download
    * file or not.
    * @param type $IdUser
    * @param type $IdFile
    * @return boolean
    */
   public function canDownload($IdShared,$IdFile=NULL,$IdFolder=NULL) {
       if(!is_null($IdFile)){
           $query = "SELECT IdShared,IdFile FROM shares WHERE IdShared= ? AND IdFile = ? LIMIT 1";
           $result = $this->db->query($query,array($IdShared,$IdFile));
       }
       else{
           $query = "SELECT IdShared,IdFolder FROM shares WHERE IdShared = ? AND IdFolder = ? AND IdFile=0 LIMIT 1";
           $result = $this->db->query($query,array($IdShared,$IdFolder));
       }
       
       if(!empty($result->row())){
           return TRUE;
       }
       return FALSE;
   }
   public function canExecute($IdShared,$IdFile=NULL,$IdFolder=NULL){
       if(!is_null($IdFile)){
           $query = "SELECT IdShared,IdFile FROM shares WHERE IdShared = ? AND IdFile = ? AND SharePrivilege = 3 LIMIT 1";
           $result = $this->db->query($query,array($IdShared,$IdFile));
       }
       else{
           $query = "SELECT IdShared,IdFolder FROM shares WHERE IdUser = ? AND IdFolder = ? AND SharePrivilege = 3 AND IdFile=0 LIMIT 1";
           $result = $this->db->query($query,array($IdShared,$IdFolder));
       }
       if(!empty($result->row())){
           return TRUE;
       }
       return FALSE;
       
   }
   public function canEdit($IdShared,$IdFile=NULL,$IdFolder=NULL){
       if(!is_null($IdFile)){
           $query = "SELECT IdShared,IdFile FROM shares WHERE IdShared = ? AND IdFile = ? AND SharePrivilege = 2 LIMIT 1";
           $result = $this->db->query($query,array($IdShared,$IdFile));
       }
       else{
           $query = "SELECT IdShared,IdFolder FROM shares WHERE IdUser = ? AND IdFolder = ? AND SharePrivilege = 2 AND IdFile=0 LIMIT 1";
           $result = $this->db->query($query,array($IdShared,$IdFolder));
       }
       if(!empty($result->row())){
           return TRUE;
       }
       return FALSE;
   }
   
    public function shareFile($IdOwner,$IdShared,$IdFile,$Name,$Path,$SharePrivilege=  self::READ,$IdFolder=0){
        //we need to check if file is already shared
        $checkQuery = "SELECT `IdOwner`,`IdShared` FROM `shares` WHERE `IdFile` = ?";
        $checkResult = $this->db->query($checkQuery,array($IdFile))->result_array();
        //if not than share
        if(empty($checkResult)){
            $ShareCreated = time();
            $query = "INSERT INTO `shares` (
                                    `IdOwner`,
                                    `IdShared`,
                                    `IdFile`,
                                    `IdFolder`,
                                    `ShareCreated`,
                                    `Name`,
                                    `FullPath`,
                                    `SharePrivilege`
                                    ) VALUES (?,?,?,?,?,?,?,?)
                                    ";
            $result=$this->db->query($query,array($IdOwner,$IdShared,$IdFile,$IdFolder,$ShareCreated,$Name,$Path,$SharePrivilege));
        }
        
    }
    
    public function shareFolder($IdOwner,$IdShared,$IdFolder,$Name,$Path,$SharePrivilege=  self::READ){
        //we need to check if folder is already shared
        $checkQuery = "SELECT `IdOwner`,`IdShared` FROM `shares` WHERE `IdFolder` = ? AND `IdFile` = ?";
        $checkResult = $this->db->query($checkQuery,array($IdFolder,0))->result_array();
        //if not than share
        if(empty($checkResult)){
            $ShareCreated = time();
            $query = "INSERT INTO `shares` (
                                    `IdOwner`,
                                    `IdShared`,
                                    `IdFile`,
                                    `IdFolder`,
                                    `ShareCreated`,
                                    `Name`,
                                    `FullPath`,
                                    `SharePrivilege`
                                    ) VALUES (?,?,?,?,?,?,?,?)
                                    ";
            //triger do all share on files in folders and folders
            $result = $this->db->query($query,array($IdOwner,$IdShared,0,$IdFolder,$ShareCreated,$Name,$Path,$SharePrivilege));
        }
        
    }
    
    public function unshareFolder($IdOwner,$IdShared,$IdFolder){
        $query = "DELETE FROM `shares` WHERE `IdOwner` = ? AND `IdShared` = ? AND `IdFolder`= ?";
        $this->db->query($query,array($IdOwner,$IdShared,$IdFolder));
    }
    
    public function unshareFile($IdOwner,$IdShared,$IdFile,$IdFolder=0){
        $query = "DELETE FROM `shares` WHERE `IdOwner` = ? AND `IdShared` = ? AND `IdFile` = ? AND `IdFolder` = ?";
        $result = $this->db->query($query,array($IdOwner,$IdShared,$IdFile,$IdFolder));
    }
    
    public function getAllSharedFiles($IdShared,$IdFolder=0){
        $query = "SELECT 
                shares.IdOwner,
                shares.IdShared,
                shares.IdFile,
                shares.IdFolder,
                shares.Name,
                shares.ShareCreated,
                shares.SharePrivilege,
                shares.FullPath,
                user.UserName,
                file.FileSize,
                file.FileLastModified,
                file.FileExtension
        FROM shares
        JOIN user ON shares.IdOwner = user.IdUser
        JOIN file ON shares.IdFile = file.IdFile
        WHERE shares.IdShared = ? AND shares.IdFolder = ?";
        $result = $this->db->query($query,array($IdShared,$IdFolder));
        return $result->result_array();
    }
    
    public function getAllSharedFolders($IdShared,$IdFolder=0){
        $query = "SELECT 
	shares.IdOwner,
	shares.IdShared,
	shares.IdFolder,
	shares.Name,
	shares.ShareCreated,
	shares.SharePrivilege,
	shares.FullPath,
	user.UserName,
	folders.FolderMask,
	folders.IdParent
FROM shares
JOIN user ON shares.IdOwner = user.IdUser
JOIN folders ON shares.IdFolder = folders.IdFolder
";
        if($IdFolder==0){
            $query.=" WHERE shares.IdFile = 0 AND shares.IdShared=?";
            $result = $this->db->query($query,array($IdShared));
        }
        else{
            $query.= " WHERE folders.IdParent = ? AND shares.IdShared = ?";
            $result = $this->db->query($query,array($IdFolder,$IdShared));
        }
        return $result->result_array();
    }
    
    public function getAllSharedFilesWithOthers($IdOwner,$IdFolder=0){
        $query = "SELECT 
	shares.IdOwner,
	shares.IdShared,
	shares.IdFile,
	shares.IdFolder,
	shares.Name,
	shares.ShareCreated,
	shares.SharePrivilege,
	shares.FullPath,
	user.UserName,
	file.FileSize,
	file.FileLastModified,
	file.FileExtension
FROM shares
JOIN user ON shares.IdShared = user.IdUser
JOIN file ON shares.IdFile = file.IdFile
WHERE shares.IdOwner = ? AND shares.IdFolder = ?";
        $result = $this->db->query($query,array($IdOwner,$IdFolder));
        return $result->result_array();
    }
    
    public function getAllSharedFoldersWithOthers($IdOwner,$IdFolder=0){
        $query = "SELECT 
	shares.IdOwner,
	shares.IdShared,
	shares.IdFolder,
	shares.Name,
	shares.ShareCreated,
	shares.SharePrivilege,
	shares.FullPath,
	user.UserName,
	folders.FolderMask,
	folders.IdParent
FROM shares
JOIN user ON shares.IdShared = user.IdUser
JOIN folders ON shares.IdFolder = folders.IdFolder";
        if($IdFolder==0){
            $query.=" WHERE shares.IdFile = 0 AND shares.IdOwner=?";
            $result = $this->db->query($query,array($IdOwner));
        }
        else{
            $query.= " WHERE folders.IdParent = ? AND shares.IdOwner= ?";
            $result = $this->db->query($query,array($IdFolder,$IdOwner));
        }
        return $result->result_array();
    }
    
    public function getAllFavFolders($IdUser){
        $query = "SELECT * FROM folders WHERE IdUser = ? AND Favourites = 1";
        $result = $this->db->query($query,array($IdUser));
        return $result->result_array();
    }
    
    public function getAllFavFiles($IdUser){
        $query = "SELECT * FROM file WHERE IdUser = ? AND Favourites = 1";
        $result = $this->db->query($query,array($IdUser));
        return $result->result_array();
    }

}
