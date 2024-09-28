<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Analytique extends CI_Controller {
   
	public function __construct() {
        parent::__construct();
        $this->load->model('VisualisationAnalytique');
    }

    public function index() {
        // Set the date range for testing
        $date_debut = '2024-01-01';
        $date_fin = '2024-12-31';

        // Call the method from the model
        $charges = $this->VisualisationAnalytique->getTabAnalytiqueByExercice($date_debut, $date_fin);
        $produits = $this->VisualisationAnalytique->CoutTousProduits($date_debut, $date_fin);

        // Load the view and pass the data
        $data['charges'] = $charges;
        $data['produits'] = $produits;
		$data['repart'] = $this->VisualisationAnalytique->getRepartitionByCentre(3,$date_debut, $date_fin);
        $this->load->view('analytique_view', $data);
    }
}
?>