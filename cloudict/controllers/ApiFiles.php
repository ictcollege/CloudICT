<?php

/*
 *  @author Darko Lesendric <dlesendric at https://github.com/ @Darko_Lesendric at https://twitter.com/>
 */

/**
 * Description of ApiFiles
 *
 * @author Darko
 */
class ApiFiles extends Frontend_Controller{
    protected $options = array(
        
    );
    
    protected $editableFileTypes = array(
        'txt','asp','aspx','axd','asx','asmx','ashx','css','cfm','yaws','swf','html','htm','xhtml','jhtml','jsp','jspx','wss','java','do','action','js','pl','php','inc','php4','php3','phtml','py','rb','rhtml','xml','rss','svg','cgi','dll','cs'
    );
    
    public function __construct() {
        parent::__construct();
        $this->options = array(
            'upload_dir' => dirname($_SERVER['SCRIPT_FILENAME'])."/data/",
            'script_url' => base_url().get_class($this).'/',
            'mkdir_mode' => 0755,
            'image_file_types' => '/(gif|jpe?g|png)$/i',
            'access_control_allow_origin' => '*',
            'access_control_allow_credentials' => false,
            'access_control_allow_methods' => array(
                'OPTIONS',
                'HEAD',
                'GET',
                'POST',
                'PUT',
                'PATCH',
                'DELETE'
            ),
            'access_control_allow_headers' => array(
                'Content-Type',
                'Content-Range',
                'Content-Disposition'
            ),
            // Read files in chunks to avoid memory limits when download_via_php
            // is enabled, set to 0 to disable chunked reading of files:
            'readfile_chunk_size' => 10 * 1024 * 1024, // 10 MiB
            // Defines which files (based on their names) are accepted for upload:
            'accept_file_types' => '/.+$/i',
            'max_file_size' => null,
            'min_file_size' => 1
        );
    }
    
    public function index(){
        $this->initialize();
    }
    
    protected function initialize() {
        switch ($this->get_server_var('REQUEST_METHOD')) {
            case 'GET':
                $this->get();
                break;
            case 'PATCH':
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
    
    protected function delete(){
        if(isset($_GET['Id'])){
            $Id = intval($_GET['Id']);
        }
        else{
            die(FALSE);
        }
        if(isset($_GET['Type'])){
            $type = $_GET['Type'];
        }
        else{
            die(FALSE);
        }
        switch ($type){
            case "File": 
                $this->deleteFile($Id);
                break;
            case "Folder":
                $this->deleteFolder($Id);
                break;
            default :
                die(FALSE);
                break;
        }
    }


    protected function get($print_response = true) {
        $id_folder = 0;
        if(isset($_GET['id_folder'])){
            $id_folder = intval($_GET['id_folder']);
        }
        
        $response = $this->get_file_objects($id_folder);
        return $this->generate_response($response, $print_response);
    }
    
    protected function post($print_response = true) {
        $upload = isset($_FILES['files']) ?
            $_FILES['files'] : null;
        // Parse the Content-Disposition header, if available:
        $file_name = $this->get_server_var('HTTP_CONTENT_DISPOSITION') ?
            rawurldecode(preg_replace(
                '/(^[^"]+")|("$)/',
                '',
                $this->get_server_var('HTTP_CONTENT_DISPOSITION')
            )) : null;
        // Parse the Content-Range header, which has the following form:
        // Content-Range: bytes 0-524287/2000000
        $content_range = $this->get_server_var('HTTP_CONTENT_RANGE') ?
            preg_split('/[^0-9]+/', $this->get_server_var('HTTP_CONTENT_RANGE')) : null;
        $size =  $content_range ? $content_range[3] : null;
        $files = array();
        if ($upload && is_array($upload['tmp_name'])) {
            // param_name is an array identifier like "files[]",
            // $_FILES is a multi-dimensional array:
            foreach ($upload['tmp_name'] as $index => $value) {
                $files[] = $this->handle_file_upload(
                    $upload['tmp_name'][$index],
                    $file_name ? $file_name : $upload['name'][$index],
                    $size ? $size : $upload['size'][$index],
                    $upload['type'][$index],
                    $upload['error'][$index],
                    $index,
                    $content_range
                );
            }
        } else {
            // param_name is a single object identifier like "file",
            // $_FILES is a one-dimensional array:
            $files[] = $this->handle_file_upload(
                isset($upload['tmp_name']) ? $upload['tmp_name'] : null,
                $file_name ? $file_name : (isset($upload['name']) ?
                        $upload['name'] : null),
                $size ? $size : (isset($upload['size']) ?
                        $upload['size'] : $this->get_server_var('CONTENT_LENGTH')),
                isset($upload['type']) ?
                        $upload['type'] : $this->get_server_var('CONTENT_TYPE'),
                isset($upload['error']) ? $upload['error'] : null,
                null,
                $content_range
            );
        }
        return $this->generate_response(
            array('files' => $files),
            $print_response
        );
    }
    
//    protected function generate_response($content, $print_response = true) {
//        if ($print_response) {
//            header("Content-type:application/json");
//            echo json_encode($content);
//        }
//        return $content;
//    }
    protected function body($str) {
        echo $str;
    }
    
    protected function header($str) {
        header($str);
    }
    protected function send_access_control_headers() {
        $this->header('Access-Control-Allow-Origin: '.$this->options['access_control_allow_origin']);
        $this->header('Access-Control-Allow-Credentials: '
            .($this->options['access_control_allow_credentials'] ? 'true' : 'false'));
        $this->header('Access-Control-Allow-Methods: '
            .implode(', ', $this->options['access_control_allow_methods']));
        $this->header('Access-Control-Allow-Headers: '
            .implode(', ', $this->options['access_control_allow_headers']));
    }
    public function head() {
        $this->header('Pragma: no-cache');
        $this->header('Cache-Control: no-store, no-cache, must-revalidate');
        $this->header('Content-Disposition: inline; filename="files.json"');
        // Prevent Internet Explorer from MIME-sniffing the content-type:
        $this->header('X-Content-Type-Options: nosniff');
        if ($this->options['access_control_allow_origin']) {
            $this->send_access_control_headers();
        }
        $this->send_content_type_header();
    }
    protected function send_content_type_header() {
        $this->header('Vary: Accept');
        if (strpos($this->get_server_var('HTTP_ACCEPT'), 'application/json') !== false) {
            $this->header('Content-type: application/json');
        } else {
            $this->header('Content-type: text/plain');
        }
    }
    protected function generate_response($content, $print_response = true) {
        if ($print_response) {
            $json = json_encode($content);
            $redirect = isset($_REQUEST['redirect']) ?
                stripslashes($_REQUEST['redirect']) : null;
            if ($redirect) {
                $this->header('Location: '.sprintf($redirect, rawurlencode($json)));
                return;
            }
            $this->head();
            if ($this->get_server_var('HTTP_CONTENT_RANGE')) {
                $files = isset($content['files']) ?
                    $content['files'] : null;
                if ($files && is_array($files) && is_object($files[0]) && $files[0]->size) {
                    $this->header('Range: 0-'.(
                        $this->fix_integer_overflow(intval($files[0]->size)) - 1
                    ));
                }
            }
            $this->body($json);
        }
        return $content;
    }
    
   
    protected function get_file_objects($IdFolder=0) {
        $this->load->model("FolderModel");
        $folders = $this->FolderModel->getAllUserFolders($this->get_user_id(),$IdFolder);
        $this->load->model("FileModel");
        $files = $this->FileModel->getAllUserFiles($this->get_user_id(),$IdFolder);
        $data = $this->renderFilesAndFolders($files, $folders);
        $content['files'] = $data;
        return $content;
    }
    
    
    //kreiranje fajla
    public function createFile($IdFolder,$filename) {
        $filename = $this->prepareName($filename);
        if($IdFolder == 0){
            $mask = "";
        }
        else{
            $this->load->model("FolderModel");
            $folder = $this->FolderModel->getFolderById($IdFolder);
            if(empty($folder)){
                die("Folder not exists!");
            }
            $mask = $folder->FolderMask;
        }
        $path = $this->options['upload_dir'].$this->get_user_id().'/'.$mask.$filename;
        if(file_exists($path)){
            $path = $this->upcount_name($path);
            $filename = $this->upcount_name($filename);
        }
        $handle = fopen($path,"w");
        fclose($handle);
        //ubacivanje u bazu
        if($handle){
            $size = 0; //po default-u kreiran fajl je 0 byta.
            $ext = pathinfo($path,PATHINFO_EXTENSION);
            $mime = $this->get_mime($filename);
            $this->load->model("FileModel");
            $file_type = $this->FileModel->getFileType($mime);
            $this->FileModel->insertUserFile($this->get_user_id(),$file_type,$IdFolder,$ext,$filename,$path,$size);
            die(TRUE);
        }
       }
    //rename fajla
    public function renameFile() {
        $file = json_decode($_POST['json']);
        $newName = $this->prepareName($file->Name);
        $this->load->model("FileModel");
        $result = $this->FileModel->getFileById($file->IdFile);
        if($result){
            if(file_exists($result->FilePath)){
                $newFilePath = dirname($result->FilePath)."/".$newName;
                if(rename($result->FilePath, $newFilePath)){
                    $this->FileModel->changeFileName($file->IdFile,$newName,$newFilePath);
                    die(TRUE);
                }
                die(FALSE);
            }
        }
    }
    //download fajla
    public function downloadFile($IdFile){
        $this->load->model("FileModel");
        $result = $this->FileModel->getFileById($IdFile);
        if(!empty($result)){
            if($result->IdUser==$this->get_user_id()){
                //user is owner of file, can download
                $this->force_download($result); 
            }
            else{
                $this->load->model("ShareModel");
                $permission = $this->ShareModel->canDownload($this->get_user_id(),$IdFile);
                if($permission){
                    $this->force_download($result);
                }
                else{
                    die("You can't download this file, file not shared with you!");
                }
            }
        }
    }
    //brisanje fajla
    public function deleteFile($IdFile){
        $this->load->model("FileModel");
        $file=$this->FileModel->getFileById($IdFile);
        if($file->IdUser==$this->get_user_id()){
            //user is owner of file
            unlink($file->FilePath);
            $this->FileModel->deleteFile($IdFile);
            die(TRUE);
        }
        $this->load->model("ShareModel");
        if($this->ShareModel->canExecute($this->get_user_id(),$IdFile)){
            if(unlink($file->FilePath)){
                $this->FileModel->deleteFile($IdFile);
                die(TRUE);
            }
            die(FALSE);
        }
        else{
            die("You don't have permision to download this file!");
        }
        
        
    }
    //kreiranje foldera
    //folder name treba prepraviti, mozda parametre treba slati preko json-a kako bi mogli sa praznim mestom mogo da se napravi folder prim( New folder) sad preimenuje u (new_folder)
    
    public function newFolder() {
        $folder = json_decode($_POST['json']);
        if(empty($folder)){
            die("ERROR:Folder not exists!");
        }
        $folder->FolderName = $this->prepareName($folder->FolderName);
        $path = $this->options['upload_dir'].$this->get_user_id().'/'.$folder->Mask.$folder->FolderName;
        $path = strtolower($path);
        if(file_exists($path)){
            $path = $this->upcount_name($path);
            $folder->FolderName = $this->upcount_name($folder->FolderName);
        }
        
        if(mkdir($path,  $this->options['mkdir_mode'])){
            $this->load->model("FolderModel");
            //insertUserFolder($IdUser,$FolderName,$FolderMask,$FolderPath)
            $this->FolderModel->insertUserFolder($this->get_user_id(), $folder->FolderName, $folder->Mask,$path,$folder->IdFolder);
            die(TRUE);
        }
        die(FALSE);
    }
    //rename foldera
    public function renameFolder() {
        $folder = json_decode($_POST['json']);
        $newName = $this->prepareName($folder->Name);
        $this->load->model("FolderModel");
        $result = $this->FolderModel->getFolderById($folder->IdFolder);
        if($result){
            if(file_exists($result->FolderPath)){
                $newFolderPath = dirname($result->FolderPath)."/".$newName;
                rename($result->FolderPath, $newFolderPath);
                $this->FolderModel->changeFolderName($folder->IdFolder,$newName,$newFolderPath);
                die(TRUE);
            }
        }
        die(FALSE);
    }
    //download foldera
    public function downloadFolder($IdFolder){
        $this->load->model("FolderModel");
        $result  = $this->FolderModel->getFolderById($IdFolder);
        if($result &&  file_exists($result->FolderPath)){
            if($result->IdUser==$this->get_user_id()){
                //user is owner of folder
                $this->createZip($result->FolderPath);
            }
            else{
                //shared folder
                $this->load->model("ShareModel");
                if($this->ShareModel->canDownload($this->get_user_id(),NULL,$IdFolder)){
                    $this->createZip($result->FolderPath);
                }
                else{
                    die("You don't have permision to download file!");
                }
            }
            
        }
        die("Folder not exists any more!");
    }
    //brisanje foldera
    public function deleteFolder($IdFolder){
        $this->load->model("FolderModel");
        $result = $this->FolderModel->getFolderById($IdFolder);
        if($result && $result->IdUser == $this->get_user_id()){
            //user is owner of folder
            if(!file_exists($result->FolderPath)){
                echo "Folder not exists!";
            }
            else{
                $this->forceDeleteDir($result->FolderPath);
            }
            //delete from db where triger do all recursive stuff
            $this->FolderModel->deleteFolder($IdFolder);
            die(TRUE);
        }
        else{
            $this->load->model("ShareModel");
            if($this->ShareModel->canExecute($this->get_user_id(),NULL,$IdFolder)){
                $this->forceDeleteDir($result->FolderPath);
                //delete from db where triger do all recursive stuff
                $this->FolderModel->deleteDir($IdFolder);
                die(TRUE);
            }
            else{
                die("You don't have permision to delete this folder!");
            }
        }
        die(FALSE);
    }
    
    public function setFavourites($IdFile,$Type,$Unset=FALSE){
        if($Type=="folder"){
            $this->load->model("FolderModel","TModel");
        }
        else{
            $this->load->model("FileModel","TModel");
        }
        $UserId = $this->get_user_id();
        $this->TModel->setFavourites($UserId,intval($IdFile),$Unset);
    }
    
    protected function renderFilesAndFolders($files,$folders){
        $data = array();
        $i = 0;
        
        foreach($folders as $folder){
            $preview = '<span class="size"><i class="fa fa-folder-open fa-fw"></i></span>';
            if($folder["Favourites"]){
                $preview.= '<a href="#" data-id="'.$folder["IdFolder"].'" data-type="folder" class="unsetfav"><i class="fa fa-star fa-fw"></i></a>';
            }
            else{
                $preview.= '<a href="#" data-id="'.$folder["IdFolder"].'" data-type="folder" class="setfav"><i class="fa fa-star-o fa-fw"></i></a>';
            }
            $checkbox = '<input type="checkbox" name="chbDelete" data-type="Folder" value="'.$folder['IdFolder'].'" class="toggle chbDelete">';
            $name = '<a href="Files/index/'.$folder["FolderMask"].$folder["FolderName"].'">'.$folder['FolderName'].'</a>';
            $manage =  '<div class="btn-group"><a href="#" data-toggle="dropdown" title="edit/rename"><i class="fa  fa-pencil fa-fw"></i></a>
          <ul class="dropdown-menu">
            <li><a href="#" data-type="folder" data-id="'.$folder["IdFolder"].'" data-name="'.$folder["FolderName"].'"  class="rename">Rename</a></li>
            <li><a href="#" data-idfolder="'.$folder["IdFolder"].'"  class="move">Move</a></li>          
          </ul>
          <a href="#" data-id="'.$folder["IdFolder"].'" data-type="Folder" class="deleteLink"><i class="glyphicon glyphicon-trash"></i></a>
          <a href="'.$this->options['script_url'].'/downloadFolder/'.$folder['IdFolder'].'" data-type="Folder" data-id="'.$folder["IdFolder"].'" class="download"  title="Download '.$folder['FolderName'].'"><i class="fa  fa-cloud-download fa-fw"></i></a>
          <a href="#" class="share" data-toggle="modal" data-target="#ShareModal" data-id="'.$folder["IdFolder"].'" data-type="folder" title="Share folder"><i class="fa  fa-share-alt fa-fw"></i></a>
          </div>';
            $size = '';
            $modified = '';
            
            $data[$i][] = $preview;
            $data[$i][] = $checkbox;
            $data[$i][] = $name;
            $data[$i][] = $manage;
            $data[$i][] = $size;
            $data[$i][] = $modified;
            $i++;
        }
        
        foreach($files as $file){
            $preview = '<span class="size"><i class="fa fa-file-text-o fa-fw"></i></span>';
            if($file->Favourites){
                $preview.= '<a href="#" data-id="'.$file->IdFile.'" data-type="file" class="unsetfav"><i class="fa fa-star fa-fw"></i></a>';
            }
            else{
                $preview.= '<a href="#" data-id="'.$file->IdFile.'" data-type="file" class="setfav"><i class="fa fa-star-o fa-fw"></i></a>';
            }
            if(preg_match($this->options['image_file_types'], $file->FileExtension)){
                $file->image = true;
                $file->name = $file->FileName;
                $this->set_download_url($file);
                $preview = '<a href="'.$file->url.'" title="'.$file->FileName.'" download="'.$file->FileName.'" data-gallery><img src="'.$file->thumbnailUrl.'"></a>';
            }
            
            $checkbox = '<input type="checkbox" name="chbDelete" data-type="File" value="'.$file->IdFile.'" class="toggle chbDelete">';
            $name = '<a href="'.$this->options['script_url'].'/downloadFile/'.$file->IdFile.'" data-type="File" data-id="'.$file->IdFile.'" class="download"  title="Download '.$file->FileName.'">'.$file->FileName.'</a>';
            $manage =  '<div class="btn-group"><a href="#" data-toggle="dropdown" title="edit/rename"><i class="fa  fa-pencil fa-fw"></i></a>
          <ul class="dropdown-menu">
            <li><a href="#" data-type="file" data-id="'.$file->IdFile.'"  class="rename">Rename</a></li>
            <li><a href="#" data-id="'.$file->IdFile.'"  class="move">Move</a></li>
            ';
            if(in_array($file->FileExtension, $this->editableFileTypes)){
                $manage.='<li><a href="#" data-idfile="'.$file->IdFile.'" class="edit">Edit</a></li>';
            }
            $manage.='
          </ul>
          <a href="#" data-id="'.$file->IdFile.'" data-type="File" class="deleteLink"><i class="glyphicon glyphicon-trash"></i></a>
          <a href="'.$this->options['script_url'].'/downloadFile/'.$file->IdFile.'" data-type="File" data-id="'.$file->IdFile.'" class="download"  title="Download '.$file->FileName.'"><i class="fa  fa-cloud-download fa-fw"></i></a>
          <a href="#" class="share" data-toggle="modal" data-target="#ShareModal" data-id="'.$file->IdFile.'" data-type="file" title="Share file"><i class="fa  fa-share-alt fa-fw"></i></a>
          </div>';
            $size = $this->formatBytes($file->FileSize);
            $modified = date("d.m.Y h:i",$file->FileLastModified);
            
            $data[$i][] = $preview;
            $data[$i][] = $checkbox;
            $data[$i][] = $name;
            $data[$i][] = $manage;
            $data[$i][] = $size;
            $data[$i][] = $modified;
            $i++;
        }
        return $data;   
    }
    
    
    
    /////////////////////////////////////////////
    ////////////////////////////////////////////
    //kopirane funkcije
    /////////////////////////////////////////////
    ////////////////////////////////////////
    
    protected function upcount_name_callback($matches) {
        $index = isset($matches[1]) ? intval($matches[1]) + 1 : 1;
        $ext = isset($matches[2]) ? $matches[2] : '';
        return ' ('.$index.')'.$ext;
    }

    protected function upcount_name($name) {
        return preg_replace_callback(
            '/(?:(?: \(([\d]+)\))?(\.[^.]+))?$/',
            array($this, 'upcount_name_callback'),
            $name,
            1
        );
    }
    
    protected function get_mime($filename) {
        if(!function_exists('mime_content_type')) {
            return $this->my_mime_content_type($filename);
        }
        else{
            return mime_content_type($filename);
        }
    }
    
    protected  function my_mime_content_type($filename){
        $mime_types = array(
            'txt' => 'text/plain',
            'htm' => 'text/html',
            'html' => 'text/html',
            'php' => 'text/html',
            'css' => 'text/css',
            'js' => 'application/javascript',
            'json' => 'application/json',
            'xml' => 'application/xml',
            'swf' => 'application/x-shockwave-flash',
            'flv' => 'video/x-flv',
            // images
            'png' => 'image/png',
            'jpe' => 'image/jpeg',
            'jpeg' => 'image/jpeg',
            'jpg' => 'image/jpeg',
            'gif' => 'image/gif',
            'bmp' => 'image/bmp',
            'ico' => 'image/vnd.microsoft.icon',
            'tiff' => 'image/tiff',
            'tif' => 'image/tiff',
            'svg' => 'image/svg+xml',
            'svgz' => 'image/svg+xml',
            // archives
            'zip' => 'application/zip',
            'rar' => 'application/x-rar-compressed',
            'exe' => 'application/x-msdownload',
            'msi' => 'application/x-msdownload',
            'cab' => 'application/vnd.ms-cab-compressed',
            // audio/video
            'mp3' => 'audio/mpeg',
            'qt' => 'video/quicktime',
            'mov' => 'video/quicktime',
            // adobe
            'pdf' => 'application/pdf',
            'psd' => 'image/vnd.adobe.photoshop',
            'ai' => 'application/postscript',
            'eps' => 'application/postscript',
            'ps' => 'application/postscript',
            // ms office
            'doc' => 'application/msword',
            'rtf' => 'application/rtf',
            'xls' => 'application/vnd.ms-excel',
            'ppt' => 'application/vnd.ms-powerpoint',
            // open office
            'odt' => 'application/vnd.oasis.opendocument.text',
            'ods' => 'application/vnd.oasis.opendocument.spreadsheet',
        );

        $tmp = explode('.', $filename);
        $ext = strtolower(array_pop($tmp));
        if (array_key_exists($ext, $mime_types)) {
            return $mime_types[$ext];
        } elseif (function_exists('finfo_open')) {
            $finfo = finfo_open(FILEINFO_MIME);
            $mimetype = finfo_file($finfo, $filename);
            finfo_close($finfo);
            return $mimetype;
        } else {
            return 'text/plain';
        }
    }
    
    protected function force_download($result){
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
    
    // Fix for overflowing signed 32 bit integers,
    // works for sizes up to 2^32-1 bytes (4 GiB - 1):
    protected function fix_integer_overflow($size) {
        if ($size < 0) {
            $size += 2.0 * (PHP_INT_MAX + 1);
        }
        return $size;
    }
    
    protected function createZip($filePath=''){
    if(count(scandir($filePath))<=2){
       die('Make sure u have something in folder before download...');            
    }    
    // Get real path for our folder
    $rootPath = realpath($filePath);
    // Initialize archive object
    $zip = new ZipArchive();
    $docname = "documents-export-".date("Y-m-d").'-'.$this->get_user_id();
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
    
    /**
     * rekurzivo brisanje foldera, brise sve fajlove unutar foldera
     * @param type $file_path - putanja fajla
     * @return type bool
     */
    protected function forceDeleteDir($file_path){
        $it = new RecursiveDirectoryIterator($file_path);
        $it = new RecursiveIteratorIterator($it, RecursiveIteratorIterator::CHILD_FIRST);
        foreach($it as $file) {
            if ('.' === $file->getBasename() || '..' ===  $file->getBasename()) continue;
            if ($file->isDir()) rmdir($file->getPathname());
            else unlink($file->getPathname());
        }
        return rmdir($file_path);
    }
    
    protected function handle_file_upload($uploaded_file, $name, $size, $type, $error,
            $index = null, $content_range = null) {
        $file = new \stdClass();
        $file->name = $this->get_file_name($uploaded_file, $name, $size, $type, $error,
            $index, $content_range);
        $file->size = $this->fix_integer_overflow(intval($size));
        $file->type = $type;
        $file->image=FALSE;
        if ($this->validate($uploaded_file, $file, $error, $index)) {
            $this->handle_form_data($file, $index);
            $upload_dir = $this->get_upload_path();
            if (!is_dir($upload_dir)) {
                mkdir($upload_dir, $this->options['mkdir_mode'], true);
            }
            $file_path = $this->get_upload_path($file->name,$file->Mask);
            $append_file = $content_range && is_file($file_path) &&
                $file->size > $this->get_file_size($file_path);
            if ($uploaded_file && is_uploaded_file($uploaded_file)) {
                // multipart/formdata uploads (POST method uploads)
                if ($append_file) {
                    file_put_contents(
                        $file_path,
                        fopen($uploaded_file, 'r'),
                        FILE_APPEND
                    );
                } else {
                    move_uploaded_file($uploaded_file, $file_path);
                }
            } else {
                // Non-multipart uploads (PUT method support)
                file_put_contents(
                    $file_path,
                    fopen('php://input', 'r'),
                    $append_file ? FILE_APPEND : 0
                );
            }
            $file_size = $this->get_file_size($file_path, $append_file);
            if ($file_size === $file->size) {
                $insert = true;
                if ($this->is_image($type)) {
                    $this->makeThumbnails($upload_dir, $file_path , $name);
                    $file->image=TRUE;
                }
            } else {
                $insert = false;
                $file->size = $file_size;
                if (!$content_range) {
                    unlink($file_path);
                    $file->error = "File upload aborted...";
                }
            }
            $file->FilePath = $this->get_upload_path($file->name, $file->Mask);
            $this->set_additional_file_properties($file,$insert);
            //$file->url = $this->get_download_url($file->name);
        }
        return $file;
    }
    
    protected function trim_file_name($file_path, $name, $size, $type, $error,
            $index, $content_range) {
        // Remove path information and dots around the filename, to prevent uploading
        // into different directories or replacing hidden system files.
        // Also remove control characters and spaces (\x00..\x20) around the filename:
        $name = trim(basename(stripslashes($name)), ".\x00..\x20");
        // Use a timestamp for empty filenames:
        if (!$name) {
            $name = str_replace('.', '-', microtime(true));
        }
        return $name;
    }

    protected function get_file_name($file_path, $name, $size, $type, $error,
            $index, $content_range) {
        $name = $this->trim_file_name($file_path, $name, $size, $type, $error,
            $index, $content_range);
        return $this->get_unique_filename(
            $file_path,
            $name,
            $size,
            $type,
            $error,
            $index,
            $content_range
        );
    }
    protected function validate($uploaded_file, $file, $error, $index) {
        if ($error) {
            $file->error = $error;
            return false;
        }
        $content_length = $this->fix_integer_overflow(intval(
            $this->get_server_var('CONTENT_LENGTH')
        ));
        $post_max_size = $this->get_config_bytes(ini_get('post_max_size'));
        if ($post_max_size && ($content_length > $post_max_size)) {
            $file->error = "File to big!";
            return false;
        }
        if (!preg_match($this->options['accept_file_types'], $file->name)) {
            $file->error = $this->get_error_message('accept_file_types');
            return false;
        }
        if ($uploaded_file && is_uploaded_file($uploaded_file)) {
            $file_size = $this->get_file_size($uploaded_file);
        } else {
            $file_size = $content_length;
        }
        if ($this->options['max_file_size'] && (
                $file_size > $this->options['max_file_size'] ||
                $file->size > $this->options['max_file_size'])
            ) {
            $file->error = "File is to big!";
            return false;
        }
        if ($this->options['min_file_size'] &&
            $file_size < $this->options['min_file_size']) {
            $file->error = "File is to small!";
            return false;
        }
        return true;
    }
    protected function get_upload_path($name=null,$mask=""){
        return $this->options['upload_dir'].$this->get_user_id().'/'.$mask.$name;
    }


    protected function get_unique_filename($file_path, $name, $size, $type, $error,
            $index, $content_range) {
        // Keep an existing filename if this is part of a chunked upload:
        $uploaded_bytes = $this->fix_integer_overflow(intval($content_range[1]));
        while(is_file($this->get_upload_path($name))) {
            if ($uploaded_bytes === $this->get_file_size(
                    $this->get_upload_path($name))) {
                break;
            }
            $name = $this->upcount_name($name);
        }
        return $name;
    }
    protected function is_image($type){
        $haystack = array(
            'image/jpeg',
            'image/jpg',
            'image/png',
            'image/gif'
        );
        if(in_array($type, $haystack)){
            return TRUE;
        }
        return FALSE;
    }
    

    protected function makeThumbnails($updir, $img, $name,$MaxWe=100,$MaxHe=100){
        $updir.="thumb/";
        if(!is_dir($updir)){
            mkdir($updir,  $this->options['mkdir_mode']);
        }
        $arr_image_details = getimagesize($img); 
        $width = $arr_image_details[0];
        $height = $arr_image_details[1];

        $percent = 100;
        if($width > $MaxWe) $percent = floor(($MaxWe * 100) / $width);

        if(floor(($height * $percent)/100)>$MaxHe)  
        $percent = (($MaxHe * 100) / $height);

        if($width > $height) {
            $newWidth=$MaxWe;
            $newHeight=round(($height*$percent)/100);
        }else{
            $newWidth=round(($width*$percent)/100);
            $newHeight=$MaxHe;
        }

        if ($arr_image_details[2] == 1) {
            $imgt = "ImageGIF";
            $imgcreatefrom = "ImageCreateFromGIF";
        }
        if ($arr_image_details[2] == 2) {
            $imgt = "ImageJPEG";
            $imgcreatefrom = "ImageCreateFromJPEG";
        }
        if ($arr_image_details[2] == 3) {
            $imgt = "ImagePNG";
            $imgcreatefrom = "ImageCreateFromPNG";
        }


        if ($imgt) {
            $old_image = $imgcreatefrom($img);
            $new_image = imagecreatetruecolor($newWidth, $newHeight);
            imagecopyresized($new_image, $old_image, 0, 0, 0, 0, $newWidth, $newHeight, $width, $height);

           $imgt($new_image, $updir.$name);
            return;    
        }
    }
    
    function get_config_bytes($val) {
        $val = trim($val);
        $last = strtolower($val[strlen($val)-1]);
        switch($last) {
            case 'g':
                $val *= 1024;
            case 'm':
                $val *= 1024;
            case 'k':
                $val *= 1024;
        }
        return $this->fix_integer_overflow($val);
    }
    
    //connection with db
    protected function set_additional_file_properties($file,$content_range=false){
        $this->load->model("FileModel");
        $ext = pathinfo($file->FilePath,PATHINFO_EXTENSION);
        $mime = $this->get_mime($file->name);
        $this->load->model("FileModel");
        $file_type = $this->FileModel->getFileType($mime);
        $file->IdFile = null;
        if($content_range){
            $file->IdFile=$this->FileModel->insertUserFile($this->get_user_id(),$file_type,$file->IdFolder,$ext,$file->name,$file->FilePath,$file->size);
        }
        $file->FileLastModified = date("d.m.Y h:i",time());
        if($file->IdFile){
            $file->deleteUrl = $this->options['script_url'].'deleteFile/'.$file->IdFile;
        }
        $this->set_download_url($file);
        
        
    }
    
    protected function handle_form_data($file, $index) {
        $file->IdFolder = @intval($_REQUEST['IdFolder'][$index]);
        $file->Mask = @$_REQUEST['Mask'][$index];
    }

    protected function set_download_url($file) {
        //direct-link
        if(!empty($file->IdFile)){
            $file->url = $this->options['script_url'].'/downloadFile/'.$file->IdFile;
        }
        else{
            $file->url = $this->options['script_url'].'/directDownload/'.  rawurlencode(base64_encode($file->FilePath));
        }
        if(empty($file->Mask)){
            $file->Mask = "";
        }
        if($file->image){
            $file->url = base_url().'data/'.$this->get_user_id().'/'.$file->Mask.$file->name;
            $file->thumbnailUrl = base_url().'data/'.$this->get_user_id().'/'.$file->Mask.'thumb/'.$file->name;
        }
    }
    
    protected function formatBytes($size, $precision = 2)
    {
//        $base = log($size, 1024);
//        $suffixes = array('B', 'KB', 'MB', 'GB', 'TB');   
//
//        return round(pow(1024, $base - floor($base)), $precision) . $suffixes[floor($base)];
        if ($size >= 1073741824) {
            $size = number_format($size / 1073741824, 2) . ' GB';
        } elseif ($size >= 1048576) {
            $size = number_format($size / 1048576, 2) . ' MB';
        } elseif ($size >= 1024) {
            $size = number_format($size / 1024, 2) . ' KB';
        } elseif ($size > 1) {
            $size = $size . ' bytes';
        } elseif ($size == 1) {
            $size = $size . ' byte';
        } else {
            $size = '0 bytes';
        }

        return $size;
    }
    
    
    public function saveFile(){
        if(isset($_POST['json'])){
            $json = json_decode($_POST['json']);
            $this->load->model("FileModel");
            $file = $this->FileModel->getFileById($json->IdFile);
            if(file_exists($file->FilePath)){
                file_put_contents($file->FilePath, $json->content , LOCK_EX);
                $size = $this->get_file_size($file->FilePath);
                $this->FileModel->updateFileSize($file->IdFile,$size);
                die(TRUE);
            }
            else{
                die(FALSE);
            }
        }
        
    }
    
    protected function prepareName($param){
        $clean = trim($param);
        return rawurlencode(preg_replace('/\s+/', '_', $clean));
    }
    
    
    public function shareFileFolders(){
        $json = json_decode($_POST['json']);
        $this->load->model("ShareModel");
        if($json->Type=="folder"){
            $this->load->model("FolderModel");
            $folder = $this->FolderModel->getFolderById($json->Id);
            foreach($json->users as $user){
                //shareFolder($IdOwner,$IdShared,$IdFolder,$Name,$Path,$SharePrivilege=  self::READ)
                $this->ShareModel->shareFolder($this->get_user_id(),$user,$json->Id,$folder->FolderName,$folder->FolderPath,$json->SharePrivilege);
            }
            foreach($json->unshare as $user){
                $this->ShareModel->unshareFolder($this->get_user_id(),$user,$json->Id);
            }
        }
        else{
            $this->load->model("FileModel");
            $file=$this->FileModel->getFileById($json->Id);
            foreach($json->users as $user){
                //shareFile($IdOwner,$IdShared,$IdFile,$Name,$Path,$SharePrivilege=  self::READ,$IdFolder=0)
                $this->ShareModel->shareFile($this->get_user_id(),$user,$json->Id,$file->FileName,$file->FilePath,$json->SharePrivilege,$file->IdFolder);
            }
            foreach($json->unshare as $user){
                $this->ShareModel->unshareFile($this->get_user_id(),$user,$json->Id,$file->IdFolder);
            }
        }
        die(TRUE);
    }
    
    public function checkShared(){
        $id = intval($_POST['id']);
        $type = $_POST['type'];
        $this->load->model("ShareModel");
        if($type=="folder"){
            $result = $this->ShareModel->getAllSharedUsersForFolder($id);  
        }
        else{
            $result = $this->ShareModel->getAllSharedUsersForFile($id);
        }
        if(!empty($result)){
            header("Content-type:application/json");
            echo json_encode($result);
        }
    }
    
    public function sharedWithYou(){
        if(isset($_GET['id_folder'])){
            $id_folder=intval($_GET['id_folder']);
        }
        else{
            $id_folder = 0;
        }
        //folders
        $this->load->model("ShareModel");
        $folders=$this->ShareModel->getAllSharedFolders($this->get_user_id(),$id_folder);
        $i = 0;
        $data = array();
        foreach($folders as $folder){
            $owner = $folder['UserName'];
            $shared_on = $shared_on = date("d-m-Y h:i:s",$folder["ShareCreated"]);
            $name = "<a href='".  base_url()."Files/shared_with_you/".$folder['IdFolder']."'>".$folder["Name"]."</a>";
            $privilege = $this->switchPrivilege($folder["SharePrivilege"]);
            $modified = '';
            $modify="<a href='#' class='unshare' data-shareduser='".$folder["IdShared"]."' data-id='".$folder["IdFolder"]."' data-type='folder' title='Remove from share'><i class='fa fa-minus-circle'></i></a>";
            $modify.="<a href='".base_url()."ApiFiles/downloadFolder/".$folder["IdFolder"]."' title='Download entire folder'><i class='fa  fa-cloud-download fa-fw'></i></a>";
            if($folder["SharePrivilege"]==3){
                $modify.="<a href='".base_url()."ApiFiles/deleteFolder/".$folder["IdFolder"]."' title='delete folder'><i class='glyphicon glyphicon-trash' title='folder'></i></a>";
            }
            
            $size = "<i class='fa fa-folder-open' title='folder'></i>";
            $data[$i][]=$owner;
            $data[$i][]=$shared_on;
            $data[$i][]=$name;
            $data[$i][]=$privilege;
            $data[$i][]=$size;
            $data[$i][]=$modified;
            $data[$i][]=$modify;
            $i++;
        }
        //files
        $files = $this->ShareModel->getAllSharedFiles($this->get_user_id(),$id_folder);
        foreach($files as $file){
            $owner = $file['UserName'];
            $shared_on = $shared_on = date("d-m-Y h:i:s",$file["ShareCreated"]);
            $name = "<a href='".  base_url()."ApiFiles/downloadFile/".$file["IdFile"]."'>".$file["Name"]."</a>";
            
            
            
            $privilege = $this->switchPrivilege($file["SharePrivilege"]);
            $modified = date("d.m.Y h:i",$file["FileLastModified"]);
            $modify="<a href='#' class='unshare' data-shareduser='".$file["IdShared"]."' data-id='".$file["IdFile"]."' data-type='file' title='Remove from share'><i class='fa fa-minus-circle'></i></a>";
            $modify.="<a href='".base_url()."ApiFiles/downloadFile/".$file["IdFile"]."' title='Download file'><i class='fa  fa-cloud-download fa-fw'></i></a>";
            if($file["SharePrivilege"]==3){
                $modify.="<a href='".base_url()."ApiFiles/deleteFile/".$file["IdFile"]."' title='delete file'><i class='glyphicon glyphicon-trash'></i></a>";
            }
            if(in_array($file["FileExtension"], $this->editableFileTypes)&&$file["SharePrivilege"]!=1){
                $modify.='<a href="#" data-idfile="'.$file["IdFile"].'" class="edit"><i class="fa  fa-pencil fa-fw"></i></a>';
            }
            $size = $this->formatBytes($file['FileSize']);
            $data[$i][]=$owner;
            $data[$i][]=$shared_on;
            $data[$i][]=$name;
            $data[$i][]=$privilege;
            $data[$i][]=$size;
            $data[$i][]=$modified;
            $data[$i][]=$modify;
            $i++;
        }
        $content['data'] = $data;
        $this->generate_response($content,TRUE);
    }
    
    public function sharedWithOthers(){
        if(isset($_GET['id_folder'])){
            $id_folder=intval($_GET['id_folder']);
        }
        else{
            $id_folder = 0;
        }
        //folders
        $this->load->model("ShareModel");
        $folders=$this->ShareModel->getAllSharedFoldersWithOthers($this->get_user_id(),$id_folder);
        $i = 0;
        $data = array();
        foreach($folders as $folder){
            $shareduser = $folder['UserName'];
            $shared_on = $shared_on = date("d-m-Y h:i:s",$folder["ShareCreated"]);
            $name = "<a href='".  base_url()."Files/shared_with_others/".$folder['IdFolder']."'>".$folder["Name"]."</a>";
            $privilege = $this->switchPrivilege($folder["SharePrivilege"]);
            $modified = '';
            $modify="<a href='#' class='unshare' data-shareduser='".$folder["IdShared"]."' data-id='".$folder["IdFolder"]."' data-type='folder' title='Unshare this folder'><i class='fa fa-minus-circle'></i></a>";
            
            $size = "<i class='fa fa-folder-open' title='folder'></i>";
            $data[$i][]=$shareduser;
            $data[$i][]=$shared_on;
            $data[$i][]=$name;
            $data[$i][]=$privilege;
            $data[$i][]=$size;
            $data[$i][]=$modified;
            $data[$i][]=$modify;
            $i++;
        }
        $files = $this->ShareModel->getAllSharedFilesWithOthers($this->get_user_id(),$id_folder);
        foreach($files as $file){
            $shareduser = $file['UserName'];
            $shared_on = $shared_on = date("d-m-Y h:i:s",$file["ShareCreated"]);
            $name = "<a href='".  base_url()."ApiFiles/downloadFile/".$file["IdFile"]."'>".$file["Name"]."</a>";
            if(in_array($file["FileExtension"], $this->editableFileTypes)){
                $name.='&nbsp;<a href="#" data-idfile="'.$file["IdFile"].'" class="edit">[Edit]</a>';
            }
            
            
            $privilege = $this->switchPrivilege($file["SharePrivilege"]);
            $modified = date("d.m.Y h:i",$file["FileLastModified"]);
            $modify="<a href='#' class='unshare' data-shareduser='".$file["IdShared"]."' data-id='".$file["IdFile"]."' data-type='file' title='Unshare this file'><i class='fa fa-minus-circle'></i></a>";
            
            $size = $this->formatBytes($file['FileSize']);
            $data[$i][]=$shareduser;
            $data[$i][]=$shared_on;
            $data[$i][]=$name;
            $data[$i][]=$privilege;
            $data[$i][]=$size;
            $data[$i][]=$modified;
            $data[$i][]=$modify;
            $i++;
        }
        $content['data'] = $data;
        $this->generate_response($content,TRUE);
        
    }
    
    public function unshareFilesFolders(){
        $json = json_decode($_POST['json']);
        $this->load->model("ShareModel");
        if($json->type=="folder"){
            $this->ShareModel->unshareFolder($this->get_user_id(),$json->IdShared,$json->id);
        }
        else{
           $this->load->model("FileModel");
           $file=$this->FileModel->getFileById($json->id);
           if(!empty($file)){
               $this->ShareModel->unshareFile($this->get_user_id(),$json->IdShared,$json->id,$file->IdFolder);
           }
            
        }
        die(TRUE);
    }
    
    protected function switchPrivilege($param){
        $type = intval($param);
        switch ($type){
            case 1:
                return "READ";
            case 2:
                return "WRITE";
            case 3:
                return "DELETE";
        }
    }
    
    public function favourites(){
        //folders
        $this->load->model("ShareModel");
        $folders=$this->ShareModel->getAllFavFolders($this->get_user_id());
        $i = 0;
        $data = array();
        foreach($folders as $folder){
            if($folder["Favourites"]){
                $fav= '<a href="#" data-id="'.$folder["IdFolder"].'" data-type="folder" class="unsetfav"><i class="fa fa-star fa-fw"></i></a>';
            }
            else{
                $fav= '<a href="#" data-id="'.$folder["IdFolder"].'" data-type="folder" class="setfav"><i class="fa fa-star-o fa-fw"></i></a>';
            }
            $name = "<a href='".  base_url()."Files/index/".$folder['FolderMask'].$folder["FolderName"]."'>".$folder["FolderName"]."</a>";
            $modified = '';
            
            $modify="<a href='".base_url()."ApiFiles/downloadFolder/".$folder["IdFolder"]."' title='Download entire folder'><i class='fa  fa-cloud-download fa-fw'></i></a>";
            
            $size = "<i class='fa fa-folder-open' title='folder'></i>";
            $data[$i][]=$fav;
            $data[$i][]=$name;
            $data[$i][]=$size;
            $data[$i][]=$modified;
            $data[$i][]=$modify;
            $i++;
        }
        //files
        $files = $this->ShareModel->getAllFavFiles($this->get_user_id());
        foreach($files as $file){
            if($file["Favourites"]){
                $fav= '<a href="#" data-id="'.$file["IdFile"].'" data-type="file" class="unsetfav"><i class="fa fa-star fa-fw"></i></a>';
            }
            else{
                $fav= '<a href="#" data-id="'.$fav["IdFile"].'" data-type="folder" class="setfav"><i class="fa fa-star-o fa-fw"></i></a>';
            }
            $name = "<a href='".  base_url()."ApiFiles/downloadFile/".$file['IdFile']."'>".$file["FileName"]."</a>";
            $modified = date("m.d.Y h:i",$file['FileLastModified']);
            
            $modify="<a href='".base_url()."ApiFiles/downloadFile/".$file["IdFile"]."' title='Download file'><i class='fa  fa-cloud-download fa-fw'></i></a>";
            $size = $this->formatBytes($file['FileSize']);
            $data[$i][]=$fav;
            $data[$i][]=$name;
            $data[$i][]=$size;
            $data[$i][]=$modified;
            $data[$i][]=$modify;
            $i++;
        }
        $content['data'] = $data;
        $this->generate_response($content,TRUE);
    }
}
