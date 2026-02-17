<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $project->name }} | LexiCode</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=JetBrains+Mono:wght@400;700&display=swap');
        body { font-family: 'JetBrains+Mono', monospace; background: black; color: white; }
    </style>
</head>
<body x-data="{ openAdd: false }">
    <nav class="border-b border-yellow-500/20 py-4 px-6 bg-zinc-950/50 backdrop-blur-md sticky top-0 z-50">
        <div class="max-w-7xl mx-auto flex justify-between items-center">
            <a href="{{ route('dashboard') }}" class="text-zinc-500 hover:text-yellow-400 text-xs font-bold uppercase tracking-widest"><< BACK_TO_DASHBOARD</a>
            <span class="text-yellow-500 font-black tracking-tighter uppercase">{{ $project->name }}</span>
        </div>
    </nav>

    <main class="max-w-4xl mx-auto py-12 px-6">
        <header class="mb-12 border-l-4 border-yellow-500 pl-6">
            <h1 class="text-3xl font-black uppercase tracking-tighter">{{ $project->name }}<span class="text-yellow-500">_</span></h1>
            <p class="text-zinc-500 text-sm mt-2">{{ $project->description }}</p>
            <div class="inline-block mt-4 bg-yellow-500 text-black text-[10px] font-black px-2 py-0.5 rounded uppercase">{{ $project->tech_stack }}</div>
        </header>

        <div class="flex justify-between items-center mb-6">
            <h2 class="text-sm font-black text-zinc-400 uppercase tracking-widest">Modules_List</h2>
            <button @click="openAdd = true" class="text-xs bg-zinc-900 border border-zinc-800 hover:border-yellow-500 px-4 py-2 rounded text-zinc-400 hover:text-yellow-500 transition-all">+ ADD_MODULE</button>
        </div>

        <div class="space-y-3">
            @forelse($project->modules as $index => $module)
                <div class="bg-zinc-900/50 border border-zinc-800 p-4 rounded-lg flex justify-between items-center group hover:border-zinc-700 transition-all cursor-pointer">
                    <div class="flex items-center gap-4">
                        <span class="text-zinc-700 font-bold">0{{ $index + 1 }}</span>
                        <span class="font-bold text-zinc-300 group-hover:text-yellow-500 uppercase">{{ $module->title }}</span>
                    </div>
                    <span class="text-zinc-600 group-hover:text-zinc-400">EDIT_CONTENT >></span>
                </div>
            @empty
                <div class="border-2 border-dashed border-zinc-900 p-10 text-center rounded-xl text-zinc-600 italic text-sm">
                    No modules found. System idle.
                </div>
            @endforelse
        </div>
    </main>

    <div x-show="openAdd" class="fixed inset-0 z-[60] flex items-center justify-center p-4 bg-black/90 backdrop-blur-sm" style="display: none;">
        <div @click.away="openAdd = false" class="bg-zinc-950 border border-yellow-500/30 w-full max-w-sm p-8 rounded-2xl">
            <h2 class="text-lg font-black text-yellow-400 uppercase tracking-tighter mb-6">Initialize_New_Module</h2>
            <form action="{{ route('modules.store', $project->id) }}" method="POST">
                @csrf
                <input type="text" name="title" required placeholder="Module Title (e.g. Auth System)" class="w-full bg-zinc-900 border border-zinc-800 rounded-lg px-4 py-3 text-sm text-white focus:outline-none focus:border-yellow-500 mb-6">
                <button type="submit" class="w-full bg-yellow-500 text-black font-black py-3 rounded-lg uppercase text-xs tracking-widest hover:bg-white transition-all">COMMIT_MODULE</button>
            </form>
        </div>
    </div>
</body>
</html>