<h1>Edit Post</h1>

<form method="post">
    <?php if (isset($errors['root'])): ?>
        <div class="error"><?=$errors['root']?></div>
    <?php endif; ?>

    <input type="hidden" name="csrfToken" value="<?php echo $csrf::token() ?>">

    <div>
        <label for="title">Title</label>

        <?php if (isset($errors['title'])): ?>
            <div class="error"><?=$errors['title'][0]?></div>
        <?php endif; ?>

        <input type="text" id="title" name="title" value="<?php echo $post->getTitle(); ?>">
    </div>

    <div>
        <label for="body">Post Body</label>

        <?php if (isset($errors['body'])): ?>
            <div class="error"><?=$errors['body'][0]?></div>
        <?php endif; ?>

        <textarea name="body" id="body">
            <?php echo $post->getBody(); ?>
        </textarea>
    </div>

    <input type="submit" value="Edit Post">
</form>
