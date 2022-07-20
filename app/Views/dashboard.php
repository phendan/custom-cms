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
