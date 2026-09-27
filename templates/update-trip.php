<?php

/**
 * Variables provided by TripController::renderUpdateTrip().
 *
 * @var \App\Model\Trip\TripToUpdateDetailsDTO $trip
 * @var array<array<string, mixed>>             $agencies
 * @var string|null                            $error
 */
$agencies = $agencies ?? [];
$error = $error ?? null;
?>
<div class='container'>
	<a class='btn btn-outline-secondary' href='/' role='button'>Retour à l'accueil</a>
	<h1>Modifier mon trajet</h1>
	<form action='/trips/update/<?= (int) $trip->id ?>' method='POST'>
		<?php if (isset($error)): ?>
			<div class="alert alert-danger" role="alert">
				<?= htmlspecialchars($error) ?>
			</div>
		<?php endif; ?>

		<!-- Static content with user informations -->
		<div class='mb-3 row'>
			<label for='staticNom' class='col-sm-2 col-form-label'>Nom</label>
			<div class='col-sm-10'>
				<input type='text' readonly class='form-control-plaintext' id='staticNom' value='<?= htmlspecialchars($trip->authorFirstName) ?>'>
			</div>
		</div>
		<div class='mb-3 row'>
			<label for='staticPrenom' class='col-sm-2 col-form-label'>Prénom</label>
			<div class='col-sm-10'>
				<input type='text' readonly class='form-control-plaintext' id='staticPrenom' value='<?= htmlspecialchars($trip->authorLastName) ?>'>
			</div>
		</div>
		<div class='mb-3 row'>
			<label for='staticEmail' class='col-sm-2 col-form-label'>Adresse email</label>
			<div class='col-sm-10'>
				<input type='text' readonly class='form-control-plaintext' id='staticEmail' value='<?= htmlspecialchars($trip->email) ?>'>
			</div>
		</div>
		<div class='mb-3 row'>
			<label for='staticTelephone' class='col-sm-2 col-form-label'>Téléphone</label>
			<div class='col-sm-10'>
				<input type='text' readonly class='form-control-plaintext' id='staticTelephone' value='<?= htmlspecialchars($trip->phone) ?>'>
			</div>
		</div>

		<!-- Form to complete -->
		<div class='mb-3 row'>
			<label for='tripDepart' class='col-sm-2 col-form-label'>Départ</label>
			<div class='col-sm-10'>
				<select class='form-select' id='tripDepart' name='depart_id' required>
					<option value=''>Choisir une agence</option>
					<?php foreach ($agencies as $agency): ?>
						<option value='<?= (int) $agency['id'] ?>'<?= (int) $agency['id'] === $trip->departureAgencyId ? ' selected' : '' ?>>
							<?= htmlspecialchars((string) $agency['nom']) ?>
						</option>
					<?php endforeach; ?>
				</select>
			</div>
		</div>
		<div class='mb-3 row'>
			<label for='tripDestination' class='col-sm-2 col-form-label'>Destination</label>
			<div class='col-sm-10'>
				<select class='form-select' id='tripDestination' name='destination_id' required>
					<option value=''>Choisir une agence</option>
					<?php foreach ($agencies as $agency): ?>
						<option value='<?= (int) $agency['id'] ?>'<?= (int) $agency['id'] === $trip->destinationAgencyId ? ' selected' : '' ?>>
							<?= htmlspecialchars((string) $agency['nom']) ?>
						</option>
					<?php endforeach; ?>
				</select>
			</div>
		</div>
		<div class='row'>
			<div class='mb-3 col-sm-6'>
				<label for='tripDateDepart' class='form-label'>Date de départ</label>
				<input type='date' class='form-control' id='tripDateDepart' required name='date_depart' value='<?= htmlspecialchars($trip->departureDate) ?>'>
			</div>
			<div class='mb-3 col-sm-6'>
				<label for='tripHeureDepart' class='form-label'>Heure de départ</label>
				<input type='time' class='form-control' id='tripHeureDepart' required name='heure_depart' value='<?= htmlspecialchars($trip->departureTime) ?>'>
			</div>
		</div>
		<div class='row'>
			<div class='mb-3 col-sm-6'>
				<label for='tripDateArrivee' class='form-label'>Date d'arrivée</label>
				<input type='date' class='form-control' id='tripDateArrivee' required name='date_arrivee' value='<?= htmlspecialchars($trip->arrivalDate) ?>'>
			</div>
			<div class='mb-3 col-sm-6'>
				<label for='tripHeureArrivee' class='form-label'>Heure d'arrivée</label>
				<input type='time' class='form-control' id='tripHeureArrivee' required name='heure_arrivee' value='<?= htmlspecialchars($trip->arrivalTime) ?>'>
			</div>
		</div>
		<div class='mb-3 row'>
			<label for='tripPlaces' class='col-sm-2 col-form-label'>Places disponibles</label>
			<div class='col-sm-10'>
				<input type='number' class='form-control' id='tripPlaces' min='1' max='8' required name='places_disponibles' value='<?= (int) $trip->availableSeats ?>'>
			</div>
		</div>
		<div class='mb-3 row'>
			<div class='col-sm-10 offset-sm-2'>
				<button type='submit' class='btn btn-primary'>Modifier le trajet</button>
			</div>
		</div>
	</form>
</div>