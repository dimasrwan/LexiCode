<?php

namespace App\Http\Controllers;

use App\Models\Snippet;
use Illuminate\Http\Request;

class RepositoryController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->query('search');
        $language = $request->query('language');

        $snippets = Snippet::with(['module.project'])
            ->when($search, function($query, $search) {
                return $query->where('title', 'like', "%{$search}%")
                             ->orWhere('code', 'like', "%{$search}%");
            })
            ->when($language, function($query, $language) {
                return $query->where('language', $language);
            })
            ->latest()
            ->paginate(15);

        $languages = Snippet::distinct()->pluck('language');

        return view('repository.index', compact('snippets', 'languages'));
    }
}