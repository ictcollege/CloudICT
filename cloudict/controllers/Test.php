<?php

/*
 *  @author Darko Lesendric <dlesendric at https://github.com/ @Darko_Lesendric at https://twitter.com/>
 */

/**
 * Description of ApiFiles
 *
 * @author Darko
 */
class Test extends Frontend_Controller{
    protected $options = array(
        
    );
    
    public function __construct() {
        parent::__construct();
        $this->options = array(
            'upload_dir' => dirname($_SERVER['SCRIPT_FILENAME'])."/data/",
            'script_url' => base_url().get_class($this),
            'mkdir_mode' => 0755
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
        $response = $this->get_file_objects();
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
        return TRUE;
    }
    
    protected function generate_response($content, $print_response = true) {
        if ($print_response) {
            header("Content-type:application/json");
            echo json_encode($content);
        }
        return $content;
    }
    
    protected function get_file_objects() {
        $this->load->model("FolderModel");
        $folders = $this->FolderModel->getAllUserFolders($this->get_user_id());
        $this->load->model("FileModel");
        $files = $this->FileModel->getAllUserFiles($this->get_user_id());  
        $data = $this->renderFilesAndFolders($files, $folders);
        $content['files'] = $data;
        return $content;
    }
    
    
    //kreiranje fajla
    public function createFile($IdFolder,$filename) {
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
    public function renameFile($IdFile,$newName) {
        $this->load->model("FileModel");
        $result = $this->FileModel->getFileById($IdFile);
        if($result){
            if(file_exists($result->FilePath)){
                $newFilePath = dirname($result->FilePath)."/".$newName;
                if(rename($result->FilePath, $newFilePath)){
                    $this->FileModel->changeFileName($IdFile,$newName,$newFilePath);
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
                    $this->checkFileTypeAndDownload($result);
                }
                else{
                    die("You can't download this file, file not shared with you!");
                }
            }
        }
    }
    //brisanje fajla
    protected function deleteFile($IdFile){
        $this->load->model("FileModel");
        $file=$this->FileModel->getFileById($IdFile);
        if($file->IdUser==$this->get_user_id()){
            //user is owner of file
            unlink($file->FilePath);
            $this->FileModel->deleteFile($IdFile);
            die(TRUE);
        }
        $this->load->model("ShareModel");
        if($this->ShareModel->canDownload($this->get_user_id(),$IdFile)){
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
    
    public function newFolder($FolderName,$IdFolder=0) {
        if($IdFolder == 0){
            $mask = "";
        }
        else{
            
            //$filepath = $this->get_upload_path($FilePath.$FolderName);
            $this->load->model("FolderModel");
            $folder = $this->FolderModel->getFolderById($IdFolder);
            if(empty($folder)){
                die("Folder not exists!");
            }
            $mask = $folder->FolderMask;
        }
        $path = $this->options['upload_dir'].$this->get_user_id().'/'.$mask.$FolderName;
        if(file_exists($path)){
            $path = $this->upcount_name($path);
        }
        $path = strtolower($path);
        if(mkdir($path,  $this->options['mkdir_mode'])){
            $this->load->model("FolderModel");
            //insertUserFolder($IdUser,$FolderName,$FolderMask,$FolderPath)
            $this->FolderModel->insertUserFolder($this->get_user_id(), $FolderName, $mask,$path);
            die(TRUE);
        }
        die(FALSE);
    }
    //rename foldera
    public function renameFolder($IdFolder,$newName) {
        $this->load->model("FolderModel");
        $result = $this->FolderModel->getFolderById($IdFolder);
        if($result){
            if(file_exists($result->FolderPath)){
                $newFolderPath = dirname($result->FolderPath)."/".$newName;
                rename($result->FolderPath, $newFolderPath);
                $this->FolderModel->changeFolderName($IdFolder,$newName,$newFolderPath);
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
    
    
    protected function renderFilesAndFolders($files,$folders){
        $data = array();
        $i = 0;
        
        foreach($folders as $folder){
            $preview = '<span class="size"><i class="fa fa-folder-open fa-fw"></i></span>';
            $checkbox = '<input type="checkbox"></input>';
            $name = $folder['FolderName'];
            $manage =  '<div class="btn-group"><a href="#" data-toggle="dropdown" title="edit/rename"><i class="fa  fa-pencil fa-fw"></i></a>
          <ul class="dropdown-menu">
            <li><a href="#" data-idfolder="'.$folder["IdFolder"].'"  class="rename">Rename</a></li>
            <li><a href="#" data-idfolder="'.$folder["IdFolder"].'"  class="move">Move</a></li>          
          </ul>
          <a href="#" data-id="'.$folder["IdFolder"].'" data-type="Folder" class="delete"><i class="glyphicon glyphicon-trash"></i></a>
          <a href="'.$this->options['script_url'].'/downloadFolder/'.$folder['IdFolder'].'" data-type="Folder" data-id="'.$folder["IdFolder"].'" class="download"  title="Download '.$folder['FolderName'].'"><i class="fa  fa-cloud-download fa-fw"></i></a>
          <a href="#" class="share" data-toggle="modal" data-target="#ShareModal" data-idfolder="'.$folder["IdFolder"].'" title="Share folder"><i class="fa  fa-share-alt fa-fw"></i></a>
          </div>';
            $size = '';
            $modified = '';
            
//            $data[$i][] = $preview;
//            $data[$i][] = $checkbox;
//            $data[$i][] = $name;
//            $data[$i][] = $manage;
//            $data[$i][] = $size;
//            $data[$i][] = $modified;
            $data[$i]['name']=$folder['FolderName'];
            $data[$i]['size'] = 0;
            $data[$i]['FilePath'] = $folder['FolderPath'];
            $data[$i]['thumbnailUrl'] = '';
            $data[$i]['deleteUrl'] = base_url()."ApiFiles/deleteFolder/".$folder["IdFolder"];
            $data[$i]['deleteType'] = "DELETE";
            $data[$i]['Mask'] = $folder['FolderMask'];
            $data[$i]['IdFile']=$folder['IdFolder'];
            $data[$i]['FileTypeMime'] = "DIR";
            $data[$i]['FileExtension']="";
            $data[$i]['FileLastModified']="";
            $data[$i]['url']=  base_url().'files/';
            $i++;
        }
        
        foreach($files as $file){
            $preview = '<span class="size"><i class="fa fa-file-text-o fa-fw"></i></span>';
            $checkbox = '<input type="checkbox"></input>';
            $name = $file['FileName'];
            $manage =  '<div class="btn-group"><a href="#" data-toggle="dropdown" title="edit/rename"><i class="fa  fa-pencil fa-fw"></i></a>
          <ul class="dropdown-menu">
            <li><a href="#" data-idfile="'.$file["IdFile"].'"  class="rename">Rename</a></li>
            <li><a href="#" data-idfile="'.$file["IdFile"].'"  class="move">Move</a></li>
            <li><a href="#" data-idfile="'.$file["IdFile"].'" class="edit">Edit</a></li>
          </ul>
          <a href="#" data-id="'.$file["IdFile"].'" data-type="File" class="delete"><i class="glyphicon glyphicon-trash"></i></a>
          <a href="'.$this->options['script_url'].'/downloadFile/'.$file['IdFile'].'" data-type="File" data-id="'.$file["IdFile"].'" class="download"  title="Download '.$file['FileName'].'"><i class="fa  fa-cloud-download fa-fw"></i></a>
          <a href="#" class="share" data-toggle="modal" data-target="#ShareModal" data-idfile="'.$file["IdFile"].'" title="Share folder"><i class="fa  fa-share-alt fa-fw"></i></a>
          </div>';
            $size = $file['FileSize'];
            $modified = date("d.m.Y h:i",$file['FileLastModified']);
            
//            $data[$i][] = $preview;
//            $data[$i][] = $checkbox;
//            $data[$i][] = $name;
//            $data[$i][] = $manage;
//            $data[$i][] = $size;
//            $data[$i][] = $modified;
            $data[$i]['name']=$file['FileName'];
            $data[$i]['size'] = $file['FileSize'];
            $data[$i]['FilePath'] = $file['FilePath'];
            $data[$i]['thumbnailUrl'] = '';
            $data[$i]['deleteUrl'] = base_url()."ApiFiles/deleteFile/".$file["IdFile"];
            $data[$i]['deleteType'] = "DELETE";
            $data[$i]['Mask'] = $file['IdFolder'];
            $data[$i]['IdFile']=$file['IdFile'];
            $data[$i]['FileTypeMime'] = $file['FileExtension'];
            $data[$i]['FileExtension']=$file['FileExtension'];
            $data[$i]['FileLastModified']=date("d.m.Y h:i",$file["FileLastModified"]);
            $data[$i]['url']= $this->options['script_url'].'/downloadFile/'.$file['IdFile'];
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
        if ($this->validate($uploaded_file, $file, $error, $index)) {
            $this->handle_form_data($file, $index);
            $upload_dir = $this->get_upload_path();
            if (!is_dir($upload_dir)) {
                mkdir($upload_dir, $this->options['mkdir_mode'], true);
            }
            $file_path = $this->get_upload_path($file->name);
            $append_file = $content_range && is_file($file_path) &&
                $file->size > $this->get_file_size($file_path);
            $chunked_file = FALSE;
            if ($uploaded_file && is_uploaded_file($uploaded_file)) {
                // multipart/formdata uploads (POST method uploads)
                if ($append_file) {
                    file_put_contents(
                        $file_path,
                        fopen($uploaded_file, 'r'),
                        FILE_APPEND
                    );
                    $chunked_file = TRUE;
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
                $file->url = $this->get_download_url($file->name);
                if ($this->is_valid_image_file($file_path)) {
                    $this->handle_image_file($file_path, $file);
                }
            } else {
                $file->size = $file_size;
                if (!$content_range && $this->options['discard_aborted_uploads']) {
                    unlink($file_path);
                    $file->error = $this->get_error_message('abort');
                }
            }
            $file->chunk = $chunked_file;//provera dal je chunked file za database 
            $this->set_additional_file_properties($file);
        }
        return $file;
    }
    
    
    
    
}
