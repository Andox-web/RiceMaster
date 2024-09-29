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
    <link rel="stylesheet" href="<?php echo base_url()?>assets/css/mystyle.css" type="text/css">
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
 
