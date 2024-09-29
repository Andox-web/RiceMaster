<?php

class CoutUnitaireModel extends CI_Model {

    public function __construct() {
        $this->load->database();
    }

    /**
     * Insère un nouveau coût unitaire
     *
     * @param array $data
     * @return bool
     * @throws Exception
     */
    public function insertCoutUnitaire($data) {
        $this->db->insert('cout_unitaire', $data);
        if ($this->db->affected_rows() == 0) {
            throw new Exception("Erreur lors de l'insertion du coût unitaire.");
        }

        return true; // Retourne succès si tout est bon
    }

    /**
     * Met à jour un coût unitaire
     *
     * @param int $id
     * @param array $data
     * @return bool
     * @throws Exception
     */
    public function updateCoutUnitaire($id, $data) {
        $this->db->where('id_cout_unitaire', $id);
        $this->db->update('cout_unitaire', $data);
        if ($this->db->affected_rows() == 0) {
            throw new Exception("Erreur lors de la mise à jour du coût unitaire ou aucune modification apportée.");
        }

        return true; // Retourne succès si tout est bon
    }

    /**
     * Supprime un coût unitaire
     *
     * @param int $id
     * @return bool
     * @throws Exception
     */
    public function deleteCoutUnitaire($id) {
        $this->db->where('id_cout_unitaire', $id);
        $this->db->delete('cout_unitaire');
        if ($this->db->affected_rows() == 0) {
            throw new Exception("Erreur lors de la suppression du coût unitaire ou le coût n'existe pas.");
        }

        return true; // Retourne succès si tout est bon
    }

    /**
     * Récupère un coût unitaire par ID
     *
     * @param int $id
     * @return array
     * @throws Exception
     */
    public function getCoutUnitaireById($id) {
        $this->db->where('id_cout_unitaire', $id);
        $query = $this->db->get('cout_unitaire');

        if ($query->num_rows() > 0) {
            return $query->row_array();
        } else {
            throw new Exception("Coût unitaire non trouvé.");
        }
    }

    /**
     * Récupère tous les coûts unitaires
     *
     * @return array
     * @throws Exception
     */
    public function getAllCoutsUnitaires() {
        $query = $this->db->get('cout_unitaire');

        return $query->result_array();
    }
}
?>