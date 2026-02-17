<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <title>LexiCode</title>
    <link rel="icon" type="image/png" href="{{ asset('images/logo-lexicode.png') }}">

    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <style>
        @import url('https://fonts.googleapis.com/css2?family=JetBrains+Mono:wght@400;700&display=swap');
        body { font-family: 'JetBrains+Mono', monospace; }
        
        [x-cloak] { display: none !important; }

        .project-card { transition: transform 0.2s cubic-bezier(0.4, 0, 0.2, 1); }
        .project-card:hover { 
            transform: translateY(-5px);
            box-shadow: 0 0 15px rgba(234, 179, 8, 0.1);
        }

        /* Custom Scrollbar ala Terminal */
        ::-webkit-scrollbar { width: 8px; }
        ::-webkit-scrollbar-track { background: #000; }
        ::-webkit-scrollbar-thumb { background: #333; border-radius: 4px; }
        ::-webkit-scrollbar-thumb:hover { background: #eab308; }
    </style>
</head>
<body class="bg-black text-zinc-100" x-data="{ openModal: false }">

    <nav class="border-b border-yellow-500/20 py-4 px-6 bg-zinc-950/50 backdrop-blur-md sticky top-0 z-50">
        <div class="max-w-7xl mx-auto flex justify-between items-center">
            <div class="flex items-center gap-3 cursor-pointer" onclick="window.location='/'">
                <img src="{{ asset('images/logo-lexicode.png') }}" alt="LexiCode" class="h-8">
                <span class="font-bold text-xl tracking-tighter text-yellow-400 uppercase">LEXI<span class="text-white font-light">CODE</span></span>
            </div>

            <div class="flex items-center gap-8">
                <div class="hidden md:flex gap-6 text-[10px] font-black uppercase tracking-[0.2em] text-zinc-500">
                    <a href="#" class="hover:text-yellow-400 transition-colors">Repository</a>
                    <a href="#" class="hover:text-yellow-400 transition-colors">Analytics</a>
                </div>
                
                <button @click="openModal = true" class="bg-yellow-500 text-black px-4 py-1.5 rounded text-[10px] font-black uppercase hover:bg-white transition-all shadow-[0_0_15px_rgba(234,179,8,0.3)]">
                    + New Project
                </button>
            </div>
        </div>
    </nav>

    <main class="max-w-7xl mx-auto py-12 px-6">
        <div class="mb-12">
            <h1 class="text-4xl font-extrabold text-white mb-2 tracking-tighter uppercase">Projects<span class="text-yellow-500">_</span></h1>
            <p class="text-zinc-500 text-sm mb-8">Welcome back, Admin. System is operational and secured.</p>

            @if(session('success'))
                <div x-data="{ show: true }" 
                     x-show="show" 
                     x-init="setTimeout(() => show = false, 5000)"
                     x-transition:leave="transition ease-in duration-500"
                     x-transition:leave-start="opacity-100"
                     x-transition:leave-end="opacity-0"
                     class="mb-8 p-4 bg-yellow-500/10 border border-yellow-500/50 text-yellow-500 text-xs font-bold uppercase tracking-[0.2em] flex items-center justify-between">
                    <span>>> SYSTEM_MESSAGE: {{ session('success') }}</span>
                    <button @click="show = false" class="text-yellow-500/50 hover:text-yellow-500">✕</button>
                </div>
            @endif
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            @forelse($projects as $project)
            <div class="project-card group relative bg-zinc-900/40 border border-zinc-800 p-6 rounded-xl hover:border-yellow-500/50 transition-all duration-300">
                <div class="absolute -top-3 -right-3 bg-yellow-500 text-black text-[10px] font-black px-2 py-1 rounded shadow-lg uppercase">
                    {{ $project->tech_stack }}
                </div>
                
                <h2 class="text-xl font-bold text-zinc-100 mb-3 group-hover:text-yellow-400 transition-colors">
                    {{ $project->name }}
                </h2>
                
                <p class="text-zinc-400 text-sm leading-relaxed mb-6 h-12 overflow-hidden">
                    {{ $project->description }}
                </p>

                <div class="flex justify-between items-center pt-4 border-t border-zinc-800">
                    <div class="text-[11px] text-zinc-500">
                        <span class="text-yellow-500 font-bold uppercase">{{ $project->modules_count }} Modules Loaded</span>
                    </div>
                    
                    <div class="flex items-center gap-4">
                        <form action="{{ route('projects.destroy', $project->id) }}" method="POST" onsubmit="return confirm('WARNING: TERMINATE THIS REPOSITORY?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-[10px] font-bold text-zinc-600 hover:text-red-500 transition-colors uppercase tracking-tighter">
                                [Delete]
                            </button>
                        </form>

                        <a href="{{ route('projects.show', $project->slug) }}" class="text-xs font-black text-yellow-500 hover:text-white transition-colors tracking-tighter">
                            OPEN DOCS >>
                        </a>
                    </div>
                </div>
            </div>
            @empty
            <div class="col-span-full border-2 border-dashed border-zinc-800 p-20 text-center rounded-2xl">
                <p class="text-zinc-600 italic">No projects found. Use <span class="text-yellow-600 font-bold">+ New Project</span> to start.</p>
            </div>
            @endforelse
        </div>
    </main>

    <div x-show="openModal" 
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0 scale-95"
         x-transition:enter-end="opacity-100 scale-100"
         class="fixed inset-0 z-[60] flex items-center justify-center p-4 bg-black/90 backdrop-blur-sm" 
         x-cloak>
        
        <div @click.away="openModal = false" class="bg-zinc-950 border border-yellow-500/30 w-full max-w-lg p-8 rounded-2xl shadow-2xl">
            <div class="flex justify-between items-center mb-6 border-b border-zinc-800 pb-4">
                <h2 class="text-xl font-black text-yellow-400 uppercase tracking-tighter">Initialize_Project</h2>
                <button @click="openModal = false" class="text-zinc-500 hover:text-white transition-colors">✕</button>
            </div>

            <form action="{{ route('projects.store') }}" method="POST" class="space-y-5">
                @csrf
                <div>
                    <label class="block text-[10px] font-black text-zinc-500 uppercase mb-2">Project Name</label>
                    <input type="text" name="name" required class="w-full bg-zinc-900 border border-zinc-800 rounded-lg px-4 py-3 text-sm focus:outline-none focus:border-yellow-500 text-white transition-colors" placeholder="e.g. Core API System">
                </div>

                <div>
                    <label class="block text-[10px] font-black text-zinc-500 uppercase mb-2">Tech Stack</label>
                    <input type="text" name="tech_stack" required class="w-full bg-zinc-900 border border-zinc-800 rounded-lg px-4 py-3 text-sm focus:outline-none focus:border-yellow-500 text-white transition-colors" placeholder="e.g. LARAVEL, POSTGRESQL">
                </div>

                <div>
                    <label class="block text-[10px] font-black text-zinc-500 uppercase mb-2">Description</label>
                    <textarea name="description" rows="3" required class="w-full bg-zinc-900 border border-zinc-800 rounded-lg px-4 py-3 text-sm focus:outline-none focus:border-yellow-500 text-white transition-colors" placeholder="Brief system overview..."></textarea>
                </div>

                <div class="pt-4">
                    <button type="submit" class="w-full bg-yellow-500 text-black font-black py-4 rounded-lg uppercase tracking-widest hover:bg-yellow-400 transition-all shadow-[0_0_20px_rgba(234,179,8,0.2)]">
                        Create_Repository >>
                    </button>
                </div>
            </form>
        </div>
    </div>

</body>
</html>