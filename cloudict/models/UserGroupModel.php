<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class UserGroupModel extends CI_Model {
    public function getAllGroupsAndUsersInThem()
    {
            $query = " 
                    SELECT	`GroupName`,
                                `IdGroup`,
				`UserName`,
                                `IdUser`,
				`UserGroupStatusAdmin` AS `Admin`

			FROM 	`User`

			JOIN	`UserGroup`
			USING	(`IdUser`)

			JOIN	`Group` AS `g`
			USING	(`IdGroup`)
            
                        ORDER BY `IdGroup`
            ";
            
            $result = $this->db->query($query)->result_array();
            
            $data = array();
            
            if(!empty($result))
            {
                foreach($result as $row)
                {
                    $data['UserGroup'] = $row;
                }
            }
            
            return $data;
    }
}



