<form method="post" class="form">
    <div class="form-title">Modifier votre profil</div>
    <div class="form-row">
        <div class="form-col">
            <label for="login">Login</label>
            <input class="form-input" type="text" name="login" id="login" required value=<?= $user->login ?? '' ?>>
            <p class="form-error"><?= $errors["login"] ?? '' ?></p>
        </div>
    </div>
    <div class="form-row">
        <div class="form-col">
            <label for="password">Password</label>
            <input class="form-input" type="password" name="password" id="password" value=<?= $form['password'] ?? '' ?>>
            <p class="form-error"><?= $errors["password"] ?? '' ?></p>
        </div>
    </div>
    <div class="form-row">
        <div class="form-col">
            <label for="password-confirmation">Password confirmation</label>
            <input class="form-input" type="password" name="password-confirmation" id="password-confirmation" value=<?= $form['password-confirmation'] ?? '' ?>>
            <p class="form-error"><?= $errors["password-confirmation"] ?? '' ?></p>
        </div>
    </div>
    <input class="btn" type="submit" name="submit-register" value="Create account" />
</form>