<<<<<<< HEAD
<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * fali neka funkcija za kvotu , da znam dal je prekoracio limit na upload-u
 * 
 * 
 */
class FileModel extends CI_Model {

	/**
	* Get All Files In Folder
	* 
	* Returns all files for $IdUser to which given $IdFolder belongs
	* 
	* Structure of returned data:
	*      array (
	*          [0] => array (
	*              [IdFile], [FileTypeMime],[FileExtension], [FileName], [FileSize], [FileLastModified], [FileCreated]
	*          ),
	*          ...
	*      )
	* 
	* @param int $IdUser
	* @param int $IdFolder
	* @return array
	*/
	public function getAllFilesInFolder($IdUser, $IdFolder=NULL)
	{
		$query = "
			SELECT	`IdFile`, 
					`FileTypeMime`,
					`FileExtension`,
					`FileName`,
                                        `FilePath`,
					`FileSize`,
					`FileLastModified`,
					`FileCreated`
				
			FROM 	`file`

			JOIN	`FileType`
			USING	(`IdFileType`)

			WHERE	`IdUser` = ? ";
                $query.=(is_null($IdFolder))? "AND `IdFolder` IS ? ":"AND `IdFolder` = ? ";
                //mozda neka funkcija za sortiranje npr(LastModified), folderi u svakom slucaju moraju prvi
                $query.= "ORDER BY `IdFileType`";
		$result = $this->db->query($query, [$IdUser, $IdFolder])->result_array();
                
		return $result;
	}
	
	/**
	* Insert File
	* 
	* Returns $IdFile of file inserted
	* 
	*      
	* 
	* @param int $IdUser
	* @param int $IdFileType
	* @param int $IdFolder
	* @param string $FileName
	* @param int $FileSize
	* @return int
	*/
	public function insertUserFile($IdUser, $IdFileType, $IdFolder, $FileExtension, $FileName,$FilePath, $FileSize)
	{
		$FileCreated = time();
		$FileModified = time();
		$query = "
			INSERT INTO `File` (
					`IdUser`,
					`IdFileType`,
					`IdFolder`,
					`FileExtension`,
					`FileName`,
                                        `FilePath`,
					`FileSize`,
					`FileCreated`,
					`FileLastModified`
				)
				
			VALUES 	(?,?,?,?,?,?,?,?,?)
		";

		$result = $this->db->query($query, [$IdUser, $IdFileType, $IdFolder, $FileExtension, $FileName,$FilePath, $FileSize, $FileCreated, $FileModified]);
		$IdFile = $this->db->insert_id();
		
		return $IdFile;
	}
	
	/**
	* Change File Folder
	* 
	* Structure of returned data:
	*      0: user was not updated
	*	   1: user was updated
	* 
	*      
	* 
	* @param int $IdFile
	* @param int $IdFolder
	* @return int
	*/
	public function changeFileFolder($IdFile, $IdFolder)
	{
		$updateQuery = "
			UPDATE `File` SET `IdFolder` = ? 
			
			WHERE IdFile = ?
		";
		$result = $this->db->query($updateQuery, [$IdFolder,$IdFile]);
		return !empty($result)?1:0; 
	}
	
	/**
	* Change File Name
	* 
	* Structure of returned data:
	*      0: user was not updated
	*	   1: user was updated
	* 
	*      
	* 
	* @param int $IdFile
	* @param int $IdFileName
	* @return int
	*/
	public function changeFileName($IdFile, $FileName,$FilePath)
	{
            //da li ovde treba FileLastModified ?
		$updateQuery = "
			UPDATE `File` SET `FileName` = ?,`FilePath` = ?
			
			WHERE IdFile = ?
		";
                
		$result = $this->db->query($updateQuery, [$FileName,$FilePath, $IdFile ]);
                
		return !empty($result)?1:0; 
	}
	
	/**
	* Update File Size
	* 
	* Structure of returned data:
	*      0: user was not updated
	*	   1: user was updated
	* 
	*      
	* 
	* @param int $IdFile
	* @param int $IdFileSize
	* @return int
	*/
	public function updateFileSize($IdFile, $FileSize)
	{
            //da li ovde treba FileLastModified ?
		$FileLastModified = time();
		$updateQuery = "
			UPDATE `File` SET `FileSize` = ?, `FileLastModified` = ?
			
			WHERE IdFile = ?
		";
		$result = $this->db->query($updateQuery, [$IdFile, $FileSize, $FileLastModified]);
		return !empty($result)?1:0; 
	}
	
	/**
	* Get File Type
	* 
	* Returns $IdFileType of given file type mime ($FileTypeMime) or returns 0 if not exists or it's not allowed
	* 
	*      
	* 
	* @param string $FileTypeMime
	* @return int
	*/
	public function getFileType($FileTypeMime)
	{
		$query = "
			SELECT `IdFileType`
			
			FROM `FileType`
			
			WHERE `FileTypeMime` = ? 
                        
                        LIMIT 1
		";

		$result = $this->db->query($query, [$FileTypeMime])->result_array();
		
		if(!empty($result)) return $result[0]['IdFileType'];
		else{
                    $query= "INSERT INTO `filetype` (
					`FileTypeMime`
				)
				
			VALUES 	(?)";
                    $result = $this->db->query($query, [$FileTypeMime]);
                    $IdFileType = $this->db->insert_id();
                    return $IdFileType;
                }
		return 0;
	}
	
	
	/**
	* Delete File
	* 
	* Structure of returned data:
	*      0: user was not deleted
	*	   1: user was deleted
	* 
	*      
	* 
	* @param int $IdFile
	*
	* @return int
	*/
	public function deleteFile($IdFile)
	{
		$deleteeQuery = "
			DELETE FROM `File` 
			
			WHERE `IdFile` = ?
		";
		$result = $this->db->query($deleteeQuery, [$IdFile]);
		return !empty($result)?1:0; 
		// Okida se triger koji brise sve podfoldere i fajlove koji pripadaju tom folderu!
	}
        
        public function getFile($IdUser,$FileName,$FilePath){
            $query = "
			SELECT	`IdFile`, 
					`FileTypeMime`,
					`FileExtension`,
					`FileName`,
                                        `FilePath`,
					`FileSize`,
					`FileLastModified`,
					`FileCreated`
				
			FROM 	`file`

			JOIN	`FileType`
			USING	(`IdFileType`)

			WHERE	`IdUser` = ? AND `FileName` = ? AND `FilePath` = ?";
            
                $query.= " LIMIT 1";
		$result = $this->db->query($query, [$IdUser, $FileName,$FilePath])->row();
                
		return $result;
        }
        public function getFolder($IdUser,$FilePath=''){
            $query = "
			SELECT	`IdFile`  
			FROM 	`file` 

			WHERE	`IdUser` = ? AND `FilePath` = ?";
                $query.= " LIMIT 1";
		$result = $this->db->query($query, [$IdUser,$FilePath])->row();
		return $result;
        }
        
        public function getFileById($IdFile){
            $query = "SELECT * FROM `file` "
                    . "JOIN `FileType` USING (`IdFileType`) "
                    . "WHERE `IdFile` = ? "
                    . "LIMIT 1";
            $result = $this->db->query($query, [$IdFile])->row();
            return $result;
        }
        

        
	
	
}
	
=======
<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/*
 *  @author Darko Lesendric <dlesendric at https://github.com/ @Darko_Lesendric at https://twitter.com/>
 */

/**
 * Description of FileModel
 * File model se koristi za sve radnje vezane sa fajlovima
 * Sadrzi funkcije za fajlove i sve radnje sa njima
 * Tabela u bazi za koriscenje [file], tabela ima vezane strane kljuceve, on delete cascade, on update cascade, kako bi se automatski brisao taj fajl ili folder iz file tabele ako vise ne postoji njegov folder
 * 
 * [eng] File model contains functions and methods for files
 * Table in use [file] , table have forigen keys to delete automaticly files if parent folder delete on delete cascade, or update on cascade.
 * @author Darko
 */
class FileModel extends CI_Model {

	/**
        * depricated
        * zastarelo ne koristi se (korisceno u v1)
	* Get All Files In Folder
	* 
	* Returns all files for $IdUser to which given $IdFolder belongs
	* 
	* Structure of returned data:
	*      array (
	*          [0] => array (
	*              [IdFile], [FileTypeMime],[FileExtension], [FileName], [FileSize], [FileLastModified], [FileCreated]
	*          ),
	*          ...
	*      )
	* 
	* @param int $IdUser
	* @param int $IdFolder
	* @return array
	*/
	public function getAllFilesInFolder($IdUser, $IdFolder=NULL)
	{
		$query = "
			SELECT	`IdFile`, 
					`FileType`,
					`FileExtension`,
					`FileName`,
                                        `FilePath`,
					`FileSize`,
					`FileLastModified`,
					`FileCreated`
				
			FROM 	`file`


			WHERE	`IdUser` = ? ";
                $query.=(is_null($IdFolder))? "AND `IdFolder` IS ? ":"AND `IdFolder` = ? ";
                //mozda neka funkcija za sortiranje npr(LastModified), folderi u svakom slucaju moraju prvi
                $query.= "ORDER BY `IdFileType`";
		$result = $this->db->query($query, array($IdUser, $IdFolder))->result_array();
                
		return $result;
	}
	
        /**
         * Metod za kreiranja fajla u bazi nakon uploada ili kreiranja
         * Method for inserting  in db  file after upload or create
         * @param type $IdUser - int IdUser owner of file
         * @param type $FileType - string mime type
         * @param type $IdFolder - int parent folder can be null 
         * @param type $FileExtension - string extension (.txt, .php)
         * @param type $FileName - string File Name
         * @param type $FilePath - string File Path
         * @param type $FileSize - int size 
         * @return type int - inserted id
         */
	public function insertUserFile($IdUser, $FileType, $IdFolder, $FileExtension, $FileName,$FilePath, $FileSize)
	{
		$FileCreated = time();
		$FileModified = time();
		$query = "INSERT INTO `File` (
					`IdUser`,
					`FileType`,
					`IdFolder`,
					`FileExtension`,
					`FileName`,
                                        `FilePath`,
					`FileSize`,
					`FileCreated`,
					`FileLastModified`
				)
				
			VALUES 	(?,?,?,?,?,?,?,?,?)
		";

		$result = $this->db->query($query, array($IdUser, $FileType, $IdFolder, $FileExtension, $FileName,$FilePath, $FileSize, $FileCreated, $FileModified));
		$IdFile = $this->db->insert_id();
		
		return $IdFile;
	}
	
	/**
	* Change File Folder
	* 
	* Structure of returned data:
	*      0: user was not updated
	*	   1: user was updated
	* 
	*      
	* 
	* @param int $IdFile
	* @param int $IdFolder
	* @return int
	*/
	public function changeFileFolder($IdFile, $IdFolder)
	{
		$updateQuery = "
			UPDATE `File` SET `IdFolder` = ? 
			
			WHERE IdFile = ?
		";
		$result = $this->db->query($updateQuery, array($IdFolder,$IdFile));
		return !empty($result)?1:0; 
	}
	
	/**
	* Change File Name
	* 
	* Structure of returned data:
	*      0: user was not updated
	*	   1: user was updated
	* 
	*      
	* 
	* @param int $IdFile
	* @param int $IdFileName
	* @return int
	*/
	public function changeFileName($IdFile, $FileName,$FilePath)
	{
            //da li ovde treba FileLastModified ?
		$updateQuery = "
			UPDATE `File` SET `FileName` = ?,`FilePath` = ?
			
			WHERE IdFile = ?
		";
                
		$result = $this->db->query($updateQuery, array($FileName,$FilePath, $IdFile ));
                
		return !empty($result)?1:0; 
	}
	
	/**
	* Update File Size
	* 
	* Structure of returned data:
	*      0: user was not updated
	*	   1: user was updated
	* 
	*      
	* 
	* @param int $IdFile
	* @param int $IdFileSize
	* @return int
	*/
	public function updateFileSize($IdFile, $FileSize)
	{
		$FileLastModified = time();
		$updateQuery = "
			UPDATE `File` SET `FileSize` = ?, `FileLastModified` = ?
			
			WHERE IdFile = ?
		";
		$result = $this->db->query($updateQuery, array($FileSize, $FileLastModified, $IdFile));
		return !empty($result)?1:0; 
	}
	
	/**
	* Get File Type
	* 
	* Returns $IdFileType of given file type mime ($FileTypeMime) or returns 0 if not exists or it's not allowed
	* 
	*      
	* 
	* @param string $FileTypeMime
	* @return int
	*/
//	public function getFileType($FileTypeMime)
//	{
//		$query = "
//			SELECT `IdFileType`
//			
//			FROM `FileType`
//			
//			WHERE `FileTypeMime` = ? 
//                        
//                        LIMIT 1
//		";
//
//		$result = $this->db->query($query, array($FileTypeMime))->result_array();
//		
//		if(!empty($result)) return $result[0]['IdFileType'];
//		else{
//                    $query= "INSERT INTO `filetype` (
//					`FileTypeMime`
//				)
//				
//			VALUES 	(?)";
//                    $result = $this->db->query($query, array($FileTypeMime));
//                    $IdFileType = $this->db->insert_id();
//                    return $IdFileType;
//                }
//		return 0;
//	}
	
	
	/**
	* Delete File
	* depricated
	* Structure of returned data:
	*      
	* 
	*      
	* 
	* @param int $IdFile
	*
	* @return int
	*/
	public function deleteFile($IdFile)
	{
		$deleteeQuery = "
			DELETE FROM `File` 
			
			WHERE `IdFile` = ?
		";
		$result = $this->db->query($deleteeQuery, array($IdFile));
		return !empty($result)?1:0; 
	}
        /**
         * depricated , zastarelo
         * @param type $IdUser - owner of file
         * @param type $FileName 
         * @param type $FilePath
         * @return type
         */
        public function getFile($IdUser,$FileName,$FilePath){
            $query = "
			SELECT	`IdFile`, 
					`FileType`,
					`FileExtension`,
					`FileName`,
                                        `FilePath`,
					`FileSize`,
					`FileLastModified`,
					`FileCreated`
				
			FROM 	`file`


			WHERE	`IdUser` = ? AND `FileName` = ? AND `FilePath` = ?";
            
                $query.= " LIMIT 1";
		$result = $this->db->query($query, array($IdUser, $FileName,$FilePath))->row();
                
		return $result;
        }
        /**
         * koristi se za maske
         * used for masks
         * @param type $IdUser 
         * @param type $FilePath
         * @return type
         */
        public function getFolder($IdUser,$FilePath=''){
            $query = "
			SELECT	`IdFile`  
			FROM 	`file` 

			WHERE	`IdUser` = ? AND `FilePath` = ?";
                $query.= " LIMIT 1";
		$result = $this->db->query($query, array($IdUser,$FilePath))->row();
		return $result;
        }
        
        public function getFileById($IdFile){
            $query = "SELECT * FROM `file` "
                    . "WHERE `IdFile` = ? "
                    . "LIMIT 1";
            $result = $this->db->query($query, array($IdFile))->row();
            return $result;
        }
        
        
           public function getFavorites($IdUser){
               $query = "
			SELECT	`IdFile`, 
					`FileType`,
					`FileExtension`,
					`FileName`,
                                        `FilePath`,
					`FileSize`,
					`FileLastModified`,
					`FileCreated`
				
			FROM 	`file`


			WHERE	`IdUser` = ? AND Favorites = 1";
		$result = $this->db->query($query, array($IdUser))->result_array();
                
		return $result;
           }
    /**
     * main method to get all files
     * @param type $IdUser
     * @param type $IdFolder
     * @return type
     */    
    public function getAllUserFiles($IdUser,$IdFolder=null){
        if(!is_null($IdFolder)){
            $query = "SELECT * FROM file WHERE IdUser = ? AND IdFolder = ?";
            $result = $this->db->query($query,array($IdUser,$IdFolder));
        }
        else{
            $query = "SELECT * FROM file WHERE IdUser = ? AND IdFolder IS NULL";
            $result = $this->db->query($query,array($IdUser));
        }
        
        
        return $result->result();
    }
    
    public function setFavourites($IdUser,$IdFile,$Unset){
        $query = "UPDATE file SET Favourites = ? WHERE IdUser=? AND IdFile = ?";
        $this->db->query($query,array($Unset,$IdUser,$IdFile));
    }
    /**
     * najlaksi sistem za premestanje fajla u db
     * the easy way to move file in db
     * @param type $destination_or_IdFolder destinacija (za root) ili u Id folder
     * @param type $IdFile
     * @param type $IdUser
     * @param type $root bool
     */
    public function moveFile($destination_or_IdFolder,$IdFile,$IdUser,$root=false){
        if($root){
            $this->db->trans_start();
            $query = "UPDATE file SET FilePath = ?,IdFolder=NULL WHERE IdUser = ? AND IdFile = ?";
            $this->db->query($query,array($destination_or_IdFolder,$IdUser,$IdFile));
            $this->db->query("UPDATE shares SET shares.FullPath = ?,shares.IdFolder=NULL WHERE IdFile=?",array($destination_or_IdFolder,$IdFile));
            $this->db->trans_complete();
        }
        else{
            $query = "call change_file_folder(?,?,?)";
            $this->db->query($query,array($destination_or_IdFolder,$IdFile,$IdUser));
        }
    }

    
    public function getAllFavFiles($IdUser){
        $query = "SELECT * FROM file WHERE IdUser = ? AND Favourites = 1";
        $result = $this->db->query($query,array($IdUser));
        return $result->result_array();
    }
    //disk used
    public function sumAllFileSize($IdUser){
        $query = "SELECT SUM(FileSize) AS diskused FROM file WHERE file.IdUser = ?";
        $result = $this->db->query($query,array($IdUser));
        return $result->row();
    }
        
    public function quotaExceededNotification($IdUser){
        $this->db->query('call create_notification(?,?,?,?,?,?)',array($IdUser,11,2,0,"Administrator","Quota Exceeded! You need to delete some files, or contact Administrator to get more space!"));
    }
}
	
>>>>>>> master
