<?php $trips = $trips ?? []; ?>
<?php

use App\Formatter; ?>


<div class="container">
	<h1>Pour plus d'informations sur un trajet, veuillez vous connecter</h1>
	<table class="table table-striped table-bordered text-center rounded">
		<thead class="table-dark">
			<tr>
				<th scope="col">Départ</th>
				<th scope="col">Date de départ</th>
				<th scope="col">Heure de départ</th>
				<th scope="col">Destination</th>
				<th scope="col">Date d'arrivée</th>
				<th scope="col">Heure d'arrivée</th>
				<th scope="col">Places disponibles</th>
				<?php if (isset($_SESSION['auth_logged_in'])): ?>
					<th></th>
				<?php endif; ?>
			</tr>
		</thead>
		<tbody>
			<?php foreach ($trips as $trip): ?>
				<tr>
					<td><?= htmlspecialchars($trip['depart']) ?></td>
					<td><?= htmlspecialchars(Formatter::date($trip['date_depart'])) ?></td>
					<td><?= htmlspecialchars(Formatter::time($trip['heure_depart'])) ?></td>
					<td><?= htmlspecialchars($trip['destination']) ?></td>
					<td><?= htmlspecialchars(Formatter::date($trip['date_arrivee'])) ?></td>
					<td><?= htmlspecialchars(Formatter::time($trip['heure_arrivee'])) ?></td>
					<td><?= htmlspecialchars($trip['places_disponibles']) ?></td>
					<?php if (isset($_SESSION['auth_logged_in'])): ?>
						<td></td>
					<?php endif; ?>
				</tr>
			<?php endforeach; ?>
		</tbody>
	</table>
</div>