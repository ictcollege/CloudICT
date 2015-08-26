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
        $query = "INSERT INTO `share` (
                                `IdFile`,
                                `IdUser`,
                                `ShareCreated`,
                                `ShareFullName,
                                `FilePath`,
                                `SharePrivilege`
                                ) VALUES (?,?,?,?,?,?)
                                ";
        $result = $this->db->query($query,[$IdFile,$IdUser,time(),$ShareFullName,$FilePath,$SharePrivilege]);
        
    }
    
    
    
    
    
}
