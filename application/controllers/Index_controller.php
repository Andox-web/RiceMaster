<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Index_controller extends CI_Controller {

    public function __construct(){
        parent::__construct();
        $this->load->model('VisualisationAnalytique');
        $this->load->model('CentreModel');
        $this->load->model('ExerciceModel');
        
    } 
    public function index() {
        if ($this->input->get('exercice') !== null) {
            $exercice = $this->ExerciceModel->getExerciceById($this->input->get('exercice'));
            $date_debut = $exercice['date_debut'];
            $date_fin = $exercice['date_fin'];
        }else{
            $date_debut =  date('Y') . '-01-01'; // Début de l'année par défaut
            $date_fin =  date('Y-m-d'); // Date actuelle par défaut    
        }
        
        if ($this->input->get('produit') !== null) {
            // Récupérer la clé 'produit' et la valeur 'recolte'
            $produitKey = $this->input->get('produit');
            $recolteValue = $this->input->get('recolte');
        
            // Initialiser la session pour 'production' si elle n'existe pas
            if (!isset($this->session->userdata['production'])) {
                $this->session->set_userdata('production', []);
            }
        
            // Mettre à jour la session avec la nouvelle valeur
            $production = $this->session->userdata('production');
            $production[$produitKey] = $recolteValue;
            $this->session->set_userdata('production', $production);
            $data['production']=$production;
        }
        
        // Appeler la méthode du modèle
        $centres = $this->CentreModel->getAllCentres();
        $charges = $this->VisualisationAnalytique->getTabAnalytiqueByExercice($date_debut, $date_fin);
        $produits = $this->VisualisationAnalytique->CoutTousProduits($date_debut, $date_fin);
        $exercices = $this->ExerciceModel->getAllExercices();
        // Charger la vue et passer les données
        $data['centres']=$centres;
        $data['charges'] = $charges;
        $data['produits'] = $produits;
        $data['exercices'] = $exercices;
        $data['repartitions'] = $this->VisualisationAnalytique->getRepartitionForAllStructures($date_debut, $date_fin);
        $data['date_debut'] = $date_debut; // Ajouter les dates pour les afficher dans la vue si besoin
        $data['date_fin'] = $date_fin;
        $data['style'] = "assets/css/indexstyle.css";
        $this->load->view('Resultat', $data);  
    }   

     

}
?>
