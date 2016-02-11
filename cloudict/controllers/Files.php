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
        $data['current_dir'] = (empty($mask)) ? NULL : $this->get_current_dir($mask);
        $data['breadcrumbs'] = $this->breadcrumbs($mask);
        $data['menu'] = $this->getMenu();
        $user_group = $this->session->userdata('group');
        $data['user_groups'] = $user_group;
        if(!empty($this->msg)){
            $data['msg']=  $this->msg;
        }
        $diskquota = $this->session->userdata('diskquota');
        $diskused = $this->session->userdata('diskused');
        $diskremain = $diskquota - $diskused;
        $percentage = round(($diskused/$diskquota) * 100);
        $data['diskused'] = $this->formatBytes($diskused);
        $data['diskremain'] = $this->formatBytes($diskremain);
        $data['diskquota'] = $this->formatBytes($diskquota);
        $data['percentage'] = ($percentage>100) ? 100 : $percentage;
        $data['base_url'] = base_url();
        $this->load_view('filesView', $data);
    }
    /**
     * favourites view and data
     */
    public function favourites(){
        $data = array();
        $data['menu'] = $this->getMenu();
        $user_group = $this->session->userdata('group');
        $data['user_groups'] = $user_group;
        if(!empty($this->msg)){
            $data['msg']=  $this->msg;
        }
        $this->load_view('favouritesView', $data);
    }
    /**
     * contorller to view all shared files with you
     * @param type $IdFolders - int Shared folder to view
     */
    public function shared_with_you($IdFolder=0){
        $data = array();
        $data['current_dir']=$IdFolder;
        $data['menu'] = $this->getMenu();
        $user_group = $this->session->userdata('group');
        $data['user_groups'] = $user_group;
        if(!empty($this->msg)){
            $data['msg']=  $this->msg;
        }
        $this->load_view('sharedWithYouView', $data);
    }
    /**
     * all data and view for shared with others
     * @param type $IdFolder - int folder to view
     * @param type $IdShared - int from which user
     */
    public function shared_with_others($IdFolder=0,$IdShared=""){
        $data = array();
        $data['current_dir']=$IdFolder;
        $data['id_shared']=$IdShared;
        $data['menu'] = $this->getMenu();
        $user_group = $this->session->userdata('group');
        $data['user_groups'] = $user_group;
        if(!empty($this->msg)){
            $data['msg']=  $this->msg;
        }
        $this->load_view('sharedWithOthersView', $data);
    }
    //shared by link
    public function shared_by_link(){
        $data = array();
        $data['menu'] = $this->getMenu();
        $user_group = $this->session->userdata('group');
        $data['user_groups'] = $user_group;
        $this->load_view('sharedByLinkView', $data);
    }




    /**
     * ovo je mozda prva funkcija koja bi trebala da se promeni ako baza krene dugo da trazi po folderpathu ili dodati index na folderpath
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
    //get menu app
    protected function getMenu(){
        $this->load->model('MenuModel');
        $menuItems = $this->MenuModel->getMenuOfApplication(2);
        $html = "";
        foreach($menuItems['Menu'] as $item)
        {
            $html .= '<li>';
            $html .= '<a href="'.$item['AppMenuLink'].'"><i class="fa '.$item['AppMenuIcon'].' fa-fw"></i> '.$item['AppMenuName'].'</a>';
            $html.= '</li>';
        }
        return $html;
    }
    //depiracted but usefull
    protected function generate_alert($msg_type,$msg){
    $tekst = '';
    $tekst.= '<div class="alert alert-'.$msg_type.' alert-dismissible" role="alert">';
    $tekst.= '<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>';
    $tekst.= $msg;
    $tekst.= "</div>";
     return $tekst;  
    }
    /*
     * editing files, permissions etc.
     */
    public function edit($IdFile){
        $this->load->model("FileModel");
        $file = $this->FileModel->getFileById($IdFile);
        $data['title'] = $file->FileName;
        if($file->IdUser==$this->get_user_id()){
            //user is owner of file
            $data['can_edit'] = true;
            
        }
        else{
            //check if file is shared with user
            $this->load->model("ShareModel");
            $permission=$this->ShareModel->canEdit($this->get_user_id(),$file->IdFile);
            if($permission==false){
                die("YOU DON'T HAVE PERMISSION TO EDIT THIS FILE!");
                exit();
            }
        }
        require 'ApiFiles.php';
        if(!in_array($file->FileExtension, ApiFiles::$editableFileTypes)){
            $data['can_edit']=false;
            $data['error'] = "File can't be edit, uneditable file type!";
        }
        if(file_exists($file->FilePath)){
            $content = file_get_contents($file->FilePath);
            $data['content'] = htmlspecialchars($content);
            $data['filePath'] = $file->FilePath;
            $data['IdFile']=$IdFile;
        }
        else{
            $data['error'] = "File not found!";
        }
        $this->load->view("editor",$data);
    }
    /**
     * notification
     * @param type $IdEvent
     */
    public function HandleNotification($IdEvent=0){
        $data['menu'] = $this->getMenu();
        if(empty($IdEvent)){
            //info
            $this->msg = $this->generate_alert('danger', 'Quota Exceeded');
            $this->index();
        }
        else{
            $this->load->model("ShareModel");
            $result=$this->ShareModel->getShareById($IdEvent);
            if(empty($result)){
                $this->msg = $this->generate_alert('danger', 'User already removed file/folder from share!');
                $this->shared_with_you();
            }
            else{
                $type = (!empty($result->IdFile))? "File" : "Folder";
                $msg=$type." name:".$result->Name."</br><input type='hidden' class='pointer' value='".$result->IdShare."'/>";
                $this->msg = $this->generate_alert('info', $msg);
                if(!empty($result->IdFolder)){
                    $this->shared_with_you($result->IdFolder);
                }
                else{
                    $this->shared_with_you();
                }
            }
            
            
        }
    }

}
