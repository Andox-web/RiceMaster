<?php

class ProduitModel extends CI_Model {

    public function __construct() {
        $this->load->database();
    }

    /**
     * Insère un nouveau produit
     *
     * @param array $data
     * @return bool
     * @throws Exception
     */
    public function insertProduit($data) {
        $this->db->insert('produit', $data);
        if ($this->db->affected_rows() == 0) {
            throw new Exception("Erreur lors de l'insertion du produit.");
        }

        return true; // Retourne succès si tout est bon
    }

    /**
     * Met à jour un produit
     *
     * @param int $id
     * @param array $data
     * @return bool
     * @throws Exception
     */
    public function updateProduit($id, $data) {
        $this->db->where('id_produit', $id);
        $this->db->update('produit', $data);
        if ($this->db->affected_rows() == 0) {
            throw new Exception("Erreur lors de la mise à jour du produit ou aucune modification apportée.");
        }

        return true; // Retourne succès si tout est bon
    }

    /**
     * Supprime un produit
     *
     * @param int $id
     * @return bool
     * @throws Exception
     */
    public function deleteProduit($id) {
        $this->db->where('id_produit', $id);
        $this->db->delete('produit');
        if ($this->db->affected_rows() == 0) {
            throw new Exception("Erreur lors de la suppression du produit ou le produit n'existe pas.");
        }

        return true; // Retourne succès si tout est bon
    }

    /**
     * Récupère un produit par ID
     *
     * @param int $id
     * @return array
     * @throws Exception
     */
    public function getProduitById($id) {
        $this->db->where('id_produit', $id);
        $query = $this->db->get('produit');

        if ($query->num_rows() > 0) {
            return $query->row_array();
        } else {
            throw new Exception("Produit non trouvé.");
        }
    }

    /**
     * Récupère tous les produits
     *
     * @return array
     * @throws Exception
     */
    public function getAllProduits() {
        $query = $this->db->get('produit');

        return $query->result_array();
    }
}
?>