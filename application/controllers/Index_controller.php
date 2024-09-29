<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Index_controller extends CI_Controller {

    public function __construct(){
        parent::__construct();
        /* $this->load->model('FamilleModel'); */
    } 
    public function index() {
        $data['style'] = "assets/css/indexstyle.css";
        $this->load->view('Resultat', $data);  
    }   

     

}
?>
