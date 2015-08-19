<?php

/*
 *  @author Darko Lesendric <dlesendric at https://github.com/ @Darko_Lesendric at https://twitter.com/>
 */

/**
 * Description of CloudFiles
 *
 * @author Darko
 */

class CloudFiles extends MY_Controller{
    protected $current_dir;
    protected $parrent_dir;
    public function __construct() {
        parent::__construct();
        $this->load->model('FileModel');
    }

    
    public function index(){

        
        
    }
    protected function initialize() {
        $current_dir = (isset($_GET['current_dir']))? intval($_GET['current_dir']) : NULL;
        $this->current_dir = '';
        $this->parrent_dir = '';
        if($current_dir!=0){
            $this->load->model("FileModel");
            $result = $this->FileModel->getFolder($this->get_user_id(),$current_dir);
            $this->current_dir = $result->FileName;
            if(is_null($result->IdFolder)||$result->IdFolder===0){
                $this->parrent_dir = $result->IdFolder; 
            }
        }
        parent::initialize();
    }
    protected function handle_form_data($file, $index) {
        $file->IdFolder = $this->current_dir;
        $file->FileExtension = pathinfo($file->name, PATHINFO_EXTENSION);
    }
    protected function handle_file_upload($uploaded_file, $name, $size, $type, $error, $index = null, $content_range = null) {
        $file=parent::handle_file_upload($uploaded_file, $name, $size, $type, $error, $index, $content_range);
        //public function insertUserFile($IdUser, $IdFileType, $IdFolder, $FileExtension, $FileName, $FileSize)
        $this->load->model('FileModel');
        $file->filetype=  $this->FileModel->getFileType($file->type);
        $file->FileLastModified = time();
        if(!$file->chunk){
            $file->IdFile=$this->FileModel->insertUserFile($this->get_user_id(),$file->filetype,$file->IdFolder,$file->FileExtension,$file->name,$file->size);
        }
        
        return $file;
    }
    /**
     * 
     * @param type $file
     */
    protected function set_additional_file_properties($file) {
        parent::set_additional_file_properties($file);
        if($_SERVER['REQUEST_METHOD']==='GET'){
//            //public function getAllFilesInFolder($IdUser, $IdFolder=NULL)
//            $this->load->model("FileModel");
//            $result = $this->FileModel->getAllFilesInFolder($this->get_user_id());
//            $i = 0;
//            foreach($file as $key=>$val){
//                var_dump($key);
//                var_dump($file->$key);
//            }
        if(isset($_GET['current_dir'])){
            $file->deleteUrl.='&current_dir='.$_GET['current_dir'];   
        }
        
            
            
        }
    }
    /**
     * overvrajtovana metoda zbog bug-a sa svim id-jevima 
     * nije idealno resenje, moguce usporavanje baze
     * trebalo bi po nekom pravilu sve file object-e da povezem sa nizom u tom folderu, ali to je nemoguce
     * @param type $file_name
     * @return type
     */
    protected function get_file_object($file_name) {
        $file = parent::get_file_object($file_name);
        $this->load->model("FileModel");
        $result = $this->FileModel->getFile($this->get_user_id(),$file_name);
        if(count($result)>0){
            $file->IdFile=$result[0]["IdFile"];
            $file->IdFolder = $this->current_dir;
            $file->FileExtension = $result[0]["FileExtension"];
            $file->FileCreated = $result[0]["FileCreated"];
            $file->FileLastModified = $result[0]["FileLastModified"]; 
            $file->FileTypeMime = $result[0]["FileTypeMime"];
            $file->deleteUrl.='&IdFile='.$file->IdFile;
        }

        return $file;
    }
    
    public function delete($print_response = true,$currentdir='') {
        if(isset($_GET['IdFile'])){
            $IdFile = intval($_GET['IdFile']);
        }
        if($currentdir==''){
            $currentdir =  $this->current_dir;
        }

        $response = parent::delete(false);
        
        
        foreach ($response as $name => $deleted) {
            if ($deleted&&isset($IdFile)) {
                $this->load->model('FileModel');
                $this->FileModel->deleteFile($IdFile);
            }
        } 
        return $this->generate_response($response, $print_response);
    }
    
    protected function newFolder() {
        $newFolder = $this->createDir();
        $current_dir= (isset($_GET['current_dir']))? $_GET['current_dir'] : null;
        if($newFolder!=FALSE){
            $this->load->model("FileModel");
            $this->FileModel->insertUserFile($this->get_user_id(),1,$current_dir,null,$newFolder,0); 
            unset($_GET['folder_name']);
            $this->initialize();
        }
    }
    
    /**
     * Novi folder
     * new folder
     */
    public function createDir(){
        //current user dir
        $filepath = $this->options['upload_dir'].$this->get_user_path();
        $folder_name = '';
        if(isset($_GET['folder_name'])){
            $folder_name = trim($_GET['folder_name']);
        }
        if($folder_name!=''){
            if(file_exists($filepath.$folder_name)){
                $folder_name= $this->upcount_name($folder_name);
            }
            //var_dump($filepath.$folder_name);
            if(mkdir($filepath.$folder_name,  $this->options['mkdir_mode'])){
                return $folder_name;
            }
            
        }
        
        return FALSE;
      
    }


//    protected function get_upload_path($file_name = null, $version = null) {
//        $separator = '';
//        if(!is_null($version)){
//            $separator = '/';
//        }
//        return parent::get_upload_path($file_name, $this->current_dir.$separator.$version);
//    }
    
//    protected function get_download_url($file_name, $version = null, $direct = false) {
//        $separator = '';
//        if($this->current_dir!=''){
//            $separator = '/';
//        }
//        return rawurldecode(parent::get_download_url($file_name, $this->current_dir.$separator.$version, $direct));
//    }
    
    protected function get_user_path() {
        $user_real_path = parent::get_user_path();
        if($this->current_dir!=''){
            $path = realpath(realpath($this->options['upload_dir'].$user_real_path));
            $objects = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($path), RecursiveIteratorIterator::SELF_FIRST);
            foreach ($objects as $name=>$obj){
                $info = new SplFileInfo($name);
                if($info->getFilename()===$this->current_dir){
                    $winpath =str_replace(realpath($this->options['upload_dir']), '', $name);
                    return str_replace('\\', '/', $winpath).'/';
                }
            }
        }
        return $user_real_path;
        
    }
    
    

    
    
    
    
    
}
