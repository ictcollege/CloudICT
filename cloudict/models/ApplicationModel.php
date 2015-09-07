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
                    SELECT `IdApp`,
                           `AppName`,
                           `AppLink`,
                           `AppIcon`,
                           `AppStatus`,
                           `AppOrder`,
                           `AppColor`
                    
                    FROM `App`
                    
                    ORDER BY AppOrder
            ";
                    
            $result = $this->db->query($query)->result_array();
            
            $data = array();
            
            if(!empty($result))
            {
                $i=0;
                foreach($result as $row)
                {
                    $data['Applications'][$i++] = $row;
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
                           `AppOrder`,
                           `AppColor`
                    
                    FROM `App`
                    
                    WHERE `AppStatus` = ?
                    
                    ORDER BY AppOrder
            ";
                    
            $result = $this->db->query($query, [$status])->result_array();
            
            $data = array();
            
            if(!empty($result))
            {
                $i=0;
                foreach($result as $row)
                {
                    $data['Applications'][$i++] = $row;
                }
            }
            return $data;
            
        }
        
        public function addNewApplication($appName, $appLink, $appIcon, $appColor)
        {
            $query = "
                    INSERT INTO `App`(`AppName`, `AppLink`, `AppIcon`, `AppOrder`, `AppColor`)
                    
                    VALUES (?,?,?,0,?)
            ";
                    
            $result = $this->db->query($query, [$appName, $appLink, $appIcon, $appColor]);
        }
        
        public function editApplication($appName, $appLink, $appIcon, $appColor, $idApp)
        {
            
            
            $query = "
                    UPDATE      `App`
                    
                    SET         `AppName` = '".$appName."', 
                                `AppLink` = '".$appLink."', 
                                `AppIcon` = '".$appIcon."', 
                                `AppColor` = '".$appColor."'
                    
                    WHERE       `IdApp` = ".$idApp."
            ";
            
            $result = $this->db->query($query);
        }
        
        public function deleteApplication($idApp)
        {
            $query = "
                    DELETE 
                    
                    FROM `App`
                    
                    WHERE `IdApp` = ?
                    ";
            
            $result = $this->db->query($query, [$idApp]);
        }
}

