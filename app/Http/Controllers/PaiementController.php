<?php

namespace App\Http\Controllers;

use App\Models\Paiement;
use App\Models\Eleve;
use App\Models\FraiScolarite;
use App\Models\AnneeScolaire;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PaiementController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $paiements = Paiement::with('eleve', 'fraiScolarite', 'anneeScolaire', 'receivedBy')->orderBy('date_paiement', 'desc')->paginate(15);

        return view('pages.paiements.index', compact('paiements'));
    }

    public function create()
    {
        $eleves = Eleve::orderBy('nom')->orderBy('prenom')->get();
        $frais = FraiScolarite::orderBy('libelle')->get();
        $annees = AnneeScolaire::orderBy('libelle', 'desc')->get();
        $users = User::orderBy('nom')->get();
        $methodes = ['Especes' => 'Espèces', 'cheque' => 'Chèque', 'virement' => 'Virement', 'mobile_money' => 'Mobile Money', 'carte' => 'Carte'];
        $statuses = ['En attente' => 'En attente', 'Terminé' => 'Terminé', 'Annulé' => 'Annulé', 'Remboursé' => 'Remboursé'];

        return view('pages.paiements.create', compact('eleves', 'frais', 'annees', 'users', 'methodes', 'statuses'));
    }

    public function store(Request $request)
    {
        try {
            $request->validate([
                'montant' => 'required|numeric|min:0',
                'date_paiement' => 'required|date',
                'methode_paiement' => 'required|in:Especes,cheque,virement,mobile_money,carte',
                'reference' => 'nullable|string|max:255',
                'status' => 'required|in:En attente,Terminé,Annulé,Remboursé',
                'notes' => 'nullable|string',
                'eleve_id' => 'required|exists:eleves,id',
                'frai_scolarite_id' => 'required|exists:frai_scolarites,id',
                'annee_scolaire_id' => 'required|exists:annee_scolaires,id',
                'received_by' => 'nullable|exists:users,id',
            ]);

            $data = $request->all();
            $data['received_by'] = $request->received_by ?? Auth::id();

            $frais = FraiScolarite::findOrFail($request->frai_scolarite_id);
            $data['reste_a_payer'] = $frais->montant - $request->montant;

            Paiement::create($data);

            return redirect()->route('gestion_paiements.index')->with('success', 'Paiement enregistré avec succès.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Erreur : ' . $e->getMessage())
                ->withInput();
        }
    }

    public function show(string $id)
    {
        $paiement = Paiement::with('eleve', 'fraiScolarite', 'anneeScolaire', 'receivedBy')->findOrFail($id);

        // Historique des paiements pour le même élève / frais / année
        $historique = Paiement::with('receivedBy')
            ->where('eleve_id', $paiement->eleve_id)
            ->where('frai_scolarite_id', $paiement->frai_scolarite_id)
            ->where('annee_scolaire_id', $paiement->annee_scolaire_id)
            ->orderBy('date_paiement', 'asc')
            ->get();

        $methodes = ['Especes' => 'Espèces', 'cheque' => 'Chèque', 'virement' => 'Virement', 'mobile_money' => 'Mobile Money', 'carte' => 'Carte'];

        return view('pages.paiements.show', compact('paiement', 'historique', 'methodes'));
    }

    public function solder(Request $request, string $id)
    {
        $paiement = Paiement::with('fraiScolarite')->findOrFail($id);

        if ($paiement->reste_a_payer <= 0) {
            return redirect()->back()->with('error', 'Ce paiement est déjà soldé.');
        }

        try {
            $request->validate([
                'montant'          => 'required|numeric|min:0.01|max:' . $paiement->reste_a_payer,
                'methode_paiement' => 'required|in:Especes,cheque,virement,mobile_money,carte',
                'date_paiement'    => 'required|date',
                'reference'        => 'nullable|string|max:255',
                'notes'            => 'nullable|string',
            ]);

            $nouveauReste  = $paiement->reste_a_payer - $request->montant;
            $nouveauStatut = $nouveauReste <= 0 ? 'Terminé' : 'En attente';

            // Créer le versement complémentaire
            $nouveauPaiement = Paiement::create([
                'eleve_id'          => $paiement->eleve_id,
                'frai_scolarite_id' => $paiement->frai_scolarite_id,
                'annee_scolaire_id' => $paiement->annee_scolaire_id,
                'montant'           => $request->montant,
                'reste_a_payer'     => max(0, $nouveauReste),
                'date_paiement'     => $request->date_paiement,
                'methode_paiement'  => $request->methode_paiement,
                'reference'         => $request->reference,
                'notes'             => $request->notes ?? 'Versement complémentaire — paiement N°' . $paiement->id,
                'status'            => $nouveauStatut,
                'received_by'       => Auth::id(),
            ]);

            // Marquer l'original comme soldé (reste = 0)
            $paiement->update(['reste_a_payer' => 0, 'status' => 'Terminé']);

            $msg = $nouveauReste <= 0
                ? 'Versement enregistré. Le paiement est entièrement soldé.'
                : 'Versement enregistré. Reste à payer : ' . number_format(max(0, $nouveauReste), 0, ',', ' ') . ' ' . ($paiement->fraiScolarite->devise ?? 'XOF') . '.';

            return redirect()->route('gestion_paiements.show', $nouveauPaiement->id)
                ->with('success', $msg);
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Erreur : ' . $e->getMessage())->withInput();
        }
    }

    public function edit(string $id)
    {
        $paiement = Paiement::findOrFail($id);
        $eleves = Eleve::orderBy('nom')->orderBy('prenom')->get();
        $frais = FraiScolarite::orderBy('libelle')->get();
        $annees = AnneeScolaire::orderBy('libelle', 'desc')->get();
        $users = User::orderBy('nom')->get();
        $methodes = ['Especes' => 'Espèces', 'cheque' => 'Chèque', 'virement' => 'Virement', 'mobile_money' => 'Mobile Money', 'carte' => 'Carte'];
        $statuses = ['En attente' => 'En attente', 'Terminé' => 'Terminé', 'Annulé' => 'Annulé', 'Remboursé' => 'Remboursé'];

        return view('pages.paiements.edit', compact('paiement', 'eleves', 'frais', 'annees', 'users', 'methodes', 'statuses'));
    }

    public function update(Request $request, string $id)
    {
        $paiement = Paiement::findOrFail($id);

        try {
            $request->validate([
                'montant' => 'required|numeric|min:0',
                'date_paiement' => 'required|date',
                'methode_paiement' => 'required|in:Especes,cheque,virement,mobile_money,carte',
                'reference' => 'nullable|string|max:255',
                'status' => 'required|in:En attente,Terminé,Annulé,Remboursé',
                'notes' => 'nullable|string',
                'eleve_id' => 'required|exists:eleves,id',
                'frai_scolarite_id' => 'required|exists:frai_scolarites,id',
                'annee_scolaire_id' => 'required|exists:annee_scolaires,id',
                'received_by' => 'nullable|exists:users,id',
            ]);

            $data = $request->all();

            $frais = FraiScolarite::findOrFail($request->frai_scolarite_id);
            $data['reste_a_payer'] = $frais->montant - $request->montant;

            $paiement->update($data);

            return redirect()->route('gestion_paiements.show', $paiement->id)->with('success', 'Paiement mis à jour avec succès.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Erreur : ' . $e->getMessage())->withInput();
        }
    }

    public function printReceipt(string $id)
    {
        $paiement = Paiement::with('eleve', 'fraiScolarite', 'anneeScolaire', 'receivedBy')->findOrFail($id);

        return view('pages.paiements.print', compact('paiement'));
    }

    public function situationFinanciere()
    {
        $annees    = AnneeScolaire::orderBy('libelle', 'desc')->get();
        $anneeId   = request('annee_id');

        $base = Paiement::query();
        if ($anneeId) {
            $base->where('annee_scolaire_id', $anneeId);
        }

        $totalEncaisse   = (clone $base)->where('status', 'Terminé')->sum('montant');
        $totalEnAttente  = (clone $base)->where('status', 'En attente')->sum('montant');
        $totalResteAPayer = (clone $base)->whereNotIn('status', ['Annulé', 'Remboursé'])->sum('reste_a_payer');
        $nbPaiements     = (clone $base)->count();
        $nbEleves        = (clone $base)->distinct('eleve_id')->count('eleve_id');

        $parFrais = (clone $base)
            ->with('fraiScolarite')
            ->selectRaw('frai_scolarite_id,
                SUM(montant) as total_paye,
                SUM(reste_a_payer) as total_reste,
                COUNT(*) as nb_paiements,
                COUNT(DISTINCT eleve_id) as nb_eleves')
            ->groupBy('frai_scolarite_id')
            ->get();

        $parEleve = (clone $base)
            ->with('eleve')
            ->whereNotIn('status', ['Annulé', 'Remboursé'])
            ->selectRaw('eleve_id,
                MAX(id) as dernier_paiement_id,
                SUM(montant) as total_paye,
                SUM(reste_a_payer) as total_reste,
                COUNT(*) as nb_paiements')
            ->groupBy('eleve_id')
            ->orderByDesc('total_reste')
            ->paginate(20);

        return view('pages.paiements.situation_financiere', compact(
            'annees', 'anneeId',
            'totalEncaisse', 'totalEnAttente', 'totalResteAPayer',
            'nbPaiements', 'nbEleves',
            'parFrais', 'parEleve'
        ));
    }

    public function destroy(string $id)
    {
        try {
            $paiement = Paiement::findOrFail($id);
            $paiement->delete();

            return redirect()->route('gestion_paiements.index')->with('success', 'Paiement supprimé avec succès.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Erreur lors de la suppression : ' . $e->getMessage());
        }
    }
}
