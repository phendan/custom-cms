<h1>Login</h1>

<form method="post" novalidate>
    <?php if (isset($errors['root'])): ?>
        <div class="error"><?=$errors['root']?></div>
    <?php endif; ?>

    <input type="hidden" name="csrfToken" value="<?php echo $csrf::token() ?>">

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

    <input type="submit" value="Sign In">
</form>
