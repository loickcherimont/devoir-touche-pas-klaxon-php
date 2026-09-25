<?php
$agencies = $agencies ?? [];
?>
<div class='container'>
	<h1>Proposer un trajet</h1>
	<form action='/trips/new' method='POST'>
		<?php if (isset($error)): ?>
			<div class="alert alert-danger" role="alert">
				<?= $error ?>
			</div>
		<?php endif; ?>

		<!-- Static content with user informations -->
		<div class='mb-3 row'>
			<label for='staticNom' class='col-sm-2 col-form-label'>Nom</label>
			<div class='col-sm-10'>
				<input type='text' readonly class='form-control-plaintext' id='staticNom' value='<?= $userInfos->nom ?? '' ?>'>
			</div>
		</div>
		<div class='mb-3 row'>
			<label for='staticPrenom' class='col-sm-2 col-form-label'>Prénom</label>
			<div class='col-sm-10'>
				<input type='text' readonly class='form-control-plaintext' id='staticPrenom' value='<?= $userInfos->prenom ?? '' ?>'>
			</div>
		</div>
		<div class='mb-3 row'>
			<label for='staticEmail' class='col-sm-2 col-form-label'>Adresse email</label>
			<div class='col-sm-10'>
				<input type='text' readonly class='form-control-plaintext' id='staticEmail' value='<?= $userInfos->email ?? '' ?>'>
			</div>
		</div>
		<div class='mb-3 row'>
			<label for='staticTelephone' class='col-sm-2 col-form-label'>Téléphone</label>
			<div class='col-sm-10'>
				<input type='text' readonly class='form-control-plaintext' id='staticTelephone' value='<?= $userInfos->telephone ?? '' ?>'>
			</div>
		</div>

		<!-- Form to complete -->
		<div class='mb-3 row'>
			<label for='tripDepart' class='col-sm-2 col-form-label'>Départ</label>
			<div class='col-sm-10'>
				<select class='form-select' id='tripDepart' name='depart_id' required>
					<option selected value=''>Choisir une agence</option>
					<?php foreach ($agencies as $agency): ?>
						<option value='<?= $agency['id'] ?>'><?= $agency['nom'] ?></option>
					<?php endforeach; ?>
				</select>
			</div>
		</div>
		<div class='mb-3 row'>
			<label for='tripDestination' class='col-sm-2 col-form-label'>Destination</label>
			<div class='col-sm-10'>
				<select class='form-select' id='tripDestination' name='destination_id' required>
					<option selected value=''>Choisir une agence</option>
					<?php foreach ($agencies as $agency): ?>
						<option value='<?= $agency['id'] ?>'><?= $agency['nom'] ?></option>
					<?php endforeach; ?>
				</select>
			</div>
		</div>
		<div class='row'>
			<div class='mb-3 col-sm-6'>
				<label for='tripDateDepart' class='form-label'>Date de départ</label>
				<input type='date' class='form-control' id='tripDateDepart' required name='date_depart'>
			</div>
			<div class='mb-3 col-sm-6'>
				<label for='tripHeureDepart' class='form-label'>Heure de départ</label>
				<input type='time' class='form-control' id='tripHeureDepart' required name='heure_depart'>
			</div>
		</div>
		<div class='row'>
			<div class='mb-3 col-sm-6'>
				<label for='tripDateArrivee' class='form-label'>Date d'arrivée</label>
				<input type='date' class='form-control' id='tripDateArrivee' required name='date_arrivee'>
			</div>
			<div class='mb-3 col-sm-6'>
				<label for='tripHeureArrivee' class='form-label'>Heure d'arrivée</label>
				<input type='time' class='form-control' id='tripHeureArrivee' required name='heure_arrivee'>
			</div>
		</div>
		<div class='mb-3 row'>
			<label for='tripPlaces' class='col-sm-2 col-form-label'>Places disponibles</label>
			<div class='col-sm-10'>
				<input type='number' class='form-control' id='tripPlaces' min='1' max='8' value='4' required name='places_disponibles'>
			</div>
		</div>
		<div class='mb-3 row'>
			<div class='col-sm-10 offset-sm-2'>
				<button type='submit' class='btn btn-primary'>Valider le trajet</button>
			</div>
		</div>
	</form>
</div>