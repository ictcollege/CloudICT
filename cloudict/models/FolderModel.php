<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/*
 *  @author Darko Lesendric <dlesendric at https://github.com/ @Darko_Lesendric at https://twitter.com/>
 */

/**
 * Description of FolderModel
 * Folder model se koristi za upravljanje sa bazm [folders] 
 * Sve operacije nad tabelom se mogu izvesti iz ovog modela.
 * [eng] this model is for all operation on table [folders]
 * 
 * 
 * 
 * 
 * 
 * @author Darko
 */
class FolderModel extends CI_Model{
    public function __construct() {
        parent::__construct();
    }
    //get folder
    public function getFolderById($IdFolder) {
       $query = "SELECT * FROM `folders` "
                . "WHERE `IdFolder` = ? "
                . "LIMIT 1";
        $result = $this->db->query($query, array($IdFolder))->row();
        return $result;
    }
    //get folder by path
    public function getFolder($IdUser,$FolderPath=''){
            $query = "
			SELECT	`IdFolder`  
			FROM 	`folders` 

			WHERE	`IdUser` = ? AND `FolderPath` = ?";
                $query.= " LIMIT 1";
		$result = $this->db->query($query, array($IdUser,$FolderPath))->row();
		return $result;
    }
    //new folder
    public function insertUserFolder($IdUser,$FolderName,$FolderMask,$FolderPath,$IdParent=NULL){
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
    /**
     * rename folder
     * @param type $IdFolder
     * @param type $FolderName
     * @param type $FolderPath
     * @return type
     */
    public function changeFolderName($IdFolder,$FolderName,$FolderPath){
        $updateQuery = "
			UPDATE `Folders` SET `FolderName` = ?,`FolderPath` = ?
			
			WHERE IdFolder = ?
		";
                
		$result = $this->db->query($updateQuery, array($FolderName,$FolderPath, $IdFolder ));
                
		return !empty($result)?1:0; 
    }
    /**
     * ovo brise sve foldere i sve njegove child iteme iako izgleda bezazleno
     * zbog constrain-a, cak brise i iz share-a
     * zakomentarisano je zastarelo
     * [eng] this will delete folder, all child items and from share
     * @param type $IdFolder - int id folder
     */
    public function deleteFolder($IdFolder){
//depriicated commented 
//        $this->db->trans_start();
//        //list child folders
//        $res=$this->db->query('SELECT folders.IdFolder FROM folders WHERE folders.IdParent = ?',array($IdFolder));
        $this->db->query("DELETE FROM folders WHERE folders.IdFolder = ?",array($IdFolder));
//        $this->db->trans_complete();
//        //if some child folders in child folders, callback 
//        if(!empty($res->result_array())){
//            $childs = $res->result_array();
//            foreach ($childs as $row){
//                $this->deleteFolder($IdFolder);
//            }
//        }
    }
    /**
     * dohvatanje svih foldera i iteracija kroz njih
     * get all folder or itereate through
     * @param type $IdUser
     * @param type $IdParent
     * @return type
     */
    public function getAllUserFolders($IdUser,$IdParent=NULL){
        if(!is_null($IdParent)){
            $query = "SELECT * FROM folders WHERE IdUser = ? AND IdParent = ?";
            $result = $this->db->query($query,array($IdUser,$IdParent));
            return $result->result_array();
        }
        $query = "SELECT * FROM folders WHERE IdUser = ? AND IdParent IS NULL";
        $result = $this->db->query($query,array($IdUser));
        return $result->result_array();
    }
    //set to favourites folder
    public function setFavourites($IdUser,$IdFolder,$Unset){
        $query = "UPDATE folders SET Favourites = ? WHERE IdUser=? AND IdFolder = ?";
        $this->db->query($query,array($Unset,$IdUser,$IdFolder));
    }
    //get all folder for user - list all folders
    public function getAllFoldersForUser($IdUser){
        $query = "SELECT * FROM folders WHERE IdUser = ? ORDER BY FolderPath ASC";
        $result = $this->db->query($query,array($IdUser));
        return $result->result_array();
    }
    //get favourites
    public function getAllFavFolders($IdUser){
        $query = "SELECT * FROM folders WHERE IdUser = ? AND Favourites = 1";
        $result = $this->db->query($query,array($IdUser));
        return $result->result_array();
    }
}
