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
    <form action="#" method="get">
        <div class="form-group">
            <label>Nom:</label>
            <input type="text" name="nom" placeholder="Entrez le nom de la rubrique" required>
        </div>

        <div class="form-group">
            <label>Unite d'oeuvre :</label>
            <select name="unite">
                <option>KG</option>
                <option>NB</option>
            </select>
        </div>

        <div class="form-group">
            <label>Nature :</label>
            <input type="radio" class="radio" name="nature"><span>Fixe</span>
            <input type="radio" class="radio" name="nature"><span>Variable</span>
        </div>

        <br>
        <!-- Bouton Soumettre avec margin-left -->
        <a href="#"><button type="submit">Soumettre</button></a>
    </form>
<main>
<!-- End Disabled Backdrop Modal-->
<?php include 'Footer.php'; ?>
