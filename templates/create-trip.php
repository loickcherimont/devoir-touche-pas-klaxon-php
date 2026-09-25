<div class='container'>
	<h1>Proposer un trajet</h1>
	<!-- TODO: wire this form with a POST handler. Display-only for now. -->
	<form>
		<div class='mb-3 row'>
			<label for='tripDepart' class='col-sm-2 col-form-label'>Agence de départ</label>
			<div class='col-sm-10'>
				<select class='form-select' id='tripDepart'>
					<option selected>Choisir une agence</option>
				</select>
			</div>
		</div>
		<div class='mb-3 row'>
			<label for='tripDestination' class='col-sm-2 col-form-label'>Destination</label>
			<div class='col-sm-10'>
				<select class='form-select' id='tripDestination'>
					<option selected>Choisir une agence</option>
				</select>
			</div>
		</div>
		<div class='row'>
			<div class='mb-3 col-sm-6'>
				<label for='tripDateDepart' class='form-label'>Date de départ</label>
				<input type='date' class='form-control' id='tripDateDepart'>
			</div>
			<div class='mb-3 col-sm-6'>
				<label for='tripHeureDepart' class='form-label'>Heure de départ</label>
				<input type='time' class='form-control' id='tripHeureDepart'>
			</div>
		</div>
		<div class='row'>
			<div class='mb-3 col-sm-6'>
				<label for='tripDateArrivee' class='form-label'>Date d'arrivée</label>
				<input type='date' class='form-control' id='tripDateArrivee'>
			</div>
			<div class='mb-3 col-sm-6'>
				<label for='tripHeureArrivee' class='form-label'>Heure d'arrivée</label>
				<input type='time' class='form-control' id='tripHeureArrivee'>
			</div>
		</div>
		<div class='mb-3 row'>
			<label for='tripPlaces' class='col-sm-2 col-form-label'>Places disponibles</label>
			<div class='col-sm-10'>
				<input type='number' class='form-control' id='tripPlaces' min='1' max='8' value='4'>
			</div>
		</div>
		<div class='mb-3 row'>
			<div class='col-sm-10 offset-sm-2'>
				<button type='submit' class='btn btn-primary'>Proposer le trajet</button>
			</div>
		</div>
	</form>
</div>