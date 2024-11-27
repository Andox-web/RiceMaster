import pandas as pd
from sklearn.ensemble import RandomForestClassifier
from sklearn.model_selection import train_test_split
from sklearn.metrics import accuracy_score, classification_report

# Liste globale pour stocker les colonnes utilisées lors de l'entraînement
columns_used = []

# Classe CV
class Cv:
    def _init_(self, diplomes, competences, experience):
        self.diplomes = diplomes
        self.competences = competences
        self.experience = experience


# Fonction pour numériser un dataframe contenant des CV
def numeriseDataframe(df):
    # Encodage des diplômes
    diplome_map = {"Baccalauréat": 1, "Licence": 2, "Master": 3}
    df["Diplome"] = df["Diplomes"].apply(lambda x: diplome_map.get(x, 0))
    
    # Pondération des compétences
    competence_weights = {
        "Python": 5,
        "SQL": 4,
        "ML": 6,
        "HTML": 1,
        "CSS": 1,
        "Java": 3
    }
    
    # Agrégation pondérée des compétences
    def aggregate_competences(competence_list):
        total_weight = sum([competence_weights.get(c, 0) for c in competence_list.split(",")])
        max_weight = sum(competence_weights.values())  # Normaliser par rapport au poids max possible
        return total_weight / max_weight
    
    df["CompetenceScore"] = df["Competences"].apply(aggregate_competences)
    
    # Suppression des colonnes textuelles inutiles après encodage
    df = df.drop(columns=["Diplomes", "Competences"])
    
    # Stocker l'ordre des colonnes utilisées pour l'entraînement (sans la cible "BonCV")
    global columns_used
    columns_used = [col for col in df.columns if col != "BonCV"]
    
    return df


# Fonction pour numériser un objet Cv
def numeriseCv(cv):
    diplome_map = {"Baccalauréat": 1, "Licence": 2, "Master": 3}
    competence_weights = {
        "Python": 5,
        "SQL": 4,
        "ML": 6,
        "HTML": 1,
        "CSS": 1,
        "Java": 3
    }
    
    # Encodage des diplômes
    diplomes_encoded = max([diplome_map.get(diplome, 0) for diplome in cv.diplomes], default=0)
    
    # Agrégation pondérée des compétences
    total_weight = sum([competence_weights.get(c, 0) for c in cv.competences])
    max_weight = sum(competence_weights.values())
    competence_score = total_weight / max_weight
    
    # Construire un dictionnaire avec les données numérisées
    data = {
        "Diplome": diplomes_encoded,
        "Experience": cv.experience,
        "CompetenceScore": competence_score
    }
    
    # Réordonner les colonnes pour correspondre à celles utilisées lors de l'entraînement
    global columns_used
    ordered_data = {col: data.get(col, 0) for col in columns_used}
    
    return ordered_data


# Fonction pour entraîner le modèle
def trainModel(csv_file):
    # Charger les données
    df = pd.read_csv(csv_file)
    
    # Prétraitement des données
    df = numeriseDataframe(df)
    
    # Séparation des features et de la cible
    X = df[columns_used]  # Utilise uniquement les colonnes nécessaires
    y = df["BonCV"]
    
    # Division en ensembles d'entraînement et de test
    X_train, X_test, y_train, y_test = train_test_split(X, y, test_size=0.2, random_state=42)
    
    # Entraînement du modèle
    model = RandomForestClassifier(random_state=42)
    model.fit(X_train, y_train)
    
    # Évaluation du modèle
    y_pred = model.predict(X_test)
    print("Précision :", accuracy_score(y_test, y_pred))
    print("\nRapport de classification :\n", classification_report(y_test, y_pred))
    
    return model


# Fonction pour prédire si un CV est bon ou mauvais
def predict(cv, model):
    # Numérisation des données du CV
    numerised_data = pd.DataFrame([numeriseCv(cv)])
    
    # Prédiction
    prediction = model.predict(numerised_data)
    return "Bon CV" if prediction[0] == 1 else "Mauvais CV"


# Étape principale : Exécution du programme
# if __name__ == "_main_":
    # Étape 1 : Entraîner le modèle avec un fichier CSV
csv_file = "cv_dataset.csv"  # Nom du fichier CSV contenant les CV
model = trainModel(csv_file)

# Étape 2 : Tester avec un nouvel objet Cv
nouveau_cv = Cv()
nouveau_cv.diplomes=["Licence"]
nouveau_cv.competences=["Java","SQL","Python"]
nouveau_cv.experience=3

resultat = predict(nouveau_cv, model)
print("\nPrédiction pour le nouveau CV :", resultat)