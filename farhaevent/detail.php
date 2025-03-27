<?php
require 'db.php';
session_start();

if (!isset($_GET['eventId'])) {
    echo "Aucun événement sélectionné.";
    exit;
}

$eventId = $_GET['eventId'];

$query = "SELECT evenement.eventTitle, evenement.eventDescription, evenement.TariffNormal, evenement.TariffReduit, edition.image, edition.dateEvent, edition.NumSalle
          FROM edition
          JOIN evenement ON edition.eventId = evenement.eventId
          WHERE evenement.eventId = :eventId
          LIMIT 1";

$stmt = $pdo->prepare($query);
$stmt->execute(['eventId' => $eventId]);
$event = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$event) {
    echo "Événement non trouvé.";
    exit;
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Détails de l'événement</title>
    <link rel="stylesheet" href="main.css">
</head>
<body>

<div class="event-details">
    <h2><?= htmlspecialchars($event['eventTitle']) ?></h2>
    <img src="<?= htmlspecialchars($event['image']) ?>" alt="Image de l'événement">
    <p><strong>Description:</strong> <?= htmlspecialchars($event['eventDescription']) ?></p>
    <p><strong>Date:</strong> <?= htmlspecialchars($event['dateEvent']) ?></p>
    <p><strong>Salle:</strong> <?= htmlspecialchars($event['NumSalle']) ?></p>
    <p><strong>Standard Ticket:</strong> <?= htmlspecialchars($event['TariffNormal']) ?> DH</p>
<p><strong>Discounted Ticket:</strong> <?= htmlspecialchars($event['TariffReduit']) ?> DH</p>


    <form method="POST" action="purchase.php">
        <input type="hidden" name="eventId" value="<?= $eventId ?>">
        <p><strong>Number of Standard Tickets:</strong> <input type="number" name="qteNormal" min="0" required></p>
        <p><strong>Number of Discounted Tickets:</strong> <input type="number" name="qteReduit" min="0" required></p>
        <button type="submit">Valider</button>
    </form>

    <?php if (!isset($_SESSION["utilisateur"])): ?>
        <p class="error">Veuillez vous <a href="login.php">connecter</a> pour acheter vos billets.</p>
    <?php endif; ?>
</div>

</body>
</html>
