<?php

/**
 * Admin dashboard, reachable at /admin by admins only (AdminController).
 *
 * The users listing is read-only and shows non-admin users only. The agencies
 * and trips sections are still to be implemented, hence the comment below:
 * - Agencies: planned as a full CRUD (create, read, update, delete);
 * - Trips: planned as read and delete, without create nor update.
 */

$users = $users ?? []; ?>

<h1>Tableau de bord</h1>

<h2 id='users'>Utilisateurs</h2>

<table class='table table-striped table-bordered text-center rounded'>
	<thead class='table-dark'>
		<tr>
			<th scope='col'>Id</th>
			<th scope='col'>Prénom</th>
			<th scope='col'>Nom</th>
			<th scope='col'>Adresse email</th>
			<th scope='col'>Rôle</th>
		</tr>
	</thead>
	<tbody>
		<?php foreach ($users as $user): ?>
			<tr>
				<td><?= htmlspecialchars((string) $user->id) ?></td>
				<td><?= htmlspecialchars($user->prenom) ?></td>
				<td><?= htmlspecialchars($user->nom) ?></td>
				<td><?= htmlspecialchars($user->email) ?></td>
				<td><?= htmlspecialchars($user->role->value ?? 'inconnu') ?></td>
			</tr>
		<?php endforeach; ?>
	</tbody>
</table>
