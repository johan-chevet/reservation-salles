<form method="post" class="form">
    <div class="form-title">Reservation</div>
    <div class="form-row">
        <div class="form-col">
            <label for="title">Titre</label>
            <input class="form-input" type="text" name="title" id="title" required value=<?= $form['title'] ?? '' ?>>
            <p class="form-error"><?= $errors["title"] ?? '' ?></p>
        </div>
    </div>
    <div class="form-row">
        <div class="form-col">
            <label for="date">Date</label>
            <input class="form-input" type="date" name="date" id="date" required value=<?= $form['date'] ?? '' ?>>
            <p class="form-error"><?= $errors["date"] ?? '' ?></p>
        </div>
    </div>
    <div class="form-row">
        <div class="form-col">
            <label for="start">Heure de début</label>
            <select class="form-input" name="start" id="start">
                <option value="9">9H</option>
                <option value="10">10H</option>
            </select>
            <p class="form-error"><?= $errors["start"] ?? '' ?></p>
        </div>
    </div>
    <div class="form-row">
        <div class="form-col">
            <label for="end">Heure de fin</label>
            <select class="form-input" name="end" id="end">
                <option value="9">9H</option>
                <option value="10">10H</option>
            </select>
            <p class="form-error"><?= $errors["end"] ?? '' ?></p>
        </div>
    </div>
    <div class="form-row">
        <div class="form-col">
            <label for="description">description</label>
            <textarea class="form-textarea" type="text" name="description" id="description" value=<?= $form['description'] ?? '' ?>></textarea>
            <p class="form-error"><?= $errors["description"] ?? '' ?></p>
        </div>
    </div>
    <input class="btn" type="submit" name="submit-register" value="Réserver" />
</form>