<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Centre extends CI_Controller {

    public function __construct(){
        parent::__construct();
        /* $this->load->model('FamilleModel'); */
    } 
    public function index() {
        $data['style'] = "assets/css/style1.css";
        $this->load->view('form_centre', $data); 
    }   
    public function insert() {
        $this->load->model("CentreModel");
        $nom = $this->input->post('nom');
        $operative = $this->input->post('operative');
        $data = array(
			'nom' =>  $nom,
            'operative' => $operative
		);
        $this->CentreModel->insertCentre($data);
        redirect("centre/index");
    }

}
?>
