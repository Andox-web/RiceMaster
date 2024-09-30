<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Rubrique extends CI_Controller {

    public function __construct(){
        parent::__construct();
        $this->load->model('ChargeModel');
        $this->load->model('CentreModel');
        $this->load->model('RepartitionChargeCentreModel');
    } 
    public function index($page) {
        if ($page == 1) {
            $data['centres'] = $this->CentreModel->getAllCentres();
            $page = "form_rubrique";
            if ($this->input->method() === 'post') {
                
                $this->db->trans_start();

                try {
                    // Récupération des données du formulaire
                    $charge = [
                        'nom' => $this->input->post('nom'),
                        'unite_oeuvre' => $this->input->post('unite'),
                        'nature' => $this->input->post('nature')
                    ];

                    // Insertion dans la base de données
                    $id_charge = $this->ChargeModel->insertCharge($charge);

                    
                    $pourcentage_data = [];
                    foreach ($data['centres'] as $key => $value) {
                        $pourcentage_data[$value['id_centre']] = !empty($this->input->post('centre'.$value['id_centre'])) ? $this->input->post('centre'.$value['id_centre']) : 0;

                    }

                    $this->RepartitionChargeCentreModel->insertRepartitionChargeOfCentre($id_charge,$pourcentage_data,date("Y-m-d"));

                    // Complète la transaction si tout s'est bien passé
                    $this->db->trans_complete();

                    // Vérification de l'état de la transaction
                    if ($this->db->trans_status() === FALSE) {
                        // Si la transaction échoue, on lance une exception
                        throw new Exception('Erreur lors de l\'insertion de la charge.');
                    }
                    // Rediriger ou montrer un message de succès
                    $this->session->set_flashdata('success', 'Charge ajoutée avec succès !'); ;

                } catch (Exception $e) {
                    // Si une erreur survient, on annule la transaction
                    $this->db->trans_rollback();
                    $this->session->set_flashdata("Une erreur est survenue : " . $e->getMessage());
                }
            }
        }
        else {
            $data['charges'] = $this->ChargeModel->getAllCharges();
            $page = "f_detail_rubrique";

            if ($this->input->method() === 'post') {
                // Récupérer les données du formulaire
                $data = [
                    'montant' => $this->input->post('montant'),
                    'date' => $this->input->post('date'),
                    'id_charge' => $this->input->post('rubrique')
                ];

                try {
                    // Appeler la méthode d'insertion du modèle
                    $this->PlacementChargeModel->insertPlacementCharge($data);

                    // Si tout est OK, rediriger vers une autre page
                    $this->session->set_flashdata('success', 'Le placement de charge a été ajouté avec succès.');
                } catch (Exception $e) {
                    // Gérer les erreurs
                    $this->session->set_flashdata('error', $e->getMessage());
                }
            }
        }
        $data['style'] = "assets/css/style1.css";
        $this->load->view($page, $data); 
          
    }   

     

}
?>
