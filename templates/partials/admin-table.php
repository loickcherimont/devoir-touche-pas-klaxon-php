<?php

/**
 * Admin listing table.
 *
 * PLACEHOLDER: the rows below are Bootstrap sample data. They will be
 * replaced by real rows built from UserModel / AgencyModel / TripModel
 * once those listings are implemented.
 *
 * Expects, provided by the controller:
 * - string           $sectionId  anchor target of the section (e.g. 'users');
 * - string           $sectionTitle
 * - array<int, string> $columns  visible column labels;
 * - array<int, array<int, string>> $rows  already escaped cell values.
 */

$sectionId = $sectionId ?? '';
$sectionTitle = $sectionTitle ?? '';
$columns = $columns ?? [];
$rows = $rows ?? []; ?>

<div id='<?= htmlspecialchars($sectionId) ?>'>
	<h2><?= htmlspecialchars($sectionTitle) ?></h2>
	<table class='table table-striped table-bordered text-center rounded'>
		<thead class='table-dark'>
			<tr>
				<?php foreach ($columns as $column): ?>
					<th scope='col'><?= htmlspecialchars($column) ?></th>
				<?php endforeach; ?>
			</tr>
		</thead>
		<tbody>
			<?php foreach ($rows as $row): ?>
				<tr>
					<?php foreach ($row as $cell): ?>
						<td><?= $cell ?></td>
					<?php endforeach; ?>
				</tr>
			<?php endforeach; ?>
		</tbody>
	</table>
</div>
