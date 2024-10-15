<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class VenteModel extends CI_Model {
    public function __construct() {
        $this->load->database();
    }
    public function Get_Clients(){
        $query = $this->db->get('Client');
        return $query->result_array();
    }
    
    public function Get_numCmd() {
        $query = $this->db->query("SELECT max(id) as num from commande");
        $result = $query->row();
        return $result->num;
    }

    public function getUnpaidInvoicesByClient($client_id) {
        $this->db->select('idfact, Total, TotalPaye, (Total - TotalPaye) as remaining');
        $this->db->from('Vente');
        $this->db->where('IdClient', $client_id);
        $this->db->where('Total > TotalPaye');
        $query = $this->db->get();
        return $query->result_array();
    }

    public function getid_fact() {
        $query = $this->db->query("SELECT generate_fact_id() as fact_id");
        $result = $query->row();
        return $result->fact_id;
    }
    
    public function insert_vente($idclient,$total,$totalP) {
        $id= $this->getid_fact();
            $datap = array(
                'idfact' => $id,
                'Dtn' => date('Y-m-d'),
                'MontantPaye' =>$totalP,
            );
            $this->db->insert('CreditPayment', $datap);
            $data = array(
                'idfact'=> $id,
                'Dtn'=>date('Y-m-d'),
                'IdClient' => $idclient,
                'Total' => $total
            );
            $this->db->insert('Vente', $data); 
            // Mise à jour du TotalPaye dans la table Vente
            $this->db->set('TotalPaye', 'TotalPaye + ' . $totalP, FALSE);
            $this->db->where('idfact', $id);
            $this->db->update('Vente');
       return $id;
    }

    // Function to insert BL details
    public function insert_vente_detail($data) {
        $this->db->insert('Vente_details', $data);
    }

    
    public function insert_Client($anaran) {
        $date=[];
        $data['nom'] =$anaran;
        $this->db->insert('Client', $data);
    }

    public function insert_Depense($data) {
        $this->db->insert('Depense', $data);
    }

    public function get_daily_recap($startDate, $endDate = null) {
        // Define the query depending on whether an end date is provided
        if ($endDate) {
            // Escape the dates for security
            $startDate = $this->db->escape($startDate);
            $endDate = $this->db->escape($endDate);
            $dateCondition = "BETWEEN $startDate AND $endDate";
        } else {
            $dateCondition = "= " . $this->db->escape($startDate);
        }
    
        // 1. Get Transactions
        $sql = "SELECT * FROM `Vue_Caisse_Accumulatee` WHERE `DateFacture` $dateCondition";
        $query = $this->db->query($sql);
        $transactions = $query->result_array();
    
        // 2. Get Total Sales
        $this->db->select_sum('Total');
        $this->db->from('Vente');
        $this->db->where("Dtn $dateCondition");
        $query = $this->db->get();
        $total_sales = $query->row()->Total;

        // 2.1
        $sql = "SELECT sum(MontantPaye) as total FROM `CreditPayment` WHERE `Dtn` $dateCondition";
        $query = $this->db->query($sql);
        $total_caisse = $query->row()->total;
        
    
        // 3. Get Total Expenses
        $this->db->select_sum('Montant');
        $this->db->from('Depense');
        $this->db->where("Dtn $dateCondition");
        $query = $this->db->get();
        $total_expenses = $query->row()->Montant;
    
        // 4. Get Product Quantities
        $this->db->select('p.nom, SUM(vd.qttA) as total_quantity');
        $this->db->from('Vente_details vd');
        $this->db->join('Produit p', 'vd.idproduit = p.id');
        $this->db->join('Vente v', 'vd.idfact = v.idfact');
        $this->db->where("v.Dtn $dateCondition");
        $this->db->group_by('p.nom');
        $query = $this->db->get();
        $product_quantities = $query->result_array();
    
        // Combine all the results in one associative array
        return [
            'total_caisse'=> $total_caisse,
            'transactions' => $transactions,
            'total_sales' => $total_sales,
            'total_expenses' => $total_expenses,
            'product_quantities' => $product_quantities
        ];
    }
    

     //Historique des ventes
    public function consult_sales($criteria = []) {
        $this->db->select('Vente.idfact, Vente.Dtn, Client.nom as Client, Produit.nom as Article, Vente_details.qttA as Quantite, Vente_details.prixA as Prix, Vente.Total');
        $this->db->from('Vente');
        $this->db->join('Vente_details', 'Vente.idfact = Vente_details.idfact');
        $this->db->join('Produit', 'Vente_details.idproduit = Produit.id');
        $this->db->join('Client', 'Vente.IdClient = Client.id');
        
        if (isset($criteria['article']) && !empty($criteria['article'])) {
            $this->db->like('Produit.nom', $criteria['article']);
        }
        if (isset($criteria['client']) && !empty($criteria['client'])) {
            $this->db->like('Client.nom', $criteria['client']);
        }
        if (isset($criteria['date_from']) && isset($criteria['date_to'])) {
            $this->db->where('Vente.Dtn >=', $criteria['date_from']);
            $this->db->where('Vente.Dtn <=', $criteria['date_to']);
        } elseif (isset($criteria['date_from'])) {
            $this->db->where('Vente.Dtn', $criteria['date_from']);
        }

        if (isset($criteria['facture']) && !empty($criteria['facture'])) {
            $this->db->where('Vente.idfact', $criteria['facture']);
        }
        $this->db->order_by('Vente.Dtn', 'DESC');
        $this->db->order_by('Vente.idfact', 'DESC');
        $query = $this->db->get();
        return $query->result();
    }


     //Commande 
    public function creer_commande($client_id, $produits,$total) {
        $data = array(
            'client_id' => $client_id,
            'date_commande' => date('Y-m-d H:i:s'),
            'montant_total'=>$total
        );
        // Insertion de la commande
        $this->db->insert('Commande', $data);
        $commande_id = $this->db->insert_id();
        // Insertion des détails de la commande
        foreach ($produits as $produit) {
            var_dump($produit);
            $detail = array(
                'commande_id' => $commande_id,
                'produit_id' => $produit['id'],
                'quantite' => (int)$produit['quantite'],
                'prix_vente' => $produit['prixUnitaire']
            );
            $this->db->insert('Commande_details', $detail);
        }
        return $commande_id;
    }

    public function get_commandes_en_cours() {
        $this->db->where('statut', 'en_cours');
        $query = $this->db->get('Commande');
        return $query->result_array();
    }

    public function get_commande_details($id) {
        $this->db->where('commande_id', $id);
        $commande = $this->db->get('CommandeInfoView')->result_array();
        
        $this->db->where('commande_id', $id);
        $details = $this->db->get('CommandeDetailsView')->result_array();

        return array('commande' => $commande, 'details' => $details);
    }

    public function annuler_commande($id) {

        // Supprimer les détails de la commande
        $this->db->where('commande_id', $id);
        $this->db->delete('Commande_details');

        // Supprimer la commande
        $this->db->where('id', $id);
        $this->db->delete('Commande');
    }

/* Vente a credit  */
public function insert_payment($idfact, $montantPaye) {
    $data = array(
        'idfact' => $idfact,
        'Dtn' => date('Y-m-d'),
        'MontantPaye' => $montantPaye
    );
    $this->db->insert('CreditPayment', $data);

    // Mise à jour du TotalPaye dans la table Vente
    $this->db->set('TotalPaye', 'TotalPaye + ' . $montantPaye, FALSE);
    $this->db->where('idfact', $idfact);
    $this->db->update('Vente');
}

public function get_payment_history($criteria = []) {
    $this->db->select('Vente.idfact, Client.nom, CreditPayment.Dtn, CreditPayment.MontantPaye');
    $this->db->from('CreditPayment');
    $this->db->join('Vente', 'Vente.idfact = CreditPayment.idfact');
    $this->db->join('Client', 'Client.id = Vente.IdClient');
    
    if (isset($criteria['client']) && !empty($criteria['client'])) {
        $this->db->like('Client.nom', $criteria['client']);
    }
    if (isset($criteria['date_from']) && isset($criteria['date_to'])) {
        $this->db->where('CreditPayment.Dtn >=', $criteria['date_from']);
        $this->db->where('CreditPayment.Dtn <=', $criteria['date_to']);
    } elseif (isset($criteria['date_from'])) {
        $this->db->where('CreditPayment.Dtn', $criteria['date_from']);
    }
    if (isset($criteria['facture']) && !empty($criteria['facture'])) {
        $this->db->where('Vente.idfact', $criteria['facture']);
    }

    $this->db->order_by('CreditPayment.Dtn', 'DESC');
    $query = $this->db->get();
    return $query->result();
}



   
    


   
}?>
