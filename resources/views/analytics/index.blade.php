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
        body { font-family: 'JetBrains+Mono', monospace; background: #050505; color: white; }
    </style>
</head>
<body class="p-8">
    <nav class="max-w-6xl mx-auto mb-16 flex justify-between items-center">
        <a href="/" class="text-zinc-500 hover:text-white text-[10px] font-black uppercase tracking-widest">← Back</a>
        <h1 class="text-xl font-black text-yellow-500 uppercase tracking-tighter">System_Insights</h1>
    </nav>

    <main class="max-w-6xl mx-auto">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-12">
            <div class="bg-zinc-900/50 border border-zinc-800 p-8 rounded-2xl shadow-xl">
                <p class="text-zinc-500 text-[10px] font-black uppercase tracking-[0.2em] mb-2">Total Projects</p>
                <h2 class="text-5xl font-bold tracking-tighter text-yellow-500">{{ $totalProjects }}</h2>
            </div>
            <div class="bg-zinc-900/50 border border-zinc-800 p-8 rounded-2xl shadow-xl">
                <p class="text-zinc-500 text-[10px] font-black uppercase tracking-[0.2em] mb-2">Total Snippets</p>
                <h2 class="text-5xl font-bold tracking-tighter text-white">{{ $totalSnippets }}</h2>
            </div>
            <div class="bg-zinc-900/50 border border-zinc-800 p-8 rounded-2xl shadow-xl">
                <p class="text-zinc-500 text-[10px] font-black uppercase tracking-[0.2em] mb-2">System Health</p>
                <h2 class="text-5xl font-bold tracking-tighter text-emerald-500 underline decoration-zinc-800">100%</h2>
            </div>
        </div>

        <div class="bg-zinc-900/30 border border-zinc-800 p-10 rounded-3xl">
            <h3 class="text-[10px] font-black uppercase tracking-[0.3em] text-zinc-600 mb-10">Language_Distribution</h3>
            <div class="space-y-8">
                @foreach($languageStats as $stat)
                @php $percentage = ($stat->total / $totalSnippets) * 100; @endphp
                <div>
                    <div class="flex justify-between text-[11px] font-bold uppercase mb-2">
                        <span>{{ $stat->language }}</span>
                        <span class="text-zinc-500">{{ $stat->total }} Snippets ({{ round($percentage) }}%)</span>
                    </div>
                    <div class="w-full bg-zinc-950 h-1.5 rounded-full overflow-hidden border border-zinc-900">
                        <div class="bg-yellow-500 h-full rounded-full" style="width: {{ $percentage }}%"></div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </main>
</body>
</html>