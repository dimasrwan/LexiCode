<?php

namespace App\Http\Controllers;

use App\Models\Module;
use App\Models\Snippet;
use Illuminate\Http\Request;

class SnippetController extends Controller
{
    /**
     * Menyimpan snippet baru ke dalam modul
     */
    public function store(Request $request, Module $module)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'language' => 'required|string',
            'code' => 'required|string',
        ]);

        $module->snippets()->create($validated);

        // Pesan 'success' ini akan ditangkap oleh Alpine.js di show.blade.php
        return back()->with('success', 'New snippet added to ' . $module->title);
    }

    /**
     * Memperbarui snippet yang sudah ada
     */
    public function update(Request $request, Snippet $snippet)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'language' => 'required|string',
            'code' => 'required|string',
        ]);

        $snippet->update($validated);

        return back()->with('success', 'Changes saved successfully!');
    }

    /**
     * Menghapus snippet
     */
    public function destroy(Snippet $snippet)
    {
        $title = $snippet->title;
        $snippet->delete();
        
        return back()->with('success', 'Snippet "' . $title . '" has been deleted');
    }
}