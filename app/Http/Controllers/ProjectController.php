<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\Module;
use App\Models\Snippet;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;

class ProjectController extends Controller
{
    /**
     * Menampilkan hanya project milik user yang sedang login.
     */
    public function index()
    {
        $projects = Project::where('user_id', Auth::id())
            ->withCount('modules')
            ->get();
            
        return view('dashboard', compact('projects'));
    }

    /**
     * Menyimpan project baru dengan mengaitkan ke ID user login.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|max:255',
            'tech_stack' => 'required',
            'description' => 'required',
        ]);

        Project::create([
            'user_id' => Auth::id(), // Sekarang dinamis
            'name' => $request->name,
            'slug' => Str::slug($request->name) . '-' . Str::random(5), // Tambah random agar slug unik jika nama sama
            'description' => $request->description,
            'tech_stack' => $request->tech_stack,
        ]);

        return redirect()->route('dashboard')->with('success', 'Project initialized successfully!');
    }

    /**
     * Update Metadata Project (Hanya jika milik sendiri).
     */
    public function update(Request $request, $id)
    {
        $project = Project::where('user_id', Auth::id())->findOrFail($id);

        $request->validate([
            'name' => 'required|max:255',
            'tech_stack' => 'required',
            'description' => 'required',
        ]);

        $project->update([
            'name' => $request->name,
            'slug' => Str::slug($request->name) . '-' . Str::random(5),
            'description' => $request->description,
            'tech_stack' => $request->tech_stack,
        ]);

        return back()->with('success', 'PROJECT_METADATA_UPDATED');
    }

    /**
     * Menampilkan detail project (Hanya jika milik sendiri).
     */
    public function show($slug)
    {
        $project = Project::where('slug', $slug)
            ->where('user_id', Auth::id()) // Proteksi agar tidak bisa intip via URL
            ->with(['modules.snippets'])
            ->firstOrFail();

        return view('projects.show', compact('project'));
    }

    /**
     * MODULE CONTROLS - Dengan validasi kepemilikan project
     */
    public function storeModule(Request $request, $projectId)
    {
        // Pastikan project yang akan ditambah module adalah milik user ini
        Project::where('user_id', Auth::id())->findOrFail($projectId);

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
        // Menggunakan relasi untuk memastikan module yang dihapus ada dalam project milik user
        $module = Module::whereHas('project', function($q) {
            $q->where('user_id', Auth::id());
        })->findOrFail($id);

        $module->delete(); 
        return back()->with('success', 'Module and all snippets purged.');
    }

    /**
     * SNIPPET CONTROLS - Dengan validasi kepemilikan lewat module
     */
    public function storeSnippet(Request $request, $moduleId)
    {
        // Pastikan module milik project milik user login
        Module::whereHas('project', function($q) {
            $q->where('user_id', Auth::id());
        })->findOrFail($moduleId);

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
        $snippet = Snippet::whereHas('module.project', function($q) {
            $q->where('user_id', Auth::id());
        })->findOrFail($id);

        $request->validate([
            'title' => 'required',
            'code' => 'required',
            'language' => 'required'
        ]);

        $snippet->update($request->all());

        return back()->with('success', 'SNIPPET_UPDATED_SUCCESSFULLY');
    }

    public function destroySnippet($id)
    {
        $snippet = Snippet::whereHas('module.project', function($q) {
            $q->where('user_id', Auth::id());
        })->findOrFail($id);

        $snippet->delete();

        return back()->with('success', 'Snippet purged successfully.');
    }

    /**
     * PROJECT DESTRUCTION
     */
    public function destroy($id)
    {
        $project = Project::where('user_id', Auth::id())->findOrFail($id);
        $project->delete();

        return redirect()->route('dashboard')->with('success', 'PROJECT_DELETED_FROM_SYSTEM');
    }
}