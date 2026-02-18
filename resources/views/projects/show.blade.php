<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lexicode | {{ str_replace('_', ' ', $project->name) }}</title>
    
    <link rel="icon" type="image/png" href="{{ asset('images/logo-lexicode.png') }}">
    
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/@alpinejs/collapse@3.x.x/dist/cdn.min.js"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    
    <link href="https://cdnjs.cloudflare.com/ajax/libs/prism/1.29.0/themes/prism-tomorrow.min.css" rel="stylesheet" />
    
    <style>
        @import url('https://fonts.googleapis.com/css2?family=JetBrains+Mono:wght@400;700&display=swap');
        body { font-family: 'JetBrains+Mono', monospace; background: black; color: white; scroll-behavior: smooth; }
        [x-cloak] { display: none !important; }
        
        pre::-webkit-scrollbar { height: 4px; }
        pre::-webkit-scrollbar-thumb { background: #333; border-radius: 10px; }
        pre::-webkit-scrollbar-thumb:hover { background: #eab308; }

        pre[class*="language-"] {
            background: #09090b !important;
            border: 1px solid #27272a !important;
            margin: 0 !important;
            padding: 1.25rem !important;
            font-size: 0.75rem !important;
            border-radius: 0.5rem;
        }
    </style>
</head>
<body x-data="{ openAdd: false, search: '' }">

    <div x-data="{ 
            show: false, 
            message: '', 
            type: 'success' 
        }" 
        x-init="
            @if(session('success'))
                show = true; message = '{{ session('success') }}'; type = 'success';
                setTimeout(() => show = false, 3000);
            @endif
            @if(session('error'))
                show = true; message = '{{ session('error') }}'; type = 'error';
                setTimeout(() => show = false, 3000);
            @endif
        "
        x-show="show" 
        x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0 translate-y-4"
        x-transition:enter-end="opacity-100 translate-y-0"
        x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="opacity-100 translate-y-0"
        x-transition:leave-end="opacity-0 translate-y-4"
        class="fixed bottom-8 right-8 z-[100]" x-cloak>
        
        <div :class="type === 'success' ? 'bg-zinc-900 border-green-500/50' : 'bg-zinc-900 border-red-500/50'" 
             class="px-6 py-4 rounded-2xl shadow-[0_20px_50px_rgba(0,0,0,0.5)] flex items-center gap-4 border backdrop-blur-xl">
            <div :class="type === 'success' ? 'bg-green-500' : 'bg-red-500'" class="w-2 h-2 rounded-full animate-pulse"></div>
            <span class="text-white text-[10px] font-bold uppercase tracking-[0.2em]" x-text="message"></span>
            <button @click="show = false" class="text-zinc-500 hover:text-white transition-colors ml-4 uppercase text-[9px] font-bold">Close</button>
        </div>
    </div>

    <nav class="border-b border-white/10 py-4 px-6 bg-zinc-950/50 backdrop-blur-md sticky top-0 z-50">
        <div class="max-w-7xl flex justify-between items-center mx-auto">
            <a href="{{ route('dashboard') }}" class="text-zinc-500 hover:text-white text-xs font-bold uppercase tracking-widest transition-colors">Back to Dashboard</a>
            <span class="text-yellow-500 font-black tracking-tight uppercase">{{ str_replace('_', ' ', $project->name) }}</span>
        </div>
    </nav>

    <main class="max-w-4xl mx-auto py-12 px-6">
        <header class="mb-12 border-l-2 border-yellow-500 pl-6">
            <h1 class="text-3xl font-black uppercase tracking-tighter">{{ str_replace('_', ' ', $project->name) }}</h1>
            <p class="text-zinc-500 text-sm mt-2">{{ $project->description }}</p>
            <div class="inline-block mt-4 bg-yellow-500 text-black text-[10px] font-bold px-2 py-0.5 rounded uppercase">
                {{ $project->tech_stack }}
            </div>

            <div class="mt-10 relative">
                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                    <span class="text-zinc-600 text-[10px] font-bold text-yellow-500/50">SEARCH</span>
                </div>
                <input 
                    type="text" 
                    x-model="search"
                    placeholder="Filter modules by title..." 
                    class="w-full bg-zinc-900/40 border border-zinc-800 rounded-lg py-3 pl-20 pr-4 text-xs font-mono text-zinc-300 focus:outline-none focus:border-yellow-500/50 transition-all shadow-inner"
                >
            </div>
        </header>

        <div class="flex justify-between items-center mb-6">
            <h2 class="text-sm font-bold text-zinc-400 uppercase tracking-widest">Modules</h2>
            <button @click="openAdd = true" class="text-xs bg-zinc-900 border border-zinc-800 hover:border-yellow-500 px-4 py-2 rounded text-zinc-400 hover:text-yellow-500 transition-all uppercase font-bold tracking-tighter">
                Add Module
            </button>
        </div>

        <div class="space-y-4">
            @forelse($project->modules as $index => $module)
                <div 
                    x-data="{ showSnippets: false }" 
                    x-show="search === '' || '{{ strtolower($module->title) }}'.includes(search.toLowerCase())"
                    x-transition.opacity
                    class="bg-zinc-900/30 border border-zinc-800 rounded-xl overflow-hidden transition-all"
                >
                    <div @click="showSnippets = !showSnippets" class="p-4 flex justify-between items-center cursor-pointer hover:bg-zinc-800/50 transition-colors group">
                        <div class="flex items-center gap-4">
                            <span class="text-zinc-700 font-bold group-hover:text-yellow-500 transition-colors">0{{ $index + 1 }}</span>
                            <span class="font-bold text-zinc-300 uppercase group-hover:text-white">{{ str_replace('_', ' ', $module->title) }}</span>
                        </div>
                        <div class="flex items-center gap-4">
                            <button @click.stop="$dispatch('open-snippet-modal', { moduleId: {{ $module->id }}, moduleTitle: '{{ $module->title }}' })" 
                                    class="text-[9px] font-bold bg-yellow-500/10 text-yellow-500 border border-yellow-500/30 px-2 py-1 rounded hover:bg-yellow-500 hover:text-black transition-all uppercase">
                                Add Code
                            </button>

                            <form action="{{ route('modules.destroy', $module->id) }}" method="POST" onsubmit="return confirm('Delete this module?')" class="m-0 inline-flex items-center">
                                @csrf @method('DELETE')
                                <button type="submit" @click.stop class="text-zinc-700 hover:text-red-500 transition-colors px-1 text-lg leading-none">✕</button>
                            </form>

                            <span class="text-zinc-600 transition-transform duration-300 text-[10px]" :class="showSnippets ? 'rotate-180' : ''">▼</span>
                        </div>
                    </div>

                    <div x-show="showSnippets" x-collapse x-cloak>
                        <div class="p-4 border-t border-zinc-800 bg-black/40 space-y-8">
                            @forelse($module->snippets as $snippet)
                                <div x-data="{ copied: false }" class="space-y-2 group/snippet">
                                    <div class="flex justify-between items-center px-1">
                                        <h4 class="text-[10px] font-bold text-zinc-500 uppercase tracking-widest flex items-center gap-2">
                                            <span class="px-1.5 py-0.5 rounded-[4px] text-[8px] font-black border {{ 
                                                $snippet->language == 'php' ? 'bg-indigo-500/10 text-indigo-400 border-indigo-500/20' : 
                                                ($snippet->language == 'javascript' ? 'bg-yellow-500/10 text-yellow-500 border-yellow-500/20' : 
                                                ($snippet->language == 'sql' ? 'bg-emerald-500/10 text-emerald-400 border-emerald-500/20' : 'bg-zinc-800 text-zinc-400 border-zinc-700'))
                                            }}">
                                                {{ strtoupper($snippet->language) }}
                                            </span>
                                            // {{ $snippet->title }}
                                        </h4>
                                        
                                        <div class="flex items-center gap-3 opacity-0 group-hover/snippet:opacity-100 transition-all">
                                            <button @click="$dispatch('open-edit-modal', { 
                                                id: {{ $snippet->id }}, 
                                                title: '{{ addslashes($snippet->title) }}', 
                                                language: '{{ $snippet->language }}', 
                                                code: `{{ addslashes($snippet->code) }}` 
                                            })" class="text-[9px] font-bold text-zinc-500 hover:text-blue-400 transition-all uppercase tracking-widest">
                                                Edit
                                            </button>

                                            <button @click="
                                                navigator.clipboard.writeText($refs.codeBlock{{ $snippet->id }}.innerText);
                                                copied = true;
                                                setTimeout(() => copied = false, 2000);
                                            " class="text-[9px] font-bold text-zinc-500 hover:text-yellow-500 transition-all uppercase tracking-widest">
                                                <span x-show="!copied">Copy</span>
                                                <span x-show="copied" class="text-green-500">Copied!</span>
                                            </button>

                                            <form action="{{ route('snippets.destroy', $snippet->id) }}" method="POST" onsubmit="return confirm('Delete this snippet?')" class="inline-flex items-center m-0">
                                                @csrf @method('DELETE')
                                                <button type="submit" class="text-[9px] font-bold text-zinc-700 hover:text-red-500 transition-all uppercase tracking-widest">
                                                    Delete
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                    <div class="rounded-lg overflow-hidden border border-zinc-800">
                                        <pre class="line-numbers"><code x-ref="codeBlock{{ $snippet->id }}" class="language-{{ $snippet->language }}">{{ $snippet->code }}</code></pre>
                                    </div>
                                </div>
                            @empty
                                <div class="py-12 flex flex-col items-center justify-center opacity-20">
                                    <p class="text-[10px] font-mono uppercase tracking-[0.2em]">No code recorded</p>
                                </div>
                            @endforelse
                        </div>
                    </div>
                </div>
            @empty
                <div class="border-2 border-dashed border-zinc-900 p-16 text-center rounded-2xl text-zinc-600 italic text-sm">
                    No modules found.
                </div>
            @endforelse
        </div>
    </main>

    <button 
        x-data="{ show: false }" 
        @scroll.window="show = (window.pageYOffset > 500)"
        x-show="show"
        x-transition.opacity
        @click="window.scrollTo({top: 0, behavior: 'smooth'})"
        class="fixed bottom-24 right-8 p-3 bg-zinc-900 border border-zinc-800 rounded-full text-yellow-500 hover:bg-yellow-500 hover:text-black transition-all shadow-2xl z-40"
    >
        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 10l7-7m0 0l7 7m-7-7v18" />
        </svg>
    </button>

    <div x-show="openAdd" class="fixed inset-0 z-[60] flex items-center justify-center p-4 bg-black/90 backdrop-blur-sm" x-cloak>
        <div @click.away="openAdd = false" class="bg-zinc-950 border border-zinc-800 w-full max-w-sm p-8 rounded-2xl shadow-2xl relative">
            <button @click="openAdd = false" class="absolute top-4 right-4 text-zinc-500 hover:text-white text-lg">✕</button>
            <h2 class="text-lg font-bold text-white mb-6 uppercase tracking-tight">New Module</h2>
            <form action="{{ route('modules.store', $project->id) }}" method="POST">
                @csrf
                <input type="text" name="title" required placeholder="Enter module title..." class="w-full bg-zinc-900 border border-zinc-800 rounded-lg px-4 py-3 text-sm text-white focus:outline-none focus:border-yellow-500 mb-6 transition-all">
                <button type="submit" class="w-full bg-yellow-500 text-black font-bold py-3 rounded-lg uppercase text-xs tracking-widest hover:bg-white transition-all">Create Module</button>
            </form>
        </div>
    </div>

    <div x-data="{ open: false, moduleId: '', moduleTitle: '' }" 
         @open-snippet-modal.window="open = true; moduleId = $event.detail.moduleId; moduleTitle = $event.detail.moduleTitle"
         x-show="open" class="fixed inset-0 z-[70] flex items-center justify-center p-4 bg-black/90 backdrop-blur-sm" x-cloak>
        <div @click.away="open = false" class="bg-zinc-950 border border-zinc-800 w-full max-w-2xl p-8 rounded-2xl shadow-2xl relative">
            <button @click="open = false" class="absolute top-4 right-4 text-zinc-500 hover:text-white text-lg">✕</button>
            <h2 class="text-xl font-bold text-white mb-6 uppercase tracking-tight">Add Code to <span x-text="moduleTitle" class="text-yellow-500"></span></h2>
            <form :action="'/module/' + moduleId + '/snippet'" method="POST" class="space-y-4">
                @csrf
                <div class="grid grid-cols-2 gap-4">
                    <div class="space-y-1">
                        <label class="text-[9px] font-bold text-zinc-500 uppercase tracking-widest ml-1">Title</label>
                        <input type="text" name="title" required placeholder="e.g. Database Config" class="w-full bg-zinc-900 border border-zinc-800 rounded px-4 py-2.5 text-sm text-white focus:border-yellow-500 outline-none">
                    </div>
                    <div class="space-y-1">
                        <label class="text-[9px] font-bold text-zinc-500 uppercase tracking-widest ml-1">Language</label>
                        <select name="language" class="w-full bg-zinc-900 border border-zinc-800 rounded px-4 py-2.5 text-sm text-white focus:border-yellow-500 outline-none uppercase font-bold cursor-pointer">
                            <option value="php">PHP</option>
                            <option value="javascript">Javascript</option>
                            <option value="sql">SQL</option>
                            <option value="html">HTML</option>
                            <option value="css">CSS</option>
                        </select>
                    </div>
                </div>
                <div class="space-y-1">
                    <label class="text-[9px] font-bold text-zinc-500 uppercase tracking-widest ml-1">Code</label>
                    <textarea name="code" rows="10" required placeholder="Paste your code here..." class="w-full bg-zinc-900 border border-zinc-800 rounded p-4 text-xs text-green-400 font-mono focus:border-yellow-500 focus:outline-none transition-all"></textarea>
                </div>
                <button type="submit" class="w-full bg-yellow-500 text-black font-bold py-4 rounded uppercase text-xs tracking-widest hover:bg-white transition-all">Save Snippet</button>
            </form>
        </div>
    </div>

    <div x-data="{ open: false, id: '', title: '', language: '', code: '' }" 
         @open-edit-modal.window="open = true; id = $event.detail.id; title = $event.detail.title; language = $event.detail.language; code = $event.detail.code"
         x-show="open" class="fixed inset-0 z-[80] flex items-center justify-center p-4 bg-black/95 backdrop-blur-md" x-cloak>
        <div @click.away="open = false" class="bg-zinc-950 border border-zinc-800 w-full max-w-2xl p-8 rounded-2xl shadow-2xl relative">
            <button @click="open = false" class="absolute top-4 right-4 text-zinc-500 hover:text-white text-lg">✕</button>
            <h2 class="text-xl font-bold text-white mb-6 uppercase tracking-tight">Edit Snippet</h2>
            <form :action="'/snippet/' + id" method="POST" class="space-y-4">
                @csrf @method('PATCH')
                <div class="grid grid-cols-2 gap-4">
                    <div class="space-y-1">
                        <label class="text-[9px] font-bold text-zinc-500 uppercase tracking-widest ml-1">Title</label>
                        <input type="text" name="title" x-model="title" required class="w-full bg-zinc-900 border border-zinc-800 rounded px-4 py-2.5 text-sm text-white focus:border-yellow-500 outline-none">
                    </div>
                    <div class="space-y-1">
                        <label class="text-[9px] font-bold text-zinc-500 uppercase tracking-widest ml-1">Language</label>
                        <select name="language" x-model="language" class="w-full bg-zinc-900 border border-zinc-800 rounded px-4 py-2.5 text-sm text-white focus:border-yellow-500 outline-none uppercase font-bold cursor-pointer">
                            <option value="php">PHP</option>
                            <option value="javascript">Javascript</option>
                            <option value="sql">SQL</option>
                            <option value="html">HTML</option>
                            <option value="css">CSS</option>
                        </select>
                    </div>
                </div>
                <div class="space-y-1">
                    <label class="text-[9px] font-bold text-zinc-500 uppercase tracking-widest ml-1">Update Code</label>
                    <textarea name="code" x-model="code" rows="10" required class="w-full bg-zinc-900 border border-zinc-800 rounded p-4 text-xs text-blue-300 font-mono focus:border-yellow-500 focus:outline-none transition-all"></textarea>
                </div>
                <button type="submit" class="w-full bg-blue-600 text-white font-bold py-4 rounded uppercase text-xs tracking-widest hover:bg-blue-500 transition-all">Update Snippet</button>
            </form>
        </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/prism/1.29.0/prism.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/prism/1.29.0/components/prism-php.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/prism/1.29.0/components/prism-javascript.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/prism/1.29.0/components/prism-sql.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/prism/1.29.0/components/prism-css.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/prism/1.29.0/components/prism-markup.min.js"></script> 
    
    <script>
        document.addEventListener('alpine:initialized', () => { 
            Prism.highlightAll(); 
        });
    </script>
</body>
</html>