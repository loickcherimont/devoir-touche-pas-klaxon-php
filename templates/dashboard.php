<?php

/**
 * Admin dashboard, reachable at /admin by admins only (AdminController).
 *
 * The users listing is read-only and shows non-admin users only. The agencies
 * section offers a read-only listing plus creation and deletion: update is
 * still to be implemented. The trips section is read-only, without create nor
 * update (the regular user flows on /trips/new and /trips/update are the
 * public side of the same feature).
 */

use App\DateTimeFormatter;

$users = $users ?? [];
$agencies = $agencies ?? [];
$trips = $trips ?? [];
?>
<?php if (isset($error)): ?>
	<div class='alert alert-danger' role='alert'>
		<?= htmlspecialchars($error) ?>
	</div>
<?php endif; ?>
<?php if (isset($success)): ?>
	<div class='alert alert-success' role='alert'>
		<?= htmlspecialchars($success) ?>
	</div>
<?php endif; ?>

<h1>Tableau de bord</h1>

<div id='users'>
	<h2>Utilisateurs</h2>
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
</div>
<div id='agencies'>
	<h2>Agences</h2>

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
				<th scope='col'><span class='visually-hidden'>Actions</span></th>
			</tr>
		</thead>
		<tbody>
			<?php foreach ($agencies as $agency): ?>
				<tr>
					<td><?= htmlspecialchars($agency->id) ?></td>
					<td><?= htmlspecialchars($agency->nom) ?></td>
					<td>
						<a class='btn btn-danger' href='/admin/agencies/delete/<?= $agency->id ?>' role='button' aria-label='Supprimer cette agence' onclick='return confirm("Supprimer définitivement cette agence ?")'>
							<svg xmlns='http://www.w3.org/2000/svg' width='16' height='16' fill='currentColor' class='bi bi-trash3' viewBox='0 0 16 16'>
								<path d='M6.5 1h3a.5.5 0 0 1 .5.5v1H6v-1a.5.5 0 0 1 .5-.5M11 2.5v-1A1.5 1.5 0 0 0 9.5 0h-3A1.5 1.5 0 0 0 5 1.5v1H1.5a.5.5 0 0 0 0 1h.538l.853 10.66A2 2 0 0 0 4.885 16h6.23a2 2 0 0 0 1.994-1.84l.853-10.66h.538a.5.5 0 0 0 0-1zm1.958 1-.846 10.58a1 1 0 0 1-.997.92h-6.23a1 1 0 0 1-.997-.92L3.042 3.5zm-7.487 1a.5.5 0 0 1 .528.47l.5 8.5a.5.5 0 0 1-.998.06L5 5.03a.5.5 0 0 1 .47-.53Zm5.058 0a.5.5 0 0 1 .47.53l-.5 8.5a.5.5 0 0 1-.998-.06l.5-8.5a.5.5 0 0 1 .528-.47M8 4.5a.5.5 0 0 1 .5.5v8.5a.5.5 0 0 1-1 0V5a.5.5 0 0 1 .5-.5' />
							</svg>
						</a>
					</td>
				</tr>
			<?php endforeach; ?>
		</tbody>
	</table>
</div>
<div id='trips'>
	<h2>Trajets</h2>
	<table class='table table-striped table-bordered text-center rounded'>
		<thead class='table-dark'>
			<tr>
				<th scope='col'>Id</th>
				<th scope='col'>Propriétaire</th>
				<th scope='col'>Départ</th>
				<th scope='col'>Date de départ</th>
				<th scope='col'>Heure de départ</th>
				<th scope='col'>Destination</th>
				<th scope='col'>Date d'arrivée</th>
				<th scope='col'>Heure d'arrivée</th>
				<th scope='col'>Places disponibles</th>
			</tr>
		</thead>
		<tbody>
			<?php foreach ($trips as $trip): ?>
				<tr>
					<td><?= htmlspecialchars($trip->id) ?></td>
					<td><?= htmlspecialchars($trip->owner) ?></td>
					<td><?= htmlspecialchars($trip->departureAgency) ?></td>
					<td><?= htmlspecialchars(DateTimeFormatter::date($trip->departureDate)) ?></td>
					<td><?= htmlspecialchars(DateTimeFormatter::time($trip->departureTime)) ?></td>
					<td><?= htmlspecialchars($trip->arrivalAgency) ?></td>
					<td><?= htmlspecialchars(DateTimeFormatter::date($trip->arrivalDate)) ?></td>
					<td><?= htmlspecialchars(DateTimeFormatter::time($trip->arrivalTime)) ?></td>
					<td><?= htmlspecialchars((string) $trip->availableSeats) ?></td>
				</tr>
			<?php endforeach; ?>
		</tbody>
	</table>
</div>
