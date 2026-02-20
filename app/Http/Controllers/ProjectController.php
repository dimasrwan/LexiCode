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
     * Menampilkan dashboard dengan daftar project milik user.
     */
    public function index()
    {
        $projects = Project::where('user_id', Auth::id())
            ->withCount('modules')
            ->latest() // Menampilkan project terbaru di atas
            ->get();
            
        return view('dashboard', compact('projects'));
    }

    /**
     * Menyimpan project baru.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|max:255',
            'tech_stack' => 'required',
            'description' => 'required',
        ]);

        Project::create([
            'user_id' => Auth::id(),
            'name' => $request->name,
            // Slug unik: nama-project-randomstring
            'slug' => Str::slug($request->name) . '-' . Str::lower(Str::random(5)),
            'description' => $request->description,
            'tech_stack' => $request->tech_stack,
        ]);

        return redirect()->route('dashboard')->with('success', 'PROJECT_INITIALIZED_SUCCESSFULLY');
    }

    /**
     * Update Metadata Project.
     */
    public function update(Request $request, $id)
    {
        $project = Project::where('user_id', Auth::id())->findOrFail($id);

        $request->validate([
            'name' => 'required|max:255',
            'tech_stack' => 'required',
            'description' => 'required',
        ]);

        $data = [
            'name' => $request->name,
            'description' => $request->description,
            'tech_stack' => $request->tech_stack,
        ];

        // LOGIC: Slug hanya diperbarui jika nama project berubah
        if ($project->name !== $request->name) {
            $data['slug'] = Str::slug($request->name) . '-' . Str::lower(Str::random(5));
        }

        $project->update($data);

        return back()->with('success', 'PROJECT_METADATA_UPDATED');
    }

    /**
     * Menampilkan detail project (Modules & Snippets).
     */
    public function show($slug)
    {
        $project = Project::where('slug', $slug)
            ->where('user_id', Auth::id())
            ->with(['modules' => function($query) {
                $query->orderBy('order', 'asc'); // Urutkan module berdasarkan field order
            }, 'modules.snippets'])
            ->firstOrFail();

        return view('projects.show', compact('project'));
    }

    /**
     * MODULE CONTROLS
     */
    public function storeModule(Request $request, $projectId)
    {
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
        $module = Module::whereHas('project', function($q) {
            $q->where('user_id', Auth::id());
        })->findOrFail($id);

        $module->delete(); 
        return back()->with('success', 'MODULE_PURGED_FROM_SYSTEM');
    }

    /**
     * SNIPPET CONTROLS
     */
    public function storeSnippet(Request $request, $moduleId)
    {
        Module::whereHas('project', function($q) {
            $q->where('user_id', Auth::id());
        })->findOrFail($moduleId);

        $request->validate([
            'title' => 'required|max:255',
            'code' => 'required',
            'language' => 'required'
        ]);

        Snippet::create([
            'module_id' => $moduleId,
            'title' => $request->title,
            'code' => $request->code,
            'language' => $request->language,
        ]);

        return back()->with('success', 'SNIPPET_ENCRYPTED_AND_STORED');
    }

    public function updateSnippet(Request $request, $id)
    {
        $snippet = Snippet::whereHas('module.project', function($q) {
            $q->where('user_id', Auth::id());
        })->findOrFail($id);

        $request->validate([
            'title' => 'required|max:255',
            'code' => 'required',
            'language' => 'required'
        ]);

        $snippet->update($request->only(['title', 'code', 'language']));

        return back()->with('success', 'SNIPPET_METADATA_UPDATED');
    }

    public function destroySnippet($id)
    {
        $snippet = Snippet::whereHas('module.project', function($q) {
            $q->where('user_id', Auth::id());
        })->findOrFail($id);

        $snippet->delete();

        return back()->with('success', 'SNIPPET_PERMANENTLY_PURGED');
    }

    /**
     * Menghapus seluruh Project.
     */
    public function destroy($id)
    {
        $project = Project::where('user_id', Auth::id())->findOrFail($id);
        $project->delete();

        return redirect()->route('dashboard')->with('success', 'PROJECT_TERMINATED');
    }
}