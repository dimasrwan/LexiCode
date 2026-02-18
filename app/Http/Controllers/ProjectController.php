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
            'user_id' => 1, // Pastikan ini sesuai dengan sistem auth kamu nantinya
            'name' => $request->name,
            'slug' => Str::slug($request->name),
            'description' => $request->description,
            'tech_stack' => $request->tech_stack,
        ]);

        return redirect()->route('dashboard')->with('success', 'Project initialized successfully!');
    }

    // --- FITUR UPDATE PROJECT (Menghilangkan Error 500) ---
    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|max:255',
            'tech_stack' => 'required',
            'description' => 'required',
        ]);

        $project = Project::findOrFail($id);
        $project->update([
            'name' => $request->name,
            'slug' => Str::slug($request->name),
            'description' => $request->description,
            'tech_stack' => $request->tech_stack,
        ]);

        return back()->with('success', 'PROJECT_METADATA_UPDATED');
    }

    public function show($slug)
    {
        $project = Project::where('slug', $slug)
            ->with(['modules.snippets']) // Eager loading snippets agar tidak N+1 query
            ->firstOrFail();

        return view('projects.show', compact('project'));
    }

    // --- MODULE CONTROLS ---
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

    public function destroyModule($id)
    {
        $module = Module::findOrFail($id);
        $module->delete(); 
        return back()->with('success', 'Module and all snippets purged.');
    }

    // --- SNIPPET CONTROLS ---
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

    public function updateSnippet(Request $request, $id)
    {
        $request->validate([
            'title' => 'required',
            'code' => 'required',
            'language' => 'required'
        ]);

        $snippet = Snippet::findOrFail($id);
        $snippet->update($request->all());

        return back()->with('success', 'SNIPPET_UPDATED_SUCCESSFULLY');
    }

    public function destroySnippet($id)
    {
        $snippet = Snippet::findOrFail($id);
        $snippet->delete();

        return back()->with('success', 'Snippet purged successfully.');
    }

    // --- PROJECT DESTRUCTION ---
    public function destroy($id)
    {
        $project = Project::findOrFail($id);
        $project->delete();

        return redirect()->route('dashboard')->with('success', 'PROJECT_DELETED_FROM_SYSTEM');
    }
}