<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/*
 *  @author Darko Lesendric <dlesendric at https://github.com/ @Darko_Lesendric at https://twitter.com/>
 */

/**
 * Description of FolderModel
 *
 * @author Darko
 */
class FolderModel extends CI_Model{
    public function __construct() {
        parent::__construct();
    }
    
    public function getFolderById($IdFolder) {
       $query = "SELECT * FROM `folders` "
                . "WHERE `IdFolder` = ? "
                . "LIMIT 1";
        $result = $this->db->query($query, array($IdFolder))->row();
        return $result;
    }
    public function getFolder($IdUser,$FolderPath=''){
            $query = "
			SELECT	`IdFolder`  
			FROM 	`folders` 

			WHERE	`IdUser` = ? AND `FolderPath` = ?";
                $query.= " LIMIT 1";
		$result = $this->db->query($query, array($IdUser,$FolderPath))->row();
		return $result;
    }
    public function insertUserFolder($IdUser,$FolderName,$FolderMask,$FolderPath,$IdParent=0){
        $query = "
			INSERT INTO `Folders` (
					`IdUser`,
					`FolderName`,
					`FolderMask`,
					`FolderPath`,
                                        `IdParent`
				)
				
			VALUES 	(?,?,?,?,?)
		";
        $this->db->query($query, array($IdUser, $FolderName, $FolderMask, $FolderPath,$IdParent));
    }
    
    public function changeFolderName($IdFolder,$FolderName,$FolderPath){
        $updateQuery = "
			UPDATE `Folders` SET `FolderName` = ?,`FolderPath` = ?
			
			WHERE IdFolder = ?
		";
                
		$result = $this->db->query($updateQuery, array($FolderName,$FolderPath, $IdFolder ));
                
		return !empty($result)?1:0; 
    }
    
    public function deleteFolder($IdFolder){
        $query = "DELETE FROM folders WHERE IdFolder = ?";
        $result = $this->db->query($query,array($IdFolder));
    }
    
    public function getAllUserFolders($IdUser,$IdParent=0){
        $query = "SELECT * FROM folders WHERE IdUser = ? AND IdParent = ?";
        $result = $this->db->query($query,array($IdUser,$IdParent));
        return $result->result_array();
    }
    
    public function setFavourites($IdUser,$IdFolder,$Unset){
        $query = "UPDATE folders SET Favourites = ? WHERE IdUser=? AND IdFolder = ?";
        $this->db->query($query,array($Unset,$IdUser,$IdFolder));
    }
}
