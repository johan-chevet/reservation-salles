<div class="table-wrapper">
    <table>
        <thead>
            <tr>
                <th></th>
                <?php foreach ($days as $key => $day): ?>
                    <th><?= $day->format('l d M Y') ?></th>
                <?php endforeach; ?>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($slots as $hour => $label): ?>
                <tr>
                    <th><?= $label ?></th>
                    <?php foreach ($days as $key => $day): ?>
                        <?php $reservation = $planning[$key][$hour]; ?>
                        <?php if ($reservation): ?>
                            <td class="reserved">
                                <div class="slot-title"><?= htmlspecialchars($reservation->title) ?></div>
                                <div class="slot-user"><?= "Réserver par " .  htmlspecialchars($reservation->user->login) ?></div>
                            </td>
                        <?php elseif ($key === 'Sat' || $key === 'Sun'): ?>
                            <td class="week-end-slot"></td>
                        <?php else: ?>
                            <td></td>
                        <?php endif ?>
                    <?php endforeach; ?>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>