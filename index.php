
<?php
session_start(); 
// Inclure avant toute sortie HTML
include './config/db.php';

// Initialiser une variable pour le message
$message = '';

// Gestion des favoris (ajout via AJAX)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'toggle_favorite') {
    if (!isset($_SESSION['logged_in']) || !$_SESSION['logged_in']) {
        echo json_encode(['success' => false, 'message' => 'Utilisateur non connecté']);
        exit;
    }

    $eventId = filter_input(INPUT_POST, 'event_id', FILTER_VALIDATE_INT);
    $userId = $_SESSION['user_id'];

    if (!$eventId) {
        echo json_encode(['success' => false, 'message' => 'ID événement invalide']);
        exit;
    }

    try {
        // Vérifier si le favori existe déjà
        $checkStmt = $pdo->prepare("SELECT id FROM favorites WHERE user_id = :user_id AND event_id = :event_id");
        $checkStmt->execute(['user_id' => $userId, 'event_id' => $eventId]);
        $existing = $checkStmt->fetch();

        if ($existing) {
            // Si existe, supprimer le favori
            $deleteStmt = $pdo->prepare("DELETE FROM favorites WHERE user_id = :user_id AND event_id = :event_id");
            $deleteStmt->execute(['user_id' => $userId, 'event_id' => $eventId]);
            echo json_encode(['success' => true, 'action' => 'removed']);
        } else {
            // Si n'existe pas, ajouter le favori
            $insertStmt = $pdo->prepare("INSERT INTO favorites (user_id, event_id, created_at) VALUES (:user_id, :event_id, NOW())");
            $insertStmt->execute(['user_id' => $userId, 'event_id' => $eventId]);
            echo json_encode(['success' => true, 'action' => 'added']);
        }
        exit;
    } catch (PDOException $e) {
        echo json_encode(['success' => false, 'message' => 'Erreur de base de données']);
        exit;
    }
}

// Récupération des favoris de l'utilisateur connecté
$userFavorites = [];
if (isset($_SESSION['logged_in']) && $_SESSION['logged_in']) {
    $favStmt = $pdo->prepare("SELECT event_id FROM favorites WHERE user_id = :user_id");
    $favStmt->execute(['user_id' => $_SESSION['user_id']]);
    $userFavorites = $favStmt->fetchAll(PDO::FETCH_COLUMN);
}

// Traitement de l'inscription à un événement
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_SESSION['logged_in']) && $_SESSION['logged_in']) {
    $eventId = $_POST['event_id'];
    $userId = $_SESSION['user_id'];

    // Préparer la requête d'insertion
    $stmt = $pdo->prepare("INSERT INTO event_user (user_id, event_id, created_at) VALUES (:user_id, :event_id, NOW())");

    // Exécuter la requête
    try {
        $stmt->execute(['user_id' => $userId, 'event_id' => $eventId]);
        $message = 'Inscription réussie à l\'événement !';
    } catch (PDOException $e) {
        $message = 'Erreur lors de l\'inscription : ' . htmlspecialchars($e->getMessage());
    }
}

// Récupérer les événements à venir
$stmt = $pdo->query("SELECT * FROM events WHERE date >= CURDATE() ORDER BY date ASC");
$events = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Accueil</title>
    <meta name="description" content="Esportify - plateforme de gestion d'évenements e-sport, inscrivez-vous à des tournois, de jeux vidéo, et connectez-vous avec d'autres passionnés de gaming.">
    <meta name="keywords" content="esport, jeu vidéo, tournois, compétitions gaming, événements">
    <link rel="stylesheet" href="./css/index.css">
    <style>
        /* Styles pour le modal et l'infobulle */
        .modal {
            display: none; /* Masqué par défaut */
            position: fixed; /* Reste en place */
            z-index: 1; /* Au-dessus des autres éléments */
            left: 0;
            top: 0;
            width: 100%; /* Largeur complète */
            height: 100%; /* Hauteur complète */
            overflow: auto; /* Ajoute une barre de défilement si nécessaire */
            background-color: rgba(0, 0, 0, 0.4); /* Fond noir avec opacité */
        }

        .modal-content {
            background-color: #C65D3A;
            margin: 15% auto; /* 15% du haut et centré */
            padding: 20px;
            border: 1px solid #888;
            width: 80%; /* Peut être ajusté */
        }

        .close {
            color: #aaa;
            float: right;
            font-size: 28px;
            font-weight: bold;
        }

        .close:hover,
        .close:focus {
            color: black;
            text-decoration: none;
            cursor: pointer;
        }

        .modal-content .btn {
            text-decoration: none;
        }

        /* Styles pour l'infobulle */
        .tooltip {
            position: fixed;
            bottom: 20px;
            left: 50%;
            transform: translateX(-50%);
            background-color: #4CAF50;
            color: white;
            padding: 10px;
            border-radius: 5px;
            display: none; /* Masqué par défaut */
            z-index: 1000; /* Au-dessus des autres éléments */
        }

        .button-container {
            display: flex;
            justify-content: center; /* Centre les boutons horizontalement */
            align-items: stretch; /* Assure que les boutons s'étendent à la même hauteur */
            gap: 10px; /* Espace entre les boutons */
        }

        .btn-common {
            flex: 1; /* Les boutons prennent une largeur égale */
            padding: 10px; /* Ajustez selon vos besoins */
            font-size: 16px; /* Taille de la police */
            cursor: pointer;
            background-color: #C65D3A; /* Couleur de fond */
            color: white; /* Couleur du texte */
            border: none; /* Pas de bordure */
            border-radius: 5px; /* Coins arrondis */
            transition: background-color 0.3s; /* Transition pour l'effet hover */
        }

        .btn-common:hover {
            background-color: #a65d3a; /* Couleur au survol */
        }

        .favorite-btn.active {
            background-color: #a65d3a !important;
        }

        .favorite-btn:hover {
            opacity: 0.8;
            transform: scale(1.1);
            transition: all 0.2s ease;
        }
    </style>
</head>
<body>

    <!-- Affichage du message d'infobulle -->
    <div id="tooltip" class="tooltip"><?php echo htmlspecialchars($message); ?></div>

    <!--  DEBUT  MENU  GRAND  ECRAN  -->
    <nav class="horizontal-menu">
        <a href="#">Accueil</a>
        <a href="./html/EventsResearch.html">Événements</a>
        <a href="./html/Contact.html">Contact</a>
        <a href="#">Favoris</a>
        <a href="#">Chat</a>
        
        <?php if (isset($_SESSION['logged_in']) && $_SESSION['logged_in']): ?>
            <a href="/StudiProjetEsport/deconnexion">Déconnexion</a>
        <?php else: ?>
            <a href="/StudiProjetEsport/connexion">Connexion</a>
        <?php endif; ?>
    </nav>
    <!--  FIN  MENU  GRAND  ECRAN  -->

    <!--  TEXTE ACCUEIL     GRAND  ECRAN  -->
    <div class="centered-div">
    
    
        <p style="margin-bottom: 80px;">Bienvenue dans cette plateforme de jeux Esportify ! <br>
        Inscrivez-vous à des tournois de jeux vidéo et connectez-vous avec d'autres passionnés de gaming.
         </p>

            <!-- Affichage des événements pour les grands écrans -->
        <div class="events-section">
                  <?php if ($events): ?>

                    <center>

                    <ul>
                                  <?php foreach ($events as $event): ?>
                                         <li>
                                                <center><h3><?php echo htmlspecialchars($event['titre']); ?></h3></center>

                                <?php if ($event['photo']): ?>
                            
                                    <img style="border-radius: 30px; margin:O auto; width:60%; height:60%;" src="<?php echo htmlspecialchars($event['photo']); ?>" alt="Photo de l'événement" />
                        
                                    <?php endif; ?>

                        
                                    <p><?php echo htmlspecialchars($event['description']); ?></p>
                        
                                    <p>Date: <?php echo htmlspecialchars($event['date']); ?> à <?php echo htmlspecialchars($event['Heure']); ?></p>
                        
                                    <p>Lieu: <?php echo htmlspecialchars($event['Adresse']); ?></p><br>
                        
                                    
                            
                                   
                                    
                                    <?php if (isset($_SESSION['logged_in']) && $_SESSION['logged_in']): ?>
                              
                                        <form action="index.php" method="POST" style="display: inline;">
                                    
                                        <input type="hidden" name="event_id" value="<?php echo htmlspecialchars($event['id']); ?>">
                                    
                                        <button type="submit" class="btn-common">Rejoindre</button>
                               
                                    </form>
                                
                                    <button onclick="window.location.href='/StudiProjetEsport/participants?event_id=<?php echo htmlspecialchars($event['id']); ?>'" class="btn-common">Les participants</button>
                                
                                    <button 
                                    onclick="toggleFavorite(<?php echo htmlspecialchars($event['id']); ?>)"
                                    class="favorite-btn <?php echo in_array($event['id'], $userFavorites) ? 'active' : ''; ?>"
                                    style="background-color: #C65D3A; border: none; cursor: pointer; padding: 5px; margin-left: 5px;"
                                    data-event-id="<?php echo htmlspecialchars($event['id']); ?>"
                                >
                               
                                    <img 
                                        src="./images/star.png" 
                                        alt="Étoile" 
                                        style="width: 20px; height: 20px; filter: <?php echo in_array($event['id'], $userFavorites) ? 'brightness(0) invert(1)' : 'none'; ?>;"
                                    >
                                </button>
                            <?php else: ?>
                                <button onclick="openModal()" class="btn-common">Rejoindre</button>
                                <button onclick="window.location.href='/StudiProjetEsport/participants?event_id=<?php echo htmlspecialchars($event['id']); ?>'" class="btn-common">Les participants</button>
                                <button style="background-color: #C65D3A; border: none; cursor: pointer; padding: 5px; margin-left: 5px;" onclick="showNotLoggedInModal()">
                                    <img src="./images/star.png" alt="Étoile" style="width: 20px; height: 20px;">
                                </button>
                            <?php endif; ?>
                      
                        <br><br>
                        <hr>
                    </li>
                <?php endforeach; ?>
            </ul>

           </CEnter>
        <?php else: ?>
            <p>Aucun événement à venir.</p>
        <?php endif; ?>
    </div>
</div>


    <!--  DEBUT  HTML  PETIT ECRAN  -->
    <!-- Menu Hamburger -->
    <div class="menu-hamburger">
        <div class="hamburger-icon" onclick="toggleMenu()">
            ☰ <!-- Icône hamburger -->
        </div>
        <div id="menu" class="menu-links">
            <a href="#">Accueil</a>
            <a href="/StudiProjetEsport/recherche-evenement">Événements</a>
            <a href="/StudiProjetEsport/contact">Contact</a>
            <?php if (isset($_SESSION['logged_in']) && $_SESSION['logged_in']): ?>
                <a href="/StudiProjetEsport/deconnexion">Déconnexion</a>
            <?php else: ?>
                <a href="/StudiProjetEsport/connexion">Connexion</a>
            <?php endif; ?>
        </div>

        <h1 id="title-mini">Bienvenue sur Esportify</h1>

        <p style="margin-top: -40px;" id="accueil-description-mini">
            Inscrivez-vous à des tournois de jeux vidéo et connectez-vous avec d'autres passionnés de gaming.
            <br><br><br><b>Événements à venir :</b><br><br>
        </p>

        <!-- Affichage des événements petits ecrans -->
        <div class="events-section">
            <?php if ($events): ?>
                <ul>
                    <?php foreach ($events as $event): ?>
                        <li>
                            <center><h3><?php echo htmlspecialchars($event['titre']); ?></h3></center>

                            <?php if ($event['photo']): ?>
                                <img style="border-radius: 30px; margin-left:20%; width:60%; height:60%;" src="<?php echo htmlspecialchars($event['photo']); ?>" alt="Photo de l'événement" />
                            <?php endif; ?>

                            <p><?php echo htmlspecialchars($event['description']); ?></p>
                            <p>Date: <?php echo htmlspecialchars($event['date']); ?> à <?php echo htmlspecialchars($event['Heure']); ?></p>
                            <p>Lieu: <?php echo htmlspecialchars($event['Adresse']); ?></p><br>
                            <center>
                                <?php if (isset($_SESSION['logged_in']) && $_SESSION['logged_in']): ?>
                                    <form action="index.php" method="POST" style="display: inline;">
                                        <input type="hidden" name="event_id" value="<?php echo htmlspecialchars($event['id']); ?>">
                                        <button type="submit" class="btn-common">Rejoindre</button>
                                    </form>
                                    <button onclick="window.location.href='/StudiProjetEsport/participants?event_id=<?php echo htmlspecialchars($event['id']); ?>'" class="btn-common">Les participants</button>
                                    <button 
                                        onclick="toggleFavorite(<?php echo htmlspecialchars($event['id']); ?>)"
                                        class="favorite-btn <?php echo in_array($event['id'], $userFavorites) ? 'active' : ''; ?>"
                                        style="background-color: #C65D3A; border: none; cursor: pointer; padding: 5px; margin-left: 5px;"
                                        data-event-id="<?php echo htmlspecialchars($event['id']); ?>"
                                    >
                                        <img 
                                            src="./images/star.png" 
                                            alt="Étoile" 
                                            style="width: 20px; height: 20px; filter: <?php echo in_array($event['id'], $userFavorites) ? 'brightness(0) invert(1)' : 'none'; ?>;"
                                        >
                                    </button>
                                <?php else: ?>
                                    <button onclick="openModal()" class="btn-common">Rejoindre</button>
                                    <button onclick="window.location.href='/StudiProjetEsport/participants?event_id=<?php echo htmlspecialchars($event['id']); ?>'" class="btn-common">Les participants</button>
                                    <button style="background-color: #C65D3A; border: none; cursor: pointer; padding: 5px; margin-left: 5px;" onclick="showNotLoggedInModal()">
                                        <img src="./images/star.png" alt="Étoile" style="width: 20px; height: 20px;">
                                    </button>
                                <?php endif; ?>
                            </center>
                            <br><br>
                            <hr>
                        </li>
                    <?php endforeach; ?>
                </ul>
            <?php else: ?>
                <p>Aucun événement à venir.</p>
            <?php endif; ?>
        </div>

        <footer id="site-footer">
            <div class="footer-icons">
                <a href="/StudiProjetEsport"> <img src="/StudiProjetEsport/images/home.png" alt="Accueil"></a>
                <a href="/StudiProjetEsport/contact"> <img src="/StudiProjetEsport/images/phone-call.png" alt="Téléphone"></a>
                <a href="/StudiProjetEsport/favoris"><img src="/StudiProjetEsport/images/favorite.png" alt="Favoris"></a>
                <a href="/StudiProjetEsport/condition-user"><img src="/StudiProjetEsport/images/contrat.png" alt="Contrat"></a>
            </div>
        </footer>
    </div>
    <!--  FIN HTML   PETIT ECRAN  -->

    <!-- Modal de connexion -->
    <div id="loginModal" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeModal()">&times;</span>
            <center><h2>Connexion requise</h2></center> 
            <p>Veuillez vous connecter pour rejoindre cet événement.</p>
            <a href="/StudiProjetEsport/connexion" class="btn">
                <center><button> Se connecter </button></center> 
            </a>
        </div>
    </div>

    <!-- Modale -->
    <div id="notLoggedInModal" style="display: none; position: fixed; z-index: 1000; left: 0; top: 0; width: 100%; height: 100%; background-color: rgba(0, 0, 0, 0.5);">
        <div style="background-color: #C65D3A; margin: 15% auto; padding: 20px; border: 1px solid #888; width: 80%; max-width: 400px; text-align: center;">
            <h2 style="color: white;">Accès refusé</h2>
            <p style="color: white;">Vous devez être connecté pour accéder à cette fonctionnalité.</p>
            <button onclick="closeNotLoggedInModal()" style="background-color: white; color: #C65D3A; border: none; padding: 5px 10px; cursor: pointer;">Fermer</button>
        </div>
    </div>

    <script>
        function openModal() {
            document.getElementById('loginModal').style.display = 'block';
        }

        function closeModal() {
            document.getElementById('loginModal').style.display = 'none';
        }

        // Afficher l'infobulle si un message est présent
        window.onload = function() {
            const message = "<?php echo addslashes($message); ?>";
            if (message) {
                const tooltip = document.getElementById('tooltip');
                tooltip.style.display = 'block';
                setTimeout(() => {
                    tooltip.style.display = 'none';
                }, 3000); // Disparaît après 3 secondes
            }
        };

        // Fermer le modal si l'utilisateur clique en dehors de celui-ci
        window.onclick = function(event) {
            const modal = document.getElementById('loginModal');
            if (event.target === modal) {
                closeModal();
            }
        }

        function showNotLoggedInModal() {
            document.getElementById('notLoggedInModal').style.display = 'block';
        }

        function closeNotLoggedInModal() {
            document.getElementById('notLoggedInModal').style.display = 'none';
        }

        function toggleFavorite(eventId) {
            fetch('index.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: `action=toggle_favorite&event_id=${eventId}`
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    const button = document.querySelector(`button[data-event-id="${eventId}"]`);
                    const img = button.querySelector('img');
                    
                    if (data.action === 'added') {
                        button.classList.add('active');
                        img.style.filter = 'brightness(0) invert(1)';
                        showTooltip('Événement ajouté aux favoris');
                    } else {
                        button.classList.remove('active');
                        img.style.filter = 'none';
                        showTooltip('Événement retiré des favoris');
                    }
                } else {
                    showTooltip('Erreur: ' + (data.message || 'Une erreur est survenue'));
                }
            })
            .catch(error => {
                console.error('Erreur:', error);
                showTooltip('Erreur lors de la modification des favoris');
            });
        }

        function showTooltip(message) {
            const tooltip = document.getElementById('tooltip');
            tooltip.textContent = message;
            tooltip.style.display = 'block';
            setTimeout(() => {
                tooltip.style.display = 'none';
            }, 3000);
        }
    </script>

    <script src="./js/index.js"></script>

</body>
</html>
