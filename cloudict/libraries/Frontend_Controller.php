<?php

/*
 *  @author Darko Lesendric <dlesendric at https://github.com/ @Darko_Lesendric at https://twitter.com/>
 */

/**
 * Description of Frontend_Controller
 *
 * @author Darko
 */
class Frontend_Controller extends MY_Controller{
    const USERS_UPLOAD_DIR = 'data'; //main upload_dir

    public function __construct() {
        parent::__construct();
        if(!$this->isLogged()){
            header('location:'.base_url());
            exit();
           //redirect('Users'); //CI_version
        }
    }
    
    protected function get_user_path() {
        $dirname = dirname($this->get_server_var('SCRIPT_FILENAME')).'/'.self::USERS_UPLOAD_DIR.'/'.$this->get_user_id().'/';
        if(!file_exists($dirname)){
            mkdir($dirname);
        }
        
        return $dirname;
    }
    
    protected function get_server_var($id) {
        return isset($_SERVER[$id]) ? $_SERVER[$id] : '';
    }
    protected function get_user_id(){
        return $this->session->userdata('userid');
    }
    
    protected function get_upload_url(){
        return base_url().'/'.self::USERS_UPLOAD_DIR.'/'.$this->get_user_id();
    }
}
