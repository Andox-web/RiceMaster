<?php

class ProductionCentreModel extends CI_Model {

    public function __construct() {
        $this->load->database();
    }

    /**
     * Insère une nouvelle production pour un centre
     *
     * @param array $data
     * @return bool
     * @throws Exception
     */
    public function insertProductionCentre($data) {
        $this->db->insert('production_centre', $data);
        if ($this->db->affected_rows() == 0) {
            throw new Exception("Erreur lors de l'insertion de la production du centre.");
        }

        return true; // Retourne succès si tout est bon
    }

    /**
     * Met à jour une production pour un centre
     *
     * @param int $id
     * @param array $data
     * @return bool
     * @throws Exception
     */
    public function updateProductionCentre($id, $data) {
        $this->db->where('id_production_centre', $id);
        $this->db->update('production_centre', $data);
        if ($this->db->affected_rows() == 0) {
            throw new Exception("Erreur lors de la mise à jour de la production du centre ou aucune modification apportée.");
        }

        return true; // Retourne succès si tout est bon
    }

    /**
     * Supprime une production pour un centre
     *
     * @param int $id
     * @return bool
     * @throws Exception
     */
    public function deleteProductionCentre($id) {
        $this->db->where('id_production_centre', $id);
        $this->db->delete('production_centre');
        if ($this->db->affected_rows() == 0) {
            throw new Exception("Erreur lors de la suppression de la production du centre ou la production n'existe pas.");
        }

        return true; // Retourne succès si tout est bon
    }

    /**
     * Récupère une production pour un centre par ID
     *
     * @param int $id
     * @return array
     * @throws Exception
     */
    public function getProductionCentreById($id) {
        $this->db->where('id_production_centre', $id);
        $query = $this->db->get('production_centre');

        if ($query->num_rows() > 0) {
            return $query->row_array();
        } else {
            throw new Exception("Production du centre non trouvée.");
        }
    }

    /**
     * Récupère toutes les productions pour un centre
     *
     * @return array
     * @throws Exception
     */
    public function getAllProductionsCentres() {
        $query = $this->db->get('production_centre');

        return $query->result_array();
    }
}
?>