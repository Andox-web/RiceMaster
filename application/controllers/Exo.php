<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Exo extends CI_Controller {

    public function __construct(){
        parent::__construct();
        /* $this->load->model('FamilleModel'); */
    } 
    public function index() {
        $data['style'] = "assets/css/style1.css";
        $this->load->view('Exercice', $data);
    }   
}
?>
