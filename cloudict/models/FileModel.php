<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * Sta sve fali od funkcija
 *  bilo bi dobro da imamo neku funkciju za prebrojavanje FileSize u folderu , 
 * neka vrati koji god broj , ja cu dodatno ovo obraditi na klijentskoj strani kolko je to u MB ili GB        
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
	public function insertUserFile($IdUser, $IdFileType, $IdFolder, $FileExtension, $FileName, $FileSize)
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
					`FileSize`,
					`FileCreated`,
					`FileLastModified`
				)
				
			VALUES 	(?,?,?,?,?,?,?,?)
		";

		$result = $this->db->query($query, [$IdUser, $IdFileType, $IdFolder, $FileExtension, $FileName, $FileSize, $FileCreated, $FileModified]);
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
	public function changeFileName($IdFile, $FileName)
	{
            //da li ovde treba FileLastModified ?
		$updateQuery = "
			UPDATE `File` SET `FileName` = ?
			
			WHERE IdFile = ?
		";
                
		$result = $this->db->query($updateQuery, [$FileName, $IdFile ]);
                
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
		";

		$result = $this->db->query($query, [$FileTypeMime])->result_array();
		
		if(!empty($result)) return $result[0]['IdFileType'];
		
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
        

        
	
	
}
	