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
						<button type='button' class='btn btn-secondary' data-bs-toggle='modal' data-bs-target='#agencyUpdateModal' data-agency-id='<?= htmlspecialchars($agency->id) ?>' aria-label='Modifier cette agence'>
							<svg xmlns='http://www.w3.org/2000/svg' width='16' height='16' fill='currentColor' class='bi bi-pencil-square' viewBox='0 0 16 16'>
								<path d='M15.502 1.94a.5.5 0 0 1 0 .706L14.459 3.69l-2-2L13.502.646a.5.5 0 0 1 .707 0l1.293 1.293zm-1.75 2.456-2-2L4.939 9.21a.5.5 0 0 0-.121.196l-.805 2.414a.25.25 0 0 0 .316.316l2.414-.805a.5.5 0 0 0 .196-.12l6.813-6.814z' />
								<path fill-rule='evenodd' d='M1 13.5A1.5 1.5 0 0 0 2.5 15h11a1.5 1.5 0 0 0 1.5-1.5v-6a.5.5 0 0 0-1 0v6a.5.5 0 0 1-.5.5h-11a.5.5 0 0 1-.5-.5v-11a.5.5 0 0 1 .5-.5H9a.5.5 0 0 0 0-1H2.5A1.5 1.5 0 0 0 1 2.5z' />
							</svg>
						</button>
					</td>
				</tr>
			<?php endforeach; ?>
		</tbody>
	</table>
	<div class='modal fade' id='agencyUpdateModal' tabindex='-1' aria-labelledby='agencyUpdateModalTitle' aria-hidden='true'>
		<div class='modal-dialog'>
			<div class='modal-content'>
				<form action='/admin/agencies/update' method='POST'>
					<div class='modal-header'>
						<h2 class='modal-title fs-5' id='agencyUpdateModalTitle'>Modifier l'agence</h2>
						<button type='button' class='btn-close' data-bs-dismiss='modal' aria-label='Fermer'></button>
					</div>
					<div class='modal-body text-start'>
						<input type='hidden' id='updateAgencyId' name='id'>
						<div class='mb-3'>
							<label for='updateAgencyName' class='form-label'>Nom de l'agence</label>
							<input type='text' id='updateAgencyName' class='form-control' name='nom' placeholder='Ex : Bordeaux' required>
						</div>
					</div>
					<div class='modal-footer'>
						<button type='button' class='btn btn-dark' data-bs-dismiss='modal'>Annuler</button>
						<button type='submit' class='btn btn-primary'>Enregistrer</button>
					</div>
				</form>
			</div>
		</div>
	</div>
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
