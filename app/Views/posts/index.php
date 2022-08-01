<h1><?php echo $post->getTitle(); ?></h1>
<p><?php echo $post->getBody(); ?></p>
<div>Posted at: <?php echo $post->getCreatedAt() ?></div>
<div>Posted by: <?php echo $post->getUser()->getFullName(); ?></div>
<?php if ($user->isLoggedIn() && ($user->getId() === $post->getUserId())): ?>
    <a href="/post/delete/<?php echo $post->getId(); ?>?csrfToken=<?php echo $csrf::token(); ?>">Delete This Post</a>
<?php endif; ?>
