<?php

/**
 * Admin dashboard, reachable at /admin by admins only (AdminController).
 *
 * PLACEHOLDER: the three listings below still hold Bootstrap sample data.
 * They will be fed by UserModel / AgencyModel / TripModel through the
 * AdminController, which currently renders this page without any data.
 */

$listings = [
	[
		'sectionId' => 'users',
		'sectionTitle' => 'Utilisateurs',
		'columns' => ['#', 'Nom', 'Prénom', 'Email', 'Rôle'],
		'rows' => [
			['1', 'Otto', 'Mark', 'mark.otto@email.fr', 'user'],
			['2', 'Thornton', 'Jacob', 'jacob.thornton@email.fr', 'user'],
			['3', 'Doe', 'John', 'admin@email.fr', 'admin'],
		],
	],
	[
		'sectionId' => 'agencies',
		'sectionTitle' => 'Agences',
		'columns' => ['#', 'Ville'],
		'rows' => [
			['1', 'Paris'],
			['2', 'Lyon'],
			['3', 'Marseille'],
		],
	],
	[
		'sectionId' => 'trips',
		'sectionTitle' => 'Trajets',
		'columns' => ['#', 'Départ', 'Destination', 'Date de départ'],
		'rows' => [
			['1', 'Paris', 'Lyon', '2026-10-01 08:00:00'],
			['2', 'Lyon', 'Marseille', '2026-10-01 09:30:00'],
			['3', 'Marseille', 'Toulouse', '2026-10-02 07:45:00'],
		],
	],
]; ?>

<h1>Tableau de bord</h1>

<?php foreach ($listings as $listing): ?>
	<?php
	$sectionId = $listing['sectionId'];
	$sectionTitle = $listing['sectionTitle'];
	$columns = $listing['columns'];
	$rows = array_map(
		fn (array $row): array => array_map('htmlspecialchars', $row),
		$listing['rows']
	);

	require ROOT_PATH . '/templates/partials/admin-table.php';
	?>
<?php endforeach; ?>
