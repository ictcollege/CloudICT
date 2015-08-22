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

    protected $controller_method = "Files/index/";   //very important to change this if name of controller and method are changed
    public function __construct() {
        parent::__construct();
        
    }
    
    function index(){
        $path = substr($this->uri->uri_string(), strlen($this->controller_method));
        $fullpath = $this->get_user_path().$path ;
        //check if is dir
        if(is_dir($fullpath)){
            $data['current_path'] = $fullpath;
        }
        else{
            $data['current_path'] = $this->get_user_path();
        }
        
        if(isset($_GET['current_dir'])){
            $data['current_dir'] = intval($_GET['current_dir']);
            $this->load->model("FileModel");
            $result = $this->FileModel->getFolder($this->get_user_id(),$data['current_dir'],$data['current_path']);
            $current_dir_name = $result->FileName;
            $data['current_dir_name'] = $current_dir_name;
            $breadcrumbs = substr($this->breadcrumbs($current_dir_name),1);
            $bread = explode('/', $breadcrumbs);
            foreach ($bread as $crumbs){
                $data['breadcrumbs'][]=$crumbs;
            }
        }
        else{
            $data['current_dir'] = 0;
        }
        $this->load_view('filesView',$data,'menu');
    }
    
    protected function breadcrumbs($filename=''){
        $root = dirname($this->get_server_var('SCRIPT_FILENAME')).'/data/'.$this->get_user_id().'/';
        $it = new RecursiveDirectoryIterator($root);
        $it = new RecursiveIteratorIterator($it, RecursiveIteratorIterator::CHILD_FIRST);
        $bread = '';
        $crumbs = '';
        $breadcrumbs = '';
        foreach($it as $file) {
            if ('.' === $file->getBasename() || '..' ===  $file->getBasename()) continue;
            if ($file->isDir()){
                if($file->getFilename()==$filename){
                    $bread = realpath($file->getPath().'/'.$filename);
                    $crumbs = realpath($root);
                    $breadcrumbs = str_replace('\\','/',str_replace($crumbs, '' , $bread));
                    unset($bread);
                    unset($crumbs);
                    break;
                }
                
            }
        }
        return $breadcrumbs;
    }

}
