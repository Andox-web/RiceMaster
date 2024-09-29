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

    <form>
        <!-- Input Montant -->
        <div class="form-group">
            <label for="nom">Nom :</label>
            <input type="text" id="nom" name="nom" placeholder="Entrez le nom du centre" required>
        </div>

        <br>
        <!-- Bouton Soumettre avec margin-left -->
        <a href="#"><button type="submit">Soumettre</button></a>
    </form>
</main> 
<!-- End Disabled Backdrop Modal-->
<?php include 'Footer.php'; ?>
