<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/*
 *  @author Darko Lesendric <dlesendric at https://github.com/ @Darko_Lesendric at https://twitter.com/>
 */

/**
 * Description of Fileupload
 *
 * @author Darko
 */
class Files extends Frontend_Controller{


    public function __construct() {
        $this->class_name = get_class($this);
        parent::__construct();
    }
    
    function index(){
        $data = array();
        $upload_dir = $this->get_upload_dir();
        $mask = $this->get_mask($this->class_name,  $this->uri->uri_string());
        // 0 is root user mask
        $data['current_path'] = $mask;
        $data['current_dir'] = (empty($mask)) ? 0 : $this->get_current_dir($mask);
        
        /*
         * Lodovanje menija /Jericho 
         */
        $this->load->model('MenuModel');
        
        $menu = $this->MenuModel->getMenuOfApplication(2);
        
        $data['menu'] = "";
        
        foreach($menu['Menu'] as $m)
        {
            $data['menu'] .= '<li>';
            $data['menu'] .= '<a href="'.$m['AppMenuLink'].'"><i class="fa '.$m['AppMenuIcon'].' fa-fw"></i> '.$m['AppMenuName'].'</a>';
            $data['menu'] .= '</li>';
        }
        
        $this->load_view('filesView', $data);
    }
    
    protected function get_current_dir($mask){
        $this->load->model("FileModel");
        $filepath = $this->get_upload_dir().$mask;
        $result = $this->FileModel->getFolder($this->get_user_id(),$filepath);
        if($result){
            return $result->IdFile;
        }
        else{
            return 0;
        }
    }

}
