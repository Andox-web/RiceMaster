<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Exo extends CI_Controller {

    public function __construct(){
        parent::__construct();
        $this->load->model('ExerciceModel');
    } 
    public function index() {
        $data['style'] = "assets/css/style1.css";
        $this->load->view('Exercice', $data);
    }   
    public function insertExercice() {
        // Récupérer les données du formulaire
        $data = array(
            'nom' => $this->input->post('nom'),
            'date_debut' => $this->input->post('date_debut'),
            'date_fin' => $this->input->post('date_fin')
        );

        try {
            // Appeler la méthode du modèle pour insérer l'exercice
            $this->ExerciceModel->insertExercice($data);
            // Rediriger vers une page de succès ou autre après l'insertion
            redirect('Exo/index');
        } catch (Exception $e) {
            // Gérer l'erreur en cas de problème
            echo 'Erreur: ' . $e->getMessage();
        }
    }
}
?>
