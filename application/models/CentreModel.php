<?php

class CentreModel extends CI_Model {

    public function __construct() {
        $this->load->database();
    }

    /**
     * Insère un nouveau centre
     *
     * @param array $data
     * @return bool
     * @throws Exception
     */
    public function insertCentre($data) {
        $this->db->insert('centre', $data);
        if ($this->db->affected_rows() == 0) {
            throw new Exception("Erreur lors de l'insertion du centre.");
        }

        return true; // Retourne succès si tout est bon
    }

    /**
     * Met à jour un centre
     *
     * @param int $id
     * @param array $data
     * @return bool
     * @throws Exception
     */
    public function updateCentre($id, $data) {
        $this->db->where('id_centre', $id);
        $this->db->update('centre', $data);
        if ($this->db->affected_rows() == 0) {
            throw new Exception("Erreur lors de la mise à jour du centre ou aucune modification apportée.");
        }

        return true; // Retourne succès si tout est bon
    }

    /**
     * Supprime un centre
     *
     * @param int $id
     * @return bool
     * @throws Exception
     */
    public function deleteCentre($id) {
        $this->db->where('id_centre', $id);
        $this->db->delete('centre');
        if ($this->db->affected_rows() == 0) {
            throw new Exception("Erreur lors de la suppression du centre ou le centre n'existe pas.");
        }

        return true; // Retourne succès si tout est bon
    }

    /**
     * Récupère un centre par ID
     *
     * @param int $id
     * @return array
     * @throws Exception
     */
    public function getCentreById($id) {
        $this->db->where('id_centre', $id);
        $query = $this->db->get('centre');

        if ($query->num_rows() > 0) {
            return $query->row_array();
        } else {
            throw new Exception("Centre non trouvé.");
        }
    }

    /**
     * Récupère tous les centres
     *
     * @return array
     * @throws Exception
     */
    public function getAllCentres() {
        $query = $this->db->get('centre');

        return $query->result_array();
    }
}
?>