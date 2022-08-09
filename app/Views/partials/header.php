<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Custom CMS</title>
        <link rel="stylesheet" href="./styles/app.css">
        <script defer type="module" src="/js/main.js"></script>
        <?php foreach ($scripts ?? [] as $script): ?>
            <script type="module" defer src="/js/<?=$script?>.js"></script>
        <?php endforeach; ?>
    </head>
    <body>
        <header>
            <nav>
                <ul>
                    <li><a href="/">Home</a></li>
                    <li><a href="/about">About</a></li>
                    <?php if (!$user->isLoggedIn()): ?>
                        <li><a href="/register">Register</a></li>
                        <li><a href="/login">Login</a></li>
                    <?php else: ?>
                        <li><a href="/post/create">Create Post</a></li>
                        <li><a href="/logout">Sign Out</a></li>
                    <?php endif; ?>
                </ul>
            </nav>
        </header>

        <div class="messages">
            <?php echo $session::flash('message'); ?>
        </div>
