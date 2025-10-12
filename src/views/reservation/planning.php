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
                            <div><?= htmlspecialchars($reservation->title) ?></div>
                        </td>
                    <?php else: ?>
                        <td></td>
                    <?php endif ?>
                <?php endforeach; ?>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>