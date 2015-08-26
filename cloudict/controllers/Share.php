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
        $Users = new stdClass();
        $Groups = new stdClass();
        
    }
    protected function generate_response($content, $print_response = true) {
        if ($print_response) {
            header("Content-type:application/json");
            echo json_encode($content);
        }
        return $content;
    }
    
}
