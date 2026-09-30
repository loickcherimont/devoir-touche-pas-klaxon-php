<!DOCTYPE html>
<html lang='fr'>

<head>
	<meta charset='UTF-8'>
	<meta name='viewport' content='width=device-width, initial-scale=1.0'>
	<title>Touche pas au klaxon</title>
	<link rel='stylesheet' href='/styles/index.css'>
</head>

<body>
	<div class="container">
		<?php

		use App\Model\User\UserRole;
		use App\Security\Flash;

		// $userRole is injected by AbstractController::render() on every page.
		// It is null for a visitor, and also for a stored role the enum does not
		// know: both fall back to the visitor navigation below.
		$userRole = $userRole ?? null;

		// Flash messages set by the previous POST (PRG pattern), shown once.
		foreach (Flash::all() as $type => $message): ?>
			<div class='alert alert-<?= htmlspecialchars($type) ?>' role='alert'>
				<?= htmlspecialchars((string) $message) ?>
			</div>
		<?php endforeach;
		Flash::clear(); ?>
		<?php if ($userRole === UserRole::Admin): ?>
			<nav class='navbar navbar-expand-lg bg-body-tertiary border border-3 rounded mt-3'>
				<div class='container-fluid'>
					<a class='navbar-brand' href='/admin'>Touche pas au klaxon</a>
					<a class='btn btn-primary' href='/admin#users' role='button'>Utilisateurs</a>
					<a class='btn btn-primary' href='/admin#agencies' role='button'>Agences</a>
					<a class='btn btn-primary' href='/admin#trips' role='button'>Trajets</a>
					<p>Bonjour <?= htmlspecialchars((string) $_SESSION['first_name']) ?> <?= htmlspecialchars((string) $_SESSION['last_name']) ?> </p>
					<a class='btn btn-dark' href='/logout' role='button'>Déconnexion</a>
				</div>
			</nav>
		<?php elseif ($userRole === UserRole::User): ?>
			<nav class='navbar navbar-expand-lg bg-body-tertiary border border-3 rounded mt-3'>
				<div class='container-fluid'>
					<a class='navbar-brand' href='/'>Touche pas au klaxon</a>
					<a class='btn btn-primary' href='/trips/new' role='button'>Créer un trajet</a>
					<p>Bonjour <?= htmlspecialchars((string) $_SESSION['first_name']) ?> <?= htmlspecialchars((string) $_SESSION['last_name']) ?> </p>
					<a class='btn btn-dark' href='/logout' role='button'>Déconnexion</a>
				</div>
			</nav>
		<?php else: ?>
			<!-- Visitor -->
			<nav class='navbar navbar-expand-lg bg-body-tertiary border border-3 rounded mt-3'>
				<div class='container-fluid'>
					<a class='navbar-brand' href='/'>Touche pas au klaxon</a>
					<a class='btn btn-primary' href='/login' role='button'>Connexion</a>
				</div>
			</nav>
		<?php endif; ?>
