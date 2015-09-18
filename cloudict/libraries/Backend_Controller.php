<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/*
 *  @author Darko Lesendric <dlesendric at https://github.com/ @Darko_Lesendric at https://twitter.com/>
 */

/**
 * Description of Backend_Controller
 *
 * @author Darko
 */
class Backend_Controller extends MY_Controller{
    public function __construct() {
        parent::__construct();
        if(!$this->isAdmin()){
            header('location:'.base_url().'User');
            exit();
            //redirect('Users'); //CI_version
        }
    }
}
