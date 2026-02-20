<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>LexiCode</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=JetBrains+Mono:wght@400;700&display=swap');
        body { font-family: 'JetBrains Mono', monospace; background-color: #000; }
        .grid-pattern {
            background-image: radial-gradient(circle, #1a1a1a 1px, transparent 1px);
            background-size: 30px 30px;
        }
    </style>
</head>
<body class="grid-pattern text-white min-h-screen flex flex-col items-center justify-center p-6 text-center">
    
    <div class="max-w-3xl space-y-6">
        <div class="inline-block border border-yellow-500/30 bg-yellow-500/5 px-4 py-1 rounded-full mb-4">
            <span class="text-yellow-500 text-[9px] font-black uppercase tracking-[0.3em]">Version 2.0 Stable</span>
        </div>
        
        <h1 class="text-6xl md:text-8xl font-black tracking-tighter text-white uppercase italic">
            Lexi<span class="text-yellow-500">Code</span>
        </h1>
        
        <p class="text-zinc-500 text-sm md:text-base max-w-xl mx-auto leading-relaxed">
            The ultimate private vault for your high-performance code snippets. 
            Organize modules, highlight syntax, and export to PNG in seconds.
        </p>

        <div class="flex flex-col md:flex-row items-center justify-center gap-4 mt-12">
            @if (Route::has('login'))
                <a href="{{ route('login') }}" class="w-full md:w-auto px-10 py-4 border border-zinc-800 text-zinc-400 hover:text-white hover:border-white transition-all text-[10px] font-black uppercase tracking-widest rounded-2xl">
                    Log In
                </a>
                <a href="{{ route('register') }}" class="w-full md:w-auto px-10 py-4 bg-yellow-500 text-black hover:bg-white transition-all text-[10px] font-black uppercase tracking-widest rounded-2xl shadow-[0_0_30px_rgba(234,179,8,0.2)]">
                    Create Account
                </a>
            @endif
        </div>
    </div>

    <div class="fixed bottom-10 text-zinc-800 text-[10px] font-bold uppercase tracking-[0.5em]">
        @Dimas Irawan - 2026
    </div>

</body>
</html>