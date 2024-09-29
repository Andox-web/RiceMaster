<?php

class ExerciceModel extends CI_Model {

    public function __construct() {
        $this->load->database();
    }

    /**
     * Insère un nouvel exercice
     *
     * @param array $data
     * @return bool
     * @throws Exception
     */
    public function insertExercice($data) {
        $this->db->insert('exercice', $data);
        if ($this->db->affected_rows() == 0) {
            throw new Exception("Erreur lors de l'insertion de l'exercice.");
        }

        return true; // Retourne succès si tout est bon
    }

    /**
     * Met à jour un exercice
     *
     * @param int $id
     * @param array $data
     * @return bool
     * @throws Exception
     */
    public function updateExercice($id, $data) {
        $this->db->where('id_exercice', $id);
        $this->db->update('exercice', $data);
        if ($this->db->affected_rows() == 0) {
            throw new Exception("Erreur lors de la mise à jour de l'exercice ou aucune modification apportée.");
        }

        return true; // Retourne succès si tout est bon
    }

    /**
     * Supprime un exercice
     *
     * @param int $id
     * @return bool
     * @throws Exception
     */
    public function deleteExercice($id) {
        $this->db->where('id_exercice', $id);
        $this->db->delete('exercice');
        if ($this->db->affected_rows() == 0) {
            throw new Exception("Erreur lors de la suppression de l'exercice ou l'exercice n'existe pas.");
        }

        return true; // Retourne succès si tout est bon
    }

    /**
     * Récupère un exercice par ID
     *
     * @param int $id
     * @return array
     * @throws Exception
     */
    public function getExerciceById($id) {
        $this->db->where('id_exercice', $id);
        $query = $this->db->get('exercice');

        if ($query->num_rows() > 0) {
            return $query->row_array();
        } else {
            throw new Exception("Exercice non trouvé.");
        }
    }

    /**
     * Récupère tous les exercices
     *
     * @return array
     * @throws Exception
     */
    public function getAllExercices() {
        $query = $this->db->get('exercice');

        return $query->result_array();
    }
}
?>