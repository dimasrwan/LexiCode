<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\Module;
use App\Models\Snippet;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ProjectController extends Controller
{
    public function index()
    {
        $projects = Project::withCount('modules')->get();
        return view('dashboard', compact('projects'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|max:255',
            'tech_stack' => 'required',
            'description' => 'required',
        ]);

        Project::create([
            'user_id' => 1, 
            'name' => $request->name,
            'slug' => Str::slug($request->name),
            'description' => $request->description,
            'tech_stack' => $request->tech_stack,
        ]);

        return redirect()->route('dashboard')->with('success', 'Project initialized successfully!');
    }

    public function storeSnippet(Request $request, $moduleId)
{
    $request->validate([
        'title' => 'required',
        'code' => 'required',
        'language' => 'required'
    ]);

    Snippet::create([
        'module_id' => $moduleId,
        'title' => $request->title,
        'code' => $request->code,
        'language' => $request->language,
    ]);

    return back()->with('success', 'SNIPPET_LOADED_TO_MODULE');
}

    public function show($slug)
    {
        // Pastikan relasi modules ada
        $project = Project::where('slug', $slug)
            ->with(['modules']) // Jika belum ada snippets, biarkan modules saja dulu
            ->firstOrFail();

        return view('projects.show', compact('project'));
    }

    // TAMBAHKAN FUNGSI INI UNTUK ADD MODULE
    public function storeModule(Request $request, $projectId)
    {
        $request->validate([
            'title' => 'required|max:255',
        ]);

        Module::create([
            'project_id' => $projectId,
            'title' => $request->title,
            'slug' => Str::slug($request->title),
            'order' => Module::where('project_id', $projectId)->count() + 1,
        ]);

        return back()->with('success', 'MODULE_INITIALIZED_SUCCESSFULLY');
    }

    public function destroy($id)
    {
        $project = Project::findOrFail($id);
        $project->delete();

        return redirect()->route('dashboard')->with('success', 'PROJECT_DELETED_FROM_SYSTEM');
    }
}