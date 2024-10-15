<?php
class VisualisationAnalytique extends CI_Model {
    public function __construct() {
        $this->load->database();
    }
 public function getChargesByExercice($date_debut, $date_fin) {
        // Sélection des charges ayant des placements dans l'intervalle de dates
        $this->db->select('charge.id_charge, charge.nom, charge.unite_oeuvre, charge.nature');
        $this->db->from('charge');
        $this->db->join('placement_charge', 'placement_charge.id_charge = charge.id_charge', 'inner');
        $this->db->where('placement_charge.date >=', $date_debut);
        $this->db->where('placement_charge.date <=', $date_fin);
        $this->db->group_by('charge.id_charge');
        
        // Récupérer les charges dans l'intervalle
        $charges = $this->db->get()->result_array();

        $result = [];

        foreach ($charges as $charge) {
            $id_charge = $charge['id_charge'];
            // Calcul du montant total de la charge
            $total_montant = 0;

            // Si id_cout_unitaire = -1, on somme les montants de placement_charge
            $this->db->select('SUM(placement_charge.montant) AS total_montant');
            $this->db->from('placement_charge');
            $this->db->where('placement_charge.id_charge', $id_charge);
            $this->db->where('placement_charge.date >=', $date_debut);
            $this->db->where('placement_charge.date <=', $date_fin);
            $total_montant = (float) $this->db->get()->row()->total_montant;

            // Fixe et variable
            $fixe = $charge['nature'] ? $total_montant : 0;
            $variable = !$charge['nature'] ? $total_montant : 0;

            // Ajouter les détails de la charge dans le résultat
            $result[$id_charge] = [
                'id_charge' => $id_charge,
                'nom' => $charge['nom'],
                'unite_oeuvre' => $charge['unite_oeuvre'],
                'nature' =>$charge['nature'],
                'total' => [
                    'total' => $total_montant,
                    'fixe' => $fixe,
                    'variable' => $variable
                ],
                'centre' => []
            ];

            // Récupération des centres associés et du pourcentage avec la dernière date
            $this->db->select('id_centre, pourcentage');
            $this->db->from('repartition_charge_centre r1');
            $this->db->where('r1.id_charge', $id_charge);
            $this->db->where('r1.date <=', $date_fin);

            // Sous-requête pour obtenir la dernière date pour chaque id_centre
            $this->db->where('r1.date = (
                SELECT MAX(r2.date) 
                FROM repartition_charge_centre r2 
                WHERE r2.id_centre = r1.id_centre 
                AND r2.id_charge = '.$this->db->escape($id_charge).' 
                AND r2.date <= '.$this->db->escape($date_fin).'
            )');


            $repartition_centres = $this->db->get()->result_array();

            foreach ($repartition_centres as $repartition) {
                $id_centre = $repartition['id_centre'];
                $pourcentage = (float) $repartition['pourcentage'] / 100;

                // Répartition du montant total, fixe, et variable pour chaque centre
                $total_centre = $total_montant * $pourcentage;
                $fixe_centre = $fixe * $pourcentage;
                $variable_centre = $variable * $pourcentage;

                $result[$id_charge]['centre'][$id_centre] = [
                    'pourcentage' => $repartition['pourcentage'],
                    'total' => $total_centre,
                    'fixe' => $fixe_centre,
                    'variable' => $variable_centre
                ];
            }
        }

        return $result;
    }

    public function calculateCentreTotals($charges_exercice, $id_centre) {
        $totals = [
            'total' => 0,
            'fixe' => 0,
            'variable' => 0
        ];

        // Parcourir les charges et récupérer les montants pour le centre donné
        foreach ($charges_exercice as $charge) {
            if (isset($charge['centre'][$id_centre])) {
                $totals['total'] += $charge['centre'][$id_centre]['total'];
                $totals['fixe'] += $charge['centre'][$id_centre]['fixe'];
                $totals['variable'] += $charge['centre'][$id_centre]['variable'];
            }
        }

        return $totals;
    }
    // Calculer le total général (total, fixe, variable) sur tous les centres
    public function calculateGeneralTotals($charges_exercice) {
        $general_totals = [
            'total' => 0,
            'fixe' => 0,
            'variable' => 0
        ];

        // Parcourir toutes les charges
        foreach ($charges_exercice as $charge) {
            $general_totals['total'] += $charge['total']['total'];
            $general_totals['fixe'] += $charge['total']['fixe'];
            $general_totals['variable'] += $charge['total']['variable'];
            
        }

        return $general_totals;
    }
     // Calculer la répartition totale pour chaque centre
     public function calculateCentreTotalsByCentre($charges_exercice) {
        $centre_totals = [];

        // Parcourir toutes les charges
        foreach ($charges_exercice as $charge) {
            foreach ($charge['centre'] as $id_centre => $centre_data) {
                // Si le centre n'est pas encore dans le tableau, l'initialiser
                if (!isset($centre_totals[$id_centre])) {
                    $centre_totals[$id_centre] = [
                        'total' => 0,
                        'fixe' => 0,
                        'variable' => 0
                    ];
                }

                // Ajouter les valeurs de la charge à celles du centre
                $centre_totals[$id_centre]['total'] += $centre_data['total']??0;
                $centre_totals[$id_centre]['fixe'] += $centre_data['fixe']??0;
                $centre_totals[$id_centre]['variable'] += $centre_data['variable']??0;
            }
        }

        return $centre_totals;
    }
    public function getTabAnalytiqueByExercice($date_debut, $date_fin) {
        // Étape 1: Récupérer les charges dans l'intervalle de dates donné
        $charges_exercice = $this->getChargesByExercice($date_debut, $date_fin);
    
        // Étape 2: Calculer le total général (total, fixe, variable)
        $total_general = $this->calculateGeneralTotals($charges_exercice);
    
        // Étape 3: Calculer la répartition par centre pour chaque charge
        $totaux_par_centre = $this->calculateCentreTotalsByCentre($charges_exercice);
    
        // Étape 4: Construction du tableau final
        $result = [
            'Rubrique' => $charges_exercice, // Liste des charges avec leurs centres associés
            'total' => [
                'total' => $total_general,  // Total général (total, fixe, variable)
                'centre' => $totaux_par_centre // Totaux par centre (total, fixe, variable)
            ]
        ];
    
        return $result;
    }
    






    public function getOperativeCentres( $centre_structure, $date_debut, $date_fin,$totaux_centres){
        // S'assurer que les dates sont au bon format
        $date_debut = date('Y-m-d', strtotime($date_debut));
        $date_fin = date('Y-m-d', strtotime($date_fin));
    
        // Sélectionner les centres opérationnels associés au centre de structure dans l'intervalle de dates
        $this->db->select('repartition_structure_operative.id_centre_operative, centre.nom, repartition_structure_operative.pourcentage');
        $this->db->from('repartition_structure_operative');
        $this->db->join('centre', 'centre.id_centre = repartition_structure_operative.id_centre_operative', 'inner'); // Join sur la table centre
        $this->db->where('id_centre_structure', $centre_structure);
        $this->db->where('date >=', $date_debut);
        $this->db->where('date <=', $date_fin);
        
        
        // Exécuter la requête
        $query = $this->db->get();
    
        // Récupérer les résultats sous forme de tableau
        $centres_ope = $query->result_array();
        $total = 0;
        foreach ($centres_ope as $centre) {
            $total+=$totaux_centres[$centre['id_centre_operative']]['total'];
        }

        // Transformer le tableau pour l'adapter à la structure souhaitée
        $result = [];
        foreach ($centres_ope as $centre) {
            $result[$centre['id_centre_operative']] = [
                'pourcentage' => round((float) ($totaux_centres[$centre['id_centre_operative']]['total']*100)/$total,2),
                'nom'=>$centre['nom']
            ];
        }
    
        return $result;
    }
    public function getRepartitionByCentre($centre_structure, $date_debut, $date_fin){
        
        // Récupérer les totaux par centre
        $totaux_centres = $this->calculateCentreTotalsByCentre($this->getChargesByExercice($date_debut,$date_fin));
        
        // Récupérer les centres opérationnels associés à la structure dans l'intervalle de dates
        $centres_ope = $this->getOperativeCentres($centre_structure, $date_debut, $date_fin,$totaux_centres);

        // Initialiser les résultats
        $resultat = [
            'centre_operative' => [],
            'total' => [
                'cout_direct' => 0,
                'cout_structure' => 0,
                'cout_total' => 0
            ]
        ];
    
        // Calculer la répartition pour chaque centre opérationnel
        foreach ($centres_ope as $id_centre_operative => $data) {
            $pourcentage = $data['pourcentage'];
            
            // Récupérer les coûts directs pour le centre opérationnel
            $cout_direct = $totaux_centres[$id_centre_operative]['total']; // S'assurer que le centre existe dans les totaux
    
            // Calculer le coût structurel
            $cout_structure = ($totaux_centres[$centre_structure]['total'] ) * ($pourcentage / 100);
    
            // Calculer le coût total
            $cout_total = $cout_structure + $cout_direct;
    
            // Ajouter les données au résultat
            $resultat['centre_operative'][$id_centre_operative] = [
                'nom' => $data['nom'],
                'cout_direct' => (int) $cout_direct,
                'cles' => (float) $pourcentage,
                'cout_structure' => (float) $cout_structure,
                'cout_total' => (float) $cout_total,
            ];
    
            // Accumuler les totaux
            $resultat['total']['cout_direct'] += $cout_direct;
        }
        $resultat['total']['cout_structure'] += $totaux_centres[$centre_structure]['total'] ?? 0;
        $resultat['total']['cout_total'] = $resultat['total']['cout_direct']+$resultat['total']['cout_structure'];
    
        return $resultat;
    }
    public function getStructureCentres() {
        // Requête pour sélectionner les centres où operative est false (0)
        $this->db->where('operative', 0); // 0 représente "non opératif"
        $query = $this->db->get('centre'); // Nom de la table

        return $query->result_array(); // Retourne les résultats sous forme de tableau
    }

    public function getRepartitionForAllStructures($date_debut, $date_fin) {
        // Récupérer les centres non opérationnels
        $nonOperativeCentres = $this->getStructureCentres();
        
        $repartitions = []; // Initialiser un tableau pour stocker les répartitions

        // Parcourir chaque centre non opérationnel et récupérer les répartitions
        foreach ($nonOperativeCentres as $centre) {
            $centre_id = $centre['id_centre']; // Récupérer l'ID du centre

            // Appeler la méthode pour obtenir les répartitions
            $centre_repartition = $this->getRepartitionByCentre($centre_id, $date_debut, $date_fin);
            $repartitions[$centre_id] = $centre_repartition; // Ajouter la répartition au tableau
            $repartitions[$centre_id]['centre']=$centre;
        }

        return $repartitions; // Retourner les répartitions
    }





    public function getStructuresByCentre(int $centre_id, array $calculateCentreTotalsByCentre,$date_debut,$date_fin) {
        // Query to get the structures linked to a specific center with their percentages
        $this->db->select('rs.id_centre_structure, rs.pourcentage');
        $this->db->from('repartition_structure_operative rs');
        $this->db->where('rs.id_centre_operative', $centre_id);
    
        // Execute the query and get the results
        $query = $this->db->get();
        $result = $query->result_array();
    
        $total_centre = 0;

        foreach ($result as $row) {
            $repartition = $this->getRepartitionByCentre($row['id_centre_structure'], $date_debut, $date_fin);
            $pourcentage = $repartition['centre_operative'][$centre_id]['cles'];

            // Calculate the total contribution of each structure
            $contribution = ((($calculateCentreTotalsByCentre[$row['id_centre_structure']]['total']??0) * $pourcentage) / 100);
            $total_centre = $contribution;
        }
        return $total_centre;
    }
     // Fonction pour obtenir les centres non opératifs
    

    public function getOperativeCentresForProduit(int $produit){
        // Initialize the return array
        $operative_centres = [];
    
        // Query to get the operative centers for the specified product within the date range
        $this->db->select('pc.id_centre, c.nom AS centre_name, pc.date_fin_production');
        $this->db->from('production_centre pc');
        $this->db->join('centre c', 'c.id_centre = pc.id_centre', 'inner');
        $this->db->where('pc.id_produit', $produit);
        
        // Execute the query and get the results
        $query = $this->db->get();
        $results = $query->result_array();
    
        // Process the results to format the output
        foreach ($results as $row) {
            $operative_centres[$row['id_centre']] = [
                'centre_name' => $row['centre_name'],
                'date_fin_production' => $row['date_fin_production'],
            ];
        }
    
        return $operative_centres;
    }
    
    public function CoutProduitWithData(int $produit, array $calculateCentreTotalsByCentre,$date_debut,$date_fin) {
        // Step 1: Initialize the return array
        $result = [
            'centre_operative' => [],
            'cout_total' => 0,
        ];
    
        // Step 2: Get the operative centres associated with the specified product
        $operative_centres = $this->getOperativeCentresForProduit($produit);
    
        // Step 3: Calculate the costs for each operative centre
        foreach ($operative_centres as $centre_id => $centre_data) {
            // Get the total costs for the center
            $cout_direct = $calculateCentreTotalsByCentre[$centre_id]['total']??0;
    
            // Get the total contribution of structures linked to this centre
            $total_structure_contribution = $this->getStructuresByCentre($centre_id, $calculateCentreTotalsByCentre,$date_debut,$date_fin);
    
            // Calculate cout_total for the operative centre
            $cout_total = $cout_direct+$total_structure_contribution;
    
            // Store the result for the current operative centre
            $result['centre_operative'][$centre_id] = [
                'nom' => $centre_data['centre_name'],
                'cout_total' => $cout_total,
            ];
    
            // Accumulate the overall cout_total
            $result['cout_total'] += $cout_total;
        }
    
        return $result;
    }
    public function CoutProduit(int $produit,$date_debut,$date_fin) {
        $charges_exercice=$this->getChargesByExercice($date_debut,$date_fin);
        $calculateCentreTotalsByCentre=$this->calculateCentreTotalsByCentre($charges_exercice);
        return $this->CoutProduitWithData($produit,$calculateCentreTotalsByCentre,$date_debut,$date_fin);
    }
    public function CoutTousProduits($date_debut, $date_fin) {
        $this->load->model('ProduitModel');
        $produits=$this->ProduitModel->getAllProduits();

        // Initialiser un tableau pour stocker les résultats pour chaque produit
        $resultats = [];
    
        // Récupérer les charges de l'exercice en une seule fois pour optimiser
        $charges_exercice = $this->getChargesByExercice($date_debut, $date_fin);
    
        // Calculer les totaux des centres par exercice
        $calculateCentreTotalsByCentre = $this->calculateCentreTotalsByCentre($charges_exercice);
    
        // Parcourir chaque produit et calculer son coût
        foreach ($produits as $produit) {
            // Calculer le coût du produit en utilisant les données existantes
            $cout_produit = $this->CoutProduitWithData($produit['id_produit'], $calculateCentreTotalsByCentre,$date_debut,$date_fin);
    
            // Stocker le résultat pour ce produit dans le tableau
            $resultats[$produit['id_produit']] = $cout_produit;
            $resultats[$produit['id_produit']]['produit'] = $produit;
            
        }
        
        // Retourner les résultats pour tous les produits
        return $resultats;
    }
    
}