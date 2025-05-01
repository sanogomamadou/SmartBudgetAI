<?php
session_start(); // Démarrer la session

// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION['user_id'])) {
    echo json_encode(['error' => 'Utilisateur non authentifié']);
    exit;
}

// Configuration de la base de données
$host = 'localhost';
$dbname = 'budget_ai';
$user = 'root';
$password = '';

header('Content-Type: application/json');

try {
    // Connexion à la base de données
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $user, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // ID de l'utilisateur à partir de la session
    $user_id = $_SESSION['user_id'];

    // Requête SQL filtrant par user_id pour l'utilisateur connecté
    $sql = "SELECT 
                MONTH(date) AS mois, 
                SUM(montant) AS total_depense
            FROM transactions
            WHERE type = 'Dépense' AND user_id = :user_id
            GROUP BY MONTH(date)
            ORDER BY MONTH(date)";
    
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
    $stmt->execute();

    // Préparation des données pour Chart.js
    $mois_labels = ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"];
    $data = array_fill(0, 12, 0); // Initialiser un tableau de 12 mois à 0

    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $index = $row['mois'] - 1; // Index du mois (décalage de 1)
        $data[$index] = $row['total_depense'];
    }

        // Requête SQL filtrant par user_id pour l'utilisateur connecté
        $sql = "SELECT 
        MONTH(date) AS mois, 
        SUM(montant) AS total_revenu
            FROM transactions
            WHERE type = 'Revenu' AND user_id = :user_id
            GROUP BY MONTH(date)
            ORDER BY MONTH(date)";

        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
        $stmt->execute();

        // Préparation des données pour Chart.js
        $rev = array_fill(0, 12, 0); // Initialiser un tableau de 12 mois à 0

        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $index = $row['mois'] - 1; // Index du mois (décalage de 1)
        $rev[$index] = $row['total_revenu'];
        }

        
            

    // Retourner les données en JSON
    echo json_encode(['labels' => $mois_labels, 'data' => $data, 'revenu' => $rev]);

} catch (PDOException $e) {
    echo json_encode(['error' => $e->getMessage()]);
    exit;
}
?>
