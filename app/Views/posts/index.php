<h1><?php echo $post->getTitle(); ?></h1>
<p><?php echo $post->getBody(); ?></p>
<?php foreach ($post->getImages() as $image): ?>
    <img src="<?php echo $image; ?>">
<?php endforeach; ?>
<div>Posted at: <?php echo $post->getCreatedAt() ?></div>
<div>Posted by: <?php echo $post->getUser()->getFullName(); ?></div>
<div>Likes: <?php echo $post->getTotalLikes(); ?></div>
<?php $token = $csrf::token(); ?>
<?php if ($user->isLoggedIn()): ?>
    <?php if ($post->isLikedBy($user->getId())): ?>
        <a href="/post/dislike/<?php echo $post->getId(); ?>?csrfToken=<?php echo $token; ?>">Dislike</a>
    <?php else: ?>
        <a href="/post/like/<?php echo $post->getId(); ?>?csrfToken=<?php echo $token; ?>">Like</a>
    <?php endif; ?>
<?php endif; ?>
<?php if ($user->isLoggedIn() && ($user->getId() === $post->getUserId())): ?>
    <a href="/post/delete/<?php echo $post->getId(); ?>?csrfToken=<?php echo $token; ?>">Delete This Post</a>
<?php endif; ?>
&lt;script&gt;console.log('test')&lt;/script&gt;
