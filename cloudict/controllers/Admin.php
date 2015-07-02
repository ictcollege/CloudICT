<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Logins
 *
 * @author Jericho
 */
class Admin extends CI_Controller {
    //put your code here
    public function index(){
        //helpers
        $this->load->helper('url');
        $this->load->helper('form');
        
        //variables
        $base_url = base_url();
            
        //data to view
        $data['base_url']= $base_url;
        $data['title'] = "ICT Cloud | Admin | Applications";
        $data['admin'] = true;
            //data for form
            
        
        //views
        $this->load->view('header', $data);
        $this->load->view('applications', $data);
    }
    
    public function allfiles()
    {
        //helpers
        $this->load->helper('url');
        $this->load->helper('form');
        
        //variables
        $base_url = base_url();
        
        //data to view
        $data['base_url']= $base_url;
        $data['title'] = "ICT Cloud | Admin | All Files";
        
        //views
        $this->load->view('header', $data);
        $this->load->view('menu', $data);
        $this->load->view('maincontent', $data);
    }
    
    public function newuser()
    {
        //helpers
        $this->load->helper('url');
        $this->load->helper('form');
        
        //variables
        $base_url = base_url();
            //form elements
            $form_attr = array(
                "id" => "formRegister",
                "role" => "form"  
            );
            
            $email_attr = array(
                "class" => "form-control tbEmail" ,
                "placeholder" => "Email" ,
                "name" => "Email" ,
                "autofocus" => "autofocus"
            );
        
            $key_attr = array(
                "class" => "form-control tbKey disabled" ,
                "placeholder" => "Generated Key" ,
                "name" => "key",
                "readonly" => "true"
            );
        
        //data to view
        $data['base_url']= $base_url;
        $data['title'] = "ICT Cloud | Admin | New User";
             //data for form
            $data['form_attr'] = $form_attr;
            $data['email_attr'] = $email_attr;
            $data['key_attr'] = $key_attr;
        
        //views
        $this->load->view('header', $data);
        $this->load->view('menu', $data);
        $this->load->view('newuser', $data);
    }
}
