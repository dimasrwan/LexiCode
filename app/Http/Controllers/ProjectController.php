<?php

namespace App\Http\Controllers;

use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Str; // Penting untuk fungsi Str::slug()

class ProjectController extends Controller
{
    /**
     * Menampilkan daftar proyek di dashboard utama.
     */
    public function index()
    {
        // Mengambil semua proyek dan menghitung jumlah modul di dalamnya
        $projects = Project::withCount('modules')->get();

        return view('dashboard', compact('projects'));
    }

    /**
     * Menyimpan proyek baru ke database.
     */
    public function store(Request $request)
    {
        // 1. Validasi input agar data tidak kosong
        $request->validate([
            'name' => 'required|max:255',
            'tech_stack' => 'required',
            'description' => 'required',
        ]);

        // 2. Simpan data ke database
        Project::create([
            'user_id' => 1, // Sementara hardcode, nanti bisa diganti auth()->id()
            'name' => $request->name,
            'slug' => Str::slug($request->name), // Mengubah "Project Satu" jadi "project-satu"
            'description' => $request->description,
            'tech_stack' => $request->tech_stack,
        ]);

        // 3. Kembali ke dashboard dengan pesan sukses
        return redirect()->route('dashboard')->with('success', 'Project initialized successfully!');
    }

    /**
     * Menampilkan detail proyek.
     */
    public function show($slug)
    {
        // Mengambil proyek berdasarkan slug beserta modul dan snippet-nya
        $project = Project::where('slug', $slug)
            ->with(['modules.snippets'])
            ->firstOrFail();

        return view('projects.show', compact('project'));
    }

    public function destroy($id)
{
    $project = Project::findOrFail($id);
    $project->delete();

    return redirect()->route('dashboard')->with('success', 'PROJECT_DELETED_FROM_SYSTEM');
}

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

    return redirect()->route('dashboard')->with('success', 'REPOSITORY_UPDATED_SUCCESSFULLY');
}
}