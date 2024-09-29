<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Rubrique extends CI_Controller {

    public function __construct(){
        parent::__construct();
        /* $this->load->model('FamilleModel'); */
    } 
    public function index($page) {
        if ($page == 1) {
            $page = "form_rubrique";
        }
        else {
            $page = "f_detail_rubrique";
        }
        $data['style'] = "assets/css/style1.css";
        $this->load->view($page, $data); 
          
    }   

     

}
?>
