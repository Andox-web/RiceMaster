<?php

class PlacementChargeModel extends CI_Model {

    public function __construct() {
        $this->load->database();
    }

    /**
     * Insère un nouveau placement de charge
     *
     * @param array $data
     * @return bool
     * @throws Exception
     */
    public function insertPlacementCharge($data) {
        $this->db->insert('placement_charge', $data);
        if ($this->db->affected_rows() == 0) {
            throw new Exception("Erreur lors de l'insertion du placement de charge.");
        }

        return true; // Retourne succès si tout est bon
    }

    /**
     * Met à jour un placement de charge
     *
     * @param int $id
     * @param array $data
     * @return bool
     * @throws Exception
     */
    public function updatePlacementCharge($id, $data) {
        $this->db->where('id_placement', $id);
        $this->db->update('placement_charge', $data);
        if ($this->db->affected_rows() == 0) {
            throw new Exception("Erreur lors de la mise à jour du placement de charge ou aucune modification apportée.");
        }

        return true; // Retourne succès si tout est bon
    }

    /**
     * Supprime un placement de charge
     *
     * @param int $id
     * @return bool
     * @throws Exception
     */
    public function deletePlacementCharge($id) {
        $this->db->where('id_placement', $id);
        $this->db->delete('placement_charge');
        if ($this->db->affected_rows() == 0) {
            throw new Exception("Erreur lors de la suppression du placement de charge ou le placement de charge n'existe pas.");
        }

        return true; // Retourne succès si tout est bon
    }

    /**
     * Récupère un placement de charge par ID
     *
     * @param int $id
     * @return array
     * @throws Exception
     */
    public function getPlacementChargeById($id) {
        $this->db->where('id_placement', $id);
        $query = $this->db->get('placement_charge');

        if ($query->num_rows() > 0) {
            return $query->row_array();
        } else {
            throw new Exception("Placement de charge non trouvé.");
        }
    }

    /**
     * Récupère tous les placements de charges
     *
     * @return array
     * @throws Exception
     */
    public function getAllPlacementsCharges() {
        $query = $this->db->get('placement_charge');

        return $query->result_array();
    }
}
?>