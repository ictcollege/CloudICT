<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/*
 *  @author Darko Lesendric <dlesendric at https://github.com/ @Darko_Lesendric at https://twitter.com/>
 */

/**
 * Description of CloudFiles
 *
 * @author Darko
 */

class CloudFiles extends File_Controller{
    private $IdFolder=0;
    private $Mask;
    public function __construct() {
        $this->class_name = get_class($this);
        parent::__construct();
        $this->load->model('FileModel');
    }

    
    public function index(){
        
    }
    protected function initialize() {
        if(isset($_POST['action'])){
            switch ($_POST['action']){
                case 'newFolder': $this->newFolder();
            }
        }
        if(isset($_POST['IdFolder'])){
            $this->IdFolder = intval($_POST['IdFolder']);
        }
        if(isset($_POST['Mask'])){
            $this->Mask = $_POST['Mask'];
        }
        parent::initialize();
    }
    protected function handle_form_data($file, $index) {
        $file->IdFolder = $this->IdFolder;
        $file->Mask = $this->Mask;
        $file->FileExtension = pathinfo($file->name, PATHINFO_EXTENSION);
    }
    protected function handle_file_upload($uploaded_file, $name, $size, $type, $error, $index = null, $content_range = null) {
        $file=parent::handle_file_upload($uploaded_file, $name, $size, $type, $error, $index, $content_range);
        //public function insertUserFile($IdUser, $IdFileType, $IdFolder, $FileExtension, $FileName, $FileSize)
        $this->load->model('FileModel');
        $file->filetype=  $this->FileModel->getFileType($file->type);
        $file->FileLastModified = time();
        
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
        $result = $this->FileModel->getFile($this->get_user_id(),$file_name,  $this->get_upload_path());
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

        $response = parent::delete(false);
        
        
        foreach ($response as $name => $deleted) {
            if ($deleted&&isset($IdFile)) {
                $this->load->model('FileModel');
                $this->FileModel->deleteFile($IdFile);
            }
        } 
        return $this->generate_response($response, $print_response);
    }
    
    public function newFolder() {
        $newFolder = $this->createDir();
        if($newFolder!=FALSE){
            $split = explode('/', $newFolder);
            $folderName = end($split);
            $result = $this->FileModel->getFolder($this->get_user_id(), $this->IdFolder);
            $parrent_dir = 0;
            if($result){
                $parrent_dir = $result->IdFile;
            }
            $this->FileModel->insertUserFile($this->get_user_id(),1,$parrent_dir,null,$folderName,$newFolder,0); 
            
        }
    }
    
    /**
     * Novi folder
     * new folder
     */
    protected function createDir(){
        $filepath = $this->get_upload_path();
        if(file_exists($filepath)){
            $filepath= $this->upcount_name($filepath);
        }
        if(mkdir($filepath,  $this->options['mkdir_mode'])){
            return $filepath;
        }
        else{
            return FALSE;
        }
      
    }

 
    protected function get_upload_path($file_name = null, $version = null) {
        $mask = $this->get_mask($this->class_name,  uri_string()).$version;
        $this->IdFolder = dirname(parent::get_upload_path($file_name,$version));
        return parent::get_upload_path($file_name, $mask);
    }
    
    
        /**
     * generates random string
     * len is length of string
     * @param int $len
     * @return string
     */
    protected function generate_random_version($len=8){
        $pool = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        return substr(str_shuffle(str_repeat($pool, ceil($len / strlen($pool)))), 0, $len);
    }
    
        /**
     * convert file size of file
     * @param int $bytes
     * @return string
     */
    protected function formatSizeUnits($bytes) {
        if ($bytes >= 1073741824) {
            $bytes = number_format($bytes / 1073741824, 2) . ' GB';
        } elseif ($bytes >= 1048576) {
            $bytes = number_format($bytes / 1048576, 2) . ' MB';
        } elseif ($bytes >= 1024) {
            $bytes = number_format($bytes / 1024, 2) . ' KB';
        } elseif ($bytes > 1) {
            $bytes = $bytes . ' bytes';
        } elseif ($bytes == 1) {
            $bytes = $bytes . ' byte';
        } else {
            $bytes = '0 bytes';
        }

        return $bytes;
    }
    

    
    
    
    
    
}
