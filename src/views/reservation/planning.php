<table>
    <thead>
        <tr>
            <th></th>
            <?php foreach ($days as $day): ?>
                <th><?= $day->format('l d M Y') ?></th>
            <?php endforeach; ?>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($slots as $slot): ?>
            <tr>
                <th><?= $slot . 'h' ?></th>
                <?php foreach ($days as $day): ?>
                    <td>
                        <?php
                        // var_dump(substr($slot, 0, 1));
                        // var_dump($day);
                        //TODO fix
                        if (isset($reservations[$day->format('D')][(int)substr($slot, 0, 1)])) {
                            echo 'yyyyyyyyyyay';
                            var_dump($day);
                        }
                        ?>
                    </td>
                <?php endforeach; ?>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>