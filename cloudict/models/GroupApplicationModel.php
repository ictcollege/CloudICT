<<<<<<< HEAD
<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class GroupApplicationModel extends CI_Model{
/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
        public function getGroupApplications()
        {
            $query = " 
                SELECT 	`IdGroup`,
                        `GroupName`,
                        `IdApp`,
                        `AppName`,
                        `AppIcon`,
                        `AppColor`

			FROM	`Group`

			JOIN	`GroupApp`
			USING	(`IdGroup`)

			JOIN `App`
                        USING (`IdApp`)
            
            ";
            
        
            $result = $this->db->query($query)->result_array();
            
            $data = array();
            
            if(!empty($result))
            {
                $i=0;
                foreach($result as $row)
                {
                    $data['GroupApplications'][$i++] = $row;
                }
            }
            
            return $data;
        }
        
        public function removeApplicationForGroup($idApp, $idGroup)
        {
            $query = " 
                DELETE
                
                FROM `GroupApp`
                
                WHERE `IdGroup` = ? 
                
                AND `IdApp` = ?
            ";
            
        
            $result = $this->db->query($query, [$idGroup, $idApp]);
        }
        
        
        public function addApplicationForGroup($idApp, $idGroup)
        {
            $query = " 
                INSERT INTO     `GroupApp`(`IdGroup`,`IdApp`)
                
                VALUES          (?, ?)
            ";
            
        
            $result = $this->db->query($query, [$idGroup, $idApp]);
        }
        
        public function getApplicationsMenu($idApp)
        {
            $query = " 
                SELECT 	*

                FROM	`AppMenu`

                WHERE `IdApp` = ?
            
            ";
            
        
            $result = $this->db->query($query, [$idApp])->result_array();
            
            $data = array();
            
            if(!empty($result))
            {
                $i=0;
                foreach($result as $row)
                {
                    $data['ApplicationMenu'][$i++] = $row;
                }
            }
            
            return $data;
        }
=======
<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class GroupApplicationModel extends CI_Model{
/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
        public function getGroupApplications()
        {
            $query = " 
                SELECT 	`IdGroup`,
                        `GroupName`,
                        `IdApp`,
                        `AppName`,
                        `AppIcon`,
                        `AppColor`

			FROM	`Group`

			JOIN	`GroupApp`
			USING	(`IdGroup`)

			JOIN `App`
                        USING (`IdApp`)
            
            ";
            
        
            $result = $this->db->query($query)->result_array();
            
            $data = array();
            
            if(!empty($result))
            {
                $i=0;
                foreach($result as $row)
                {
                    $data['GroupApplications'][$i++] = $row;
                }
            }
            
            return $data;
        }
        
        public function removeApplicationForGroup($idApp, $idGroup)
        {
            $query = " 
                DELETE
                
                FROM `GroupApp`
                
                WHERE `IdGroup` = ? 
                
                AND `IdApp` = ?
            ";
            
        
            $result = $this->db->query($query, array($idGroup, $idApp));
        }
        
        
        public function addApplicationForGroup($idApp, $idGroup)
        {
            $query = " 
                INSERT INTO     `GroupApp`(`IdGroup`,`IdApp`)
                
                VALUES          (?, ?)
            ";
            
        
            $result = $this->db->query($query, array($idGroup, $idApp));
        }
        
        public function getApplicationsMenu($idApp)
        {
            $query = " 
                SELECT 	*

                FROM	`AppMenu`

                WHERE `IdApp` = ?
            
            ";
            
        
            $result = $this->db->query($query, array($idApp))->result_array();
            
            $data = array();
            
            if(!empty($result))
            {
                $i=0;
                foreach($result as $row)
                {
                    $data['ApplicationMenu'][$i++] = $row;
                }
            }
            
            return $data;
        }
>>>>>>> master
}