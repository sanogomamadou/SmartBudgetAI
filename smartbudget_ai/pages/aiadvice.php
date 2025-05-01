 
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <link rel="apple-touch-icon" sizes="76x76" href="../assets/img/apple-icon.png">
  <link rel="icon" type="image/png" href="../assets/img/favicon.png">
  <title>
    Smart Budget AI
  </title>
  <!--     Fonts and icons     -->
  <link href="https://fonts.googleapis.com/css?family=Inter:300,400,500,600,700,800" rel="stylesheet" />
  <!-- Nucleo Icons -->
  <link href="https://demos.creative-tim.com/soft-ui-dashboard/assets/css/nucleo-icons.css" rel="stylesheet" />
  <link href="https://demos.creative-tim.com/soft-ui-dashboard/assets/css/nucleo-svg.css" rel="stylesheet" />
  <!-- Font Awesome Icons -->
  <script src="https://kit.fontawesome.com/42d5adcbca.js" crossorigin="anonymous"></script>
  <!-- CSS Files -->
  <link id="pagestyle" href="../assets/css/soft-ui-dashboard.css?v=1.1.0" rel="stylesheet" />
</head>

<body class="g-sidenav-show  bg-gray-100">
<?php
session_start(); // Démarrer la session

// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION['user_id'])) {
    header("Location: sign-in.php"); // Redirige l'utilisateur vers la page de connexion
    exit;
}

// Récupérer l'ID de l'utilisateur
$user_id = $_SESSION['user_id'];

// Connexion à la base de données
$host = 'localhost';
$dbname = 'budget_ai';
$username = 'root';
$password = ''; // Changez selon votre configuration

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Erreur de connexion : " . $e->getMessage());
}

// Utiliser l'ID de l'utilisateur pour récupérer les données spécifiques
$stmt = $pdo->prepare("SELECT id, nom, email, mdp FROM users WHERE id = ?");
$stmt->execute([$user_id]);

// Récupérer les données de l'utilisateur
$user = $stmt->fetch(PDO::FETCH_ASSOC); // Renvoie un tableau associatif



?>

  <aside class="sidenav navbar navbar-vertical navbar-expand-xs border-0 border-radius-xl my-3 fixed-start ms-3 " id="sidenav-main">
    <div class="sidenav-header">
      <i class="fas fa-times p-3 cursor-pointer text-secondary opacity-5 position-absolute end-0 top-0 d-none d-xl-none" aria-hidden="true" id="iconSidenav"></i>
      <a class="navbar-brand m-0" href=" https://demos.creative-tim.com/soft-ui-dashboard/pages/dashboard.php " target="_blank">
        <img src="../assets/img/logo-ct-dark.png" class="navbar-brand-img h-100" alt="main_logo">
        <span class="ms-1 font-weight-bold">Smart Budget AI</span>
      </a>
    </div>
    <hr class="horizontal dark mt-0">
    <div class="collapse navbar-collapse  w-auto " id="sidenav-collapse-main">
      <ul class="navbar-nav">
        <li class="nav-item">
          <a class="nav-link  " href="../pages/dashboard.php">
            <div class="icon icon-shape icon-sm shadow border-radius-md bg-white text-center me-2 d-flex align-items-center justify-content-center">
            <svg width="16px" height="16px" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
            <title>shop </title>
                <!-- Cadre principal du dashboard -->
                <rect x="3" y="3" width="18" height="18" rx="1" stroke="#000000" stroke-width="1.5" fill="white"/>
                
                <!-- Grille du dashboard (4 sections) -->
                <path d="M12 3V21" stroke="#000000" stroke-width="1.2" stroke-linecap="round"/>
                <path d="M3 12H21" stroke="#000000" stroke-width="1.2" stroke-linecap="round"/>
                
                <!-- Indicateurs dans chaque quadrant -->
                <!-- Secteur 1 (en haut à gauche) - Graphique linéaire -->
                <path d="M5 8L7 6L9 9L11 7" stroke="#000000" stroke-width="1.5" stroke-linecap="round" fill="none"/>
                
                <!-- Secteur 2 (en haut à droite) - Barres -->
                <rect x="14" y="7" width="2" height="4" fill="#000000"/>
                <rect x="17" y="5" width="2" height="6" fill="#000000"/>
                
                <!-- Secteur 3 (en bas à gauche) - Camembert -->
                <circle cx="8" cy="16" r="3" stroke="#000000" stroke-width="1.5" fill="none"/>
                <path d="M8 16L8 13" stroke="#000000" stroke-width="1.5" stroke-linecap="round"/>
                
                <!-- Secteur 4 (en bas à droite) - Points de données -->
                <circle cx="16" cy="16" r="0.8" fill="#000000"/>
                <circle cx="18" cy="14" r="0.8" fill="#000000"/>
                <circle cx="15" cy="15" r="0.8" fill="#000000"/>
                <circle cx="17" cy="17" r="0.8" fill="#000000"/>
              </svg>
            </div>
            <span class="nav-link-text ms-1">Dashboard</span>
          </a>
        </li>
        
        <li class="nav-item">
          <a class="nav-link  " href="../pages/tables.php">
            <div class="icon icon-shape icon-sm shadow border-radius-md bg-white text-center me-2 d-flex align-items-center justify-content-center">
            <svg width="16px" height="16px" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                <title>office</title>
                <!-- Fond de tableau (style feuille avec lignes) -->
                <rect x="3" y="5" width="18" height="16" rx="1" stroke="#000000" stroke-width="1.5" fill="white"/>
                <!-- Lignes horizontales (simulant des entrées de tableau) -->
                <path d="M3 9H21" stroke="#000000" stroke-width="1.2" stroke-linecap="round"/>
                <path d="M3 13H21" stroke="#000000" stroke-width="1.2" stroke-linecap="round"/>
                <path d="M3 17H21" stroke="#000000" stroke-width="1.2" stroke-linecap="round"/>
                <!-- Icône de calendrier ou horloge (optionnelle) -->
                <rect x="7" y="2" width="3" height="3" rx="0.5" stroke="#000000" stroke-width="1" fill="white"/>
                <rect x="14" y="2" width="3" height="3" rx="0.5" stroke="#000000" stroke-width="1" fill="white"/>
                <!-- Texte "Historique" simplifié (optionnel) -->
                <path d="M6 6H8" stroke="#000000" stroke-width="1.5" stroke-linecap="round"/>
                <path d="M16 6H18" stroke="#000000" stroke-width="1.5" stroke-linecap="round"/>
              </svg>
            </div>
            <span class="nav-link-text ms-1">Transactions / Budget</span>
          </a>
        </li>


        <li class="nav-item">
          <a class="nav-link  active" href="../pages/aiadvice.php">
            <div class="icon icon-shape icon-sm shadow border-radius-md bg-white text-center me-2 d-flex align-items-center justify-content-center">
            <svg width="18px" height="18px" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
            <title>credit-card</title>
                <!-- Tête du robot (agrandie) -->
                <rect x="3" y="3" width="18" height="18" rx="3" stroke="#000000" stroke-width="1.5" fill="white"/>
                <!-- Yeux -->
                <circle cx="9" cy="10" r="1.5" fill="#000000"/>
                <circle cx="15" cy="10" r="1.5" fill="#000000"/>
                <!-- Bouche souriante (courbe) -->
                <path d="M8 15C8 15 10 17 12 17C14 17 16 15 16 15" stroke="#000000" stroke-width="1.5" stroke-linecap="round" fill="none"/>
                <!-- Antenne optionnelle -->
                <path d="M12 3V1" stroke="#000000" stroke-width="1.5" stroke-linecap="round"/>
                <circle cx="12" cy="1" r="1" fill="#000000"/>
              </svg>
            </div>
            <span class="nav-link-text ms-1">AI Advice</span>
          </a>
        </li>
      
        <li class="nav-item mt-3">
          <h6 class="ps-4 ms-2 text-uppercase text-xs font-weight-bolder opacity-6">Account pages</h6>
        </li>
        <li class="nav-item">
          <a class="nav-link  " href="../pages/profile.php">
            <div class="icon icon-shape icon-sm shadow border-radius-md bg-white text-center me-2 d-flex align-items-center justify-content-center">
            <svg width="16px" height="16px" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
            <title>customer-support</title>
                <!-- Cercle de la tête -->
                <circle cx="12" cy="8" r="4" stroke="#000000" stroke-width="1.5" fill="white"/>
                <!-- Corps -->
                <path d="M5 20V19C5 16.7909 6.79086 15 9 15H15C17.2091 15 19 16.7909 19 19V20" stroke="#000000" stroke-width="1.5" stroke-linecap="round" fill="none"/>
                <!--  Silhouette complète alternative -->
                <circle cx="12" cy="8" r="4" stroke="#000000" stroke-width="1.5" fill="white"/>
                <path d="M5 20V19C5 15.6863 7.68629 13 11 13H13C16.3137 13 19 15.6863 19 19V20" stroke="#000000" stroke-width="1.5" stroke-linecap="round" fill="none"/>
                
              </svg>
            </div>
            <span class="nav-link-text ms-1">Profile</span>
          </a>
        </li>
         
         

      </ul>
    </div>
    <div class="sidenav-footer mx-3 ">
      <div class="card card-background shadow-none card-background-mask-secondary" id="sidenavCard">
        <div class="full-background" style="background-image: url('../assets/img/curved-images/white-curved.jpg')"></div>
        <div class="card-body text-start p-3 w-100">
          <div class="icon icon-shape icon-sm bg-white shadow text-center mb-3 d-flex align-items-center justify-content-center border-radius-md">
          <svg width="14" height="14" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path d="M4 4H20C21.1 4 22 4.9 22 6V18C22 19.1 21.1 20 20 20H4C2.9 20 2 19.1 2 18V6C2 4.9 2.9 4 4 4Z" stroke="currentColor" stroke-width="2"/>
            <path d="M22 6L12 13L2 6" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
          </svg>
          </div>
          <div class="docs-info">
            <h6 class="text-white up mb-0">Need help?</h6>
            <p class="text-xs font-weight-bold">Send us an email</p>
            <a href="mailto:mamadou.sanogo@uir.ac.ma" target="_blank" class="btn btn-white btn-sm w-100 mb-0">Mail</a>
          </div>
        </div>
      </div>
      <a class="btn btn-primary mt-3 w-100" href="logout.php">Log Out</a>

    </div> 
  </aside>
  <main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg ">
    <!-- Navbar -->
    <nav class="navbar navbar-main navbar-expand-lg px-0 mx-4 shadow-none border-radius-xl" id="navbarBlur" navbar-scroll="true">
      <div class="container-fluid py-1 px-3">
        <nav aria-label="breadcrumb">
          <ol class="breadcrumb bg-transparent mb-0 pb-0 pt-1 px-0 me-sm-6 me-5">
            <li class="breadcrumb-item text-sm"><a class="opacity-5 text-dark" href="javascript:;">Pages</a></li>
            <li class="breadcrumb-item text-sm text-dark active" aria-current="page">AI Advice</li>
          </ol>
          <h6 class="font-weight-bolder mb-0">AI Advice</h6>
        </nav>
         
      </div>
    </nav>
    <!-- End Navbar -->
    <div class="container-fluid py-4">
      <div class="row">
        <div class="col-lg-8">
          <div class="row">
            <div class="col-xl-6 mb-xl-0 mb-4">
              <div class="card bg-transparent shadow-xl">
                <div class="overflow-hidden position-relative border-radius-xl" style="background-image: url('../assets/img/curved-images/curved14.jpg');">
                  <span class="mask bg-gradient-dark"></span>
                  <div class="card-body position-relative z-index-1 p-3">
                    <i class="fas fa-wifi text-white p-2"></i>
                    <h5 class="text-white mt-4 mb-5 pb-2"> <?php
                            // Générer un nombre aléatoire à 4 chiffres
                            $random_number = rand(1000, 9999);

                            
                            echo $random_number;
                            ?>
                            &nbsp;&nbsp;&nbsp;
                            1122&nbsp;&nbsp;&nbsp;4594&nbsp;&nbsp;&nbsp;7852</h5>
                    <div class="d-flex">
                      <div class="d-flex">
                        <div class="me-4">
                          <p class="text-white text-sm opacity-8 mb-0">Card Holder</p>
                          <h6 class="text-white mb-0"><?= htmlspecialchars($user['nom']) ?> </h6>
                        </div>
                        <div>
                          <p class="text-white text-sm opacity-8 mb-0">Expires</p>
                          <h6 class="text-white mb-0">11/26</h6>
                        </div>
                      </div>
                      <div class="ms-auto w-20 d-flex align-items-end justify-content-end">
                        <img class="w-60 mt-2" src="../assets/img/logos/mastercard.png" alt="logo">
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="col-xl-6">
              <div class="row">
                <div class="col-md-6">
                  <div class="card">
                    <div class="card-header mx-4 p-3 text-center">
                      
                    </div>
                    <div class="card-body pt-0 p-3 text-center">
                      <h6 class="text-center mb-0">Saving</h6>
                      <span class="text-xs">Help to save money</span>
                      <hr class="horizontal dark my-3">
                      <h5 class="mb-0"> </h5>
                    </div>
                  </div>
                </div>
                <div class="col-md-6 mt-md-0 mt-4">
                  <div class="card">
                    <div class="card-header mx-4 p-3 text-center">
                      
                    </div>
                    <div class="card-body pt-0 p-3 text-center">
                      <h6 class="text-center mb-0">Prevent</h6>
                      <span class="text-xs">Avoid running out of money</span>
                      <hr class="horizontal dark my-3">
                      <h5 class="mb-0"> </h5>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="col-md-12 mb-lg-0 mb-4">
              <div class="card mt-4">
                <div class="card-header pb-0 p-3">
                  <div class="row">
                    <div class="col-6 d-flex align-items-center">
                      <h6 class="mb-0">Get personalized advice</h6>
                    </div>
                    <div class="col-6 text-end">
                      <button class="btn bg-gradient-dark mb-0" onclick="sendMessage()">Send</button>
                    </div>
                  </div>
                </div>
                <div class="card-body p-3">
                  <div class="row">
                    <input class="card card-body border card-plain border-radius-lg d-flex align-items-center flex-row" type="text" id="user-input" placeholder="Type your need here..." style="width: 70%; max-width: 600px; padding: 10px; box-sizing: border-box;">                        
                    
                  </div>
                </div>
                <div class="card mt-4" id="response-container" style="display: none;">
                    <div class="card-header pb-0 p-3 d-flex justify-content-between align-items-center">
                        <h6 class="mb-0">Smart Budget AI Response</h6>
                        <button class="btn btn-sm btn-outline-secondary copy-response" title="Copier" style="font-family: Arial, sans-serif;">
                            <i class="fas fa-copy">Copy the answer</i>
                        </button>
                    </div>
                    <div class="card-body p-3">
                        <div class="row">
                            <div class="card card-body border-0 shadow-xs p-4" 
                                id="ai-response" 
                                style="background: #f8fafc; min-height: 150px; border-radius: 12px;">
                                <!-- La réponse apparaîtra ici -->
                            </div>
                        </div>
                        <div class="mt-3 text-end small">
                            <span class="badge bg-light text-dark">
                                <i class="fas fa-info-circle me-1"></i>
                                Smart Budget AI gives you advice, please adapt it to your situation.
                            </span>
                        </div>
                    </div>
                </div>
              </div>
            </div>
          </div>
        </div>
        
      </div>
      
      <footer class="footer pt-3  ">
        <div class="container-fluid">
          <div class="row align-items-center justify-content-lg-between">
            <div class="col-lg-6 mb-lg-0 mb-4">
              <div class="copyright text-center text-sm text-muted text-lg-start">
                © <script>
                  document.write(new Date().getFullYear())
                </script>
                made  by
                <a href="mailto:mamadou.sanogo@uir.ac.ma" class="font-weight-bold" target="_blank">DeepRoots AI</a>
              </div>
            </div>
            
          </div>
        </div>
      </footer>
    </div>
  </main>
   
  <!--   Core JS Files   -->
  <script src="../assets/js/core/popper.min.js"></script>
  <script src="../assets/js/core/bootstrap.min.js"></script>
  <script src="../assets/js/plugins/perfect-scrollbar.min.js"></script>
  <script src="../assets/js/plugins/smooth-scrollbar.min.js"></script>
  <script>
      var win = navigator.platform.indexOf('Win') > -1;
      if (win && document.querySelector('#sidenav-scrollbar')) {
        var options = {
          damping: '0.5'
        }
        Scrollbar.init(document.querySelector('#sidenav-scrollbar'), options);
      }
    </script>
    <!-- Github buttons -->
    <script async defer src="https://buttons.github.io/buttons.js"></script>
    <!-- Control Center for Soft Dashboard: parallax effects, scripts for the example pages etc -->
    <script src="../assets/js/soft-ui-dashboard.min.js?v=1.1.0"></script>
    <script>
  function sendMessage() {
    const userInput = document.getElementById('user-input').value.trim();
    const responseContainer = document.getElementById('response-container');
    const aiResponseElement = document.getElementById('ai-response');
    const userId = <?php echo json_encode($user_id); ?>;  // Passage du user_id à JS
    const inputForBackend = `${userId}|${userInput}`;

    
    // Reset et validation
    aiResponseElement.innerHTML = '';
    responseContainer.style.display = 'block';
    
    if (!userInput) {
        aiResponseElement.innerHTML = `
            <div class="alert alert-warning alert-dismissible fade show">
                <i class="fas fa-exclamation-triangle me-2"></i>
                Veuillez saisir votre question
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>`;
        return;
    }
    
    // Animation de chargement
    aiResponseElement.innerHTML = `
        <div class="text-center py-4">
            <div class="spinner-border text-primary" style="width: 3rem; height: 3rem;" role="status">
                <span class="visually-hidden">Chargement...</span>
            </div>
            <p class="mt-3">L'AI analyse votre demande...</p>
        </div>`;
    
    // Envoi de la requête
    fetch('http://localhost:8000/ask-agent', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-Requested-With': 'XMLHttpRequest'
        },
        body: JSON.stringify({ 
            query: inputForBackend,
            user_id: userId
        })
    })

    .then(async response => {
        if (!response.ok) {
            const errorData = await response.json();
            throw new Error(errorData.error || `Erreur HTTP: ${response.status}`);
        }
        return response.json();
    })
    .then(data => {
        if (data.error) {
            throw new Error(data.error);
        }
        
        // Formatage de la réponse
        const formattedResponse = data.response
            .replace(/\n/g, '<br>')
            .replace(/\*\*(.*?)\*\*/g, '<strong>$1</strong>')
            .replace(/\*(.*?)\*/g, '<em>$1</em>');
        
        // Affichage
        aiResponseElement.innerHTML = `
            <div class="ai-response-content">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h6 class="mb-0"></h6>
                    <span class="badge bg-primary">
                        <i class="fas fa-clock me-1"></i>
                        ${new Date().toLocaleTimeString()}
                    </span>
                </div>
                <div class="response-text">
                    ${formattedResponse}
                </div>
                <div class="mt-3 text-end small text-muted">
                    <i class="fas fa-robot"></i> Powered by Smart Budget AI
                </div>
            </div>`;
    })
    .catch(error => {
        console.error('Error:', error);
        aiResponseElement.innerHTML = `
            <div class="alert alert-danger alert-dismissible fade show">
                <i class="fas fa-exclamation-circle me-2"></i>
                <strong>Erreur :</strong> ${error.message}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>`;
    });
}

// Gestion de la touche Entrée
document.getElementById('user-input').addEventListener('keypress', function(e) {
    if (e.key === 'Enter') {
        e.preventDefault();
        sendMessage();
    }
});

// Optionnel : Bouton de copie de la réponse
document.addEventListener('DOMContentLoaded', function() {
    const responseContainer = document.getElementById('response-container');
    responseContainer.addEventListener('click', function(e) {
        if (e.target.classList.contains('copy-response')) {
            const responseText = document.getElementById('ai-response').innerText;
            navigator.clipboard.writeText(responseText);
            alert('Réponse copiée !');
        }
    });
});
  </script>
</body>

</html>