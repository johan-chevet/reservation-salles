<div class="content-wrapper">
    <div class="details-container">
        <h1>Reservation de <?php echo htmlspecialchars($reservation->user->login) ?></h1>
        <h2><?php echo htmlspecialchars($reservation->title) ?></h2>
        <p>Jour de réservation: <?= $reservation->start->format('d F Y') ?></p>
        <p>Heure de début: <?= $reservation->start->format('G') ?>h</p>
        <p>Heure de fin: <?= $reservation->end->format('G') ?>h</p>
        <p>Description: <?php echo htmlspecialchars($reservation->description) ?></p>
    </div>
</div>