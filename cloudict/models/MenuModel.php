<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class MenuModel extends CI_Model{
/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
        public function getMenuOfApplication($idApplication)
        {
            $query = "
                    SELECT `AppMenuName`,
                           `AppMenuLink`,
                           `AppMenuIcon`
                           
                    FROM `appmenu`
                    
                    WHERE `IdApp` = ?
                    
                    ORDER BY `AppMenuOrder`
            ";
            
            $result = $this->db->query($query, [$idApplication])->result_array();
            
            $data = array();
            
            if(!empty(($result)))
            {
                $i=0;
                foreach($result as $row) 
                {
                    $data['Menu'][$i++] = $row;
                }
            }
            
            return $data;
        }
}
