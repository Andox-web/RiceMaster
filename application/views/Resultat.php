<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Riziculture</title>

    <!-- Css Styles -->
    <link rel="stylesheet" href="<?php echo base_url()?>assets/css/bootstrap.min.css" type="text/css">
    <link rel="stylesheet" href="<?php echo base_url()?>assets/css/style.css" type="text/css">
    <link rel="stylesheet" href="<?php echo base_url()?>assets/css/indexstyle.css" type="text/css">
    
</head>
<body>
    <header class="header">
        <div class="header__top">
            <div class="container">
                <div class="row">
                    <div class="col-lg-8 col-md-10 order-md-2 order-3">
                        <nav class="header__menu">
                            <ul>
                                <li class="active"><a href="<?php echo site_url("Index_controller/index"); ?>">Home</a></li>
                                <li><a href="<?php echo site_url("Rubrique/index/1"); ?>">Rubrique</a></li>
                                <li><a href="<?php echo site_url("Centre/index"); ?>">Centre</a></li>
                                <li><a href="<?php echo site_url("Rubrique/index/2"); ?>">Details Rubriques</a></li>
                                <li><a href="<?php echo site_url("Exo/index"); ?>">Execrcice</a></li>
                            </ul>
                        </nav>
                    </div>
                </div>
            </div>
        </div>
    </header>
<main>
        <div class="row">
            <div class="col-md-10">
                <div class="scrollable-table">
                    <table class="table table-bordered table-sm">
                        <thead>
                            <tr><th colspan="17">CALCUL DE COUT DE REVIENT PRODUCTION RIZ / KG </th></tr>
                            <tr><th colspan="17">Moi de l'exercice</th></tr>
                            <tr>
                                <th rowspan="2">RUBRIQUES</th>
                                <th rowspan="2">TOTAL</th>
                                <th rowspan="2">UNITE D'OEUVRE</th>
                                <th rowspan="2">NATURE</th>
                                
                                <th colspan="3">ADMINISTR</th>
                                <th colspan="3">USINE</th>
                                <th colspan="3">PLANTATION</th>
                                <th colspan="2">TOTAL</th>
                            </tr>
                            <tr>
                                <!-- BOUCLENNA ILAY TELO admin -->
                                <th>%</th>
                                <th colspan="2">Montant</th>
                                <!-- uSINE -->
                                <th>%</th>
                                <th colspan="2">Montant</th>
                                <!-- PLANTATION -->
                                <th>%</th>
                                <th colspan="2">Montant</th>
                                <!-- TOTAL -->
                                <th>FIXE</th>
                                <th>VARIABLE</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>COUT D'APPROVISIONNEMENT 1</td>
                                <td>2 373 838</td>
                                <td>KG</td>
                                <td>V</td>
                                <td>0,00%</td>
                                <td colspan="2">-</td>
                                <td>50,00%</td>
                                <td colspan="2">-</td>
                                <td>30</td>
                                <td colspan="2">-</td>
                                <td>152 000,00</td>
                                <td>2 373 838,00</td>
                            </tr>
                            <tr>
                                <td>COUT D'APPROVISIONNEMENT </td>
                                <td>2 373 838</td>
                                <td>KG</td>
                                <td>V</td>
                                <td>0,00%</td>
                                <td colspan="2">-</td>
                                <td>50,00%</td>
                                <td colspan="2">-</td>
                                <td>30</td>
                                <td colspan="2">-</td>
                                <td>152 000,00</td>
                                <td>2 373 838,00</td>
                            </tr>
                            <!-- Ajoutez d'autres lignes ici pour compléter le tableau -->
                        </tbody>
                        <tfoot>
                            <tr>
                                <td rowspan="2">TOTAL</td>
                                <td rowspan="2">521 674 590</td>
                                <td colspan="2"></td>
                                <td colspan="3">admintotal</td>
                                <td colspan="3">usine total</td>
                                <td colspan="3">plantation total</td>
                                <td>Total fixe</td>
                                <td>Total variable</td>
                            </tr>
                            <tr>

                            </tr>
                        </tfoot>
                    </table>
                </div> 
                <div class="row">
                    <div class="col-md-7">
                        <table border="1">
                            <thead>
                                <th>REPARTITION ADM/DISTR</th>
                                <th>Cout direct</th>
                                <th>CLES</th>
                                <th>ADM/DIST</th>
                                <th>Cout total</th>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>TOTAL PLANTATION</td>
                                    <td>25 000 000 Ar</td>
                                    <td>25 %</td>
                                    <td>5 000 000 Ar</td>
                                    <td>30 000 000 Ar</td>
                                </tr>
                                <tr>
                                    <td>TOTAL USINE</td>
                                    <td>35 000 000 Ar</td>
                                    <td>35 %</td>
                                    <td>5 000 000 Ar</td>
                                    <td>40 000 000 Ar</td>
                                </tr>
                            </tbody>
                            <tfoot>
                                <tr>
                                    <td>Total Genaral</td>
                                    <td>60 000 000 Ar</td>
                                    <td></td>
                                    <td >10 000 000</td>
                                    <td >70 000 000</td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                    <div class="col-md-4 offset-md-1">
                        <table border="1">
                            <thead>
                                <th colspan="2">Cout du Kg de Mais</th>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>Unite d'Oeuvre</td>
                                    <td>KG</td>
                                </tr>
                                <tr>
                                    <td>Nombre</td>
                                    <td>1 500 Kg</td>
                                </tr>
                                <tr>
                                    <td>Cout de Plantation</td>
                                    <td>20 000 Ar</td>
                                </tr>
                                <tr>
                                    <td>Couts totaux</td>
                                    <td>20 000 Ar</td>
                                </tr>
                            </tbody>
                            <tfoot>
                                <th>Cout du kg de Mais Concasse</th>
                                <th>50 Ar</th>
                            </tfoot>
                        </table>
                    </div>
                </div>  
            </div>
            <div class="col-md-2">
                <H2>Recolte</H2>
                <form  method="post">
                    <div class="form-group">
                        <label for="date">Exercice:</label>
                        <select name="" id="" class="form-control">
                            <option value="1">Exo 1</option>
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label for="number">KG du recolte total:</label>
                        <input type="number" id="number" name="recolte" class="form-control" required>
                    </div>
                    
                    <div class="form-group">
                        <label>Sélectionnez le type de Grain:</label>
                        <div class="checkbox">
                            <label><input type="checkbox" name="centers[]" value="Concassé"> Concassé</label>
                        </div>
                        <div class="checkbox">
                            <label><input type="checkbox" name="centers[]" value="grain"> Grain</label>
                        </div>
                    </div>
                    
                    <button type="submit" class="btn btn-primary">Soumettre</button>
                </form>
            </div>
        </div>
    </main><!-- End #main -->

<!-- End Disabled Backdrop Modal-->
<?php include 'Footer.php'; ?>
