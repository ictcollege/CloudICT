<?php

/*
 *  @author Darko Lesendric <dlesendric at https://github.com/ @Darko_Lesendric at https://twitter.com/>
 */

/**
 * Description of ApiFiles
 * ApiFiles je glavni api za sve radnje sa fajlovima i folderima ali share-om. Baziran je po
 * https://github.com/blueimp/jQuery-File-Upload
 * ali ovaj link samo moze da vam dodatno pomogne pri dokumentaciji, generalno cela klasa 
 * je izmenjena i trebalo bi se drzati komentara navedenih u ovom fajlu
 * 1. prvo sta bi trebalo izmeniti ovde je putanja directDownload na kontroler koji nema proveru sesija ko je ulogovan.
 * 2. Promeniti $options['uplaod_dir'] - mesto gde se smestaju fajlovi, moze biti bilo koja putanja na kompu
 * 3. Slike se moraju cuvati na appache serveru kako bi se generalno i ucitavale, da bi to izmenili treba malo prepraviti set_download_url() u putanju koju vi zelite.
 * @author Darko
 */
class ApiFiles extends Frontend_Controller{
    protected $options = array(
        
    ); //for later use
    protected $Mask = "";

    //for errors and messages
    const ERROR = "danger";
    const WARNING = "warning";
    const SUCCESS = "success";

    //koji sve fajlovi smeju da se edituju
    //which extension can edit user
    public static $editableFileTypes = array(
        'txt','asp','aspx','axd','asx','asmx','ashx','css','cfm','yaws','swf','html','htm','xhtml','jhtml','jsp','jspx','wss','java','do','action','js','pl','php','inc','php4','php3','phtml','py','rb','rhtml','xml','rss','svg','cgi','dll','cs','sql'
    );
    /*
     * u konstruktor mozete dodati kasnije opcije ako zelite nesto da izmenite
     */
    public function __construct() {
        parent::__construct();
        $this->options = array(
            'upload_dir' => dirname($_SERVER['SCRIPT_FILENAME'])."/data/", //main upload directory
            'script_url' => base_url().get_class($this).'/', //this script name
            'redirect_url' => base_url()."Files", //where to redirect when something is wrong
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
            'accept_file_types' => '/.+$/i', //any
            'max_file_size' => null, //no limit
            'min_file_size' => 1
        );

    }
    
    public function index(){
        $this->initialize();
    }
    /**
     *  this method start all methods 
     *  ovaj metod se koristi startovanje u svim slucajevima zahteva
     */
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
        $this->fixDiskUsed(); //this will fix disk used if sometimes trigers block
    }
    /**
     * delete method , decide what to delete
     * delete metod , odlucuje sta se i kako brise
     * parametre slati putem GET
     */
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
            case "File": case "file" :
                $this->deleteFile($Id);
                break;
            case "Folder": case "folder":
                $this->deleteFolder($Id);
                break;
            default :
                die(FALSE);
                break;
        }
    }

    /**
     * default method to get all files and folders
     * @param type $print_response 
     * @return type json 
     */
    protected function get($print_response = true) {
        $id_folder = NULL;
        if(isset($_GET['id_folder'])){
            $id_folder = (!empty($_GET['id_folder'])) ? intval($_GET['id_folder']) : NULL;
        }
        if(isset($_GET['Mask'])){
            $this->Mask = $_GET['Mask']; //relative path from user root directory
        }
        
        $response = $this->get_file_objects($id_folder); 
        return $this->generate_response($response, $print_response);
    }
    /**
     * main method for any $_POST and $_FILES;
     * glavni metod kad se nesto salje na server putem $_POST i  $_FILES
     * @param type $print_response
     * @return type json
     */
    protected function post($print_response = true) {
        $upload = isset($_FILES['files']) ?
            $_FILES['files'] : NULL;
        // Parse the Content-Disposition header, if available:
        $file_name = $this->get_server_var('HTTP_CONTENT_DISPOSITION') ?
            rawurldecode(preg_replace(
                '/(^[^"]+")|("$)/',
                '',
                $this->get_server_var('HTTP_CONTENT_DISPOSITION')
            )) : NULL;
        // Parse the Content-Range header, which has the following form:
        // Content-Range: bytes 0-524287/4000000 ( chunk )
        $content_range = $this->get_server_var('HTTP_CONTENT_RANGE') ?
            preg_split('/[^0-9]+/', $this->get_server_var('HTTP_CONTENT_RANGE')) : null;
        $size =  $content_range ? $content_range[3] : null;
        $files = array();
        $disk_space_remain = ($this->session->userdata('diskquota')-$this->session->userdata('diskused'));
        if ($upload && is_array($upload['tmp_name'])) {
            // param_name is an array identifier like "files[]",
            // $_FILES is a multi-dimensional array:
            foreach ($upload['tmp_name'] as $index => $value) {
                if($upload['size'][$index]>$disk_space_remain){
                    $upload['error'][$index]='You have probably exceeded your disk quota!';
                }
                
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
            if($upload['size'][$index]>$disk_space_remain){
                    $upload['error']='You have probably exceeded your disk quota!';
            }
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
    
    /**
     * 
     * @param type $str string , you can echo anything to body of this class if need in future
     */
    protected function body($str) {
        echo $str;
    }
    
    protected function header($str) {
        header($str);
    }
    /**
     * https , if need in future 
     * nije testirano nikad
     */
    protected function send_access_control_headers() {
        $this->header('Access-Control-Allow-Origin: '.$this->options['access_control_allow_origin']);
        $this->header('Access-Control-Allow-Credentials: '
            .($this->options['access_control_allow_credentials'] ? 'true' : 'false'));
        $this->header('Access-Control-Allow-Methods: '
            .implode(', ', $this->options['access_control_allow_methods']));
        $this->header('Access-Control-Allow-Headers: '
            .implode(', ', $this->options['access_control_allow_headers']));
    }
    /**
     * important to no cache response
     */
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
    /**
     * this generates json
     * @param type $content can be anything like objects and arrays
     * @param type $print_response
     * @return type json
     */
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
    
    /**
     * main function to get all files and folders
     * glavna funkcija za dohvatanje svih fajlova i foldera
     * @param type $IdFolder int
     * @return type
     */
   
    protected function get_file_objects($IdFolder=NULL) {
        $this->load->model("FolderModel");
        $folders = $this->FolderModel->getAllUserFolders($this->get_user_id(),$IdFolder);
        $this->load->model("FileModel");
        $files = $this->FileModel->getAllUserFiles($this->get_user_id(),$IdFolder);
        $data = $this->renderFilesAndFolders($files, $folders);
        $content['files'] = $data;
        return $content;
    }
    
    
    /**
     * public function to create file
     * echo 1 : 0
     * glavna funkcija za kreiranje fajlova
     */
    public function createFile() {
        $file = json_decode($_POST['json']);
        $filename = $this->prepareName($file->FileName);
        if(empty($file->IdFolder)){
            $path=$this->options['upload_dir'].$this->get_user_id().'/'.$filename;
            $file->IdFolder=NULL;
        }
        else{
            $this->load->model("FolderModel");
            $folder = $this->FolderModel->getFolderById($file->IdFolder);
            if(empty($folder)){
                $this->generateError("Folder not exists!");
            }
            $path=$folder->FolderPath.'/'.$filename;
        }
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
            $this->FileModel->insertUserFile($this->get_user_id(),$mime,$file->IdFolder,$ext,$filename,$path,$size);
            die(TRUE);
        }
       }
    /**
     * public function to rename file
     * accept json
     * metod za preimenovanje fajla, slati json 
     * echo 1 : 0
     */
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
    /**
     * funkcija za download fajlova
     * public method to download files
     * @param type $IdFile int IdFile
     */
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
                    $this->generateError("You can't download this file, file not shared with you!",  self::WARNING);
                    
                }
            }
        }
    }
    /**
     * public funkcija za brisanje fajlova
     * public method to delete file, pass file ID
     * @param type $IdFile int
     */
    public function deleteFile($IdFile){
        $this->load->model("FileModel");
        $file=$this->FileModel->getFileById($IdFile);
        if($file->IdUser==$this->get_user_id()){
            //user is owner of file
            if(file_exists($file->FilePath)):
                unlink($file->FilePath);
            endif;
            $this->FileModel->deleteFile($IdFile);
            $this->updateUserQuota($file->FileSize,false);
            die(TRUE);
        }
        $this->load->model("ShareModel");
        if($this->ShareModel->canExecute($this->get_user_id(),$file->IdFile)){
            if(unlink($file->FilePath)){
                $this->FileModel->deleteFile($IdFile);
                $this->updateUserQuota($file->FileSize, false, true,$file->IdUser);
                die(TRUE);
            }
            die(FALSE);
        }
        else{
            $this->generateError("You don't have permision to download this file!");
        }
        
        
    }
    
    /**
     * kreiranje foldera
     * slati json, ipak funkcija menja sve u lowercase i stavlja donje crte na prazna mesta
     */
    //kreiranje foldera
    //folder name treba prepraviti, mozda parametre treba slati preko json-a kako bi mogli sa praznim mestom mogo da se napravi folder prim( New folder) sad preimenuje u (new_folder)
    
    public function newFolder() {
        $folder = json_decode($_POST['json']);
        if(empty($folder)){
            $this->generateError("Folder not exists!");
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
            $this->FolderModel->insertUserFolder($this->get_user_id(), $folder->FolderName, $folder->Mask,$path,($folder->IdFolder==0)? NULL : $folder->IdFolder);
            die(TRUE);
        }
        die(FALSE);
    }
    /**
     * rename folders
     * pass json
     */
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
                exit;
            }
            
        }
        die(FALSE);
    }
    /**
     * downlaod folder (force_download)
     * @param type $IdFolder int
     */
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
                    $this->generateError("You don't have permision to download this folder!");
                }
            }
            
        }
    }
    /**
     * Brisanje foldera i svih child itema u njemu
     * Delete folder and all child items
     * @param type $IdFolder int
     */
    public function deleteFolder($IdFolder){
        $this->load->model("FolderModel");
        $result = $this->FolderModel->getFolderById($IdFolder);
        if($result && $result->IdUser == $this->get_user_id()){
            //user is owner of folder
            if(file_exists($result->FolderPath)){
                $this->forceDeleteDir($result->FolderPath);
            }
            //delete from db where constraint do all recursive stuff
            $this->FolderModel->deleteFolder($IdFolder);
            die(TRUE);
        }
        else{
            $this->load->model("ShareModel");
            if($this->ShareModel->canExecute($this->get_user_id(),NULL,$IdFolder)){
                if(file_exists($result->FolderPath)){
                    $this->forceDeleteDir($result->FolderPath);
                }
                //delete from db where constraint do all recursive stuff
                $this->FolderModel->deleteFolder($IdFolder);
                die(TRUE);
            }
            else{
                $this->generateError("You don't have permision to delete this folder!");
            }
        }
        die(FALSE);
    }
    /**
     * if someone want to put that file or folder in favourites so he can find him later easy
     * metod za dodavanje fajlova i foldera u favourites
     * @param type $IdFile - int
     * @param type $Type - type (folder/file)
     * @param type $Unset bool (set, unset)
     */
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
    
    /**
     * glavni metod za renderovanje svih fajlova i foldera za jquery.datatables
     * main method to render all stuff for jquery.datatables
     * @param type $files - result $files (objects)
     * @param type $folders - rresult $folders (associative array)
     * @return type asscoiative array
     */
    
    protected function renderFilesAndFolders($files,$folders){
        $data = array();
        $i = 0;
        
        foreach($folders as $folder){
            $preview = '<span class="size"><i class="fa fa-folder-open fa-fw"></i></span>';
            if($folder["Favourites"]){
                $preview.= '<a href="javascript:void(0);" data-id="'.$folder["IdFolder"].'" data-type="folder" data-set="0" class="unsetfav" onclick="setFavourites(this);"><i class="fa fa-star fa-fw"></i></a>';
            }
            else{
                $preview.= '<a href="javascript:void(0);" data-id="'.$folder["IdFolder"].'" data-type="folder" data-set="1" class="setfav" onclick="setFavourites(this);"><i class="fa fa-star-o fa-fw"></i></a>';
            }
            $checkbox = '<input type="checkbox" name="chbDelete" data-type="folder" data-id="'.$folder["IdFolder"].'" value="'.$folder['IdFolder'].'" class="toggle chbDelete">';
            $name = '<a href="Files/index/'.$folder["FolderMask"].$folder["FolderName"].'">'.$folder['FolderName'].'</a>';
            $manage =  '<div class="btn-group"><a href="javascript:void(0);" data-toggle="dropdown" title="edit/rename"><i class="fa  fa-pencil fa-fw"></i></a>
          <ul class="dropdown-menu">
            <li><a href="javascript:void(0);" data-type="folder" data-id="'.$folder["IdFolder"].'" data-name="'.$folder["FolderName"].'"  class="rename" onclick="renameFileFolder(this);">Rename</a></li>     
          </ul>
          <a href="javascript:void(0);" data-id="'.$folder["IdFolder"].'" data-type="folder" class="deleteLink" onclick="deleteFileFolder(this);"><i class="glyphicon glyphicon-trash"></i></a>
          <a href="'.$this->options['script_url'].'/downloadFolder/'.$folder['IdFolder'].'" data-type="Folder" data-id="'.$folder["IdFolder"].'" class="download"  title="Download '.$folder['FolderName'].'"><i class="fa  fa-cloud-download fa-fw"></i></a>
          <a href="javascript:void(0);" class="share" data-toggle="modal" data-target="#ShareModal" data-id="'.$folder["IdFolder"].'" data-type="folder" title="Share folder" onclick="shareFileFolder(this);"><i class="fa  fa-share-alt fa-fw"></i></a>
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
                $preview.= '<a href="javascript:void(0);" data-id="'.$file->IdFile.'" data-type="file" class="unsetfav" data-set="0" onclick="setFavourites(this);"><i class="fa fa-star fa-fw"></i></a>';
            }
            else{
                $preview.= '<a href="javascript:void(0);" data-id="'.$file->IdFile.'" data-type="file" class="setfav" data-set="1" onclick="setFavourites(this);"><i class="fa fa-star-o fa-fw"></i></a>';
            }
            if(preg_match($this->options['image_file_types'], $file->FileExtension)){
                $file->image = true;
                $file->name = $file->FileName;
                $this->set_download_url($file);
                $preview = '<a href="'.$file->url.'" title="'.$file->FileName.'" download="'.$file->FileName.'" data-gallery=""><img src="'.$file->thumbnailUrl.'"></a>';
            }
            
            $checkbox = '<input type="checkbox" name="chbDelete" data-id="'.$file->IdFile.'" data-type="file" value="'.$file->IdFile.'" class="toggle chbDelete">';
            $name = '<a href="'.$this->options['script_url'].'downloadFile/'.$file->IdFile.'" data-type="File" data-id="'.$file->IdFile.'" class="download"  title="Download '.$file->FileName.'">'.$file->FileName.'</a>';
            $manage =  '<div class="btn-group"><a href="javascript:void(0);" data-toggle="dropdown" title="edit/rename"><i class="fa  fa-pencil fa-fw"></i></a>
          <ul class="dropdown-menu">
            <li><a href="javascript:void(0);" onclick="renameFileFolder(this);" data-type="file" data-id="'.$file->IdFile.'"  class="rename">Rename</a></li>
            <li><a href="javascript:void(0);" data-parent="'.$file->IdFolder.'" data-name="'.$file->FileName.'" data-id="'.$file->IdFile.'" data-type="file" onclick="moveFile(this)"  class="move" data-toggle="modal" data-target="#MoveModal">Move</a></li>
            ';
            if(in_array($file->FileExtension, self::$editableFileTypes)){
                $manage.='<li><a href="javascript:void(0);" data-idfile="'.$file->IdFile.'" class="edit" onclick="editFile(this);">Edit</a></li>';
            }
            $manage.='
          </ul>
          <a href="javascript:void(0);" data-id="'.$file->IdFile.'" data-type="file" class="deleteLink" onclick="deleteFileFolder(this);"><i class="glyphicon glyphicon-trash"></i></a>
          <a href="'.$this->options['script_url'].'downloadFile/'.$file->IdFile.'" data-type="File" data-id="'.$file->IdFile.'" class="download"  title="Download '.$file->FileName.'"><i class="fa  fa-cloud-download fa-fw"></i></a>
          <a href="javascript:void(0);" class="share" data-toggle="modal" data-target="#ShareModal" data-id="'.$file->IdFile.'" data-type="file" title="Share file" onclick="shareFileFolder(this)"><i class="fa  fa-share-alt fa-fw" onclick="shareFileFolder(this);"></i></a>
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
    
    /**
     * callback_upcount file or folder
     * exp : folder (exists) -> rename to folder(1) //win style
     * @param type $matches
     * @return type string
     */
    
    protected function upcount_name_callback($matches) {
        $index = isset($matches[1]) ? intval($matches[1]) + 1 : 1;
        $ext = isset($matches[2]) ? $matches[2] : '';
        return ' ('.$index.')'.$ext;
    }

    /**
     * call this to rename files or folders if that name already exists
     * @param type $name (file/folder name)
     * @return type string
     */
    protected function upcount_name($name) {
        return preg_replace_callback(
            '/(?:(?: \(([\d]+)\))?(\.[^.]+))?$/',
            array($this, 'upcount_name_callback'),
            $name,
            1
        );
    }
    
    /**
     * determine which mime tyep of file is this
     * odredjuje mime type
     * @param type $filename
     * @return type string mime tyep exp "text/plain"
     */
    protected function get_mime($filename) {
        if(!function_exists('mime_content_type')) {
            return $this->my_mime_content_type($filename);
        }
        else{
            return mime_content_type($filename);
        }
    }
    /**
     * ako ne postoji po defaultu na serveru funkcije myme_content_type ova ce se pozivati
     * @param type $filename (exp: test.txt)
     * @return string
     */
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
    
    /**
     * ovaj metod se koristi za force_download fajlova i van apache servera 
     * metod se koristi iskljucivo za download fajlova, ne i foldera
     * this method try to force download file wherever it is.
     * @param type $result , object
     */
    protected function force_download($result){
        // Prevent browsers from MIME-sniffing the content-type:
        header('X-Content-Type-Options: nosniff');
        if (!preg_match('/\.(gif|jpe?g|png)$/i', $result->FileName)) {
           header('Content-Type: application/octet-stream');
            header('Content-Disposition: attachment; filename="'.$result->FileName.'"');
        } else {
            header('Content-Type: '.$result->FileType);
            header('Content-Disposition: inline; filename="'.$result->FileName.'"');
        }
        header('Content-Length: '.$this->get_file_size($result->FilePath));
        header('Last-Modified: '.gmdate('D, d M Y H:i:s T', filemtime($result->FilePath)));
       
        $this->readfile($result->FilePath); 
    }
    /**
     * moj readfile
     * downloaduje chunkovan fajl
     * izbegava limit na php.ini 
     * 
     * @param type $file_path string
     * @return type
     */
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
    /**
     * dohvata velicinu fajla
     * get file size in int
     * @param type $file_path
     * @param type $clear_stat_cache
     * @return type int
     */
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
    /**
     * creates zip from folder
     * kreira zip od foldera
     * @param type $filePath - folder path
     */
    protected function createZip($filePath=''){
    if(count(scandir($filePath))<=2){
        $this->generateError('Make sure u have something in folder before download...',  self::WARNING);
    }    
    // Get real path for our folder
    $rootPath = realpath($filePath);
    // Initialize archive object
    $zip = new ZipArchive();
    $docname = "documents-export-".date("Y-m-d-h-i").'-'.$this->get_user_id().".zip";
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
    if (!is_file($file)) {
        header($_SERVER['SERVER_PROTOCOL'].' 404 Not Found');
        echo 'File not found';
    } else if (!is_readable($file)) {
        header($_SERVER['SERVER_PROTOCOL'].' 403 Forbidden');
        echo 'File not readable';
    } else {
        header($_SERVER['SERVER_PROTOCOL'].' 200 OK');
        header('Content-Type: application/zip');
        header('Content-Length: ' . filesize($docname));
        header('Content-Disposition: attachment; filename="'.$docname.'"');
        $this->readfile($docname);
    }
        if(file_exists($docname)){
            unlink($docname);
        }
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
    
    /**
     *  glavni metod koji treba da se izbori sa poslatim fajlovima
     *  moze da primi razne vrste zahteva
     * this will move_uploaded_file
     * @param type $uploaded_file -tmp_name
     * @param type $name - file name
     * @param type $size - file size
     * @param type $type - file type (mime)
     * @param type $error - file error
     * @param type $index - index of $_FILES[]
     * @param type $content_range - range 2mb-4mb (chunk)
     * @return \stdClass
     */
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
                    $this->makeThumbnails($file_path , $name);
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
    /**
     * escape empty strings
     * @param type $file_path
     * @param type $name
     * @param type $size
     * @param type $type
     * @param type $error
     * @param type $index
     * @param type $content_range
     * @return type
     */
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
    
    /**
     * this will validate main upload process
     * @param type $uploaded_file
     * @param type $file
     * @param type $error
     * @param type $index
     * @return boolean
     */
    
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
        //if we need some custom error messages, make sure to create this method get_error_message
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
    /**
     * method to return where to store all uploaded files and for other stuff
     * @param type $name - file name
     * @param type $mask - file mask
     * @return type string filepath
     */
    protected function get_upload_path($name=null,$mask=""){
        return $this->options['upload_dir'].$this->get_user_id().'/'.$mask.$name;
    }


    /**
     * returns unique file name
     * @param type $file_path
     * @param type $name
     * @param type $size
     * @param type $type
     * @param type $error
     * @param type $index
     * @param type $content_range
     * @return type
     */
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
    
    /**
     * check if is image
     * @param type $type - mimetype
     * @return boolean
     */
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
    
    /**
     * create thumbnails from images
     * @param type $img
     * @param type $name
     * @param type $MaxWe
     * @param type $MaxHe
     * @return type
     */
    protected function makeThumbnails($img, $name,$MaxWe=100,$MaxHe=100){
        $updir = dirname($img)."/thumb/";
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
    /**
     * 
     * @param type $val
     * @return type
     */
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
    /**
     * this method connect to db when upload or download file
     * ovaj metod je glavni za konekciju sa bazom pri upload-u fajla
     * @param type $file
     * @param type $content_range
     */
    protected function set_additional_file_properties($file,$content_range=false){
        $this->load->model("FileModel");
        $ext = pathinfo($file->FilePath,PATHINFO_EXTENSION);
        $mime = $this->get_mime($file->name);
        $this->load->model("FileModel");
        $file->IdFile = null;
        if($content_range){
            $file->IdFile=$this->FileModel->insertUserFile($this->get_user_id(),$mime,$file->IdFolder,$ext,$file->name,$file->FilePath,$file->size);
            $this->updateUserQuota($file->size);
        }
        $file->FileLastModified = date("d.m.Y h:i",time());
        if($file->IdFile){
            $file->deleteUrl = $this->options['script_url'].'deleteFile/'.$file->IdFile;
        }
        $this->set_download_url($file);
        
        
    }
    /**
     * ako treba da se salje nesto jos dodatno pri slanju fajla
     * send via form more stuff like in which folder to uplaod
     * @param type $file
     * @param type $index
     */
    protected function handle_form_data($file, $index) {
        if(isset($_REQUEST['IdFolder'])){
            $file->IdFolder = intval($_REQUEST['IdFolder'][$index]);
        }
        if(empty($file->IdFolder)){
            //root file
            $file->IdFolder = null;
        }
        $file->Mask = @$_REQUEST['Mask'][$index];
    }

    /**
     * set downlaod url
     * kome se obratiti za download
     * @param type $file - object
     */
    protected function set_download_url($file) {
        //direct-link
        if(!empty($file->IdFile)){
            $file->url = $this->options['script_url'].'downloadFile/'.$file->IdFile;
        }
        else{
            $file->url = $this->options['script_url'].'directDownload/'.  rawurlencode(base64_encode($file->FilePath));
        }
        if(empty($file->Mask)){
            $file->Mask = $this->Mask;
        }
        if($file->image){
            $file->url = base_url().'data/'.$this->get_user_id().'/'.$file->Mask.$file->name;
            $file->thumbnailUrl = base_url().'data/'.$this->get_user_id().'/'.$file->Mask.'thumb/'.$file->name;
            $dir = dirname($file->FilePath);
            if(!file_exists($dir.'/thumb/'.$file->FileName)){
                $file->thumbnailUrl = base_url().'public/img/no-image.png';
            }

            
            
        }
    }
    

    
    /**
     * how to save edited file
     * metod za cuvanje editovanog fajla
     */
    public function saveFile(){
        if(isset($_POST['json'])){
            $json = json_decode($_POST['json']);
            $this->load->model("FileModel");
            $file = $this->FileModel->getFileById($json->IdFile);
            if(file_exists($file->FilePath)){
                file_put_contents($file->FilePath, $json->content , LOCK_EX);
                $size = $this->get_file_size($file->FilePath);
                $this->FileModel->updateFileSize($file->IdFile,$size);
                $this->updateUserQuota($size,TRUE,$file->IdUser);
                die(TRUE);
            }
            else{
                die(FALSE);
            }
        }
        
    }
    /**
     * ovo izbegava prazna mesta medju nazivima (Novi folder -> Novi_folder)
     * this will escape file/folder space chars
     * @param type $param
     * @return type
     */
    protected function prepareName($param){
        $clean = trim($param);
        return rawurlencode(preg_replace('/\s+/', '_', $clean));
    }
    
    /**
     * depricated
     * metod za proveru skim je vec share-ovan ovaj fajl ili folder
     * zastareo metod, nije u koriscenju
     * Method to determine with who is already shared that file or folder
     */
    public function checkShared(){
//        $id = intval($_POST['id']);
//        $type = $_POST['type'];
//        $this->load->model("ShareModel");
//        if($type=="folder"){
//            $result = $this->ShareModel->getAllSharedFoldersWithOthers($this->get_user_id(),  $id);  
//        }
//        else{
//            $result = $this->ShareModel->getAllSharedFilesWithOthers($this->get_user_id(), $id);
//        }
//        if(!empty($result)){
//            header("Content-type:application/json");
//            echo json_encode($result);
//        }
    }
    /**
     * render json for datatables used in view Shared With you
     */
    public function sharedWithYou(){
        if(isset($_GET['id_folder'])){
            $id_folder=intval($_GET['id_folder']);
        }
        else{
            $id_folder = null;
        }
        if(empty($id_folder)){
            $id_folder = null;
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
            $modify="<a href='#' class='unshare' data-idshare='".$folder["IdShare"]."' data-idshared='".$folder["IdShared"]."' data-id='".$folder["IdFolder"]."' data-type='folder' title='Remove from share'><i class='fa fa-minus-circle'></i></a>";
            $modify.="<a href='".base_url()."ApiFiles/downloadFolder/".$folder["IdFolder"]."' title='Download entire folder'><i class='fa  fa-cloud-download fa-fw'></i></a>";
            if($folder["SharePrivilege"]==3){
                $modify.="<a href='javascript:void(0);' title='delete folder' data-id='".$folder["IdFolder"]."' data-type='folder' onclick='deleteFileFolder(this);'><i class='glyphicon glyphicon-trash' title='delete folder'></i></a>";
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
            $modify="<a href='#' class='unshare' data-idshare='".$file["IdShare"]."' data-type='file' data-id='".$file["IdFile"]."' data-idshared='".$file["IdShared"]."' title='Remove from share'><i class='fa fa-minus-circle'></i></a>";
            $modify.="<a href='".base_url()."ApiFiles/downloadFile/".$file["IdFile"]."' title='Download file'><i class='fa  fa-cloud-download fa-fw'></i></a>";
            if($file["SharePrivilege"]==3){
                $modify.="<a href='javascript:void(0);' title='delete file' data-id='".$file["IdFile"]."' data-type='file' onclick='deleteFileFolder(this);'><i class='glyphicon glyphicon-trash'></i></a>";
            }
            if(in_array($file["FileExtension"], self::$editableFileTypes)&&$file["SharePrivilege"]!=1){
                $modify.='<a href="'.base_url().'Files/edit/'.$file["IdFile"].'" data-idfile="'.$file["IdFile"].'" class="edit" target="_new"><i class="fa  fa-pencil fa-fw"></i></a>';
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
    /**
     * render json for datatables used in view Shared With Others
     */
    public function sharedWithOthers(){
        if(isset($_GET['id_folder'])){
            $id_folder=intval($_GET['id_folder']);
        }
        else{
            $id_folder = null;
        }
        if(isset($_GET['id_shared'])){
            $id_shared=intval($_GET['id_shared']);
        }
        else{
            $id_shared="";
        }
        if(empty($id_folder)){
            $id_folder = null;
        }
        //folders
        $this->load->model("ShareModel");
        $folders=$this->ShareModel->getAllSharedFoldersWithOthers($this->get_user_id(),$id_folder,$id_shared);
        $i = 0;
        $data = array();
        foreach($folders as $folder){
            $shareduser = $folder['UserName'];
            $shared_on = $shared_on = date("d-m-Y h:i:s",$folder["ShareCreated"]);
            $name = "<a href='".  base_url()."Files/shared_with_others/".$folder['IdFolder']."/".$folder["IdShared"]."'>".$folder["Name"]."</a>";
            $privilege = $this->switchPrivilege($folder["SharePrivilege"]);
            $modified = '';
            $modify="<a href='#' class='unshare' data-idshared='".$folder["IdShared"]."' data-id='".$folder["IdFolder"]."' data-type='folder' data-idshare='".$folder["IdShare"]."' title='Unshare this folder'><i class='fa fa-minus-circle'></i></a>";
            
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
        $files = $this->ShareModel->getAllSharedFilesWithOthers($this->get_user_id(),$id_folder,$id_shared);
        foreach($files as $file){
            $shareduser = $file['UserName'];
            $shared_on = $shared_on = date("d-m-Y h:i:s",$file["ShareCreated"]);
            $name = "<a href='".  base_url()."ApiFiles/downloadFile/".$file["IdFile"]."'>".$file["Name"]."</a>";
            
            
            
            $privilege = $this->switchPrivilege($file["SharePrivilege"]);
            $modified = date("d.m.Y h:i",$file["FileLastModified"]);
            $modify="<a href='#' class='unshare' data-idshared='".$file["IdShared"]."' data-id='".$file["IdFile"]."' data-type='file' data-idshare='".$file["IdShare"]."' title='Unshare this file'><i class='fa fa-minus-circle'></i></a>";
            
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
    /**
     * method to unshare files or folders
     * accept json via $_POST
     */
    public function unshareFilesFolders(){
        $json = json_decode($_POST['json']);
        $this->load->model("ShareModel");
        if($json->Type=="folder"){
            $this->ShareModel->unshareFolder($this->get_user_id(),$json->IdShared,$json->Id);
        }
        else{
           $this->ShareModel->deleteShareById($json->IdShare);     
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
    /**
     * metod to render all favourites stuff
     */
    public function favourites(){
        //folders
        $this->load->model("FolderModel");
        $folders=$this->FolderModel->getAllFavFolders($this->get_user_id());
        $i = 0;
        $data = array();
        foreach($folders as $folder){
            if($folder["Favourites"]){
                $fav= '<a href="javascript:void(0);" data-id="'.$folder["IdFolder"].'" data-type="folder" class="unsetfav"><i class="fa fa-star fa-fw"></i></a>';
            }
            else{
                $fav= '<a href="javascript:void(0);" data-id="'.$folder["IdFolder"].'" data-type="folder" class="setfav"><i class="fa fa-star-o fa-fw"></i></a>';
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
        $this->load->model("FileModel");
        //files
        $files = $this->FileModel->getAllFavFiles($this->get_user_id());
        foreach($files as $file){
            if($file["Favourites"]){
                $fav= '<a href="javascript:void(0);" data-id="'.$file["IdFile"].'" data-type="file" class="unsetfav"><i class="fa fa-star fa-fw"></i></a>';
            }
            else{
                $fav= '<a href="javascript:void(0);" data-id="'.$fav["IdFile"].'" data-type="folder" class="setfav"><i class="fa fa-star-o fa-fw"></i></a>';
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
    
    /**
     * method to generate custom errors
     * method za genersianje gresaka
     * @param type $msg - message
     * @param type $msg_type - constant
     * @param type $redirect if(true), this will redirect
     */
    public function generateError($msg,$msg_type=self::ERROR,$redirect=TRUE){
        $alert = '<div class="alert alert-'.$msg_type.' alert-dismissible" role="alert">
            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <strong>'.strtoupper($msg_type).'!</strong>'.$msg.'</div>';
        if($redirect){
            $this->session->set_flashdata('alert',$alert);
            header("location:".$this->options['redirect_url']);
            die();exit();
        }
        echo $alert;

    }
    /**
     * zastarela funkcija, nije u koriscenju.
     * depricated function
     */
    public function getSharedItem(){
        $id=intval($_POST['id']);
        $type = $_POST['type'];
        $this->load->model("ShareModel");
        if($type=="folder"){
            $Share = $this->ShareModel->getSharedFolder($this->get_user_id(),$id);
            if(!empty($Share)){
               $Share->url = $this->options['script_url'].'directDownload/'.  rawurlencode(base64_encode($Share->FullPath));
            }
            $content['folder'] = $Share;
        }
        else{
            $Share = $this->ShareModel->getSharedFile($this->get_user_id(),$id);
            if(!empty($Share)){
               $Share->url = $this->options['script_url'].'directDownload/'.  rawurlencode(base64_encode($Share->FullPath));
            }
            $content['file'] = $Share;
        }
        $this->generate_response($content);
        
        
    }
    /**
     * put file or folder in direct share mode (shared by link)
     */
    public function directShare(){
        $json = json_decode($_POST['json']);
        $this->load->model("ShareModel");
        $path = $this->options['script_url']."directDownload/";
        if($json->State){
            //than share file or folder
            if($json->Type=="folder"){
                $result=$this->ShareModel->shareDirectFolder($json->Id,  $this->get_user_id());
            }
            else{
                $result=$this->ShareModel->shareDirectFile($json->Id, $this->get_user_id());
            }
            if(!empty($result)){
               $content['directLink'] =  $path.  rawurlencode(base64_encode($result));
               $this->generate_response($content);
            }
        }
        else{
            //than unshare file or folder
            if($json->Type=="folder"){
                $this->ShareModel->unshareDirectFolder($json->Id,  $this->get_user_id());
            }
            else{
                $this->ShareModel->unshareDirectFile($json->Id, $this->get_user_id());
            }
            die(TRUE);
        }
        
    }
    /**
     * method to download direct files or folders
     * @param type $path string
     */
    public function directDownload($path){
        $filepath=base64_decode(rawurldecode($path));
        $this->load->model("ShareModel");
        if($this->ShareModel->canDirectDownload($filepath)){
            if(!file_exists($filepath)){
                $this->generateError("File or folder not exists!");
            }
            if(is_dir($filepath)){
                $this->createZip($filepath);
            }
            if(is_file($filepath)){
                //codeigniter helper 
                //ovo treba izmeniti kasnije u nas foce_download ili posto ovo ide u zaseban kontroler
                //najverovatnije se nece ni menjati
                $this->load->helper('download');
                force_download($filepath, NULL);
            }
        }
        else{
            $this->generateError("You don't have permision to download this file! File not shared directly!");
        }
        
    }
    
    /**
     * function to generate directDownload link 
     * funkcija za generisanje direktDownload direktnog linka
     * @param type $filepath string
     * @return type string hashed link
     */
    protected function get_direct_link($filepath){
        return $this->options['script_url']."directDownload/".rawurlencode(base64_encode($filepath));
    }
    /**
     * renderuje json za sharedByLink view
     */
    public function sharedByLink(){
        $this->load->model("ShareModel");
        $result = $this->ShareModel->getDirectShares($this->get_user_id());
        $i=0;
        $data=array();
        foreach($result as $row){
            //type
            if(empty($row['IdFile'])||$row['IdFile']==0){
                $type="folder";
                $data[$i][]='<span class="size" title="Folder"><i class="fa fa-folder-open fa-fw"></i></span>';
            }
            else{
                $type="file";
                $data[$i][]='<span class="size" title="File"><i class="fa fa-file-text-o fa-fw"></i></span>';
            }
            //name
            $data[$i][]=$row['Name']; 
            //shared on
            $data[$i][] = date("d.m.Y h:i",$row['ShareCreated']);
            //link
            $url = $this->get_direct_link($row['FullPath']);
            $data[$i][]="<a href='".$url."'>".$url."</a>";
            $id = ($type=="file")? $row['IdFile'] : $row['IdFolder'];
            //manage
            $manage = "<a href='#' class='unshare' data-id='".$id."' data-type='".$type."' title='Unshare this ".$type."'><i class='fa fa-minus-circle'></i></a>";
            $data[$i][]=$manage;
            $i++;
        }
        $content['data']=$data;
        $this->generate_response($content);
    }
    

    /**
     * premestanje fajlova
     * move files
     */
    public function moveFile(){
        $json = json_decode($_POST['json']);
        $this->load->model("FileModel");
        $file = $this->FileModel->getFileById($json->Id);
        
        if($json->MoveTo==0 || empty($json->MoveTo)){
            //move to root
            $destination=$this->options['upload_dir'].$this->get_user_id().'/'.$file->FileName;
            if($file->FilePath!=$destination){
                if(copy($file->FilePath,$destination)){
                    //moveFile($destination_or_IdFolder,$IdFile,$IdUser,$root=false)
                    $this->FileModel->moveFile($destination,$json->Id,  $this->get_user_id(),true);
                    die(unlink($file->FilePath));
                    
                }

            }
              
        }
        else{
            $this->load->model("FolderModel");
            $folder = $this->FolderModel->getFolderById($json->MoveTo);
            $destination = $folder->FolderPath.'/'.$file->FileName;
            if(copy($file->FilePath,$destination)){
                $this->FileModel->moveFile($json->MoveTo,$json->Id,  $this->get_user_id());
                die(unlink($file->FilePath));
            }
            
        }
        
    }
    /**
     * list all user folders, used to move file along folders
     */
    public function listFolders(){
        $this->load->model("FolderModel");
        $folders=$this->FolderModel->getAllFoldersForUser($this->get_user_id());
        $content['folders']=$folders;
        $this->generate_response($content);
    }
    /**
     * Method for share
     */
    public function shareFilesFolders(){
        $btnSumbit = $this->input->post('btnShare');
        if($btnSumbit!=''){
            $sharePrivilege = $this->input->post('SharePrivilege');
            $idToShare = $this->input->post('inputIdToShare');
            if(empty($idToShare)){
                $this->generateError("File/Folder not shared,try again!");
            }
            $typeToShare=  $this->input->post('inputTypeToShare');
            $chbUsers = array_unique($this->input->post('chbUsers[]'));
            $this->load->model("ShareModel");
            if($typeToShare=="folder"){
                
                foreach ($chbUsers as $user){
                    $this->ShareModel->shareFolder($this->get_user_id(),$user,$idToShare,$sharePrivilege);
                    
                }
                $this->generateError("Shared", self::SUCCESS);
            }
            elseif($typeToShare=="file"){
                foreach ($chbUsers as $user){
                    $this->ShareModel->shareFile($this->get_user_id(),$user,$idToShare,$sharePrivilege);
                }
                
                $this->generateError("Shared", self::SUCCESS);
            }
            else{
                $this->generateError("File/Folder not shared,try again!");
            }
        }
    }
    /**
     * koristi se za dodavanje ili oduzimanje velicine na disku koju koristi korisnik.
     * ako se prosledi false u drugi parametar, velicina se oduzima od postojece
     * [eng] use this metod to add or subtract user disk space.
     * @param type $size int file size
     * @param type $add bool (if true, we will add to current used space, else we will subtract)
     * @param type $other_user bool (if true we won't add to this user session)
     */
    protected function updateUserQuota($size,$add=true,$IdUser=NULL){
        if($add){
            $new_size = $this->session->userdata('diskused')+$size;
        }
        else{
            $new_size = $this->session->userdata('diskused')-$size;
        }
        if($new_size<0){
            $new_size = 0;
        }

        if(is_null($IdUser)){
            $IdUser = $this->get_user_id();
            $this->session->set_userdata(array('diskused'=>$new_size)); 
        }
        //trigger do this bottom code
//        $this->load->model("UserModel");
//        $this->UserModel->updateUsedSpaceByUser($IdUser,$new_size);
        $diskused = $this->session->userdata('diskused');
        $diskquota = $this->session->userdata('diskquota');
        if($diskused>$diskquota){
            $this->load->model("FileModel");
            $this->FileModel->quotaExceededNotification($IdUser);
        }
    }
    /**
     * ovo se poziva na svakih 10 min (ako je korisnik ulogovan i klikce nesto po sajtu)
     * moze se desiti da usedSpace postoji iako korisnik nema fajlova uopste, ovo ispravlja taj bug 
     * bug se desava zato sto se rekurzivno brisu fajlovi i zbog constraina, primer (brisanje foldera u folderima i svih njegovi itema)
     * 
     */
    public function fixDiskUsed(){
        //run this if u use disk quota
        $session = $this->session->userdata("FixSize");
        if(empty($session)){
            $array = array(
                'FixSize'=>true
            );
            $this->session->set_userdata($array);
            $this->session->mark_as_temp('FixSize', 600); /* expire 10 minutes */
            $this->load->model("FileModel");
            $row=$this->FileModel->sumAllFileSize($this->get_user_id());
            $this->session->set_userdata(array('diskused'=>$row->diskused));
            $this->load->Model("UserModel");
            $this->UserModel->updateUsedSpaceByUser($this->get_user_id(),$row->diskused);
        }
        
        
    }
    
    
    
    
    
    
}
