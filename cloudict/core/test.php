<<<<<<< HEAD
<?php

/*
 *  @author Darko Lesendric <dlesendric at https://github.com/ @Darko_Lesendric at https://twitter.com/>
 */

/**
 * Description of MY_Controller
 *
 * @author Darko
 */
class MY_Controller extends CI_Controller{
    public function __construct() {
        parent::__construct();
        error_reporting(E_ALL | E_STRICT);
        $this->load->library("UploadHandler");
    }
}
=======
<?php

/*
 *  @author Darko Lesendric <dlesendric at https://github.com/ @Darko_Lesendric at https://twitter.com/>
 */

/**
 * Description of MY_Controller
 *
 * @author Darko
 */
class MY_Controller extends CI_Controller{
    public function __construct() {
        parent::__construct();
        error_reporting(E_ALL | E_STRICT);
        $this->load->library("UploadHandler");
    }
}
>>>>>>> master
