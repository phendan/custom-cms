<h1>Register</h1>

<form method="post" novalidate>
    <div>
        <label for="first-name">First Name</label>

        <?php if (isset($errors['firstName'])): ?>
            <div class="error"><?=$errors['firstName'][0]?></div>
        <?php endif; ?>

        <input type="text" id="first-name" name="firstName">
    </div>

    <div>
        <label for="last-name">Last Name</label>

        <?php if (isset($errors['lastName'])): ?>
            <div class="error"><?=$errors['lastName'][0]?></div>
        <?php endif; ?>

        <input type="text" id="last-name" name="lastName">
    </div>

    <div>
        <label for="email">Email</label>

        <?php if (isset($errors['email'])): ?>
            <div class="error"><?=$errors['email'][0]?></div>
        <?php endif; ?>

        <input type="email" id="email" name="email">
    </div>

    <div>
        <label for="password">Password</label>

        <?php if (isset($errors['password'])): ?>
            <div class="error"><?=$errors['password'][0]?></div>
        <?php endif; ?>

        <input type="password" id="password" name="password">
    </div>

    <div>
        <label for="password-again">Repeat Password</label>

        <?php if (isset($errors['passwordAgain'])): ?>
            <div class="error"><?=$errors['passwordAgain'][0]?></div>
        <?php endif; ?>

        <input type="password" id="password-again" name="passwordAgain">
    </div>

    <input type="submit" value="Register">
</form>
