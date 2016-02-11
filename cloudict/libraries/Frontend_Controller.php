<<<<<<< HEAD
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
    const USERS_UPLOAD_DIR = 'C:/xampp/htdocs/CloudICT/data/'; //main upload_dir exp /srv/uploads/ or C:/xampp/htdocs/CloudFiles/
    public $class_name;
    public function __construct() {
        $this->class_name = get_class($this);
        parent::__construct();
        if(!$this->isLogged()){
            header('location:'.base_url());
            exit();
           //redirect('Users'); //CI_version
        }
        if(!file_exists(self::USERS_UPLOAD_DIR)){
            mkdir(self::USERS_UPLOAD_DIR);
        }
    }
    
    protected function get_upload_dir() {
        $userpath = self::USERS_UPLOAD_DIR.$this->get_user_id().'/';
        if(!file_exists($userpath)){
            mkdir($userpath);
        }
        
        return $userpath;
    }
    
    protected function get_server_var($id) {
        return isset($_SERVER[$id]) ? $_SERVER[$id] : '';
    }
    protected function get_user_id(){
        return $this->session->userdata('userid');
    }
    


    
    protected function get_mask($class_name='',$uri_string=''){
        $class_name = strtolower($class_name);
        $uri_string = strtolower($uri_string);
        if(empty($class_name)){
            $class_name= strtolower($this->class_name);
        }
        if(empty($uri_string)){
            $uri_string = strtolower($this->uri->uri_string());
        }
        $split = explode('/', $uri_string);
        $upload_path = '';
        foreach($split as $segment){
            if($segment != $class_name && $segment!=strtolower($this->uri->segment(2))){
                $upload_path.=$segment.'/';
            }
        }
        return $upload_path;
    }
    

    
}
=======
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
    //const USERS_UPLOAD_DIR = 'C:/xampp/htdocs/CloudICT/data/'; //main upload_dir exp /srv/uploads/ or C:/xampp/htdocs/cloud_project/data/
    const USERS_UPLOAD_DIR = 'C:/xampp/htdocs/cloud_project/data/';
    public $class_name;
    public function __construct() {
        $this->class_name = get_class($this);    
        parent::__construct();
        if(!$this->isLogged()){
            header('location:'.base_url());
            exit();
           //redirect('Users'); //CI_version
        }
        if(!file_exists(self::USERS_UPLOAD_DIR)){
            mkdir(self::USERS_UPLOAD_DIR);
        }
        $this->get_upload_dir();
    }
    
    protected function get_upload_dir() {
        $userpath = self::USERS_UPLOAD_DIR.$this->get_user_id().'/';
        if(!file_exists($userpath)){
            mkdir($userpath);
        }
        
        return $userpath;
    }
    
    protected function get_server_var($id) {
        return isset($_SERVER[$id]) ? $_SERVER[$id] : '';
    }
    protected function get_user_id(){
        return $this->session->userdata('userid');
    }
    


    
    protected function get_mask($class_name='',$uri_string=''){
        $class_name = strtolower($class_name);
        $uri_string = strtolower($uri_string);
        if(empty($class_name)){
            $class_name= strtolower($this->class_name);
        }
        if(empty($uri_string)){
            $uri_string = strtolower($this->uri->uri_string());
        }
        $split = explode('/', $uri_string);
        $upload_path = '';
        foreach($split as $segment){
            if($segment != $class_name && $segment!=strtolower($this->uri->segment(2))){
                $upload_path.=$segment.'/';
            }
        }
        return $upload_path;
    }
    
    
    

    
}
>>>>>>> master
