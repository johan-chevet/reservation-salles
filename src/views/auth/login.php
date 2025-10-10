<form method="post" class="form">
    <div class="form-title">Se connecter</div>
    <div class="form-row">
        <div class="form-col">
            <label for="login">Login</label>
            <input class="form-input" type="text" name="login" id="login" required value=<?= $form['login'] ?? '' ?>>
            <p class="form-error"><?= $errors["login"] ?? '' ?></p>
        </div>
    </div>
    <div class="form-row">
        <div class="form-col">
            <label for="password">Password</label>
            <input class="form-input" type="password" name="password" id="password" required value=<?= $form['password'] ?? '' ?>>
            <p class="form-error"><?= $errors["password"] ?? '' ?></p>
        </div>
    </div>
    <input class="btn" type="submit" name="submit-register" value="Create account" />
</form>