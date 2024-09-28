<?php

class RepartitionChargeCentreModel extends CI_Model {

    public function __construct() {
        $this->load->database();
        $this->load->model('UtilModel');
    }

    /**
     * Insère une nouvelle répartition de charge entre centre
     *
     * @param array $data
     * @return bool
     * @throws Exception
     */
    public function insertRepartitionChargeCentre($data) {
        $this->db->insert('repartition_charge_centre', $data);
        if ($this->db->affected_rows() == 0) {
            throw new Exception("Erreur lors de l'insertion de la répartition de charge.");
        }

        return true; // Retourne succès si tout est bon
    }

    /**
     * Met à jour une répartition de charge entre centre
     *
     * @param int $id
     * @param array $data
     * @return bool
     * @throws Exception
     */
    public function updateRepartitionChargeCentre($id, $data) {
        $this->db->where('id_repartition_charge_centre', $id);
        $this->db->update('repartition_charge_centre', $data);
        if ($this->db->affected_rows() == 0) {
            throw new Exception("Erreur lors de la mise à jour de la répartition de charge ou aucune modification apportée.");
        }

        return true; // Retourne succès si tout est bon
    }

    /**
     * Supprime une répartition de charge entre centre
     *
     * @param int $id
     * @return bool
     * @throws Exception
     */
    public function deleteRepartitionChargeCentre($id) {
        $this->db->where('id_repartition_charge_centre', $id);
        $this->db->delete('repartition_charge_centre');
        if ($this->db->affected_rows() == 0) {
            throw new Exception("Erreur lors de la suppression de la répartition de charge ou la répartition n'existe pas.");
        }

        return true; // Retourne succès si tout est bon
    }

    /**
     * Récupère une répartition de charge par ID
     *
     * @param int $id
     * @return array
     * @throws Exception
     */
    public function getRepartitionChargeCentreById($id) {
        $this->db->where('id_repartition_charge_centre', $id);
        $query = $this->db->get('repartition_charge_centre');

        if ($query->num_rows() > 0) {
            return $query->row_array();
        } else {
            throw new Exception("Répartition de charge non trouvée.");
        }
    }

    /**
     * Récupère toutes les répartitions de charges
     *
     * @return array
     * @throws Exception
     */
    public function getAllRepartitionsCharges() {
        $query = $this->db->get('repartition_charge_centre');

        return $query->result_array();
    }

    public function insertRepartitionChargeOfCentre($charge, array $pourcentage_centre,$date) {
        // Validation des pourcentages
        if (!$this->UtilModel->validatePourcentages(array_values($pourcentage_centre))) {
            throw new Exception("La somme des pourcentages doit être égale à 100 et chaque pourcentage doit être compris entre 0 et 100.");
        }

        $this->db->trans_start(); // Démarre la transaction

        try {
            // Insérer ou mettre à jour les répartitions de charge pour chaque centre
            foreach ($pourcentage_centre as $id_centre => $pourcentage) {
                // Préparation des données
                $data = [
                    'id_charge' => $charge,
                    'pourcentage' => $pourcentage,
                    'id_centre' => $id_centre,
                    'date' => $date
                ];

                $this->insertRepartitionChargeCentre($data);
            }
            if ($this->assurePourcentagesForCharge() === false) {
                throw new Exception("La somme des pourcentages doit être égale à 100 et chaque pourcentage doit être compris entre 0 et 100");
            }
            $this->db->trans_complete(); // Termine la transaction

            if ($this->db->trans_status() === FALSE) {
                throw new Exception("Erreur lors de la transaction de répartition des charges.");
            }

            return true; // Retourne succès si tout est bon

        } catch (Exception $e) {
            $this->db->trans_rollback(); // Annule la transaction en cas d'erreur
            throw new Exception("Échec de l'insertion de la répartition des charges : " . $e->getMessage());
        }
    }
    
    /**
     * Vérifie si une répartition de charge existe pour le centre donné
     *
     * @param int $id_centre
     * @return bool
     */
    private function isExistingRepartition($id_centre){
        $this->db->where('id_centre', $id_centre);
        $query = $this->db->get('repartition_charge_centre');
        return $query->num_rows() > 0; // Retourne vrai si une répartition existe
    }
    /**
     * Récupère les pourcentages de répartition pour une charge donnée à partir d'une date spécifiée
     *
     * @param int $id_charge L'ID de la charge à vérifier
     * @param string $date La date de référence pour la récupération
     * @return array Un tableau de pourcentages
     */
    private function assurePourcentagesForCharge(int $id_charge, string $date){
        // Récupérer les pourcentages pour le dernier enregistrement de chaque id_centre
        $this->db->select('id_centre, pourcentage');
        $this->db->from('repartition_charge_centre');
        $this->db->where('id_charge', $id_charge);
        $this->db->where('date <=', $date);

        // On utilise GROUP BY pour obtenir le dernier enregistrement par id_centre
        $this->db->group_by('id_centre'); // Group by id_centre

        // On applique un tri pour s'assurer d'obtenir le dernier enregistrement
        $this->db->order_by('date', 'DESC');

        $query = $this->db->get();

        // Si aucune ligne n'est trouvée, retourner un tableau vide
        if ($query->num_rows() == 0) {
            return true; // Aucun pourcentage trouvé
        }

        // Récupération des pourcentages dans un tableau
        $pourcentages = [];
        foreach ($query->result() as $row) {
            $pourcentages[$row->id_centre] = $row->pourcentage; // Associe l'id_centre au pourcentage
        }

        return $this->UtilModel->validatePourcentages($pourcentages); // Retourne le tableau des pourcentages
    }
    
}
