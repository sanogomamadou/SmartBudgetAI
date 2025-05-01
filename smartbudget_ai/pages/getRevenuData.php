<?php
session_start(); // Démarre la session

header('Content-Type: application/json');

// Vérifie si l'utilisateur est connecté
if (!isset($_SESSION['user_id'])) {
    echo json_encode(["error" => "Utilisateur non connecté"]);
    exit;
}

// Connexion à la base de données
$host = "localhost";
$user = "root";
$password = "";
$dbname = "budget_ai";

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $user, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Récupérer l'ID de l'utilisateur connecté depuis la session
    $user_id = $_SESSION['user_id'];

    // Requête pour les revenus groupés par catégorie pour cet utilisateur
    $query = "SELECT categorie, SUM(montant) AS total_revenu 
              FROM transactions
              WHERE type = 'Revenu' AND user_id = :user_id
              GROUP BY categorie";

    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
    $stmt->execute();

    $revenus = [];
    $totaux = [];

    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $revenus[] = $row['categorie'];       // Catégories des revenus
        $totaux[] = (float) $row['total_revenu']; // Totaux des revenus
    }

    // Retourner les résultats sous forme JSON
    echo json_encode([
        "categories" => $revenus,
        "totaux" => $totaux
    ]);
} catch (PDOException $e) {
    echo json_encode(["error" => $e->getMessage()]);
}
?>
