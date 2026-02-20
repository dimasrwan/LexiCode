<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\Snippet;
use Illuminate\Support\Facades\DB;

class AnalyticsController extends Controller
{
    public function index()
    {
        $totalProjects = Project::count();
        $totalSnippets = Snippet::count();
        
        // Data untuk chart bahasa (Pie/Bar)
        $languageStats = Snippet::select('language', DB::raw('count(*) as total'))
            ->groupBy('language')
            ->orderBy('total', 'desc')
            ->get();

        // Data aktivitas (7 hari terakhir)
        $activity = Snippet::select(DB::raw('DATE(created_at) as date'), DB::raw('count(*) as total'))
            ->where('created_at', '>=', now()->subDays(7))
            ->groupBy('date')
            ->get();

        return view('analytics.index', compact('totalProjects', 'totalSnippets', 'languageStats', 'activity'));
    }
}