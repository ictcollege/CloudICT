<?php

/*
 *  @author Darko Lesendric <dlesendric at https://github.com/ @Darko_Lesendric at https://twitter.com/>
 */

/**
 * Description of ShareModel
 * Share model se koristi za sve radnje vezane sa share-om. 
 * Sadrzi funkcije za serovan i unshareovanje fajlova i foldera.
 * Tabela u bazi za koriscenje [shares], tabela ima vezane strane kljuceve, on delete cascade, on update cascade, kako bi se automatski brisao taj fajl ili folder iz share-a ako vise ne postoji
 * 
 * [eng] Share model contains functions and methods for share and ushare files and folders
 * Table in use [shares] , table have forigen keys to delete automaticly files or folders on delete, or update on delete.
 * @author Darko
 */
class ShareModel extends CI_Model{
    const READ = 1;
    const WRITE = 2;
    const EXECUTE = 3;
    
    public function __construct() {
        parent::__construct();
    }
    
   /**
    * Upit za definisanje dal neko nesto moze da downloaduje
    * [eng]Query to check if someone can download file or folder
    * 
    * @param type Idshared int Which user
    * @param type $IdFile int || null if null we will check folder
    * @param type $IdFolder int || null if null we will check file
    * @return boolean true/false
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
       
       if(!empty($result)){
           return TRUE;
       }
       return FALSE;
   }
   /**
    * Metod za proveru dal neko moze obrisati fajl ili folder
    * [eng] Method will check if user can delete shared file or folder 
    * @param type $IdShared int IdUser who want's to delete
    * @param type $IdFile int || null if null that's folder
    * @param type $IdFolder int || nulll if null that's file
    * @return boolean
    */
   public function canExecute($IdShared,$IdFile=NULL,$IdFolder=NULL){
       if(!is_null($IdFile)){
           $query = "SELECT IdShared,IdFile FROM shares WHERE IdShared = ? AND IdFile = ? AND SharePrivilege = 3 LIMIT 1";
           $result = $this->db->query($query,array($IdShared,$IdFile));
       }
       else{
           $query = "SELECT IdShared,IdFolder FROM shares WHERE IdShared = ? AND IdFolder = ? AND SharePrivilege = 3 AND IdFile=0 LIMIT 1";
           $result = $this->db->query($query,array($IdShared,$IdFolder));
       }
       if(!empty($result)){
           return TRUE;
       }
       return FALSE;
       
   }
   public function canEdit($IdShared,$IdFile=NULL,$IdFolder=NULL){
       if(!is_null($IdFile)){
           $query = "SELECT IdShared,IdFile FROM shares WHERE IdShared = ? AND IdFile = ? AND SharePrivilege <> 1 LIMIT 1";
           $result = $this->db->query($query,array($IdShared,$IdFile))->result_array();
       }
       else{
           $query = "SELECT IdShared,IdFolder FROM shares WHERE IdShared = ? AND IdFolder = ? AND SharePrivilege <> 1 AND IdFile IS NULL LIMIT 1";
           $result = $this->db->query($query,array($IdShared,$IdFolder))->result_array();
       }
       if(!empty($result)){
           return TRUE;
       }
       return FALSE;
   }
   
    /**
     * Method za unshareovanje foldera sa shareovanim userom
     * Method to unshare folder with user
     * @param type $IdOwner - int IdUser who created that file
     * @param type $IdShared - int IdUser with who is shared
     * @param type $IdFolder - int IdFolder in share
     */
    
    public function unshareFolder($IdOwner,$IdShared,$IdFolder){
        $this->db->trans_start();
        //unshare that folder
        $this->db->query('DELETE FROM shares WHERE IdOwner = ? AND IdShared = ? AND IdFolder= ?',array($IdOwner,$IdShared,$IdFolder));
        //ushare all files in it
        $this->db->query('DELETE FROM shares WHERE IdFolder=? AND IdShared=?',array($IdFolder,$IdShared));
        //list child folders
        $res=$this->db->query('SELECT folders.IdFolder FROM folders WHERE folders.IdParent = ?',array($IdFolder));
        $this->db->trans_complete();
        //if some child folders, callback 
        $array = $res->result_array();
        if(!empty($array)){
            $childs = $res->result_array();
            foreach ($childs as $row){
                $this->unshareFolder($IdOwner, $IdShared, $row['IdFolder']);
            }
        }
    }
    /**
     * Method za unshareovanje fajla sa korisnikom
     * Method to unshre file with shared user
     * 
     * @param type $IdOwner - int IdUser, owner of file
     * @param type $IdShared - int IdUser , with who to unshare
     * @param type $IdFile - int IdFile , which file 
     * @param type $IdFolder - int IdFolder, if 0 that folder have no parent folde, else have.
     */
    public function unshareFile($IdOwner,$IdShared,$IdFile,$IdFolder=0){
        $query = "DELETE FROM `shares` WHERE `IdOwner` = ? AND `IdShared` = ? AND `IdFile` = ? AND `IdFolder` = ?";
        $result = $this->db->query($query,array($IdOwner,$IdShared,$IdFile,$IdFolder));
    }
    
    /**
     * Metod za dobijanje svih share-ovanih fajlova
     * Method to get all shared files
     * @param type $IdShared int IdUser who have share
     * @param type $IdFolder int IdFolder
     * @return type array
     */
    public function getAllSharedFiles($IdShared,$IdFolder=null){
        $query = "SELECT 
                shares.IdShare,
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
        WHERE shares.IdShared = ?";
        if(!is_null($IdFolder)){
            $query.=" AND shares.IdFolder=".$IdFolder;
        }
//        else{
//            $query.=" AND shares.IdFolder IS NULL";
//        }
        $result = $this->db->query($query,array($IdShared));
        return $result->result_array();
    }
    
    /**
     * Metod za dobijanje svih share-ovanih folder
     * Method to get all shared files
     * @param type $IdShared int IdUser who have share
     * @param type $IdFolder int IdFolder
     * @return type array
     */
    public function getAllSharedFolders($IdShared,$IdFolder=null){
        $query = "SELECT 
        shares.IdShare,
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
        if(is_null($IdFolder)){
            $query.=" WHERE shares.IdFile IS NULL  AND shares.IdShared=?";
            $result = $this->db->query($query,array($IdShared));
        }
        else{
            $query.= " WHERE shares.IdFile IS NULL AND folders.IdParent = ? AND shares.IdShared = ?";
            $result = $this->db->query($query,array($IdFolder,$IdShared));
        }
        return $result->result_array();
    }
    /**
     * Metod za dobijanje svih share-ovanih fajlova , ako je korisnik u nekom folderu izbacice za tog korisnika child elemente tog foldera
     * Metod to get all shared child files if IdFolder != 0 else root files
     * @param type $IdOwner - int IdUser, owner of files
     * @param type $IdFolder - int IdFolder if 0 , root
     * @param type $IdShared - int IdShared if user go into some folder this will show just his child items
     * @return type array 
     */
    public function getAllSharedFilesWithOthers($IdOwner,$IdFolder=NULL,$IdShared=""){
        $query = "SELECT
        shares.IdShare,
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
WHERE shares.IdOwner = ?";
        $query.=(is_null($IdFolder))? "AND shares.IdFolder IS NULL" : " AND shares.IdFolder=".$IdFolder;
        if(!empty($IdShared)){
           $query.=" AND shares.IdShared=".$IdShared;    
        }
        $result = $this->db->query($query,array($IdOwner));
        return $result->result_array();
    }
    /**
     * Metod za dobijanje svih share-ovanih folder , ako je korisnik u nekom folderu izbacice za tog korisnika child elemente tog foldera
     * Metod to get all shared child folders if IdFolder != 0 else root files
     * @param type $IdOwner - int IdUser, owner of files
     * @param type $IdFolder - int IdFolder if 0 , root
     * @param type $IdShared - int IdShared if user go into some folder this will show just his child items
     * @return type array 
     */
    public function getAllSharedFoldersWithOthers($IdOwner,$IdFolder=null,$IdShared=""){
        $query = "SELECT 
        shares.IdShare,
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
        if(is_null($IdFolder)){
            $query.=" WHERE folders.IdParent IS NULL AND shares.IdOwner=? AND shares.IdFile IS NULL";
            $result = $this->db->query($query,array($IdOwner));
        }
        else{
            $query.= " WHERE folders.IdParent = ? AND shares.IdOwner= ? AND shares.IdShared=? AND shares.IdFile IS NULL";
            $result = $this->db->query($query,array($IdFolder,$IdOwner,$IdShared));
        }
        return $result->result_array();
    }
    

    /**
     * depricated function 
     * Metod za dobijanje share-ovanog fajla. Koristi se najcesce za direktno share-ovan file
     * Ovaj metod je zastareo i nije u korscenju
     * [eng] Method to get shared file, deprcited , not in use
     * @param type $IdOwner - int IdUser, owner of file
     * @param type $IdFile - int IdFile
     * @return type object file
     */
    public function getSharedFile($IdOwner,$IdFile){
        $query = "SELECT * FROM shares WHERE IdOwner = ? AND IdFile = ? LIMIT 1";
        $result = $this->db->query($query,array($IdOwner,$IdFile));
        return $result->row();
    }
    /**
     * depricated , not in use 
     * Metod zastareo , nije u korscenju, mozda zatreba
     * vraca share-ovan folder
     * @param type $IdOwner
     * @param type $IdFolder
     * @return type
     */
    public function getSharedFolder($IdOwner,$IdFolder){
        $query = "SELECT * FROM shares WHERE IdOwner = ? AND IdFolder = ? LIMIT 1";
        $result = $this->db->query($query,array($IdOwner,$IdFolder));
        return $result->row();
    }
    /**
     * Method za serovanje direktno folder, procedura share-uje folder i vraca putanju koja se dalje koristi za generisanje direktnog linka
     * Method to share direct folder so someone can download , procedure returns folder path which we later use to generate direct link.
     * [procedure]
     * CREATE DEFINER=`root`@`localhost` PROCEDURE `new_direct_share_folder`(IN `idowner` INT UNSIGNED, IN `idfolder` INT UNSIGNED, IN `privilege` INT)
    NO SQL
BEGIN
DECLARE path VARCHAR(511) DEFAULT NULL;
DECLARE name VARCHAR(100);
SELECT folders.FolderPath,folders.FolderName INTO path,name FROM folders WHERE folders.IdFolder = idfolder;


INSERT INTO shares (
    shares.IdOwner,
    shares.IdFolder,
    shares.Name,
    shares.ShareCreated,
    shares.FullPath,
    shares.SharePrivilege,
    shares.SharedByLink
    ) 
VALUES (
    idowner,
    idfolder,
    name,
    UNIX_TIMESTAMP(),
    path,
    privilege,
    1
    );
	SELECT path AS path;
END
     * @param type $IdFolder - int IdFolder to share
     * @param type $IdOwner - int IdUser owner of folder
     * @param type $SharePrivilege - constant read, write, execute, for later use, if we wont to create view to iterate throgh folder
     * @return type string - folder path
     */
    public function shareDirectFolder($IdFolder,$IdOwner,$SharePrivilege=  self::READ){
        $query = "call new_direct_share_folder(?,?,?)";
        $result = $this->db->query($query,array($IdOwner,$IdFolder,$SharePrivilege));
        $path = (empty($result))? null : $result->row()->path;
        return $path;
    }
    /**
     * Method za serovanje direktno fajla, procedura share-uje fajl i vraca putanju koja se dalje koristi za generisanje direktnog linka
     * Method to share direct file so someone can download , procedure returns file path which we later use to generate direct link.
     * [procedure]
     * CREATE DEFINER=`root`@`localhost` PROCEDURE `new_direct_share_file`(IN `idowner` INT UNSIGNED, IN `idfile` INT UNSIGNED, IN `privilege` INT)
    NO SQL
BEGIN
DECLARE path VARCHAR(511) DEFAULT NULL;
DECLARE name VARCHAR(255);

SELECT file.FilePath,file.FileName INTO path,name FROM file WHERE file.IdFile = idfile;


INSERT INTO shares (
    shares.IdOwner,
    shares.IdFile,
    shares.Name,
    shares.ShareCreated,
    shares.FullPath,
    shares.SharePrivilege,
    shares.SharedByLink
    ) 
VALUES (
    idowner,
    idfile,
    name,
    UNIX_TIMESTAMP(),
    path,
    privilege,
    1
    );
	SELECT path AS path;
END
     * @param type $IdFile - int IdFile to share
     * @param type $IdOwner - int IdUser, owner of file
     * @param type $SharePrivilege - int Permission (constants)
     * @return type string , file path
     */
    public function shareDirectFile($IdFile,$IdOwner,$SharePrivilege = self::READ){
        $query = "call new_direct_share_file(?,?,?)";
        $result = $this->db->query($query,array($IdOwner,$IdFile,$SharePrivilege));
        $path = (empty($result))? null : $result->row()->path;
        return $path;
    }
    /**
     * Metod za brisanje direktno sharevanog foldera
     * Method to delete direct shared folder
     * 
     * @param type $IdFolder - int IdFolder
     * @param type $IdOwner - int IdUser , (owner) 
     */
    public function unshareDirectFolder($IdFolder,$IdOwner){
        $query = "DELETE FROM shares WHERE IdFolder = ? AND IdOwner = ? AND SharedByLink = 1";
        $this->db->query($query,array($IdFolder,$IdOwner));
    }
    /**
     * Metod za brisanje direktno shareovanog fajla
     * Method to delete direct shared file
     * @param type $IdFile - int IdFile to delete
     * @param type $IdOwner - int IdUser , owner of file
     */
    public function unshareDirectFile($IdFile,$IdOwner){
        $query = "DELETE FROM shares WHERE IdFile = ? AND IdOwner = ? AND SharedByLink = 1";
        $this->db->query($query,array($IdFile,$IdOwner));
    }
    
    /**
     * Method to check if user can download direct shared file or folder
     * Metod koji proverava dal user sme da downloaduje direktno shareovan fajl ili folder
     * @param type $filepath - path of file
     * @return boolean 
     */

    public function canDirectDownload($filepath){
        $query = "SELECT * FROM shares WHERE FullPath = ? AND SharedByLink = 1 LIMIT 1";
        $result = $this->db->query($query,array($filepath))->result_array();
        if(!empty($result)){
            return TRUE;
        }
        return FALSE;
    }
    /**
     * Metod za dobijanje svih direktno share-ovanih fajlova i foldera
     * Method to get all owner direct shared files or folders
     * @param type $IdOwner - owner of shared files
     * @return type array (associative)
     */
    public function getDirectShares($IdOwner){
        $query = "SELECT * FROM shares WHERE IdOwner = ? AND SharedByLink = 1";
        $result = $this->db->query($query,array($IdOwner));
        return $result->result_array();
    }
    

    /**
     * Method to share folders and all his child items
     * [callback]This method call it's self if child items in folder
     * Metod za share-ovanje foldera i svih njegovih unutrasnjih fajlova i foldera
     * Ovaj metod zove sam sebe da bih share-ovao child iteme dok god ima svoje unutrasnje fajlove
     * @param type $IdOwner - int IdUser, owner of folder
     * @param type $IdShared - int IdUser , with who to share
     * @param type $IdFolder - int IdFolder 
     * @param type $SharePrivilege - constant - permission
     */
    public function shareFolder($IdOwner,$IdShared,$IdFolder,$SharePrivilege){
        $this->db->trans_start();
        //share that folder
        $this->db->query('INSERT INTO shares (shares.IdOwner,shares.IdShared,shares.IdFolder,shares.ShareCreated,shares.Name,shares.FullPath,shares.SharePrivilege)
SELECT DISTINCT ?,?,?,UNIX_TIMESTAMP(),folders.FolderName,folders.FolderPath,? FROM folders WHERE folders.IdFolder = ?',array($IdOwner,$IdShared,$IdFolder,$SharePrivilege,$IdFolder));
        //notification
        $id = $this->db->insert_id();
        $user = $this->db->query("SELECT UserFullname FROM user WHERE user.IdUser=?",$IdOwner)->row();
        $this->db->query('call create_notification(?,?,?,?,?,?)',array($IdShared,13,2,$id,$user->UserFullname,"Shared folder with child items!"));
        //share all files in it
        $this->db->query('INSERT INTO shares(shares.IdOwner,shares.IdShared,shares.IdFile,shares.IdFolder,shares.ShareCreated,shares.Name,shares.FullPath,shares.SharePrivilege)
SELECT DISTINCT ?,?,file.IdFile,file.IdFolder,UNIX_TIMESTAMP(),file.FileName,file.FilePath,? FROM file WHERE file.IdFolder = ?;',array($IdOwner,$IdShared,$SharePrivilege,$IdFolder));
        //list child folders
        $res=$this->db->query('SELECT folders.IdFolder FROM folders WHERE folders.IdParent = ?',array($IdFolder));
        $this->db->trans_complete();
//        if some child folders, callback 
        $array = $res->result_array();
        if(!empty($array)){
            $childs = $res->result_array();
            foreach ($childs as $row){
                $this->shareFolder($IdOwner, $IdShared, $row['IdFolder'], $SharePrivilege);
            }
        }
        
    }
    
    /**
     * Metod za shareovanje fajla sa korisnikom
     * 
     * Method to share file with some user
     * @param type $IdOwner - int IdUser, owner of file
     * @param type $IdShared - int IdUser , with who to share
     * @param type $IdFile - int IdFile
     * @param type $SharePrivilege - constant privilege/ permission
     */
    public function shareFile($IdOwner,$IdShared,$IdFile,$SharePrivilege){
        $query = 'INSERT INTO shares(
            shares.IdOwner,
            shares.IdShared,
            shares.IdFile,
            shares.IdFolder,
            shares.ShareCreated,
            shares.Name,
            shares.FullPath,
            shares.SharePrivilege
            )
            SELECT DISTINCT ?,?,file.IdFile,file.IdFolder,UNIX_TIMESTAMP(),file.FileName,file.FilePath,? FROM file WHERE file.IdFile = ?';
        $this->db->trans_start();
        $this->db->query($query,array($IdOwner,$IdShared,$SharePrivilege,$IdFile));
        $id = $this->db->insert_id();
        $user = $this->db->query("SELECT UserFullname FROM user WHERE user.IdUser=?",array($IdOwner))->row();
        //notification
        $this->db->query('call create_notification(?,?,?,?,?,?)',array($IdShared,13,2,$id,$user->UserFullname,"Shared new file with you!"));
        $this->db->trans_complete();
    }
    /**
     * Metod za brisanje specificnog id-a , sta god to bilo , fajl folder, svaki share ima svoj primarni kljuc
     * Method to delete from shares by primary key.
     * @param type $Id - int IdShare
     */
    public function deleteShareById($Id){
        $query = "DELETE FROM shares WHERE IdShare = ?";
        $this->db->query($query,array($Id));
    }
    
    
    public function getShareById($IdShare){
        $query = "SELECT * FROM shares WHERE IdShare = ? LIMIT 1";
        $result = $this->db->query($query,array($IdShare))->row();
        return $result;
    }
    
        
}
