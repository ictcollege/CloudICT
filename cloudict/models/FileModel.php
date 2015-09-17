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
	