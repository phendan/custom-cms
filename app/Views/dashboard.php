<h1>Dashboard</h1>

Hallo, <?=$user->getFirstName()?>

<h2>Your Profile Details</h2>
<dl>
    <dt>Email</dt>
    <dd><?=$user->getEmail()?></dd>

    <dt>First Name</dt>
    <dd><?=$user->getFirstName()?></dd>

    <dt>Last Name</dt>
    <dd><?=$user->getLastName()?></dd>
</dl>

<div>
    <h2>Your Posts</h2>
    <?php $csrfToken = $csrf::token(); ?>
    <?php foreach ($user->getPosts() as $post): ?>
        <div>
            <a href="/post/<?php echo $post->getId(); ?>/<?php echo $post->getSlug(); ?>"><?php echo $post->getTitle(); ?></a>
            <a href="/post/delete/<?php echo $post->getId(); ?>?csrfToken=<?php echo $csrfToken; ?>">Delete</a>
            <a href="/post/edit/<?php echo $post->getId(); ?>">Edit</a>
        </div>
    <?php endforeach; ?>
</div>
