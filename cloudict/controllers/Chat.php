<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Chat extends CI_Controller {


    public function __construct()
    {
        parent::__construct();
        // Your own constructor code

        /**
         * check if session is set
         */
        if(!$this->session->has_userdata('username'))
        {
            redirect('/');
        }

        $this->load->model('usermodel');
        $this->load->model('userlogmodel');
        $this->load->helper('url');
    }

    public function index(){

        $UsersIds=$this->userlogmodel->getUsersLoggedIn();

        $Users = array();
        foreach($UsersIds as $UserId)
        {
            $Users[]  = $this->usermodel->getUserById($UserId->IdUser);

        }

        $data["Users"] = $Users;

        $this->load->view('chat',$data);
    }

    /**Get messages for logged in user and chat user
     *
     * @param null $IdUser
     */
    public function getMessages($IdUser=null){
        //getting data for chat user
        if($IdUser==null)
        {
            $UserName = $this->input->post('username');
            $User=$this->usermodel->getUserByName($UserName);

            $IdUser=$User->Id;
        }
//        var_dump($this->session->userdata('id'));exit;

        $this->load->model('chatmessagemodel');
        //getting all messages sent between logged user and chat user
        $Messages = $this->chatmessagemodel->getMessages($this->session->userdata('id'),$IdUser);
//
        $data = array(
            'Messages' => $Messages
        );

        $this->load->view('messages',$data);

    }

    /**
     * insert new message
     */
    public function message(){

        $TextMessage = $this->input->post('text');
        $ReceiverName  = $this->input->post('username');

        $Sender     = $this->usermodel->getUserById($this->session->userdata('id'));
        $Receiver   = $this->usermodel->getUserByName($ReceiverName);

        $this->load->model('chatmessagemodel');
        $this->chatmessagemodel->insertMessage($Sender->Id,$Receiver->Id,$TextMessage,$Sender->User,$Receiver->User);

        $this->getMessages($Receiver->Id);

    }


}