<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Files
 *
 * @author Darko Lesendric <dlesendric at https://github.com/ @Darko_Lesendric at https://twitter.com/>
 */
class Files extends CI_Controller{
    
protected $options;
protected $error_messages = array(
        1 => 'The uploaded file exceeds the upload_max_filesize directive in php.ini',
        2 => 'The uploaded file exceeds the MAX_FILE_SIZE directive that was specified in the HTML form',
        3 => 'The uploaded file was only partially uploaded',
        4 => 'No file was uploaded',
        6 => 'Missing a temporary folder',
        7 => 'Failed to write file to disk',
        8 => 'A PHP extension stopped the file upload',
        'post_max_size' => 'The uploaded file exceeds the post_max_size directive in php.ini',
        'max_file_size' => 'File is too big',
        'min_file_size' => 'File is too small',
        'accept_file_types' => 'Filetype not allowed',
        'max_number_of_files' => 'Maximum number of files exceeded',
        'max_width' => 'Image exceeds maximum width',
        'min_width' => 'Image requires a minimum width',
        'max_height' => 'Image exceeds maximum height',
        'min_height' => 'Image requires a minimum height'
    );


    public function __construct() {
        parent::__construct();
        $this->load->helper('url');
        $this->options = array(
            'upload_dir'=> 'data/',
            //'user_dir'=> base_url().'data/'.$this->get_user().'/',
            'user_dir'=> 'data/admin/',
            'mkdir_mode'=>0755,
            'max_file_size' => 4*1024*1024*1024, //4 gb
            'min_file_size' => 1,
            'max_number_of_files' => null,
            'thumbnail' => array(
                'max_width' => 150,
                'max-height' => 150,
                ),
            'image_file_types' => '/\.(gif|jpe?g|png)$/i'
            
        
        );      
        
        //load model
        $this->load->model('FileModel');
        
    }
    public function index(){
        
        $this->initialize();
        
    }
    protected function initialize() {
        
            switch ($_SERVER['REQUEST_METHOD']){
            case 'GET':
                $this->get();
                break;
            case 'PUT':
            case 'POST':
                $this->post();
                break;
            case 'DELETE':
                $this->delete();
                break;
            default:
                $this->header('HTTP/1.1 405 Method Not Allowed');

            }
        
        
        
    }
    
    public function get(){
        if(isset($_GET['all_files'])){
            $files = $this->getAllFiles();
            $this->generate_response($files);
        }
        elseif(isset($_GET['file'])){
            $file_name = $_GET['file'];
            $files = $this->getFile($file_name);
        }
        else{
            $data['base_url'] = base_url();
            $this->load->view('header',$data);
            $this->load->view('menu',$data);
            $this->load->view('filesView',$data);
        }
        
    }
    
    public function post(){
        $current_dir = $_POST['current_dir'];
        if(isset($_POST['OldName'])){
            $oldName = $_POST['OldName'];
            $newName = $_POST['NewName'];
            $IdFile = $_POST['IdFile'];
            $fileExtension = $_POST['FileExtension'];
            $this->load->model('FileModel');
            $this->FileModel->changeFileName($IdFile,$newName);
            $this->renameFile($oldName, $newName, $fileExtension);
            
        }

        if(isset($_POST['newFolder'])){
            
            $this->createDir($current_dir, $_POST['newFolder']);
        }
        if (isset($_FILES['files'])) {
            $file_count = count($_FILES['files']['name']);
            $file = $_FILES['files'];
            $i = 0;
            while ($i < $file_count) {
                if ($file['error'][$i] == UPLOAD_ERR_OK) {
                    $tmp_name = $file['tmp_name'][$i];
                    $filename = $this->get_filename($file['name'][$i]);
                    $destination = $this->options['user_dir'] . $filename;
                    $size = $file['size'][$i];
                    //Prepare data for insert in db
                    $UploadedFile = new stdClass();
                    $UploadedFile->IdUser = $this->get_user();
                    $UploadedFile->IdFileType = $this->get_file_type($file['type']);
                    $UploadedFile->IdFolder = $this->get_IdFolder($current_dir);
                    $UploadedFile->FileName = pathinfo($filename, PATHINFO_FILENAME);
                    $UploadedFile->FileExtension = pathinfo($filename, PATHINFO_EXTENSION);
                    //$UploadedFile->FileTypeMime = mime_content_type($filename);
                    $UploadedFile->FileSize = $size;
                    //insertUserFile($IdUser, $IdFileType, $IdFolder, $FileExtension, $FileName, $FileSize)

                    $UploadedFile->IdFile = $this->FileModel->insertUserFile($UploadedFile->IdUser, $UploadedFile->IdFileType, $UploadedFile->IdFolder, $UploadedFile->FileExtension, $UploadedFile->FileName, $UploadedFile->FileSize);

                    move_uploaded_file($tmp_name, $destination);
                }

                $i++;
            }
            header('location:'.base_url().'Files');
        }
        
        
    }
      
    public function delete(){
        $IdFile = $_GET['IdFile'];
        $FileName = $_GET['FileName'];
        $FileExtension = $_GET['FileExtension'];
        var_dump($FileName);
        var_dump($FileExtension);
        $this->load->model('FileModel');
        $this->FileModel->deleteFile($IdFile);
        
        if($FileExtension==""){
            //than it's folder
            $directory = $this->options['user_dir'];
            
            rmdir($directory.$FileName);
        }else{
            $dest  = $this->options['user_dir'].$FileName.'.'.$FileExtension;
            chown($dest, 'admin');
            unlink($dest);
        }
    }
    
    protected function get_filename($param) {
        if (file_exists($this->options['user_dir'].$param)) {
            return $this->generate_random_version().'_'.$param;
        }
        return $param;
    }
    
    protected function get_user(){
        //return anything from session
        //return $_SESSION['username'];
        return 1;
    }
    
    protected function getAllFiles(){
        $this->load->model('FileModel');
        $files = $this->FileModel->getAllFilesInFolder($this->get_user());
//        $scandir = scandir($this->options['user_dir']);
//        foreach($scandir as $file_obj){
//            if($this->is_valid_file_object($file_obj)){
//                $file = new stdClass();
//                $file->url = $file_obj;
//                $file->type = '';
//                $file->size = '';
//                $files[]=$file;
//            }
//        }
        return $files;
    }
    /**
     * this method check if is valid file type or dir
     * @param string $file_name (name of file)
     * @return boolean 
     */
    protected function is_valid_file_object($file_name) {
        if ($file_name!='thumbnail' && $file_name[0] !== '.') {
            return true;
        }
        return false;
    }
    /**
     * this generate response and print in json format
     * @param type $files array of files
     */
    protected function generate_response($files) {
        header('Content-Type: application/json');
        $all_files = json_encode($files);
        
        echo $all_files;
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
    
    protected function get_file_type($param) {
        return 2;
    }
    
    protected function get_IdFolder($param) {
        
        if($param==0){
            return NULL;
        }
        return $param;
    }
    
    protected function prepareFiles($files) {
        
    }
    /**
     * treba da se zameni admin, cim se implementira user i location
     * @param string $location
     * @param string $name (folder name)
     */
    protected function createDir($location,$name) {
        if(file_exists($this->options['user_dir'].$name)){
            $name = $this->generate_random_version().'_'.$name; 
            
        }
        mkdir($this->options['user_dir'].$name,  $this->options['mkdir_mode']);
        $this->load->model("FileModel");
        //insertUserFile($IdUser, $IdFileType, $IdFolder, $FileExtension, $FileName, $FileSize)
        //hardcoded IdFileType 1
        $this->FileModel->insertUserFile($this->get_user(), 1, NULL, NULL, $name, 0);
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
    
    protected function renameFile($oldName,$newName,$fileExtension,$current_dir=NULL) {
        //if is_null fileExtension than this is folder
        (is_null($fileExtension))? '' : $fileExtension = '.'.$fileExtension;
        $oldName = $this->options['user_dir'].$oldName.$fileExtension;
        $newName = $this->options['user_dir'].$newName.$fileExtension;
        rename($oldName, $newName);
    }
    

}
