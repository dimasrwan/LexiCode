<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $project->name }} - LexiCode</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=JetBrains+Mono:wght@400;700&display=swap');
        body { font-family: 'JetBrains+Mono', monospace; }
        .code-shadow { box-shadow: 0 0 20px rgba(234, 179, 8, 0.1); }
    </style>
</head>
<body class="bg-black text-zinc-300">
    <div class="flex h-screen">
        <aside class="w-64 border-r border-yellow-500/20 bg-zinc-950 p-6 overflow-y-auto">
            <a href="{{ route('dashboard') }}" class="text-yellow-500 text-xs font-bold mb-8 block hover:underline"><< BACK TO DASHBOARD</a>
            <h3 class="text-white font-bold text-sm mb-6 uppercase tracking-widest">Modules</h3>
            <nav class="space-y-4">
                @foreach($project->modules as $module)
                    <div class="text-sm">
                        <p class="text-yellow-500 font-bold mb-2">{{ $module->title }}</p>
                        <ul class="pl-3 border-l border-zinc-800 space-y-2">
                            @foreach($module->snippets as $snippet)
                                <li><a href="#snippet-{{ $snippet->id }}" class="hover:text-white transition-colors text-xs text-zinc-500">- {{ $snippet->title }}</a></li>
                            @endforeach
                        </ul>
                    </div>
                @endforeach
            </nav>
        </aside>

        <main class="flex-1 overflow-y-auto p-12 bg-zinc-900/20">
            <header class="mb-12 border-b border-zinc-800 pb-8">
                <span class="bg-yellow-500 text-black text-[10px] font-black px-2 py-1 rounded mb-4 inline-block uppercase">{{ $project->tech_stack }}</span>
                <h1 class="text-5xl font-black text-white italic tracking-tighter">{{ $project->name }}</h1>
                <p class="mt-4 text-zinc-500 max-w-2xl leading-relaxed">{{ $project->description }}</p>
            </header>

            <section class="space-y-16">
                @foreach($project->modules as $module)
                    @foreach($module->snippets as $snippet)
                    <div id="snippet-{{ $snippet->id }}" class="scroll-mt-10">
                        <h2 class="text-2xl font-bold text-white mb-4 border-l-4 border-yellow-500 pl-4">{{ $snippet->title }}</h2>
                        
                        <div class="bg-zinc-950 border border-zinc-800 rounded-lg overflow-hidden code-shadow mb-6">
                            <div class="bg-zinc-900/50 px-4 py-2 border-b border-zinc-800 flex justify-between items-center text-[10px] text-zinc-500">
                                <span>LANGUAGE: {{ strtoupper($snippet->language) }}</span>
                                <span class="text-yellow-500/50 italic font-bold">LEXICODE_SECURE_VIEW</span>
                            </div>
                            <pre class="p-6 text-yellow-100 overflow-x-auto text-sm leading-relaxed"><code>{{ $snippet->code_block }}</code></pre>
                        </div>

                        <div class="bg-yellow-500/5 border border-yellow-500/20 p-6 rounded-lg">
                            <h4 class="text-yellow-500 text-xs font-black uppercase mb-3 tracking-widest">// Human Explanation</h4>
                            <p class="text-zinc-400 text-sm leading-relaxed italic">
                                "{{ $snippet->human_explanation }}"
                            </p>
                        </div>
                    </div>
                    @endforeach
                @endforeach
            </section>
        </main>
    </div>
</body>
</html>