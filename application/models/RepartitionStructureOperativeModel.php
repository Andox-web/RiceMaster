<?php

class RepartitionStructureOperativeModel extends CI_Model {

    public function __construct() {
        $this->load->database();
    }

    /**
     * Insère une nouvelle répartition structure/operative
     *
     * @param array $data
     * @return bool
     * @throws Exception
     */
    public function insertRepartitionStructureOperative(array $data) {
        $this->db->insert('repartition_structure_operative', $data);

        if ($this->db->affected_rows() === 0) {
            throw new Exception("Erreur lors de l'insertion de la répartition structure/operative.");
        }

        return true; // Retourne succès
    }

    /**
     * Met à jour une répartition structure/operative
     *
     * @param int $id
     * @param array $data
     * @return bool
     * @throws Exception
     */
    public function updateRepartitionStructureOperative(int $id, array $data) {
        $this->db->where('id_repartition_structure_operation', $id);
        $this->db->update('repartition_structure_operative', $data);

        if ($this->db->affected_rows() === 0) {
            throw new Exception("Erreur lors de la mise à jour de la répartition structure/operative ou aucune ligne n'a été modifiée.");
        }

        return true; // Retourne succès
    }

    /**
     * Supprime une répartition structure/operative
     *
     * @param int $id
     * @return bool
     * @throws Exception
     */
    public function deleteRepartitionStructureOperative(int $id) {
        $this->db->where('id_repartition_structure_operation', $id);
        $this->db->delete('repartition_structure_operative');

        if ($this->db->affected_rows() === 0) {
            throw new Exception("Erreur lors de la suppression de la répartition structure/operative ou aucune ligne n'a été supprimée.");
        }

        return true; // Retourne succès
    }

    /**
     * Récupère une répartition structure/operative par ID
     *
     * @param int $id
     * @return array
     * @throws Exception
     */
    public function getRepartitionStructureOperativeById(int $id) {
        $this->db->where('id_repartition_structure_operation', $id);
        $query = $this->db->get('repartition_structure_operative');

        if ($query->num_rows() > 0) {
            return $query->row_array(); // Retourne le résultat
        } else {
            throw new Exception("Répartition structure/operative non trouvée.");
        }
    }

    /**
     * Récupère toutes les répartitions structure/operative
     *
     * @return array
     * @throws Exception
     */
    public function getAllRepartitionsStructureOperative() {
        $query = $this->db->get('repartition_structure_operative');

        return $query->result_array(); // Retourne tous les résultats
    }
    public function insertRepartitionChargeOfCentre($id_centre_operative, array $pourcentage_centre, $date) {
        // Validation des pourcentages
        if (!$this->UtilModel->validatePourcentages(array_values($pourcentage_centre))) {
            throw new Exception("La somme des pourcentages doit être égale à 100 et chaque pourcentage doit être compris entre 0 et 100.");
        }

        $this->db->trans_start(); // Démarre la transaction

        try {
            // Insérer ou mettre à jour les répartitions de charge pour chaque centre
            foreach ($pourcentage_centre as $id_centre_structure => $pourcentage) {
                // Préparation des données
                $data = [
                    'id_centre_operative' => $id_centre_operative, // Utiliser le bon ID de charge
                    'id_centre_structure' => $id_centre_structure,
                    'pourcentage' => $pourcentage,
                    'date' => $date
                ];

                $this->insertRepartitionStructureOperative($data); // Appel à la méthode d'insertion
            }

            // Validation des pourcentages après insertion
            if (!$this->assurePourcentagesForCharge($id_centre_operative , $date)) {
                throw new Exception("La somme des pourcentages doit être égale à 100 et chaque pourcentage doit être compris entre 0 et 100.");
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
     * Insère une répartition de structure dans la base de données
     *
     * @param array $data Les données à insérer
     * @return bool
     */
    private function insertRepartitionStructure($data) {
        $this->db->insert('repartition_structure_operative', $data);
        if ($this->db->affected_rows() == 0) {
            throw new Exception("Erreur lors de l'insertion de la répartition de structure.");
        }

        return true; // Retourne succès si tout est bon
    }

    /**
     * Vérifie si une répartition de charge existe pour le centre donné
     *
     * @param int $id_centre
     * @return bool
     */
    private function isExistingRepartition($id_centre) {
        $this->db->where('id_centre_structure', $id_centre);
        $query = $this->db->get('repartition_structure_operative');
        return $query->num_rows() > 0; // Retourne vrai si une répartition existe
    }

    /**
     * Récupère les pourcentages de répartition pour une charge donnée à partir d'une date spécifiée
     *
     * @param int $id_charge L'ID de la charge à vérifier
     * @param string $date La date de référence pour la récupération
     * @return bool
     */
    private function assurePourcentagesForCharge(int $id_centre_operative, string $date) {
        // Récupérer les pourcentages pour le dernier enregistrement de chaque id_centre_structure
        $this->db->select('id_centre_structure, pourcentage');
        $this->db->from('repartition_structure_operative');
        $this->db->where('id_centre_operative', $id_centre_operative);
        $this->db->where('date <=', $date);

        // On utilise GROUP BY pour obtenir le dernier enregistrement par id_centre_structure
        $this->db->group_by('id_centre_structure'); // Group by id_centre_structure

        // On applique un tri pour s'assurer d'obtenir le dernier enregistrement
        $this->db->order_by('date', 'DESC');

        $query = $this->db->get();

        // Si aucune ligne n'est trouvée, retourner true
        if ($query->num_rows() == 0) {
            return true; // Aucun pourcentage trouvé
        }

        // Récupération des pourcentages dans un tableau
        $pourcentages = [];
        foreach ($query->result() as $row) {
            $pourcentages[$row->id_centre_structure] = $row->pourcentage; // Associe l'id_centre_structure au pourcentage
        }

        return $this->UtilModel->validatePourcentages($pourcentages); // Valide les pourcentages
    }
}