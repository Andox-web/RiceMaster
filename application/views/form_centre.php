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

    <h1>Formulaire Centre</h1>

    <form action="<?php echo site_url('centre/insert') ?>" method="post">
        <div class="form-group">
            <label for="nom">Nom :</label>
            <input type="text" id="nom" name="nom" placeholder="Entrez le nom du centre" required>
        </div>
        <div class="form-group">
            <label for="nom">Operative :</label>
            <input type="radio" class="radio" name="operative" value="true"><span>Operationnelle</span>
            <input type="radio" class="radio" name="operative" value="false"><span>Structurelle</span>
        </div>

        <br>
        <!-- Bouton Soumettre avec margin-left -->
        <button type="submit">Soumettre</button>
    </form>
</main> 
<!-- End Disabled Backdrop Modal-->
<?php include 'Footer.php'; ?>
