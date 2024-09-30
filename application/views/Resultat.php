<?php include 'Header.php'; ?>

    <main>
        <div class="row">
            <div class="col-md-10">
                <div class="scrollable-table">
                    <table class="table table-bordered table-sm">
                        <thead>
                            <tr><th colspan="17">CALCUL DE COUT DE REVIENT PRODUCTION RIZ / KG </th></tr>
                            <tr><th colspan="17">Exercice du <?=$date_debut?> au <?=$date_fin?></th></tr>
                            <tr>
                                <th rowspan="2">RUBRIQUES</th>
                                <th rowspan="2">TOTAL</th>
                                <th rowspan="2">UNITE D'OEUVRE</th>
                                <th rowspan="2">NATURE</th>
                                
                                <?php foreach ($centres as $centre): ?>
                                    <th colspan="3"><?= $centre['nom'] ?></th>
                                <?php endforeach; ?>
                                <th colspan="2">TOTAL</th>
                            </tr>
                            <tr>
                            <?php for ($i = 0; $i < count($centre); $i++): ?>
                                <th>%</th>
                                <th>FIXE</th>
                                <th>VARIABLE</th>
                            <?php endfor; ?>
                                <th>FIXE</th>
                                <th>VARIABLE</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($charges['Rubrique'] as $charge): ?>
                                <tr>
                                    <td><?php echo $charge['nom']; ?></td>
                                    <td><?php echo $charge['total']['total']; ?></td>
                                    <td><?php echo $charge['unite_oeuvre']; ?></td>
                                    <td>
                                        <?php
                                            echo $charge['nature'] ? 'F' : 'V';
                                        ?>
                                     </td>
                                     <?php foreach ($centres as $centre){
                                        $id_centre=$centre['id_centre'];
                                        $montant=[
                                            'pourcentage'=>0,
                                            'total' => '-',
                                            'fixe' => '-',
                                            'variable' => '-'
                                        ];
                                        if (isset($charge['centre'][$id_centre])) {
                                            $montant=$charge['centre'][$id_centre];      
                                        }
                                        ?>

                                        <td><?php echo $montant['pourcentage']; ?>%</td>
                                        <td><?php echo $montant['fixe']; ?></td>
                                        <td><?php echo $montant['variable']; ?></td>

                                        <?php
                                        
                                    }?>
                                    
                                    <td><?php echo $charge['total']['fixe']; ?></td>
                                    <td><?php echo $charge['total']['variable']; ?></td>
                                            
                                </tr>
                            <?php endforeach; ?>
                            <!-- Ajoutez d'autres lignes ici pour compléter le tableau -->
                        </tbody>
                        <tfoot>
                            <tr>
                                <td rowspan="2">TOTAL</td>
                                <td rowspan="2"><?=$charges['total']['total']['total']?></td>
                                <td rowspan="2" colspan="2"></td>
                                <?php foreach ($centres as $centre): ?>
                                    <td></td>
                                    <td><?=$charges['total']['centre'][$centre['id_centre']]['fixe']??0?></td>
                                    <td><?=$charges['total']['centre'][$centre['id_centre']]['variable']??0?></td>
                                <?php endforeach; ?>
                                <td><?=$charges['total']['total']['fixe']?></td>
                                <td><?=$charges['total']['total']['variable']?></td>
                            </tr>
                            <tr>
                                <?php foreach ($centres as $centre): ?>
                                    <td colspan="3"><?=$charges['total']['centre'][$centre['id_centre']]['total']??0?></td>
                                <?php endforeach; ?>
                                <td><?=$charges['total']['total']['fixe']?></td>
                                <td><?=$charges['total']['total']['variable']?></td>
                            </tr>
                        </tfoot>
                    </table>
                </div> 
                <div class="row">
                    <?php foreach ($repartitions as $repartition): ?>
                        <div class="col-md-7">
                            <table border="1">
                                <thead>
                                    <th>REPARTITION <?=$repartition['centre']['nom']?></th>
                                    <th>Cout direct</th>
                                    <th>CLES</th>
                                    <th><?=$repartition['centre']['nom']?></th>
                                    <th>Cout total</th>
                                </thead>
                                <tbody>
                                    <?php foreach ($repartition['centre_operative'] as $id_centre => $operation): ?>
                                        <tr>
                                            <td>TOTAL <?=$operation['nom']?></td>
                                            <td><?=$operation['cout_direct']?></td>
                                            <td><?=$operation['cles']?> %</td>
                                            <td><?=$operation['cout_structure']?></td>
                                            <td><?=$operation['cout_total']?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <td>Total Genaral</td>
                                        <td><?=$repartition['total']['cout_direct']?></td>
                                        <td></td>
                                        <td ><?=$repartition['total']['cout_structure']?></td>
                                        <td ><?=$repartition['total']['cout_total']?></td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    <?php endforeach; ?>
                                
                    <div class="col-md-4 offset-md-1">
                    <?php foreach ($produits as $id_produit=>$produit){ 
                        $recolte = $production[$id_produit] ?? 0; 
                        ?>
                        <table border="1">
                            <thead>
                                <th colspan="2">Cout du Kg de <?=$produit['produit']['nom']?></th>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>Unite d'Oeuvre</td>
                                    <td>KG</td>
                                </tr>
                                <tr>
                                    <td>Nombre</td>
                                    <td><?=$recolte?> Kg</td>
                                </tr>
                                <?php foreach ($produit['centre_operative'] as $revenu){ ?>
                                <tr>
                                    <td>Cout de <?=$revenu['nom']?></td>
                                    <td><?=$revenu['cout_total']?></td>
                                </tr>
                                <?php } ?>
                            </tbody>
                            <tfoot>
                                <th>Cout du kg de <?=$produit['produit']['nom']?></th>
                                <th><?=$recolte!=0?$produit['cout_total']/$recolte:0?></th>
                            </tfoot>
                        </table>
                        <br>
                    <?php } ?>
                    </div>
                </div>  
            </div>
            <div class="col-md-2">
                <H2>Recolte</H2>
                <form  method="get" action="<?php echo site_url("Index_controller"); ?>">
                    <div class="form-group">
                        <label for="date">Exercice:</label>
                        <select name="exercice" id="" class="form-control">
                            <?php foreach ($exercices as $exercice){ ?> 
                                <option value="<?=$exercice['id_exercice']?>"><?=$exercice['nom']?></option>    
                            <?php } ?>
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label for="number">KG du recolte total:</label>
                        <input type="number" id="number" name="recolte" class="form-control" required>
                    </div>
                    
                    <div class="form-group">
                        <label>Sélectionnez le type de Grain:</label>
                        <select name="produit" id="" class="form-control">
                            <?php foreach ($produits as $id_produit=>$produit){ ?> 
                                <option value="<?=$id_produit?>"><?=$produit['produit']['nom']?></option>    
                            <?php } ?>
                        </select>
                    </div>
                    
                    <button type="submit" class="btn btn-primary">Soumettre</button>
                </form>
            </div>
        </div>
    </main><!-- End #main -->

<!-- End Disabled Backdrop Modal-->
<?php include 'Footer.php'; ?>
