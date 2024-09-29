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

        <h1>Formulaire d'Exercice</h1>

        <form action="insert_exercice.php" method="POST">
            <div class="form-group">
                <label for="nom">Nom :</label>
                <input type="text" id="nom" name="nom" placeholder="Entrez un nom" required>
            </div>

            <div class="form-group">
                <label for="date_debut">Date de début :</label>
                <input type="date" id="date_debut" name="date_debut" required>
            </div>

            <div class="form-group">
                <label for="date_fin">Date de fin :</label>
                <input type="date" id="date_fin" name="date_fin" required>
            </div>

            <br>
            <button type="submit">Soumettre</button>
        </form>
    </main>
</body>
</html>
