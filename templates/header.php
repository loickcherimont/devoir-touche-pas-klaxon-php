<!DOCTYPE html>
<html lang='fr'>

<head>
	<meta charset='UTF-8'>
	<meta name='viewport' content='width=device-width, initial-scale=1.0'>
	<title>Touche pas au klaxon</title>
	<link rel="stylesheet" href='/styles/index.css'>
</head>

<body>
	<div class="container">
		<nav class='navbar navbar-expand-lg bg-body-tertiary border border-3 rounded mt-3'>
			<div class='container-fluid'>
				<a class='navbar-brand'>Touche pas au klaxon</a>
				<?php if (isset($_SESSION['auth_logged_in'])): ?>
					<a class='btn btn-primary' href='/trips/new' role='button'>Créer un trajet</a>
					<p>Bonjour <?= $_SESSION['first_name'] ?> <?= $_SESSION['last_name'] ?> </p>
					<a class='btn btn-dark' href='/logout' role='button'>Déconnexion</a>
				<?php elseif (empty($hideButton)): ?>
					<a class='btn btn-primary' href='/login' role='button'>Connexion</a>
				<?php endif; ?>
			</div>
		</nav>