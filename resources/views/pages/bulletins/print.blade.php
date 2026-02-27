<!DOCTYPE html>
<html lang="fr">

<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Bulletin - {{ $bulletin->eleve->nom ?? '' }} {{ $bulletin->eleve->prenom ?? '' }}</title>
	<style>
		:root {
			--border: #1f1f1f;
			--soft: #6b6b6b;
		}

		* {
			box-sizing: border-box;
		}

		body {
			margin: 0;
			background: #5f5f5f;
			font-family: "Times New Roman", Times, serif;
			color: #161616;
			padding: 18px;
		}

		.sheet {
			max-width: 980px;
			margin: 0 auto;
			background: #fff;
			border: 2px solid var(--border);
			border-radius: 6px;
			box-shadow: 0 0 12px rgba(0, 0, 0, .25);
			padding: 16px 18px;
		}

		.topline {
			border-bottom: 2px solid #444;
			display: grid;
			grid-template-columns: 1fr 1fr 1fr;
			gap: 8px;
			margin: 0 28px 10px;
			padding-bottom: 4px;
			font-size: 34px;
		}

		.topline div {
			text-align: center;
		}

		.topline div:first-child {
			text-align: left;
		}

		.head-identity {
			display: grid;
			grid-template-columns: 1fr 1fr;
			gap: 8px 22px;
			margin: 6px 8px 14px;
			font-size: 36px;
			line-height: 1.15;
		}

		.head-identity strong {
			font-weight: 700;
		}

		table {
			width: 100%;
			border-collapse: collapse;
		}

		.marks {
			border: 2px solid var(--border);
			margin-bottom: 0;
		}

		.marks th,
		.marks td {
			border: 1px solid #3b3b3b;
			padding: 1px 6px;
			vertical-align: middle;
			font-size: 33px;
			line-height: 1.05;
		}

		.marks th {
			text-align: center;
			font-weight: 700;
		}

		.marks td:nth-child(2),
		.marks td:nth-child(3),
		.marks td:nth-child(4),
		.marks td:nth-child(5) {
			text-align: center;
		}

		.marks .subject {
			width: 33%;
		}

		.marks .small {
			font-size: 24px;
		}

		.section-row td {
			font-weight: 700;
			border-top-width: 2px;
			border-bottom-width: 2px;
		}

		.totaux-row td {
			font-weight: 700;
			border-top-width: 2px;
			font-size: 34px;
		}

		.summary {
			border: 2px solid var(--border);
			border-top: 0;
			display: grid;
			grid-template-columns: 1fr 1.15fr 1fr;
		}

		.summary .box {
			min-height: 104px;
			padding: 8px 10px;
			border-right: 1px solid #3b3b3b;
			font-size: 36px;
			line-height: 1.15;
		}

		.summary .box:last-child {
			border-right: 0;
		}

		.summary .box strong {
			font-weight: 700;
		}

		.checks div {
			display: flex;
			justify-content: space-between;
			align-items: center;
			gap: 10px;
		}

		.check {
			width: 20px;
			height: 20px;
			border: 2px solid #4b4b4b;
			display: inline-block;
		}

		.bottom-grid {
			border: 2px solid var(--border);
			border-top: 0;
			display: grid;
			grid-template-columns: 1.2fr 1fr;
		}

		.bottom-grid .cell {
			border-right: 1px solid #3b3b3b;
			padding: 8px 10px;
			font-size: 38px;
			line-height: 1.15;
			min-height: 96px;
		}

		.bottom-grid .cell:last-child {
			border-right: 0;
		}

		.print-note {
			text-align: right;
			font-size: 22px;
			font-style: italic;
			margin-top: 6px;
			color: #2a2a2a;
		}

		.toolbar {
			text-align: center;
			margin: 0 0 12px;
		}

		.toolbar button {
			border: 0;
			background: #111;
			color: #fff;
			padding: 8px 16px;
			border-radius: 4px;
			font-size: 14px;
			cursor: pointer;
		}

		@page {
			size: A4;
			margin: 8mm;
		}

		@media print {
			body {
				background: #fff;
				padding: 0;
			}

			.toolbar {
				display: none;
			}

			.sheet {
				max-width: 100%;
				border-radius: 0;
				box-shadow: none;
			}
		}
	</style>
</head>

<body>
	@php
		$details = $bulletin->detailBulletins ?? collect();
		$sommeCoeff = (float) ($bulletin->total_coefficient ?? $details->sum(function ($item) {
			return (float) ($item->coefficient ?? 0);
		}));
		$sommePoints = (float) ($bulletin->total_points ?? $details->sum(function ($item) {
			return (float) ($item->moyenne_ponderee ?? 0);
		}));
		$moyenne = $bulletin->moyenne_globale;
		if ($moyenne === null && $sommeCoeff > 0) {
			$moyenne = $sommePoints / $sommeCoeff;
		}
		$moyClasse = $details->whereNotNull('moyenne_classe')->avg('moyenne_classe');
		$maxClasse = $details->whereNotNull('point_max')->max('point_max');
		$minClasse = $details->whereNotNull('point_min')->min('point_min');

		$fmt = function ($value, $dec = 2) {
			return $value === null ? '-' : number_format((float) $value, $dec, ',', ' ');
		};

		$rangTexte = '-';
		if ($bulletin->rang && $bulletin->total_eleves) {
			$rangTexte = $bulletin->rang . 'ème / ' . $bulletin->total_eleves;
		}
	@endphp

	<div class="toolbar">
		<button type="button" onclick="window.print()">Imprimer le bulletin</button>
	</div>

	<section class="sheet">
		<div class="topline">
			<div><strong>Classe :</strong> {{ $bulletin->classe->nom ?? '-' }}</div>
			<div><strong>Effectif :</strong> {{ $bulletin->total_eleves ?? '-' }}</div>
			<div><strong>Année scolaire :</strong> {{ $bulletin->periodEvaluation->anneeScolaire->libelle ?? '-' }}</div>
		</div>

		<div class="head-identity">
			<div><strong>Nom :</strong> {{ strtoupper($bulletin->eleve->nom ?? '-') }}</div>
			<div><strong>Prénoms :</strong> {{ $bulletin->eleve->prenom ?? '-' }}</div>
			<div><strong>Né(e) le :</strong> {{ optional($bulletin->eleve->date_naissance)->format('d/m/Y') ?? '-' }}</div>
			<div><strong>Classes redoublées :</strong> -</div>
			<div><strong>Matricule :</strong> {{ $bulletin->eleve->registration_number ?? '-' }}</div>
			<div></div>
		</div>

		<table class="marks">
			<thead>
				<tr>
					<th class="subject">Matières</th>
					<th>Coefficients</th>
					<th>Moyennes</th>
					<th>Pondérées</th>
					<th>Appréciations</th>
					<th>Signature du professeur</th>
				</tr>
			</thead>
			<tbody>
				@forelse($details as $detail)
					<tr>
						<td>{{ $detail->matiere->intitule ?? '-' }}</td>
						<td>{{ $fmt($detail->coefficient) }}</td>
						<td>{{ $fmt($detail->moyenne) }}</td>
						<td>{{ $fmt($detail->moyenne_ponderee) }}</td>
						<td>{{ $detail->appreciation ?? '-' }}</td>
						<td class="small">
							{{ $detail->enseignant->nom ?? '-' }} {{ $detail->enseignant->prenom ?? '' }}
						</td>
					</tr>
				@empty
					<tr>
						<td colspan="6" style="text-align:center; padding: 8px;">Aucun détail de bulletin</td>
					</tr>
				@endforelse

				<tr class="totaux-row">
					<td>Totaux</td>
					<td>{{ $fmt($sommeCoeff) }}</td>
					<td>{{ $fmt($moyenne) }}</td>
					<td>{{ $fmt($sommePoints) }}</td>
					<td colspan="2"></td>
				</tr>
			</tbody>
		</table>

		<div class="summary">
			<div class="box">
				<div><strong>Conduite :</strong> {{ $bulletin->mention_conduite ?? '-' }}</div>
				<div><strong>Absences :</strong> {{ $bulletin->absences ?? 0 }}</div>
				<div><strong>Bonus (en points) :</strong> 0,00</div>
				<div><strong>Pénalités (en points) :</strong> 0,00</div>
			</div>
			<div class="box checks">
				<div><strong>Tableau d’honneur :</strong> <span class="check"></span></div>
				<div><strong>Encouragements :</strong> <span class="check"></span></div>
				<div><strong>Félicitations :</strong> <span class="check"></span></div>
			</div>
			<div class="box">
				<div><strong>Moyenne Générale :</strong> {{ $fmt($moyenne) }} / 20</div>
				<div><strong>Mention :</strong> {{ $bulletin->mention_conduite ?? '-' }}</div>
				<div><strong>Rang :</strong> {{ $rangTexte }}</div>
			</div>
		</div>

		<div class="bottom-grid">
			<div class="cell">
				<div><strong>Avertissement pour :</strong></div>
				<div><strong>Blâme pour :</strong></div>
				<div><strong>Autres observations :</strong> {{ $bulletin->commentaire_principal ?? '-' }}</div>
			</div>
			<div class="cell">
				<div><strong>Moyenne la plus élevée de la classe :</strong> {{ $fmt($maxClasse) }} / 20</div>
				<div><strong>Moyenne la plus faible de la classe :</strong> {{ $fmt($minClasse) }} / 20</div>
				<div><strong>Moyenne de la classe :</strong> {{ $fmt($moyClasse) }} / 20</div>
			</div>
		</div>

		<div class="print-note">
			Bulletin certifié le, {{ optional($bulletin->generated_at ?? $bulletin->updated_at ?? now())->format('d/m/Y') }}
		</div>
	</section>
</body>

</html>
