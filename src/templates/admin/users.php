<?php

helper('crud');

$title = 'Users';
$layout = 'admin/_layout.php';
?>

<h1>Users</h1>
<p><a href="../admin/user.php">Add new user</a></p>
<table>
    <tr>
        <th>Name</th>
        <th>Email</th>
        <th>Roles</th>
        <th></th>
    </tr>

    <?php
    foreach ($users as $u) { ?>
        <tr>
            <td><?= linkTo("user.php?email={$u->email}", e($u->name)) ?></td>
            <td><?= mailTo(e($u->email)) ?></td>
            <td><?= implode(', ', $u->roles()) ?></td>
            <td class="button-group"><?= actionButtons('user.php', $u->key()) ?></td>
        </tr>
        <?php
    } ?>
</table>
