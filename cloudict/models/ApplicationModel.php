<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class ApplicationModel extends CI_Model{
/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
        public function getAllApplications()
        {
            $query = "
                    SELECT `AppName`,
                           `AppLink`,
                           `AppIcon`,
                           `AppStatus`,
                           `AppOrder`
                    
                    FROM `App`
                    
                    ORDER BY AppOrder
            ";
                    
            $result = $this->db->query($query)->result_array();
            
            $data['Applications'] = array();
            
            if(!empty($result))
            {
                $i=0;
                foreach($result as $row)
                {
                    $data['Application'][$i++] = $row;
                }
            }
            return $data;
            
        }
        
        public function getApplications($status)
        {
            $query = "
                    SELECT `AppName`,
                           `AppLink`,
                           `AppIcon`,
                           `AppStatus`,
                           `AppOrder`
                    
                    FROM `App`
                    
                    WHERE `AppStatus` = ?
                    
                    ORDER BY AppOrder
            ";
                    
            $result = $this->db->query($query, [$status])->result_array();
            
            $data['Applications'] = array();
            
            if(!empty($result))
            {
                $i=0;
                foreach($result as $row)
                {
                    $data['Application'][$i++] = $row;
                }
            }
            return $data;
            
        }
}

