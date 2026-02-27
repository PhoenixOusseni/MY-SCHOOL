<?php

namespace App\Http\Controllers;

use App\Helpers\LogHelper;
use App\Models\AnneeScolaire;
use App\Models\Classe;
use App\Models\EmploiTemp;
use App\Models\EnseignementMatiereClasse;
use Illuminate\Http\Request;

class EmploiTempController extends Controller
{
    /* ═══════════════════════════════════════════════════════════
     |  GRILLE HEBDOMADAIRE
     ═══════════════════════════════════════════════════════════ */

    public function index(Request $request)
    {
        $annees   = AnneeScolaire::orderByDesc('id')->get();
        $annee    = $request->integer('annee_id')
            ? AnneeScolaire::find($request->integer('annee_id'))
            : (AnneeScolaire::where('is_current', true)->first() ?? AnneeScolaire::latest('id')->first());

        $classes  = $annee
            ? Classe::where('annee_scolaire_id', $annee->id)->get()
            : collect();

        $classeId = $request->integer('classe_id') ?: ($classes->first()?->id);
        $classe   = $classeId ? Classe::find($classeId) : null;

        // Grille : créneaux organisés par jour
        $grille = [];
        foreach (EmploiTemp::JOURS as $jour) {
            $grille[$jour] = [];
        }

        $creneaux = collect();
        if ($annee && $classeId) {
            $creneaux = EmploiTemp::where('annee_scolaire_id', $annee->id)
                ->whereHas('enseignementMatiereClasse', fn($q) => $q->where('classe_id', $classeId))
                ->with([
                    'enseignementMatiereClasse.matiere',
                    'enseignementMatiereClasse.enseignant',
                ])
                ->orderBy('heure_debut')
                ->get();

            foreach ($creneaux as $c) {
                if (isset($grille[$c->jour_semaine])) {
                    $grille[$c->jour_semaine][] = $c;
                }
            }
        }

        return view('pages.emploi_temps.index', compact(
            'annees', 'annee', 'classes', 'classeId', 'classe', 'grille', 'creneaux'
        ));
    }

    /* ═══════════════════════════════════════════════════════════
     |  CRÉATION
     ═══════════════════════════════════════════════════════════ */

    public function create(Request $request)
    {
        $annees  = AnneeScolaire::orderByDesc('id')->get();
        $anneeId = $request->integer('annee_id')
            ?: (AnneeScolaire::where('is_current', true)->first()?->id ?? $annees->first()?->id);

        $classes = $anneeId
            ? Classe::where('annee_scolaire_id', $anneeId)->get()
            : collect();

        $classeId = $request->integer('classe_id') ?: ($classes->first()?->id);

        $emcParClasse = $anneeId
            ? EnseignementMatiereClasse::where('annee_scolaire_id', $anneeId)
                ->with(['matiere', 'enseignant'])
                ->get()
                ->groupBy('classe_id')
            : collect();

        return view('pages.emploi_temps.create', compact(
            'annees', 'anneeId', 'classes', 'classeId', 'emcParClasse'
        ));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'annee_scolaire_id'              => 'required|exists:annee_scolaires,id',
            'enseignement_matiere_classe_id'  => 'required|exists:enseignement_matiere_classes,id',
            'jour_semaine'                    => 'required|in:' . implode(',', EmploiTemp::JOURS),
            'heure_debut'                    => 'required|date_format:H:i',
            'heure_fin'                      => 'required|date_format:H:i|after:heure_debut',
            'salle'                          => 'nullable|string|max:50',
            'effective_from'                 => 'nullable|date',
            'effective_to'                   => 'nullable|date|after_or_equal:effective_from',
        ]);

        $classeId = EnseignementMatiereClasse::find($data['enseignement_matiere_classe_id'])->classe_id;
        $conflit  = $this->verifierConflit(
            $data['annee_scolaire_id'], $classeId,
            $data['jour_semaine'], $data['heure_debut'], $data['heure_fin']
        );
        if ($conflit) {
            $emc = $conflit->enseignementMatiereClasse;
            return back()->withInput()->with('error',
                "Conflit d'horaire : un créneau {$conflit->heure_debut}–{$conflit->heure_fin} ({$emc?->matiere?->intitule}) existe déjà ce jour."
            );
        }

        EmploiTemp::create($data);
        LogHelper::log('création', 'Emploi du temps', "Créneau ajouté : {$data['jour_semaine']} {$data['heure_debut']}–{$data['heure_fin']}");

        return redirect()
            ->route('gestion_emploi_temps.index', ['annee_id' => $data['annee_scolaire_id'], 'classe_id' => $classeId])
            ->with('success', 'Créneau ajouté avec succès.');
    }

    /* ═══════════════════════════════════════════════════════════
     |  MODIFICATION
     ═══════════════════════════════════════════════════════════ */

    public function edit(string $id)
    {
        $creneau  = EmploiTemp::with('enseignementMatiereClasse.classe')->findOrFail($id);
        $annees   = AnneeScolaire::orderByDesc('id')->get();
        $anneeId  = $creneau->annee_scolaire_id;
        $classeId = $creneau->enseignementMatiereClasse->classe_id;

        $classes = Classe::where('annee_scolaire_id', $anneeId)->get();

        $emcParClasse = EnseignementMatiereClasse::where('annee_scolaire_id', $anneeId)
            ->with(['matiere', 'enseignant'])
            ->get()
            ->groupBy('classe_id');

        return view('pages.emploi_temps.edit', compact(
            'creneau', 'annees', 'anneeId', 'classes', 'classeId', 'emcParClasse'
        ));
    }

    public function update(Request $request, string $id)
    {
        $creneau = EmploiTemp::findOrFail($id);

        $data = $request->validate([
            'annee_scolaire_id'              => 'required|exists:annee_scolaires,id',
            'enseignement_matiere_classe_id'  => 'required|exists:enseignement_matiere_classes,id',
            'jour_semaine'                    => 'required|in:' . implode(',', EmploiTemp::JOURS),
            'heure_debut'                    => 'required|date_format:H:i',
            'heure_fin'                      => 'required|date_format:H:i|after:heure_debut',
            'salle'                          => 'nullable|string|max:50',
            'effective_from'                 => 'nullable|date',
            'effective_to'                   => 'nullable|date|after_or_equal:effective_from',
        ]);

        $classeId = EnseignementMatiereClasse::find($data['enseignement_matiere_classe_id'])->classe_id;
        $conflit  = $this->verifierConflit(
            $data['annee_scolaire_id'], $classeId,
            $data['jour_semaine'], $data['heure_debut'], $data['heure_fin'], $id
        );
        if ($conflit) {
            $emc = $conflit->enseignementMatiereClasse;
            return back()->withInput()->with('error',
                "Conflit d'horaire : un créneau {$conflit->heure_debut}–{$conflit->heure_fin} ({$emc?->matiere?->intitule}) existe déjà ce jour."
            );
        }

        $creneau->update($data);
        LogHelper::log('modification', 'Emploi du temps', "Créneau modifié (ID {$id}) : {$data['jour_semaine']} {$data['heure_debut']}–{$data['heure_fin']}");

        return redirect()
            ->route('gestion_emploi_temps.index', ['annee_id' => $data['annee_scolaire_id'], 'classe_id' => $classeId])
            ->with('success', 'Créneau mis à jour avec succès.');
    }

    /* ═══════════════════════════════════════════════════════════
     |  SUPPRESSION
     ═══════════════════════════════════════════════════════════ */

    public function destroy(string $id)
    {
        $creneau  = EmploiTemp::with('enseignementMatiereClasse')->findOrFail($id);
        $anneeId  = $creneau->annee_scolaire_id;
        $classeId = $creneau->enseignementMatiereClasse->classe_id;
        $label    = "{$creneau->jour_semaine} {$creneau->heure_debut}–{$creneau->heure_fin}";

        $creneau->delete();
        LogHelper::log('suppression', 'Emploi du temps', "Créneau supprimé : {$label}");

        return redirect()
            ->route('gestion_emploi_temps.index', ['annee_id' => $anneeId, 'classe_id' => $classeId])
            ->with('success', "Créneau « {$label} » supprimé.");
    }

    /* ═══════════════════════════════════════════════════════════
     |  HELPER : vérification de conflit d'horaire
     ═══════════════════════════════════════════════════════════ */

    private function verifierConflit(
        int $anneeId, int $classeId, string $jour,
        string $debut, string $fin, ?string $excludeId = null
    ): ?EmploiTemp {
        return EmploiTemp::where('annee_scolaire_id', $anneeId)
            ->where('jour_semaine', $jour)
            ->whereHas('enseignementMatiereClasse', fn($q) => $q->where('classe_id', $classeId))
            ->when($excludeId, fn($q) => $q->where('id', '!=', $excludeId))
            ->where(fn($q) => $q
                ->whereBetween('heure_debut', [$debut, $fin])
                ->orWhereBetween('heure_fin',  [$debut, $fin])
                ->orWhere(fn($q2) => $q2->where('heure_debut', '<=', $debut)->where('heure_fin', '>=', $fin))
            )
            ->with('enseignementMatiereClasse.matiere')
            ->first();
    }
}

