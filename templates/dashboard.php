<?php

/**
 * Admin dashboard, reachable at /admin by admins only (AdminController).
 *
 * The users listing is read-only and shows non-admin users only. The agencies
 * section offers a read-only listing plus a creation form: update and delete
 * are still to be implemented. Only the trips section is missing, hence the
 * comment below:
 * - Trips: planned as read and delete, without create nor update.
 */

$users = $users ?? [];
$agencies = $agencies ?? []; ?>

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
				<td><?= htmlspecialchars($user->id) ?></td>
				<td><?= htmlspecialchars($user->prenom) ?></td>
				<td><?= htmlspecialchars($user->nom) ?></td>
				<td><?= htmlspecialchars($user->email) ?></td>
				<td><?= htmlspecialchars($user->role->value ?? 'inconnu') ?></td>
			</tr>
		<?php endforeach; ?>
	</tbody>
</table>

<h2 id='agencies'>Agences</h2>

<?php if (isset($error)): ?>
	<div class='alert alert-danger' role='alert'>
		<?= htmlspecialchars($error) ?>
	</div>
<?php endif; ?>

<form action='/admin/agencies/new' method='POST' class='mb-3'>
	<div class='row g-2 align-items-center'>
		<div class='col-auto'>
			<label for='newAgencyName' class='col-form-label'>Nom de la nouvelle agence</label>
		</div>
		<div class='col-auto'>
			<input type='text' id='newAgencyName' class='form-control' name='nom' required placeholder='Ex : Bordeaux'>
		</div>
		<div class='col-auto'>
			<button type='submit' class='btn btn-primary'>Ajouter</button>
		</div>
	</div>
</form>

<table class='table table-striped table-bordered text-center rounded'>
	<thead class='table-dark'>
		<tr>
			<th scope='col'>Id</th>
			<th scope='col'>Nom</th>
			<th></th>
		</tr>
	</thead>
	<tbody>
		<?php foreach ($agencies as $agency): ?>
			<tr>
				<td><?= htmlspecialchars($agency->id) ?></td>
				<td><?= htmlspecialchars($agency->nom) ?></td>
				<td></td>
			</tr>
		<?php endforeach; ?>
	</tbody>
</table>
