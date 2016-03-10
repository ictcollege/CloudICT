<?php

/*
 *  @author Darko Lesendric <dlesendric at https://github.com/ @Darko_Lesendric at https://twitter.com/>
 */

/**
 * Description of DirectDownload
 * Koristi se za direktno downloadovanje fajlova kad je fajl ili folder share-ovan
 * This controllers we use for direct download files or folders if it is shared
 * @author Darko
 */
class DirectDownload extends MY_Controller{
    public function __construct() {
        parent::__construct();
    }
    //main download method
    public function download($path){
        $filepath=base64_decode(rawurldecode($path));
        $this->load->model("ShareModel");
        if($this->ShareModel->canDirectDownload($filepath)){
            if(!file_exists($filepath)){
                die("File or folder not exists!");
            }
            if(is_dir($filepath)){
                $this->createZip($filepath);
            }
            if(is_file($filepath)){
                //codeigniter helper 
                //ovo treba izmeniti kasnije u nas foce_download ako se ne koristi CI
                $this->load->helper('download');
                force_download($filepath, NULL);
            }
        }
        else{
            die("You don't have permision to download this file! File not shared directly!");
        }
    }
    
    /**
     * kopirano iz ApiFiles
     * creates zip from folder
     * kreira zip od foldera
     * @param type $filePath - folder path
     */
    protected function createZip($filePath=''){
    if(count(scandir($filePath))<=2){
        die('Make sure u have something in folder before download...');
    }    
    // Get real path for our folder
    $rootPath = realpath($filePath);
    // Initialize archive object
    $zip = new ZipArchive();
    $docname = "documents-export-".date("Y-m-d-h-i").".zip";
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
    if (!is_file($docname)) {
        header($_SERVER['SERVER_PROTOCOL'].' 404 Not Found');
        echo 'File not found';
    } else if (!is_readable($docname)) {
        header($_SERVER['SERVER_PROTOCOL'].' 403 Forbidden');
        echo 'File not readable';
    } else {
        $this->load->helper('download');
        force_download($docname, NULL);
    }
        if(file_exists($docname)){
            unlink($docname);
        }
    }   
}
