<?php

/*
 *  @author Darko Lesendric <dlesendric at https://github.com/ @Darko_Lesendric at https://twitter.com/>
 */

/**
 * Description of Share
 *
 * @author Darko
 */
class Share extends Frontend_Controller{
    public function __construct() {
        parent::__construct();
    }
    
    public function index(){
        if(isset($_GET['action'])){
            switch($_GET['action']){
                case 'checkFileShare':
                    $this->checkFileShare($_GET['IdFile']);
                    break;
                
                case 'sharedWithYou':
                    $this->sharedWithYou();
                    break;
                case 'sharedWithOthers':
                    $this->sharedWithOthers();
                    break;
                case 'saveFile':
                    $this->saveFile($_GET['IdFile']);
                    break;
            }
        }
        
    }
    
    public function shareFile(){
        if(isset($_POST['json'])){
            $json = json_decode($_POST['json']);
            $this->load->model("ShareModel");
            foreach($json->users as $user){
                $this->ShareModel->shareWithUser($user,$json->IdFile,TRUE,$json->SharePrivilege);
            }
            foreach($json->unshare as $user){
                $this->ShareModel->shareWithUser($user,$json->IdFile,FALSE);
            }
            echo "1";
        }
    }

    
    public function download($IdFile){
        $this->load->model("FileModel");
        $result = $this->FileModel->getFileById($IdFile);
        if(!empty($result)){
            if($result->IdUser==$this->get_user_id()){
                //user is owner of file, can download
                $this->checkFileTypeAndDownload($result); 
            }
            else{
                $this->load->model("ShareModel");
                $permission = $this->ShareModel->canDownload($this->get_user_id(),$IdFile);
                if($permission){
                    $this->checkFileTypeAndDownload($result);
                }
                else{
                    die("You can't download this file, file not shared with you!");
                }
            }
        }
        
    }
    protected function generate_response($content, $print_response = true) {
        if ($print_response) {
            header("Content-type:application/json");
            echo json_encode($content);
        }
        return $content;
    }
    
    protected function createZip($filePath=''){
    if(count(scandir($filePath))<=2){
        if(isset($_SERVER['HTTP_REFERER'])){
                header('Location:'.$_SERVER['HTTP_REFERER'].'?msg_type=danger&msg=Folder is empty! Make sure u have something in folder before download...');
            }
            else{
                redirect('files/');
            }
    }    
        // Get real path for our folder
    $rootPath = realpath($filePath);

    // Initialize archive object
    $zip = new ZipArchive();
    $docname = "documents-export-".date("Y-m-d");
    $zip->open($docname, ZipArchive::CREATE | ZipArchive::OVERWRITE);

    // Create recursive directory iterator
    /** @var SplFileInfo[] $data */
    $data = new RecursiveIteratorIterator(
        new RecursiveDirectoryIterator($rootPath),
        RecursiveIteratorIterator::LEAVES_ONLY
    );

    foreach ($data as $name => $file)
    {
        // Skip directories (they would be added automatically)
        if (!$file->isDir())
        {
            // Get real and relative path for current file
            $filePath = $file->getRealPath();
            $relativePath = substr($filePath, strlen($rootPath) + 1);

            // Add current file to archive
            $zip->addFile($filePath, $relativePath);
        }
    }

    // Zip archive will be created only after closing object
    $zip->close();
    header('Content-Type: application/zip');
    header('Content-Length: ' . filesize($docname));
    header('Content-Disposition: attachment; filename="'.$docname.'"');
    readfile($docname);
    unlink($docname);
    }
    
    protected function download_file($result){
        // Prevent browsers from MIME-sniffing the content-type:
        header('X-Content-Type-Options: nosniff');
        if (!preg_match('/\.(gif|jpe?g|png)$/i', $result->FileName)) {
           header('Content-Type: application/octet-stream');
            header('Content-Disposition: attachment; filename="'.$result->FileName.'"');
        } else {
            header('Content-Type: '.$result->FileTypeMime);
            header('Content-Disposition: inline; filename="'.$result->FileName.'"');
        }
        header('Content-Length: '.$this->get_file_size($result->FilePath));
        header('Last-Modified: '.gmdate('D, d M Y H:i:s T', filemtime($result->FilePath)));
        $this->readfile($result->FilePath); 


        

    }
    //ovo treba ukloniti kad se popravi bug sa filesize
    protected function get_file_size($file_path, $clear_stat_cache = false) {
        if ($clear_stat_cache) {
            if (version_compare(PHP_VERSION, '5.3.0') >= 0) {
                clearstatcache(true, $file_path);
            } else {
                clearstatcache();
            }
        }
        return $this->fix_integer_overflow(filesize($file_path));
    }
    //i ovo isto
    protected function fix_integer_overflow($size) {
        if ($size < 0) {
            $size += 2.0 * (PHP_INT_MAX + 1);
        }
        return $size;
    }
    protected function readfile($file_path) {
        $file_size = $this->get_file_size($file_path);
        $chunk_size = 10 * 1024 * 1024; //10 MB
        if ($chunk_size && $file_size > $chunk_size) {
            $handle = fopen($file_path, 'rb');
            while (!feof($handle)) {
                echo fread($handle, $chunk_size);
                @ob_flush();
                @flush();
            }
            fclose($handle);
            return $file_size;
        }
        return readfile($file_path);
    }
    
    protected function checkFileShare($IdFile){
        $this->load->model("ShareModel");
        $result = $this->ShareModel->getAllSharedUsersForFile(intval($IdFile));
        if(!empty($result)){
            $this->generate_response($result,TRUE);
        }
    }
    
    /**
     * this method format result into datatable which display shared files with some user
     * result can be preview in files/shared_with_you
     */
    protected function sharedWithYou(){
        $this->load->model("ShareModel");
        $files=  $this->ShareModel->sharedWithUser($this->get_user_id());
        $i =0;
        $data = array();
        foreach($files as $file){
            $owner = $file["Owner"];
            $shared_on = date("d-m-Y h:i:s",$file["ShareCreated"]);
            $file_name = $file["ShareFullName"];
            $privilege = $this->switchPrivilege($file["SharePrivilege"]);
            $file_size = $this->formatSizeUnits($file["FileSize"]);
            $file_modified = date("d-m-Y h:i:s",$file["FileLastModified"]);
            $modify = "<a href='".base_url()."Share/download/".$file["IdFile"]."' title='download' class='download'><i class='fa fa-download'></i></a>";
            $modify.="&nbsp;<a href='#' class='unshare' data-idfile='".$file["IdFile"]."' title='Remove this file from my share'><i class='fa fa-minus-circle'></i></a>";
            if($file["SharePrivilege"]==3){
                $modify.="<a href='".base_url()."Share/delete/".$file["IdFile"]."' title='delete file' class='delete'><i class='fa fa-trash'></i></a>";
            }
            if($file["FileTypeMime"]=="DIR"){
                $file_size = "<i class='fa fa-folder-open' title='folder'></i>";
                $file_name= "<a href='#' class='viewFolder' data-idfile='".$file["IdFile"]."'>".$file["ShareFullName"]."</a>";
            }
            $data[$i][]=$owner;
            $data[$i][]=$shared_on;
            $data[$i][]=$file_name;
            $data[$i][]=$privilege;
            $data[$i][]=$file_size;
            $data[$i][]=$file_modified;
            $data[$i][]=$modify;
            $i++;
        }
        $content["data"] = $data;
        $this->generate_response($content,TRUE);
        
    }
    /**
     * this method format result into datatable which display shared files with some user
     * result can be preview in files/shared_with_others
     */
    protected function sharedWithOthers(){
        $this->load->model("ShareModel");
        $files=  $this->ShareModel->sharedByUser($this->get_user_id());
        $i =0;
        $data = array();
        foreach($files as $file){
            $sharedusername = $file["SharedUsername"];
            $shared_on = date("d-m-Y h:i:s",$file["ShareCreated"]);
            $file_name = $file["ShareFullName"];
            $privilege = $this->switchPrivilege($file["SharePrivilege"]);
            $file_size = $this->formatSizeUnits($file["FileSize"]);
            $file_modified = date("d-m-Y h:i:s",$file["FileLastModified"]);
            $modify="<a href='#' class='unshare' data-shareduser='".$file["SharedUser"]."' data-idfile='".$file["IdFile"]."' title='Unshare this file'><i class='fa fa-minus-circle'></i></a>";
            
            if($file["FileTypeMime"]=="DIR"){
                $file_size = "<i class='fa fa-folder-open' title='folder'></i>";
                $file_name= "<a href='#' class='viewFolder' data-idfile='".$file["IdFile"]."'>".$file["ShareFullName"]."</a>";
            }
            $data[$i][]=$sharedusername;
            $data[$i][]=$shared_on;
            $data[$i][]=$file_name;
            $data[$i][]=$privilege;
            $data[$i][]=$file_size;
            $data[$i][]=$file_modified;
            $data[$i][]=$modify;
            $i++;
        }
        $content["data"] = $data;
        $this->generate_response($content,TRUE);
        
    }
    protected function checkFileTypeAndDownload($result) {
        if($result->FileTypeMime == "DIR"){
            $this->createZip($result->FilePath);
        }
        $this->download_file($result);
        
    }
    
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
    
    protected function switchPrivilege($param){
        switch ($param){
            case 1:
            case "1":
                return "READ";
            case 2:
            case "2":
                return "WRITE";
            case 3:
            case "3":
                return "DELETE";
        }
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
    
    public function saveFile(){
        if(isset($_POST['json'])){
            $json = json_decode($_POST['json']);
            $this->load->model("FileModel");
            $file = $this->FileModel->getFileById($json->IdFile);
            if(file_exists($file->FilePath)){
                file_put_contents($file->FilePath, $json->content , LOCK_EX);
                
                die(TRUE);
            }
            else{
                die(FALSE);
            }
        }
        
    }
    
}
