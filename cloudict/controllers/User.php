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
class User extends CI_Controller {
    //put your code here
    public function index(){
        //helpers
        $this->load->helper('url');

        $this->load->view('login');
    }

    public function login()
    {
        $username = $this->input->post('Username');
        $password = $this->input->post('Password');

        $this->load->model('UserModel');
        $this->load->model('UserLogModel');
        $this->load->helper('url');

        $user = $this->UserModel->getUser($username, md5($password));

        if(count($user["User"]) != 0)
        {
            $idrole = $user["User"]["IdRole"];
            $username = $user["User"]["User"];
            $id = $user["User"]["Id"];

            $groups = $this->UserModel->getAllUsersInGroups($user["User"]["Id"]);

            $session= array(
                'username' => $username,
                'role' => $idrole,
                'group' => $groups,
                'id' => $id
            );

            $this->session->set_userdata($session);

            $this->UserLogModel->login($user["User"]["Id"]);

            redirect('/chat');
        }

        redirect('/');
    }
}