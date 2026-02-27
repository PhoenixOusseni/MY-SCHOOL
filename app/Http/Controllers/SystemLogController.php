<?php

namespace App\Http\Controllers;

use App\Helpers\LogHelper;
use App\Models\SystemLog;
use App\Models\User;
use Illuminate\Http\Request;

class SystemLogController extends Controller
{
    public function index(Request $request)
    {
        $query = SystemLog::with('user')->latest();

        // Filtres
        if ($request->filled('module')) {
            $query->where('module', $request->module);
        }
        if ($request->filled('action')) {
            $query->where('action', $request->action);
        }
        if ($request->filled('user_id')) {
            $query->where('user_id', $request->user_id);
        }
        if ($request->filled('date_debut')) {
            $query->whereDate('created_at', '>=', $request->date_debut);
        }
        if ($request->filled('date_fin')) {
            $query->whereDate('created_at', '<=', $request->date_fin);
        }

        $logs    = $query->paginate(50);
        $modules = SystemLog::distinct()->pluck('module')->sort()->values();
        $actions = SystemLog::distinct()->pluck('action')->sort()->values();
        $users   = User::orderBy('nom')->get();

        $stats = [
            'total'       => SystemLog::count(),
            'aujourd_hui' => SystemLog::whereDate('created_at', today())->count(),
            'cette_semaine' => SystemLog::whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()])->count(),
            'connexions'  => SystemLog::where('action', 'connexion')->count(),
        ];

        return view('pages.logs.index', compact('logs', 'modules', 'actions', 'users', 'stats'));
    }

    public function clear(Request $request)
    {
        $avant = SystemLog::count();

        if ($request->filled('avant_date')) {
            SystemLog::whereDate('created_at', '<=', $request->avant_date)->delete();
            $msg = "Logs antérieurs au {$request->avant_date} supprimés.";
        } else {
            SystemLog::truncate();
            $msg = "Tous les logs système ont été effacés ({$avant} entrées supprimées).";
        }

        // Log de l'action de purge (ce log sera enregistré après le truncate)
        LogHelper::log('purge', 'Logs Système', $msg);

        return redirect()->route('gestion_logs.index')->with('success', $msg);
    }
}
