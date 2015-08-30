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
    private $current_dir;
    private $current_path;
    private $Mask;
    public function __construct() {
        $this->class_name = get_class($this);
        parent::__construct();
        
    }

    
    public function index(){
        
    }
    
    protected function initialize() {
        $this->Mask = $this->get_mask($this->class_name,  uri_string());
        if(isset($_GET['action'])){
            switch ($_GET['action']){
                case "newFolder":
                    $clean = trim($_GET['folderName']);
                    $folderName = rawurlencode(preg_replace('/\s+/', '_', $clean));
                    $this->newFolder($folderName,$_GET['Mask'],intval($_GET['IdFolder']));
                    break;
                case "renameFile":
                    $clean = trim($_GET['newName']);
                    $newName = rawurlencode(preg_replace('/\s+/', '_', $clean));
                    $IdFile = intval($_GET['IdFile']);
                    $this->renameFile($IdFile, $newName);
                    break;
            }
            
        }


        parent::initialize();
    }
    
    
    protected function newFolder($FolderName,$FilePath='',$IdFolder=0) {
        if($IdFolder == 0){
            $filepath = $this->get_upload_path($FolderName);
        }
        else{
            
            $filepath = $this->get_upload_path($FilePath.$FolderName);
        }
        if(file_exists($filepath)){
            $filepath = $this->upcount_name($filepath);
        }
        $filepath = strtolower($filepath);
        if(mkdir($filepath)){
            $this->load->model("FileModel");
            $this->FileModel->insertUserFile($this->get_user_id(), 1, $IdFolder, null, $FolderName,$filepath, 0);
            die(TRUE);
        }
        
    }
    
    protected function renameFile($IdFile,$newName) {
        $this->load->model("FileModel");
        $result = $this->FileModel->getFileById($IdFile);
        if($result){
            if(file_exists($result->FilePath)){
                $newFilePath = dirname($result->FilePath)."/".$newName;
                if(rename($result->FilePath, $newFilePath)){
                    $this->FileModel->changeFileName($IdFile,$newName,$newFilePath);
                    die(TRUE);
                }
            }
        }
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
    
    
    
    protected function handle_form_data($file, $index) {
        $file->IdFolder = @intval($_REQUEST['IdFolder'][$index]);
        $file->Mask = @$_REQUEST['Mask'][$index];
    }

    protected function handle_file_upload($uploaded_file, $name, $size, $type, $error,
            $index = null, $content_range = null) {
        $file = parent::handle_file_upload(
            $uploaded_file, $name, $size, $type, $error, $index, $content_range
        );
        if (empty($file->error)) {
            
            $this->load->model("FileModel");
            $file->filetype=  $this->FileModel->getFileType($file->type);
            $file->FilePath = strtolower($this->get_upload_path($file->name));
            $file->FileExtension = pathinfo($file->FilePath,PATHINFO_EXTENSION);
            $file->FileLastModified = time();
            if($file->chunk==FALSE){
                $file->size = $this->get_file_size($file->FilePath);
                $file->IdFile = $this->FileModel->insertUserFile($this->get_user_id(),$file->filetype,  $file->IdFolder,$file->FileExtension,$file->name, $file->FilePath,$file->size);
                switch ($file->FileExtension){
                    case "jpg":
                    case "jpeg":
                    case "png":
                    case "gif":

                        $file->url= $this->get_download_url($file->name);
                        break;
                    default :
                        $file->url= base_url()."share/download/".$file->IdFile;
                        break;
                }
            }
        }
        return $file;
    }

    protected function set_additional_file_properties($file) {
        parent::set_additional_file_properties($file);
        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            $file->Mask = $this->get_mask($this->class_name,  uri_string());
            $this->load->model("FileModel");
            $result = $this->FileModel->getFile($this->get_user_id(),$file->name,  $file->FilePath);
            if($result){
                $file->IdFile = $result->IdFile;
                $file->FileTypeMime = $result->FileTypeMime;
                $file->FileExtension = $result->FileExtension;
                $file->FileLastModified = $result->FileLastModified;
                $file->deleteUrl.="&IdFile=".$result->IdFile."&Mask=".$file->Mask;
                switch ($file->FileExtension){
                    case "jpg":
                    case "jpeg":
                    case "png":
                    case "gif":

                        $file->url= $this->get_download_url($file->name);
                        break;
                    default :
                        $file->url= base_url()."share/download/".$file->IdFile;
                        break;
                }
            }
            else{
                //can't find file in db so it need to be downloaded directly
                $file->url = base_url()."share/download/".  rawurlencode(base64_encode($file->FilePath));
            }
        }
    }
    protected function get_file_object($file_name) {
        if ($this->is_valid_file_object($file_name)) {
            $file = new \stdClass();
            $file->name = $file_name;
            $file->size = $this->get_file_size(
                $this->get_upload_path($file_name)
            );            
            $file->FilePath = strtolower($this->get_upload_path($file->name));
            foreach($this->options['image_versions'] as $version => $options) {
                if (!empty($version)) {
                    if (is_file($this->get_upload_path($file_name, $version))) {
                        $file->{$version.'Url'} = $this->get_download_url(
                            $file->name,
                            $version
                        );
                    }
                }
            }
            $this->set_additional_file_properties($file);
            return $file;
        }
        return null;
    }
    protected function get_file_objects($iteration_method = 'get_file_object') {
        $upload_dir = $this->get_upload_path();
        if (!is_dir($upload_dir)) {
            return array();
        }
        return array_values(array_filter(array_map(
            array($this, $iteration_method),
            scandir($upload_dir)
        )));
    }
    


    protected function getFilePath($file_name){
        $user_path = $this->options['upload_dir'];
        if($this->options['user_dirs']){
            $user_path.=$this->get_user_id();
        }
        $user_path.=$this->get_mask($this->class_name,  uri_string());
        if(file_exists($user_path.'/'.$file_name)){
            return $user_path;
        }
        else{
            return null;
        }
    }
    
    protected function get_upload_path($file_name = null, $version = null) {
        $file_name = $file_name ? $file_name : '';
        if (empty($version)) {
            $version_path = '';
        } else {
            $version_dir = @$this->options['image_versions'][$version]['upload_dir'];
            if ($version_dir) {
                return $version_dir.$this->get_user_path().$file_name;
            }
            $version_path = $version.'/';
        }

         return $this->options['upload_dir'].$this->get_user_path().$this->Mask.$version_path.$file_name;
    }
    
    public function delete($print_response = true,$currentdir='') {
        if(isset($_GET['IdFile'])){
            $IdFile = intval($_GET['IdFile']);
        }
        $mask = '';
        if(isset($_GET['Mask'])){
            $mask = $_GET['Mask'];
        }
        $file_name = $_GET['file'];
        if (empty($file_name)) {
            $file_names = array($this->get_file_name_param());
        }
        $response = array();

        $file_path = $this->get_upload_path($mask.$file_name);
        $success = is_file($file_path) && $file_name[0] !== '.' && unlink($file_path);
        if(!$success){
            $success = is_dir($file_path) && $file_name[0] !== '.' && $this->forceDeleteDir($file_path);
        }
        if($success){
            foreach($this->options['image_versions'] as $version => $options) {
                if (!empty($version)) {
                    $file = $this->get_upload_path($mask.$file_name, $version);
                    if (is_file($file)) {
                        unlink($file);
                    }
                    if(is_dir($file)){
                        rmdir($file);
                        //force delete thumbnail

                    }
                }
            }
            $this->load->model("FileModel");
            $this->FileModel->deleteFile($IdFile);
        }
        
        $response[$file_name] = $success;
         
        return $this->generate_response($response, $print_response);
    }
    
    protected function get_download_url($file_name, $version = null, $direct = false) {
        return parent::get_download_url($file_name, $this->Mask.$version, $direct);
    }
    
    
    
    
    
    
    
    
  
    

    
    

    


    
}
