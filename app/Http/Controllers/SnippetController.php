<?php

namespace App\Http\Controllers;

use App\Models\Module;
use App\Models\Snippet;
use Illuminate\Http\Request;

class SnippetController extends Controller
{
    // Menyimpan snippet baru ke dalam modul
    public function store(Request $request, Module $module)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'language' => 'required|string',
            'code' => 'required|string',
        ]);

        $module->snippets()->create($validated);

        return back()->with('success', 'Snippet added successfully!');
    }

    // Memperbarui snippet yang sudah ada
    public function update(Request $request, Snippet $snippet)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'language' => 'required|string',
            'code' => 'required|string',
        ]);

        $snippet->update($validated);

        return back()->with('success', 'Snippet updated successfully!');
    }

    // Menghapus snippet
    public function destroy(Snippet $snippet)
    {
        $snippet->delete();
        return back()->with('success', 'Snippet deleted successfully!');
    }
}