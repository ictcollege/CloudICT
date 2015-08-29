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
                if($result->FileTypeMime == "DIR"){
                    $this->createZip($result->FilePath);
                }
                else{
                    $this->download_file($result);
                }
                
            }
            else{
                
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
        
        // Get real path for our folder
    $rootPath = realpath($filePath);

    // Initialize archive object
    $zip = new ZipArchive();
    $docname = "documents-export-".date("Y-m-d");
    $zip->open($docname, ZipArchive::CREATE | ZipArchive::OVERWRITE);

    // Create recursive directory iterator
    /** @var SplFileInfo[] $files */
    $files = new RecursiveIteratorIterator(
        new RecursiveDirectoryIterator($rootPath),
        RecursiveIteratorIterator::LEAVES_ONLY
    );

    foreach ($files as $name => $file)
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
        $chunk_size = $this->options['readfile_chunk_size'];
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
}
