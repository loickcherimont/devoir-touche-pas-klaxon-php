<?php $trips = $trips ?? [];
?>
<?php

use App\DateTimeFormatter; ?>


<div class='container'>
	<h1>Pour plus d'informations sur un trajet, veuillez vous connecter</h1>
	<table class='table table-striped table-bordered text-center rounded'>
		<thead class='table-dark'>
			<tr>
				<th scope='col'>Départ</th>
				<th scope='col'>Date de départ</th>
				<th scope='col'>Heure de départ</th>
				<th scope='col'>Destination</th>
				<th scope='col'>Date d'arrivée</th>
				<th scope='col'>Heure d'arrivée</th>
				<th scope='col'>Places disponibles</th>
				<?php if (isset($_SESSION['auth_logged_in'])): ?>
					<th></th>
				<?php endif; ?>
			</tr>
		</thead>
		<tbody>
			<?php foreach ($trips as $trip): ?>
				<tr>
					<td><?= htmlspecialchars($trip['depart']) ?></td>
					<td><?= htmlspecialchars(DateTimeFormatter::date($trip['date_depart'])) ?></td>
					<td><?= htmlspecialchars(DateTimeFormatter::time($trip['heure_depart'])) ?></td>
					<td><?= htmlspecialchars($trip['destination']) ?></td>
					<td><?= htmlspecialchars(DateTimeFormatter::date($trip['date_arrivee'])) ?></td>
					<td><?= htmlspecialchars(DateTimeFormatter::time($trip['heure_arrivee'])) ?></td>
					<td><?= htmlspecialchars($trip['places_disponibles']) ?></td>
					<?php if (isset($_SESSION['auth_logged_in'])): ?>
						<td>
							<button type='button' class='btn btn-primary' data-bs-toggle='modal' data-bs-target='#tripDetailsModal' data-trip-id='<?= (int) $trip['id'] ?>' aria-label='Afficher les informations du trajet'>
								<svg xmlns='http://www.w3.org/2000/svg' width='16' height='16' fill='currentColor' class='bi bi-eye text-bg-primary' viewBox='0 0 16 16'>
									<path d='M16 8s-3-5.5-8-5.5S0 8 0 8s3 5.5 8 5.5S16 8 16 8M1.173 8a13 13 0 0 1 1.66-2.043C4.12 4.668 5.88 3.5 8 3.5s3.879 1.168 5.168 2.457A13 13 0 0 1 14.828 8q-.086.13-.195.288c-.335.48-.83 1.12-1.465 1.755C11.879 11.332 10.119 12.5 8 12.5s-3.879-1.168-5.168-2.457A13 13 0 0 1 1.172 8z' />
									<path d='M8 5.5a2.5 2.5 0 1 0 0 5 2.5 2.5 0 0 0 0-5M4.5 8a3.5 3.5 0 1 1 7 0 3.5 3.5 0 0 1-7 0' />
								</svg>
							</button>
							<svg xmlns='http://www.w3.org/2000/svg' width='16' height='16' fill='currentColor' class='bi bi-pencil-square' viewBox='0 0 16 16'>
								<path d='M15.502 1.94a.5.5 0 0 1 0 .706L14.459 3.69l-2-2L13.502.646a.5.5 0 0 1 .707 0l1.293 1.293zm-1.75 2.456-2-2L4.939 9.21a.5.5 0 0 0-.121.196l-.805 2.414a.25.25 0 0 0 .316.316l2.414-.805a.5.5 0 0 0 .196-.12l6.813-6.814z' />
								<path fill-rule='evenodd' d='M1 13.5A1.5 1.5 0 0 0 2.5 15h11a1.5 1.5 0 0 0 1.5-1.5v-6a.5.5 0 0 0-1 0v6a.5.5 0 0 1-.5.5h-11a.5.5 0 0 1-.5-.5v-11a.5.5 0 0 1 .5-.5H9a.5.5 0 0 0 0-1H2.5A1.5 1.5 0 0 0 1 2.5z' />
							</svg>
							<svg xmlns='http://www.w3.org/2000/svg' width='16' height='16' fill='currentColor' class='bi bi-trash3' viewBox='0 0 16 16'>
								<path d='M6.5 1h3a.5.5 0 0 1 .5.5v1H6v-1a.5.5 0 0 1 .5-.5M11 2.5v-1A1.5 1.5 0 0 0 9.5 0h-3A1.5 1.5 0 0 0 5 1.5v1H1.5a.5.5 0 0 0 0 1h.538l.853 10.66A2 2 0 0 0 4.885 16h6.23a2 2 0 0 0 1.994-1.84l.853-10.66h.538a.5.5 0 0 0 0-1zm1.958 1-.846 10.58a1 1 0 0 1-.997.92h-6.23a1 1 0 0 1-.997-.92L3.042 3.5zm-7.487 1a.5.5 0 0 1 .528.47l.5 8.5a.5.5 0 0 1-.998.06L5 5.03a.5.5 0 0 1 .47-.53Zm5.058 0a.5.5 0 0 1 .47.53l-.5 8.5a.5.5 0 1 1-.998-.06l.5-8.5a.5.5 0 0 1 .528-.47M8 4.5a.5.5 0 0 1 .5.5v8.5a.5.5 0 0 1-1 0V5a.5.5 0 0 1 .5-.5' />
							</svg>
						</td>
					<?php endif; ?>
				</tr>
			<?php endforeach; ?>
		</tbody>
	</table>
	<div class='modal fade' id='tripDetailsModal' tabindex='-1' aria-labelledby='tripDetailsModalTitle' aria-hidden='true'>
		<div class='modal-dialog'>
			<div class='modal-content'>
				<div class='modal-header'>
					<h2 class='modal-title fs-5' id='tripDetailsModalTitle'>Informations du trajet</h2>
					<button type='button' class='btn-close' data-bs-dismiss='modal' aria-label='Fermer'></button>
				</div>
				<div class='modal-body text-start'>
					<p>Auteur : <strong id='tripAuthor'></strong></p>
					<p>Téléphone : <strong id='tripPhone'></strong></p>
					<p>Email : <strong id='tripEmail'></strong></p>
					<p>Places disponibles : <strong id='tripAvailableSeats'></strong></p>
				</div>
				<div class='modal-footer'>
					<button type='button' class='btn btn-dark' data-bs-dismiss='modal'>Fermer</button>
				</div>
			</div>
		</div>
	</div>
</div>
