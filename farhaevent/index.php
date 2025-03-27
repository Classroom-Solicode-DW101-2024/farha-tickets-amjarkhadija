<?php
require 'db.php';
session_start();

$search = isset($_POST['search']) ? $_POST['search'] : "";

$query = "SELECT edition.image, edition.timeEvent, edition.NumSalle, edition.dateEvent,
          evenement.eventTitle, evenement.eventId
          FROM edition
          JOIN evenement ON edition.eventId = evenement.eventId";

if (!empty($search)) {
    $query .= " WHERE evenement.eventTitle  , evenement.eventTitle LIKE :search";
}

$query .= " ORDER BY edition.dateEvent ASC";

$stmt = $pdo->prepare($query);

$params = !empty($search) ? [':search' => "%$search%"] : [];
$stmt->execute($params);

$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>FarhaEvents</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<nav>
    <ul>
        <li><a href="index.php">Accueil</a></li>
        <li><a href="login.php">login</a></li>
        <li><a href="register.php">Register</a></li>
    </ul>
</nav>

<div class="content">
    <h1>Bienvenue sur Farha</h1>
    <p>Bienvenue à notre site ! Découvrez nos événements à venir.</p>
</div>

<form method="POST" class="search-form">
    <input type="text" name="search" placeholder="Rechercher un événement..." value="<?= htmlspecialchars($search) ?>" class="search-input">
    <button type="submit" class="search-button">Rechercher</button>
</form>

<div style="display: flex; flex-wrap: wrap; justify-content: center;">
    <?php if (count($rows) > 0): ?>
        <?php foreach ($rows as $row): ?>
            <div class="card">
                <img src="<?= htmlspecialchars($row['image']) ?>" alt="Image de l'événement">
                <p><strong>Titre de l'événement:</strong> <?= htmlspecialchars($row['eventTitle']) ?></p>
                <p><strong>Date:</strong> <?= htmlspecialchars($row['dateEvent']) ?></p>
                <p><strong>Heure:</strong> <?= htmlspecialchars($row['timeEvent']) ?></p>
                <p><strong>Salle:</strong> <?= htmlspecialchars($row['NumSalle']) ?></p>

                <form action="detail.php" method="get">
                    <input type="hidden" name="eventId" value="<?= $row['eventId'] ?>">
                    <button type="submit" class="buy-button">J'achète</button>
                </form>
            </div>
        <?php endforeach; ?>
    <?php else: ?>
        <p class="no-events">Aucun événement trouvé.</p>
    <?php endif; ?>
</div>

</body>
</html>
