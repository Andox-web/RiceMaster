<?php

class ChargeModel extends CI_Model {

    public function __construct() {
        $this->load->database();
    }

    /**
     * Insère une nouvelle charge
     *
     * @param array $data
     * @return bool
     * @throws Exception
     */
    public function insertCharge($data) {
        $this->db->insert('charge', $data);
        if ($this->db->affected_rows() == 0) {
            throw new Exception("Erreur lors de l'insertion de la charge.");
        }

        return $this->db->insert_id();; // Retourne succès si tout est bon
    }

    /**
     * Met à jour une charge
     *
     * @param int $id
     * @param array $data
     * @return bool
     * @throws Exception
     */
    public function updateCharge($id, $data) {
        $this->db->where('id_charge', $id);
        $this->db->update('charge', $data);
        if ($this->db->affected_rows() == 0) {
            throw new Exception("Erreur lors de la mise à jour de la charge ou aucune modification apportée.");
        }

        return true; // Retourne succès si tout est bon
    }

    /**
     * Supprime une charge
     *
     * @param int $id
     * @return bool
     * @throws Exception
     */
    public function deleteCharge($id) {
        $this->db->where('id_charge', $id);
        $this->db->delete('charge');
        if ($this->db->affected_rows() == 0) {
            throw new Exception("Erreur lors de la suppression de la charge ou la charge n'existe pas.");
        }

        return true; // Retourne succès si tout est bon
    }

    /**
     * Récupère une charge par ID
     *
     * @param int $id
     * @return array
     * @throws Exception
     */
    public function getChargeById($id) {
        $this->db->where('id_charge', $id);
        $query = $this->db->get('charge');

        if ($query->num_rows() > 0) {
            return $query->row_array();
        } else {
            throw new Exception("Charge non trouvée.");
        }
    }

    /**
     * Récupère toutes les charges
     *
     * @return array
     * @throws Exception
     */
    public function getAllCharges() {
        $query = $this->db->get('charge');

        return $query->result_array();
    }
}
?>