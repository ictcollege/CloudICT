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
    /**
     * Default action for opening files controller
     */
    function index(){
        $data = array();
        $mask = $this->get_mask($this->class_name,  $this->uri->uri_string());
        $data['current_path'] = $mask;
        $data['current_dir'] = (empty($mask)) ? 0 : $this->get_current_dir($mask);
        $data['breadcrumbs'] = $this->breadcrumbs($mask);
        $data['menu'] = $this->getMenu(0);
        $user_group = $this->session->userdata('group');
        $this->load->model("UserModel");
        $data['user_groups'] = $user_group;
        $this->load_view('filesView', $data);
    }
    public function favourites(){
        var_dump("Mora se doda tabela u bazi!");
    }
    public function shared_with_you(){
        var_dump("Nije zavrseno!");
    }
    
    public function shared_with_others(){
        var_dump("Nije zavrseno!");
    }
    
    public function shared_by_link(){
        var_dump("Sta ovde bogte?");
    }




    /**
     * get position of user current folder
     * @param type $mask -> Is mask for user dir exp: (upload_dir/user_dir/MASK IS FROM THIS POSITION )
     * @return int
     */
    protected function get_current_dir($mask){
        $this->load->model("FileModel");
        $filepath = substr(strtolower($this->get_upload_dir().$mask),0,-1);
        $result = $this->FileModel->getFolder($this->get_user_id(),$filepath);
        if($result){
            return $result->IdFile;
        }
        else{
            return 0;
        }
    }
    /**
     * creates breadcrumbs for user position so he can go up one level in folder three
     * @param type $mask
     * @return type
     */
    protected function breadcrumbs($mask){
        $bread = explode('/', $mask);
        $breadcrumbs = array();
        foreach($bread as $crumbs){
            if(!empty($crumbs)){
                $breadcrumbs[] = $crumbs;
            }
        }
        return $breadcrumbs;
    }
    
    protected function getMenu($active_link){
        $this->load->model('MenuModel');

        /*
         * Lodovanje menija /Jericho 
         */
        $this->load->model('MenuModel');
        
        $menu = $this->MenuModel->getMenuOfApplication(2);
        
        $html = "";
        $i = 0;
        
        foreach($menu['Menu'] as $m)
        {
            
            $html .= '<li>';
            if($i==$active_link){
                $active = " class = 'active' ";
            }
            else{
                $active = '';
            }
            $html .= '<a href="'.$m['AppMenuLink'].'" '.$active.'><i class="fa '.$m['AppMenuIcon'].' fa-fw"></i> '.$m['AppMenuName'].'</a>';
            $html .= '</li>';
            $i++;
        }
        
        return $html;
    }

}
