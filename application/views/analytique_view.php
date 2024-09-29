<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Analyse Financière</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body {
            padding: 20px;
        }
        table {
            margin-bottom: 20px;
        }
    </style>
</head>
<body>

<div class="container">
    <h1>Analyse Financière</h1>
    
    <!-- Affichage des rubriques -->
    <h2>Rubriques</h2>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>ID Charge</th>
                <th>Nom</th>
                <th>Unité d'Oeuvre</th>
                <th>Total</th>
                <th>Fixe</th>
                <th>Variable</th>
                <th>Centres</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($charges['Rubrique'] as $rubrique): ?>
                <tr>
                    <td><?php echo $rubrique['id_charge']; ?></td>
                    <td><?php echo $rubrique['nom']; ?></td>
                    <td><?php echo $rubrique['unite_oeuvre']; ?></td>
                    <td><?php echo $rubrique['total']['total']; ?></td>
                    <td><?php echo $rubrique['total']['fixe']; ?></td>
                    <td><?php echo $rubrique['total']['variable']; ?></td>
                    <td>
                        <ul>
                            <?php foreach ($rubrique['centre'] as $id_centre => $centre): ?>
                                <li>Centre ID <?php echo $id_centre; ?>: Total <?php echo $centre['total']; ?>, Fixe <?php echo $centre['fixe']; ?>, Variable <?php echo $centre['variable']; ?></li>
                            <?php endforeach; ?>
                        </ul>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <!-- Affichage du total général -->
    <h2>Total Général</h2>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Total</th>
                <th>Fixe</th>
                <th>Variable</th>
                <th>Centres</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td><?php echo $charges['total']['total']['total']; ?></td>
                <td><?php echo $charges['total']['total']['fixe']; ?></td>
                <td><?php echo $charges['total']['total']['variable']; ?></td>
                <td>
                    <ul>
                        <?php foreach ($charges['total']['centre'] as $id_centre => $centre): ?>
                            <li>Centre ID <?php echo $id_centre; ?>: Total <?php echo $centre['total']; ?>, Fixe <?php echo $centre['fixe']; ?>, Variable <?php echo $centre['variable']; ?></li>
                        <?php endforeach; ?>
                    </ul>
                </td>
            </tr>
        </tbody>
    </table>
    <table class="table table-bordered">
    <thead>
        <tr>
            <th>ID Produit</th>
            <th>Nom Produit</th>
            <th>Coût Total</th>
            <th>
                
            </th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($produits as $produit): ?>
            <tr>
                <td><?php echo $produit['produit']['id_produit']; ?></td>
                <td><?php echo $produit['produit']['nom']; ?></td>
                <td><?php echo $produit['cout_total']; ?></td>
                <td>
                    <ul>
                        <?php foreach ($produit['centre_operative'] as $id_centre => $cout): ?>
                            <li>Centre ID <?php echo $id_centre; ?>: Total <?php echo $cout['cout_total']; ?></li>
                        <?php endforeach; ?>
                    </ul>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>
<table class="table table-bordered">
    <thead>
        <tr>
            <th>ID Centre Opérationnel</th>
            <th>Coût Direct</th>
            <th>Pourcentage Clés</th>
            <th>Coût Structure</th>
            <th>Coût Total</th>
        </tr>
    </thead>
    <tbody>
        <!-- Boucle pour afficher les centres opérationnels -->
        <?php foreach ($repart['centre_operative'] as $id_centre_operative => $details): ?>
            <tr>
                <td><?php echo $id_centre_operative; ?></td>
                <td><?php echo $details['cout_direct']; ?></td>
                <td><?php echo $details['cles']; ?>%</td>
                <td><?php echo $details['cout_structure']; ?></td>
                <td><?php echo $details['cout_total']; ?></td>
            </tr>
        <?php endforeach; ?>
    </tbody>
    <tfoot>
        <!-- Ligne pour afficher les totaux -->
        <tr>
            <th>Total</th>
            <th><?php echo $repart['total']['cout_direct']; ?></th>
            <th></th>
            <th><?php echo $repart['total']['cout_structure']; ?></th>
            <th><?php echo $repart['total']['cout_total']; ?></th>
        </tr>
    </tfoot>
</table>

</div>

</body>
</html>
