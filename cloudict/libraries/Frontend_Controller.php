<?php

/*
 *  @author Darko Lesendric <dlesendric at https://github.com/ @Darko_Lesendric at https://twitter.com/>
 */

/**
 * Description of Frontend_Controller
 *
 * @author Darko
 */
class Frontend_Controller extends MY_Controller{
    public function __construct() {
        parent::__construct();
        if(!$this->isLogged()){
            header('location:'.base_url());
            exit();
           //redirect('Users'); //CI_version
        }
    }
}
