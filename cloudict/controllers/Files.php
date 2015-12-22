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

    private $msg = '';

    public function __construct() {
        $this->class_name = get_class($this);
        parent::__construct();
        if(isset($_GET['msg_type'])){
            $this->msg = $this->generate_alert($_GET['msg_type'], $_GET['msg']);
        }
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
        $data['user_groups'] = $user_group;
        if(!empty($this->msg)){
            $data['msg']=  $this->msg;
        }
        $this->load_view('filesView', $data);
    }
    public function favourites(){
        $data = array();
//        $mask = $this->get_mask($this->class_name,  $this->uri->uri_string());
//        $data['mask'] = $mask;
        
        $data['menu'] = $this->getMenu(1);
        $user_group = $this->session->userdata('group');
        $data['user_groups'] = $user_group;
        if(!empty($this->msg)){
            $data['msg']=  $this->msg;
        }
        $this->load_view('favouritesView', $data);
    }
    public function shared_with_you($IdFolder=0){
        
        $data = array();
//        $mask = $this->get_mask($this->class_name,  $this->uri->uri_string());
//        $data['mask'] = $mask;
        $data['current_dir']=$IdFolder;
        $data['menu'] = $this->getMenu(2);
        $user_group = $this->session->userdata('group');
        $data['user_groups'] = $user_group;
        if(!empty($this->msg)){
            $data['msg']=  $this->msg;
        }
        $this->load_view('sharedWithYouView', $data);
        
    }
    
    public function shared_with_others($IdFolder=0){
        $this->load->model("ShareModel");
        $data = array();
//        $mask = $this->get_mask($this->class_name,  $this->uri->uri_string());
//        $data['mask'] = $mask;
        $data['current_dir']=$IdFolder;
        $data['menu'] = $this->getMenu(3);
        $user_group = $this->session->userdata('group');
        $data['user_groups'] = $user_group;
        if(!empty($this->msg)){
            $data['msg']=  $this->msg;
        }
        $this->load_view('sharedWithOthersView', $data);
    }
    
    public function shared_by_link(){
        var_dump("Sta ovde ...?");
    }




    /**
     * get position of user current folder
     * @param type $mask -> Is mask for user dir exp: (upload_dir/user_dir/MASK IS FROM THIS POSITION )
     * @return int
     */
    protected function get_current_dir($mask){
        $this->load->model("FolderModel");
        $filepath = substr(strtolower($this->get_upload_dir().$mask),0,-1);
        $result = $this->FolderModel->getFolder($this->get_user_id(),$filepath);
        if($result){
            return $result->IdFolder;
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
    
    protected function generate_alert($msg_type,$msg){
    $tekst = '';
    $tekst.= '<div class="alert alert-'.$msg_type.' alert-dismissible" role="alert">';
    $tekst.= '<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>';
    $tekst.= $msg;
    $tekst.= "</div>";
     return $tekst;  
    }
    
    public function edit($IdFile){
        $this->load->model("FileModel");
        $file = $this->FileModel->getFileById($IdFile);
        $data['title'] = $file->FileName;
        
        if(file_exists($file->FilePath)){
            $content = file_get_contents($file->FilePath);
            $data['content'] = $content;
            $data['filePath'] = $file->FilePath;
            $data['IdFile']=$IdFile;
        }
        else{
            $data['error'] = "File not found!";
        }
        $this->load->view("editor",$data);
    }

}
