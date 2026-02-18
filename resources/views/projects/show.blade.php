<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $project->name }} | LexiCode</title>
    
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/@alpinejs/collapse@3.x.x/dist/cdn.min.js"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    
    <link href="https://cdnjs.cloudflare.com/ajax/libs/prism/1.29.0/themes/prism-tomorrow.min.css" rel="stylesheet" />
    
    <style>
        @import url('https://fonts.googleapis.com/css2?family=JetBrains+Mono:wght@400;700&display=swap');
        body { font-family: 'JetBrains+Mono', monospace; background: black; color: white; }
        [x-cloak] { display: none !important; }
        
        /* Custom scrollbar area kode */
        pre::-webkit-scrollbar { height: 4px; }
        pre::-webkit-scrollbar-thumb { background: #333; border-radius: 10px; }
        pre::-webkit-scrollbar-thumb:hover { background: #eab308; }

        /* Override Prism agar match dengan UI */
        pre[class*="language-"] {
            background: #09090b !important; /* zinc-950 */
            border: 1px solid #27272a !important; /* zinc-800 */
            margin: 0 !important;
            padding: 1.25rem !important;
            font-size: 0.75rem !important;
            border-radius: 0.5rem;
        }
    </style>
</head>
<body x-data="{ openAdd: false }">

    <nav class="border-b border-yellow-500/20 py-4 px-6 bg-zinc-950/50 backdrop-blur-md sticky top-0 z-50">
        <div class="max-w-7xl mx-auto flex justify-between items-center">
            <a href="{{ route('dashboard') }}" class="text-zinc-500 hover:text-yellow-400 text-xs font-bold uppercase tracking-widest transition-colors"><< BACK_TO_DASHBOARD</a>
            <span class="text-yellow-500 font-black tracking-tighter uppercase">{{ $project->name }}</span>
        </div>
    </nav>

    <main class="max-w-4xl mx-auto py-12 px-6">
        <header class="mb-12 border-l-4 border-yellow-500 pl-6">
            <h1 class="text-3xl font-black uppercase tracking-tighter">{{ $project->name }}<span class="text-yellow-500">_</span></h1>
            <p class="text-zinc-500 text-sm mt-2">{{ $project->description }}</p>
            <div class="inline-block mt-4 bg-yellow-500 text-black text-[10px] font-black px-2 py-0.5 rounded uppercase tracking-tighter">
                {{ $project->tech_stack }}
            </div>
        </header>

        <div class="flex justify-between items-center mb-6">
            <h2 class="text-sm font-black text-zinc-400 uppercase tracking-widest">Modules_List</h2>
            <button @click="openAdd = true" class="text-xs bg-zinc-900 border border-zinc-800 hover:border-yellow-500 px-4 py-2 rounded text-zinc-400 hover:text-yellow-500 transition-all shadow-lg hover:shadow-yellow-500/10">
                + ADD_MODULE
            </button>
        </div>

        <div class="space-y-4">
            @forelse($project->modules as $index => $module)
                <div x-data="{ showSnippets: false }" class="bg-zinc-900/30 border border-zinc-800 rounded-xl overflow-hidden transition-all">
                    
                    <div @click="showSnippets = !showSnippets" class="p-4 flex justify-between items-center cursor-pointer hover:bg-zinc-800/50 transition-colors group">
                        <div class="flex items-center gap-4">
                            <span class="text-zinc-700 font-bold group-hover:text-yellow-500 transition-colors">0{{ $index + 1 }}</span>
                            <span class="font-bold text-zinc-300 uppercase tracking-tight group-hover:text-white">{{ $module->title }}</span>
                        </div>
                        <div class="flex items-center gap-4">
                            <button @click.stop="$dispatch('open-snippet-modal', { moduleId: {{ $module->id }}, moduleTitle: '{{ $module->title }}' })" 
                                    class="text-[9px] font-black bg-yellow-500/10 text-yellow-500 border border-yellow-500/30 px-2 py-1 rounded hover:bg-yellow-500 hover:text-black transition-all uppercase tracking-tighter">
                                + Add_Code
                            </button>

                            <form action="{{ route('modules.destroy', $module->id) }}" method="POST" onsubmit="return confirm('ERASE_MODULE: This will delete all snippets inside. Continue?')" class="m-0 flex items-center">
                                @csrf
                                @method('DELETE')
                                <button type="submit" @click.stop class="text-zinc-700 hover:text-red-500 transition-colors px-1" title="Delete Module">
                                    ✕
                                </button>
                            </form>

                            <span class="text-zinc-600 transition-transform duration-300" :class="showSnippets ? 'rotate-180' : ''">▼</span>
                        </div>
                    </div>

                    <div x-show="showSnippets" x-collapse x-cloak>
                        <div class="p-4 border-t border-zinc-800 bg-black/40 space-y-8">
                            @forelse($module->snippets as $snippet)
                                <div x-data="{ copied: false }" class="space-y-2 group/snippet">
                                    <div class="flex justify-between items-center px-1">
                                        <h4 class="text-[10px] font-bold text-zinc-500 uppercase tracking-widest">
                                            // {{ $snippet->title }} <span class="text-yellow-500/50">({{ $snippet->language }})</span>
                                        </h4>
                                        
                                        <div class="flex items-center gap-3 opacity-0 group-hover/snippet:opacity-100 transition-all">
                                            <button @click="
                                                navigator.clipboard.writeText($refs.codeBlock{{ $snippet->id }}.innerText);
                                                copied = true;
                                                setTimeout(() => copied = false, 2000);
                                            " class="text-[9px] font-black text-zinc-500 hover:text-yellow-500 transition-all uppercase tracking-widest">
                                                <span x-show="!copied">[Copy_Code]</span>
                                                <span x-show="copied" class="text-green-500 animate-pulse">[Copied!]</span>
                                            </button>

                                            <form action="{{ route('snippets.destroy', $snippet->id) }}" method="POST" onsubmit="return confirm('PURGE_SNIPPET: Erase this code forever?')" class="inline-flex items-center m-0">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-[9px] font-black text-zinc-700 hover:text-red-500 transition-all uppercase tracking-widest">
                                                    [Erase_X]
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                    
                                    <div class="rounded-lg overflow-hidden border border-zinc-800">
                                        <pre class="line-numbers"><code x-ref="codeBlock{{ $snippet->id }}" class="language-{{ $snippet->language }}">{{ $snippet->code }}</code></pre>
                                    </div>
                                </div>
                            @empty
                                <p class="text-[10px] text-zinc-700 italic py-2 px-1">No snippets loaded. System idle.</p>
                            @endforelse
                        </div>
                    </div>
                </div>
            @empty
                <div class="border-2 border-dashed border-zinc-900 p-16 text-center rounded-2xl text-zinc-600 italic text-sm">
                    No modules found. System idle.
                </div>
            @endforelse
        </div>
    </main>

    <div x-show="openAdd" class="fixed inset-0 z-[60] flex items-center justify-center p-4 bg-black/90 backdrop-blur-sm" x-cloak>
        <div @click.away="openAdd = false" class="bg-zinc-950 border border-yellow-500/30 w-full max-w-sm p-8 rounded-2xl shadow-2xl">
            <h2 class="text-lg font-black text-yellow-400 uppercase tracking-tighter mb-6">Initialize_New_Module</h2>
            <form action="{{ route('modules.store', $project->id) }}" method="POST">
                @csrf
                <input type="text" name="title" required placeholder="Module Title (e.g. Auth System)" class="w-full bg-zinc-900 border border-zinc-800 rounded-lg px-4 py-3 text-sm text-white focus:outline-none focus:border-yellow-500 mb-6 transition-all">
                <button type="submit" class="w-full bg-yellow-500 text-black font-black py-3 rounded-lg uppercase text-xs tracking-widest hover:bg-white transition-all">COMMIT_MODULE</button>
            </form>
        </div>
    </div>

    <div x-data="{ open: false, moduleId: '', moduleTitle: '' }" 
         @open-snippet-modal.window="open = true; moduleId = $event.detail.moduleId; moduleTitle = $event.detail.moduleTitle"
         x-show="open" class="fixed inset-0 z-[70] flex items-center justify-center p-4 bg-black/90 backdrop-blur-sm" x-cloak>
        
        <div @click.away="open = false" class="bg-zinc-950 border border-yellow-500/30 w-full max-w-2xl p-8 rounded-2xl shadow-2xl">
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-xl font-black text-yellow-400 uppercase tracking-tighter">Inject_Code: <span x-text="moduleTitle" class="text-white"></span></h2>
                <button @click="open = false" class="text-zinc-500 hover:text-white transition-colors">✕</button>
            </div>
            
            <form :action="'/module/' + moduleId + '/snippet'" method="POST" class="space-y-4">
                @csrf
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="text-[10px] font-black text-zinc-500 uppercase mb-1 block">Title</label>
                        <input type="text" name="title" required placeholder="e.g. Controller Logic" class="w-full bg-zinc-900 border border-zinc-800 rounded px-4 py-2.5 text-sm text-white focus:border-yellow-500 outline-none transition-all">
                    </div>
                    <div>
                        <label class="text-[10px] font-black text-zinc-500 uppercase mb-1 block">Language</label>
                        <select name="language" class="w-full bg-zinc-900 border border-zinc-800 rounded px-4 py-2.5 text-sm text-white focus:border-yellow-500 outline-none uppercase font-bold cursor-pointer">
                            <option value="php">PHP</option>
                            <option value="javascript">Javascript</option>
                            <option value="sql">SQL</option>
                            <option value="html">HTML</option>
                            <option value="css">CSS</option>
                        </select>
                    </div>
                </div>
                <div>
                    <label class="text-[10px] font-black text-zinc-500 uppercase mb-1 block">Source_Code</label>
                    <textarea name="code" rows="10" required placeholder="// Write or paste your code here..." class="w-full bg-zinc-900 border border-zinc-800 rounded p-4 text-xs text-green-400 font-mono focus:border-yellow-500 focus:outline-none transition-all"></textarea>
                </div>
                
                <button type="submit" class="w-full bg-yellow-500 text-black font-black py-4 rounded uppercase text-xs tracking-[0.2em] hover:bg-white transition-all shadow-lg">
                    COMMIT_SNIPPET_TO_SYSTEM >>
                </button>
            </form>
        </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/prism/1.29.0/prism.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/prism/1.29.0/components/prism-php.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/prism/1.29.0/components/prism-javascript.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/prism/1.29.0/components/prism-sql.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/prism/1.29.0/components/prism-markup-bash.min.js"></script>
    
    <script>
        document.addEventListener('alpine:initialized', () => {
            Prism.highlightAll();
        });
    </script>
</body>
</html>