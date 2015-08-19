<?php

/*
 *  @author Darko Lesendric <dlesendric at https://github.com/ @Darko_Lesendric at https://twitter.com/>
 */

/**
 * Description of Fileupload
 *
 * @author Darko
 */
class Fileupload extends CI_Controller{
    public function __construct() {
        parent::__construct();
    }
    
    function index(){
        if(isset($_GET['current_dir'])){
            $data['current_dir'] = intval($_GET['current_dir']);
            $this->load->model("FileModel");
            $result = $this->FileModel->getFolder($this->get_user_id(),$data['current_dir']);
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
        $this->load->view('header', $data);
        $this->load->view('menu', $data);
        $this->load->view('fileupload',$data);
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
    protected function get_server_var($id) {
        return isset($_SERVER[$id]) ? $_SERVER[$id] : '';
    }
    protected function get_user_id(){
        //return $this->session->userdata('userid');
        return 1;
    }
}
