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

    <h1>Formulaire de Culture de Riz</h1>

    <form>
        <!-- Input Montant -->
        <div class="form-group">
            <label for="montant">Montant :</label>
            <input type="number" id="montant" name="montant" oninput="validateMontant(this)" placeholder="Entrez un montant" required>
        </div>

        <!-- Input Date -->
        <div class="form-group">
            <label for="date">Date :</label>
            <input type="date" id="date" name="date" required>
        </div>

        <!-- Select Rubrique -->
        <div class="form-group">
            <label for="rubrique">Rubrique :</label>
            <select id="rubrique" name="rubrique">
                <option value="rubrique1">Rubrique 1</option>
                <option value="rubrique2">Rubrique 2</option>
                <option value="rubrique3">Rubrique 3</option>
            </select>
        </div>

        <br>
        <!-- Bouton Soumettre avec margin-left -->
        <a href="#"><button type="submit">Soumettre</button></a>
    </form>

    <script>
        // Validation du montant pour empêcher les valeurs négatives
        function validateMontant(input) {
            if (input.value < 0) {
                input.value = "";
                alert("Le montant ne peut pas être négatif !");
            }
        }
    </script>
</main><!-- End #main -->

<!-- End Disabled Backdrop Modal-->
<?php include 'Footer.php'; ?>

