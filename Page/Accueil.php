<?php
// Connexion à la base de données

include '../bdd.php';

$results = [];
$searchPerformed = false; // Add this flag

// Fonction pour calculer la similarité
function calculateSimilarity($str1, $str2) {
    $str1 = strtolower(normalizeString($str1));
    $str2 = strtolower(normalizeString($str2));
    similar_text($str1, $str2, $percent);  // Calcul de la similarité
    return $percent;
}

// Fonction pour normaliser les chaînes (supprimer les accents et caractères spéciaux)
function normalizeString($str) {
    $str = htmlentities($str, ENT_NOQUOTES, 'UTF-8');
    $str = preg_replace('/&([a-zA-Z])(acute|grave|circ|tilde|uml|cedil|ring|slash|lig|zlig);/', '$1', $str);
    $str = preg_replace('/&[^;]+;/', '', $str);
    return $str;
}

// Vérifie si un terme a été soumis
if (!empty($_GET['query'])) {
    $searchPerformed = true; // Set flag to true when search is performed
    $query = htmlspecialchars($_GET['query']);
    $normalizedQuery = normalizeString($query);

    // Requête SQL avec recherche approximative dans les deux tables
    $stmt = $db->prepare("
        SELECT 'benevole' as type, id_Benevole as id, nomBenevole as nom, prenomBenevole as prenom, description, localisation, tag
        FROM gt_benevole
        WHERE nomBenevole LIKE :fuzzy_query
            OR prenomBenevole LIKE :fuzzy_query
            OR SOUNDEX(nomBenevole) = SOUNDEX(:query)
            OR SOUNDEX(prenomBenevole) = SOUNDEX(:query)
            OR description LIKE :fuzzy_query
            OR localisation LIKE :fuzzy_query
            OR tag LIKE :fuzzy_query
        UNION
        SELECT 'association' as type, id_Association as id, nomAssociation as nom, '' as prenom, description, localisation, tag
        FROM gt_association
        WHERE nomAssociation LIKE :fuzzy_query
            OR SOUNDEX(nomAssociation) = SOUNDEX(:query)
            OR description LIKE :fuzzy_query
            OR localisation LIKE :fuzzy_query
            OR tag LIKE :fuzzy_query
        ORDER BY nom ASC
    ");

    $fuzzy_query = "%" . $normalizedQuery . "%";
    $params = [
        'query' => $normalizedQuery,
        'fuzzy_query' => $fuzzy_query,
    ];

    $stmt->execute($params);
    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Filtrage des résultats avec un score de similarité minimum
    $filtered_results = array_filter($results, function($result) use ($normalizedQuery) {
        return calculateSimilarity($result['nom'], $normalizedQuery) > 30 || 
               calculateSimilarity($result['prenom'], $normalizedQuery) > 30 ||
               stripos(normalizeString($result['tag']), $normalizedQuery) !== false; // Vérifie si le tag contient la recherche
    });

    $results = !empty($filtered_results) ? $filtered_results : $results;
}
?>
 
    <h1>GiveTime</h1>

    <!-- Boutons de filtre au-dessus de la barre de recherche -->
    <div class="filter-buttons">
        <button class="filter-btn" value="all">Tout afficher</button>
        <button class="filter-btn" value="benevole">Bénévole</button>
        <button class="filter-btn" value="association">Association</button>
    </div>

    <!-- Barre de recherche avec bouton à droite -->
    <div class="search-container">
        <form method="GET" action="" class="search-form">
            <div class="search-input-group">
                <input type="text" id="searchInput" name="query" placeholder="Rechercher par nom, prénom ou tag..." 
                       value="<?= isset($_GET['query']) ? htmlspecialchars($_GET['query']) : '' ?>">
                <button type="submit" class="search-button">Rechercher</button>
            </div>
        </form>
    </div>

    <!-- Résultats de la recherche -->
    <div class="results-wrapper">
        <div id="resultsContainer" class="results">
        <?php if ($searchPerformed && !empty($results)): ?>
            <?php 
            $displayedResults = array_slice($results, 0, 3);
            foreach ($displayedResults as $user): 
            ?>
                <div class="user-card <?= htmlspecialchars($user['type']) ?>">
                    <strong><?= htmlspecialchars($user['nom']) ?> <?= htmlspecialchars($user['prenom']) ?></strong><br>
                    <small><?= $user['type'] == 'association' ? "Association" : "Bénévole" ?></small><br>
                    <em><?= htmlspecialchars($user['description']) ?></em><br>
                    <div class="button-container">
                        <a href="message.php?IDcontact=<?= htmlspecialchars($user['id']) ?>&nom=<?= htmlspecialchars($user['nom']) ?>&prenom=<?= htmlspecialchars($user['prenom']) ?>">
                            <button type="button" id="bouton_envoyer">Envoyer un message</button>
                        </a>
                        <a href="profil.php?type=<?= htmlspecialchars($user['type']) ?>&id=<?= htmlspecialchars($user['id']) ?>">
                            <button type="button" id="bouton_profil">Voir le profil</button>
                        </a>
                    </div>
                </div>
            <?php endforeach; ?>

            <?php if (count($results) > 5): ?>
                <div class="voir-plus-container" style="text-align: center; margin-top: 20px;">
                    <a href="AccueilRecherche.php?query=<?= urlencode($_GET['query']) ?><?= isset($_GET['type']) ? '&type=' . urlencode($_GET['type']) : '' ?>">
                        <button type="button" class="voir-tous-btn">Voir tous les profils</button>
                    </a>
                </div>
            <?php endif; ?>

        <?php elseif ($searchPerformed): ?>
            <p>Aucun résultat trouvé pour votre recherche.</p>
        <?php endif; ?>
        </div>
    </div>

    <script>
    document.addEventListener("DOMContentLoaded", function () {
        const filterButtons = document.querySelectorAll(".filter-btn");

        filterButtons.forEach(button => {
            button.addEventListener("click", function () {
                const type = this.getAttribute("value");
                const url = new URL(window.location.href);
                url.searchParams.set("type", type);

                // Supprimer le paramètre si "all" est sélectionné
                if (type === "all") {
                    url.searchParams.delete("type");
                }

                window.location.href = url.toString();
            });
        });

        // Filtrage des résultats en fonction du type
        const params = new URLSearchParams(window.location.search);
        const selectedType = params.get("type");
        if (selectedType && selectedType !== "all") {
            document.querySelectorAll(".user-card").forEach(card => {
                if (!card.classList.contains(selectedType)) {
                    card.style.display = "none";
                }
            });
            
            // Mettre en surbrillance le bouton de filtre sélectionné
            document.querySelector(`.filter-btn[value="${selectedType}"]`).classList.add('active');
        } else {
            // Si aucun filtre ou "tous", mettre en surbrillance le bouton "Tout afficher"
            document.querySelector('.filter-btn[value="all"]').classList.add('active');
        }
    });
    </script>