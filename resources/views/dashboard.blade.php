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
        body { font-family: 'JetBrains Mono', monospace; background-color: #000; color: #f4f4f5; }
        
        [x-cloak] { display: none !important; }

        .project-card { transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1); }
        .project-card:hover { 
            transform: translateY(-5px);
            border-color: rgba(234, 179, 8, 0.5);
            box-shadow: 0 10px 30px -10px rgba(234, 179, 8, 0.2);
        }

        /* Custom Scrollbar */
        ::-webkit-scrollbar { width: 8px; }
        ::-webkit-scrollbar-track { background: #000; }
        ::-webkit-scrollbar-thumb { background: #27272a; border-radius: 4px; }
        ::-webkit-scrollbar-thumb:hover { background: #eab308; }
    </style>
</head>
<body x-data="{ 
    openModal: false, 
    editMode: false, 
    editData: { id: '', name: '', tech_stack: '', description: '' } 
}">

    <nav class="border-b border-yellow-500/20 py-4 px-6 bg-zinc-950/50 backdrop-blur-md sticky top-0 z-50">
        <div class="max-w-7xl mx-auto flex justify-between items-center">
            <div class="flex items-center gap-3 cursor-pointer" onclick="window.location='{{ route('dashboard') }}'">
                <img src="{{ asset('images/logo-lexicode.png') }}" alt="LexiCode" class="h-8">
                <span class="font-bold text-xl tracking-tighter text-yellow-400 uppercase">LEXI<span class="text-white font-light">CODE</span></span>
            </div>

            <div class="flex items-center gap-6">
                <div class="hidden md:flex gap-6 text-[10px] font-black uppercase tracking-[0.2em] text-zinc-500 border-r border-zinc-800 pr-6 mr-2">
                    <a href="{{ route('repository.index') }}" class="hover:text-yellow-400 transition-colors {{ request()->routeIs('repository.index') ? 'text-yellow-400' : '' }}">Repository</a>
                    <a href="{{ route('analytics.index') }}" class="hover:text-yellow-400 transition-colors {{ request()->routeIs('analytics.index') ? 'text-yellow-400' : '' }}">Analytics</a>
                </div>
                
                <button @click="editMode = false; editData = { id: '', name: '', tech_stack: '', description: '' }; openModal = true" 
                        class="bg-yellow-500 text-black px-4 py-1.5 rounded text-[10px] font-black uppercase hover:bg-white transition-all shadow-[0_0_15px_rgba(234,179,8,0.3)]">
                    + New Project
                </button>

                <form method="POST" action="{{ route('logout') }}" class="inline">
                    @csrf
                    <button type="submit" 
                            class="flex items-center gap-2 bg-zinc-900 border border-zinc-800 px-3 py-1.5 rounded text-[10px] font-black text-zinc-500 hover:text-red-500 hover:border-red-500/50 transition-all uppercase tracking-widest">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                        </svg>
                        Logout
                    </button>
                </form>
            </div>
        </div>
    </nav>

    <main class="max-w-7xl mx-auto py-12 px-6">
        <div class="mb-12">
            <h1 class="text-4xl font-extrabold text-white mb-2 tracking-tighter uppercase">Projects<span class="text-yellow-500">_</span></h1>
            <p class="text-zinc-500 text-sm mb-8">Welcome back, <span class="text-yellow-500 font-bold">{{ Auth::user()->name }}</span>. System is operational and secured.</p>

            @if(session('success'))
                <div x-data="{ show: true }" 
                     x-show="show" 
                     x-init="setTimeout(() => show = false, 5000)"
                     x-transition:leave="transition ease-in duration-500"
                     class="mb-8 p-4 bg-yellow-500/10 border border-yellow-500/50 text-yellow-500 text-xs font-bold uppercase tracking-[0.2em] flex items-center justify-between">
                    <span>>> SYSTEM_MESSAGE: {{ session('success') }}</span>
                    <button @click="show = false" class="text-yellow-500/50 hover:text-yellow-500">✕</button>
                </div>
            @endif
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            @forelse($projects as $project)
            <div class="project-card group relative bg-zinc-900/40 border border-zinc-800 p-6 rounded-xl flex flex-col justify-between h-full">
                <div>
                    <div class="absolute -top-3 -right-3 bg-yellow-500 text-black text-[10px] font-black px-2 py-1 rounded shadow-lg uppercase">
                        {{ $project->tech_stack }}
                    </div>
                    
                    <h2 class="text-xl font-bold text-zinc-100 mb-3 group-hover:text-yellow-400 transition-colors">
                        {{ $project->name }}
                    </h2>
                    
                    <p class="text-zinc-400 text-sm leading-relaxed mb-6 line-clamp-2">
                        {{ $project->description }}
                    </p>
                </div>

                <div class="flex justify-between items-center pt-4 border-t border-zinc-800">
                    <div class="text-[11px] text-zinc-500 font-bold uppercase">
                        <span class="text-yellow-500">{{ $project->modules_count }}</span> Modules
                    </div>
                    
                    <div class="flex items-center gap-3">
                        <button @click="editMode = true; editData = { id: '{{ $project->id }}', name: '{{ addslashes($project->name) }}', tech_stack: '{{ $project->tech_stack }}', description: '{{ addslashes($project->description) }}' }; openModal = true" 
                                class="text-[10px] font-bold text-zinc-600 hover:text-yellow-500 transition-colors uppercase tracking-tighter">
                            [Edit]
                        </button>

                        <form action="{{ route('projects.destroy', $project->id) }}" method="POST" onsubmit="return confirm('WARNING: TERMINATE THIS REPOSITORY?')" class="inline">
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
                <h2 class="text-xl font-black text-yellow-400 uppercase tracking-tighter" x-text="editMode ? 'Update_Repository' : 'Initialize_Project'"></h2>
                <button @click="openModal = false" class="text-zinc-500 hover:text-white">✕</button>
            </div>

            <form :action="editMode ? '/project/' + editData.id : '{{ route('projects.store') }}'" method="POST" class="space-y-5">
                @csrf
                <template x-if="editMode">
                    <input type="hidden" name="_method" value="PUT">
                </template>

                <div>
                    <label class="block text-[10px] font-black text-zinc-500 uppercase mb-2">Project Name</label>
                    <input type="text" name="name" x-model="editData.name" required class="w-full bg-zinc-900 border border-zinc-800 rounded-lg px-4 py-3 text-sm focus:outline-none focus:border-yellow-500 text-white transition-colors">
                </div>

                <div>
                    <label class="block text-[10px] font-black text-zinc-500 uppercase mb-2">Tech Stack</label>
                    <input type="text" name="tech_stack" x-model="editData.tech_stack" required placeholder="e.g. Laravel, React, Python" class="w-full bg-zinc-900 border border-zinc-800 rounded-lg px-4 py-3 text-sm focus:outline-none focus:border-yellow-500 text-white transition-colors">
                </div>

                <div>
                    <label class="block text-[10px] font-black text-zinc-500 uppercase mb-2">Description</label>
                    <textarea name="description" x-model="editData.description" rows="3" required class="w-full bg-zinc-900 border border-zinc-800 rounded-lg px-4 py-3 text-sm focus:outline-none focus:border-yellow-500 text-white transition-colors"></textarea>
                </div>

                <div class="pt-4">
                    <button type="submit" class="w-full bg-yellow-500 text-black font-black py-4 rounded-lg uppercase tracking-widest hover:bg-yellow-400 transition-all">
                        <span x-text="editMode ? 'Commit_Changes >>' : 'Create_Repository >>'"></span>
                    </button>
                </div>
            </form>
        </div>
    </div>

</body>
</html>