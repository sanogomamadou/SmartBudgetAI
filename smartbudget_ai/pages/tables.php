 
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
  <!-- Nepcha Analytics (nepcha.com) -->
  <!-- Nepcha is a easy-to-use web analytics. No cookies and fully compliant with GDPR, CCPA and PECR. -->
  <script defer data-site="YOUR_DOMAIN_HERE" src="https://api.nepcha.com/js/nepcha-analytics.js"></script>
</head>

<body class="g-sidenav-show  bg-gray-100">
  <?php
    session_start(); // Démarrer la session
     // Vérifier si l'utilisateur est connecté 
     if (!isset($_SESSION['user_id'])) { header("Location: sign-in.php"); exit; } 
     // Récupérer l'ID de l'utilisateur 
     $user_id = $_SESSION['user_id'];
// Connection à la base de données
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
$stmt = $pdo->prepare("SELECT * FROM transactions WHERE user_id = ? ORDER BY date DESC");
 $stmt->execute([$user_id]); 
 $transactions = $stmt->fetchAll(PDO::FETCH_ASSOC);


// Récupérer les transactions
$stmt = $pdo->prepare("SELECT * FROM transactions WHERE user_id = ? ORDER BY date DESC"); $stmt->execute([$user_id]); $transactions = $stmt->fetchAll(PDO::FETCH_ASSOC);


// Récupérer le mois et l'année en cours
$currentMonth = date('m'); // Format '05' pour mai
$currentYear = date('Y');  // Format '2025'

// Requête pour récupérer les budgets du mois en cours
$stmt = $pdo->prepare("SELECT category, amount FROM budgets 
                      WHERE MONTH(created_at) = ? 
                      AND YEAR(created_at) = ?
                      AND user_id = ?");
$stmt->execute([$currentMonth, $currentYear, $user_id]);
$budgets = $stmt->fetchAll(PDO::FETCH_ASSOC);
/////////////////////////fin/////////////////////////////////////



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
          <a class="nav-link  active" href="../pages/tables.php">
            <div class="icon icon-shape icon-sm shadow border-radius-md bg-white text-center me-2 d-flex align-items-center justify-content-center">
            <svg width="16px" height="16px" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                <title>office</title>
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
          <a class="nav-link  " href="../pages/aiadvice.php">
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
            <li class="breadcrumb-item text-sm text-dark active" aria-current="page">Transactions - Budget</li>
          </ol>
          <h6 class="font-weight-bolder mb-0">Transactions & Budget Tables</h6>
        </nav>
         
      </div>
    </nav>
    <!-- End Navbar -->
    <div class="container-fluid py-4">
      <div class="row">
        <div class="col-12">
        <div class="card mb-4">
            <div class="card-header pb-0">
              <h6>Budget table</h6>
            </div>
            <div class="card-body px-0 pt-0 pb-2">
              <div class="table-responsive p-0">
                <table class="table align-items-center mb-0">
                  <thead>
                    <tr>
                      <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Category</th>
                      <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">amount</th>                      
                    </tr>
                  </thead>
                  <tbody>

                  <?php foreach ($budgets as $budget): ?>
                    <tr>
                      <td>
                        <div class="d-flex px-2 py-1">
                          <div class="d-flex flex-column justify-content-center">
                            <h6 class="mb-0 text-sm"><?= htmlspecialchars($budget['category']) ?></h6>
                            
                          </div>
                        </div>
                      </td>
                      <td>
                        <p class="text-xs font-weight-bold mb-0"><?= htmlspecialchars($budget['amount']) ?></p>
                        
                      </td>
                    
                       
                    </tr>
                    <?php endforeach; ?>
   
                  </tbody>
                </table>
              </div>
            </div>
          </div>

          <div class="card mb-4">
            <div class="card-header pb-0">
              <h6>Transactions table</h6>
            </div>
            <div class="card-body px-0 pt-0 pb-2">
              <div class="table-responsive p-0">
                <table class="table align-items-center mb-0">
                  <thead>
                    <tr>
                      <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Type</th>
                      <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Category</th>
                      <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">amount</th>
                      <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">date</th>
                      
                    </tr>
                  </thead>
                  <tbody>

                  <?php foreach ($transactions as $transaction): ?>
                    <tr>
                      <td>
                        <div class="d-flex px-2 py-1">
                          <div class="d-flex flex-column justify-content-center">
                            <h6 class="mb-0 text-sm"><?= htmlspecialchars($transaction['type']) ?></h6>
                            
                          </div>
                        </div>
                      </td>
                      <td>
                        <p class="text-xs font-weight-bold mb-0"><?= htmlspecialchars($transaction['categorie']) ?></p>
                        
                      </td>
                      <td class="align-middle text-center text-sm">
                        <span class="<?= $transaction['type'] === 'Revenu' ? 'badge badge-sm bg-gradient-success' : 'badge badge-sm bg-gradient-secondary' ?> "><?= $transaction['type'] === 'Revenu' ? '+' : '-' ?>
                        <?= number_format($transaction['montant'], 2) ?> MAD</span>
                      </td>
                      <td class="align-middle text-center">
                        <span class="text-secondary text-xs font-weight-bold"><?= htmlspecialchars($transaction['date']) ?></span>
                      </td>
                       
                    </tr>
                    <?php endforeach; ?>
   
                  </tbody>
                </table>
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
</body>

</html>