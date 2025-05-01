<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Transaction</title>
    <style>
        body {
            font-family: Arial, Helvetica, sans-serif;
            margin: 0;
            padding: 20px;
            box-sizing: border-box;
            background-color: #f9f9f9;
        }

        .container {
            max-width: 500px;
            margin: 0 auto;
            background: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2);
        }

        h2 {
            text-align: center;
            color: #ff7612;
        }

        form {
            display: flex;
            flex-direction: column;
            gap: 15px;
        }

        label {
            font-weight: bold;
        }

        input, select, button {
            padding: 10px;
            font-size: 16px;
            border: 1px solid #ddd;
            border-radius: 5px;
        }

        button {
            background-color: #ff7612;
            color: white;
            cursor: pointer;
        }

        button:hover {
            background-color: #b64200;
        }

        .back-link {
            display: block;
            text-align: center;
            margin-top: 10px;
            color: #ff7612;
            text-decoration: none;
        }

        .back-link:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <?php
    session_start();
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

    // Gestion de l'ajout de transactions
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            
            $montant = $_POST['montant'] ?? 0;
            $type = $_POST['type'] ?? '';
            $categorie = $_POST['categorie'] ?? '';
            $date = $_POST['date'] ?? date('Y-m-d');

            if ($montant && $type && $categorie) {
                $stmt = $pdo->prepare("INSERT INTO transactions (user_id, montant, type, categorie, date) VALUES (?, ?, ?, ?, ?)");
                $stmt->execute([ $user_id,$montant, $type, $categorie, $date]);
                header("Location: dashboard.php");
                exit;
            }
        }
    ?>

    <div class="container">
        <h2>Add a New Transaction</h2>
        

        <form method="POST" action="">
            
            
        
            <label for="type">Type :</label>
            <select id="type" name="type" onchange="updateCategorieOptions()" required>
                <option value="Revenu">Revenu</option>
                <option value="Dépense">Dépense</option>
            </select>
        
            <label for="categorie">Catégorie :</label>
            <select id="categorie" name="categorie" required>
                <!-- Les options seront remplies par JavaScript -->
            </select>

            <label for="montant">Montant :</label>
            <input type="number" id="montant" name="montant" step="0.01" required>
        
            <label for="date">Date :</label>
            <input type="date" id="date" name="date" required>
        
            <button type="submit">Ajouter</button>
        </form>
        
        <script>
            const categories = {
                Revenu: ["Investissement","Don","Salaire"],
                Dépense: ["Abonnement","Alimentation","Factures","Fournitures","Loyer","Santé","Soins corporels","Loisir","Transport","Vêtements","Autres"]
            };
        
            function updateCategorieOptions() {
                const typeSelect = document.getElementById("type");
                const categorieSelect = document.getElementById("categorie");
        
                // Efface les anciennes options
                categorieSelect.innerHTML = "";
        
                // Ajoute les nouvelles options basées sur le type sélectionné
                const selectedType = typeSelect.value;
                categories[selectedType].forEach(categorie => {
                    const option = document.createElement("option");
                    option.value = categorie;
                    option.textContent = categorie;
                    categorieSelect.appendChild(option);
                });
            }
        
            // Initialisation au chargement de la page
            document.addEventListener("DOMContentLoaded", updateCategorieOptions);
        </script>
        
    </div>
</body>
</html>