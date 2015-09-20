<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Chat extends MY_Controller {


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

		// $this->load->model('usermodel');
		// $this->load->model('userlogmodel');
		$this->load->helper('url');
	}

	public function index(){


	   $this->load->helper('url');
		$this->load->helper('form');
		
		//variables
		$base_url = base_url();

		 //model
		$this->load->model('MenuModel');
		$this->load->model("UserModel");

		 $menu = $this->MenuModel->getMenuOfApplication(5);
		if($menu!=null){
			$data['menu'] = "";

			foreach($menu['Menu'] as $m)
			{
				$data['menu'] .= '<li>';
				$data['menu'] .= '<a href="'.$m['AppMenuLink'].'"><i class="fa '.$m['AppMenuIcon'].' fa-fw"></i> '.$m['AppMenuName'].'</a>';
				$data['menu'] .= '</li>';
			}
		
		}
		$users = $this->UserModel->getAllUsers();
		// var_dump($users);exit();
		$data['base_url']=$base_url;
		$data['count']="1";
		$data['Users']=$users;
		$data['CurrentUserId']=$this->session->userdata('userid');
		$data['Messages']=$this->getGroupMessages(1);
		$this->load_view('chat',$data);
	}

	/**Get messages for logged in user and chat user
	 *
	 * @param null $IdUser
	 */
	public function getMessages($IdUser=null){
		//getting data for chat user
		if($IdUser==null)
		{
			$IdUser = $this->input->post('IdUser');
		}

		$this->load->model('chatmessagemodel');
		//getting all messages sent between logged user and chat user
		$Messages = $this->chatmessagemodel->getMessages($this->session->userdata('userid'),$IdUser);
//      
		$data = array(
			'Messages' => $Messages
		);

		$this->load->view('messages',$data);

	}

	/**
	 * get group messages
	 * @param  int  $IdGroup 
	 * @return array          group messages
	 */
	public function getGroupMessages($IdGroup=null){
		$this->load->model('chatmessagemodel');
		 return  $this->chatmessagemodel->getGroupMessages(1);
	}


	public function showGroupMessages($IdGroup=null){
		$Messages 	=	$this->getGroupMessages(1);
		$data 		= array(
			'Messages' 	=>	 $Messages
		);

		$this->load->view('messages',$data);
	}


	

	/**
	 * insert new message
	 */
	public function message(){

		$TextMessage = $this->input->post('text');
		$ReceiverId  = $this->input->post('IdUser');

		$this->load->model("UserModel");

		$Sender     = $this->UserModel->getUserById($this->session->userdata('userid'));
		$Receiver   = $this->UserModel->getUserById($ReceiverId);

		$this->load->model('chatmessagemodel');
		$this->chatmessagemodel->insertMessage(NULL,$Sender['0']['IdUser'],$Receiver['0']['IdUser'],$TextMessage,$Sender['0']['UserName'],$Receiver['0']['UserName']);

		$this->getMessages($Receiver['0']['IdUser']);

	}
	/**
	 * insert new message
	 */
	public function GroupMessage($IdGroup=null){

		$TextMessage = $this->input->post('text');

		$this->load->model("UserModel");

		$Sender     = $this->UserModel->getUserById($this->session->userdata('userid'));

		$this->load->model('chatmessagemodel');
		$this->chatmessagemodel->insertMessage(1,$Sender['0']['IdUser'],$Sender['0']['IdUser'],$TextMessage,$Sender['0']['UserName'],$Sender['0']['UserName']);

		$this->showGroupMessages(1);

	}


}