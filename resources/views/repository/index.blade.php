<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>LexiCode</title>
    <link rel="icon" type="image/png" href="{{ asset('images/logo-lexicode.png') }}">
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=JetBrains+Mono:wght@400;700&display=swap');
        body { font-family: 'JetBrains+Mono', monospace; background: black; color: white; }
    </style>
</head>
<body class="p-8">
    <nav class="max-w-7xl mx-auto mb-12 flex justify-between items-center">
        <a href="/" class="text-zinc-500 hover:text-white text-[10px] font-black uppercase tracking-widest transition-colors">← Dashboard</a>
        <h1 class="text-xl font-black text-yellow-500 uppercase">Global Repository<span class="animate-pulse">_</span></h1>
    </nav>

    <main class="max-w-7xl mx-auto">
        <form action="{{ route('repository.index') }}" method="GET" class="mb-10 flex gap-4">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Search by title or code contents..." 
                class="flex-1 bg-zinc-900/50 border border-zinc-800 rounded-lg px-6 py-4 text-sm focus:outline-none focus:border-yellow-500/50 transition-all">
            <select name="language" onchange="this.form.submit()" class="bg-zinc-900 border border-zinc-800 rounded-lg px-6 py-4 text-sm text-yellow-500 font-bold uppercase outline-none">
                <option value="">All Languages</option>
                @foreach($languages as $lang)
                    <option value="{{ $lang }}" {{ request('language') == $lang ? 'selected' : '' }}>{{ strtoupper($lang) }}</option>
                @endforeach
            </select>
        </form>

        <div class="grid grid-cols-1 gap-4">
            @forelse($snippets as $snippet)
            <div class="bg-zinc-900/30 border border-zinc-800 p-5 rounded-xl hover:border-zinc-600 transition-all group">
                <div class="flex justify-between items-start">
                    <div>
                        <div class="flex items-center gap-3 mb-2">
                            <span class="text-[9px] font-black px-2 py-0.5 bg-yellow-500 text-black rounded uppercase">{{ $snippet->language }}</span>
                            <h3 class="font-bold text-zinc-200 group-hover:text-yellow-400 transition-colors">{{ $snippet->title }}</h3>
                        </div>
                        <p class="text-[10px] text-zinc-500 uppercase tracking-tight">
                            Project: <span class="text-zinc-300">{{ $snippet->module->project->name }}</span> 
                            <span class="mx-2 text-zinc-700">/</span> 
                            Module: <span class="text-zinc-300">{{ $snippet->module->title }}</span>
                        </p>
                    </div>
                    <a href="{{ route('projects.show', $snippet->module->project->slug) }}#module-{{ $snippet->module_id }}" 
                    class="text-[10px] font-black text-zinc-600 hover:text-yellow-500 uppercase">View Details >></a>
                </div>
            </div>
            @empty
            <div class="text-center py-20 border-2 border-dashed border-zinc-900 rounded-2xl">
                <p class="text-zinc-600 italic">No snippets found in the repository.</p>
            </div>
            @endforelse
        </div>
        <div class="mt-8">{{ $snippets->links() }}</div>
    </main>
</body>
</html>