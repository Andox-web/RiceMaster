<?php include 'Header.php'; ?>
<main>   
    <div class="rain-container">
        <!-- Les emojis de blé qui tombent -->
        <div class="emoji-rain">🌾</div>
        <div class="emoji-rain">🌾</div>
        <div class="emoji-rain">🌾</div>
        <div class="emoji-rain">🌾</div>
        <div class="emoji-rain">🌾</div>
        <!-- Ajout de 3 blés supplémentaires -->
        <div class="emoji-rain">🌾</div>
        <div class="emoji-rain">🌾</div>
        <div class="emoji-rain">🌾</div>
    </div>

    <h1>Formulaire Rubrique</h1>
    <form action="<?php echo site_url("Rubrique/index/1"); ?>" method="post">
        <div class="form-group">
            <label>Nom:</label>
            <input type="text" name="nom" placeholder="Entrez le nom de la rubrique" required>
        </div>

        <div class="form-group">
            <label>Unite d'oeuvre :</label>
            <input type="text" name="unite">
        </div>

        <div class="form-group">
            <label>Nature :</label>
            <input type="radio" class="radio" name="nature" value="1"><span>Fixe</span>
            <input type="radio" class="radio" name="nature" value="0"><span>Variable</span>
        </div>
        <br>
        <?php foreach($centres as $centre){ ?>
            <div class="form-group">
                <label><?=$centre['nom']?> :</label>
                <input type="number" name="centre<?=$centre['id_centre']?>">
            </div>
        <?php } ?>
        <!-- Bouton Soumettre avec margin-left -->
        <button type="submit">Soumettre</button>
    </form>
<main>
<!-- End Disabled Backdrop Modal-->
<?php include 'Footer.php'; ?>
