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
                SELECT	`IdGroup`,
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
}