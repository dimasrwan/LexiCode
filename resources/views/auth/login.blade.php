<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>LexiCode</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=JetBrains+Mono:wght@400;700&display=swap');
        body { font-family: 'JetBrains Mono', monospace; background: black; color: white; }
    </style>
</head>
<body class="antialiased bg-black">
    <div class="min-h-screen flex flex-col items-center justify-center p-6">
        <div class="w-full max-w-md bg-zinc-950 border border-zinc-900 p-10 rounded-3xl shadow-[0_20px_50px_rgba(0,0,0,1)]">
            
            <div class="text-center mb-10">
                <h1 class="text-3xl font-black text-yellow-500 tracking-tighter uppercase">LEXICODE</h1>
                <p class="text-zinc-500 text-[10px] font-bold tracking-[0.3em] uppercase mt-2">Access your vault</p>
            </div>

            @if(session('status'))
                <div class="mb-4 font-medium text-xs text-green-500 bg-green-500/10 p-3 rounded-lg border border-green-500/20 text-center">
                    {{ session('status') }}
                </div>
            @endif

            <form method="POST" action="{{ route('login') }}" class="space-y-6">
                @csrf

                <div class="space-y-2">
                    <label class="text-[9px] font-black text-zinc-600 uppercase tracking-widest ml-1">Identity</label>
                    <input type="email" name="email" value="{{ old('email') }}" required autofocus
                        class="w-full bg-zinc-900/50 border border-zinc-800 rounded-xl px-4 py-3 text-sm text-white focus:outline-none focus:border-yellow-500 transition-all placeholder-zinc-700"
                        placeholder="email@example.com">
                    @error('email') <p class="text-[10px] text-red-500 mt-1 font-bold italic">{{ $message }}</p> @enderror
                </div>

                <div class="space-y-2">
                    <div class="flex justify-between items-center px-1">
                        <label class="text-[9px] font-black text-zinc-600 uppercase tracking-widest">Secret Key</label>
                        @if (Route::has('password.request'))
                            <a href="{{ route('password.request') }}" class="text-[8px] font-bold text-zinc-700 hover:text-yellow-500 transition-colors uppercase">Lost Key?</a>
                        @endif
                    </div>
                    <input type="password" name="password" required
                        class="w-full bg-zinc-900/50 border border-zinc-800 rounded-xl px-4 py-3 text-sm text-white focus:outline-none focus:border-yellow-500 transition-all placeholder-zinc-700"
                        placeholder="••••••••">
                </div>

                <label class="flex items-center gap-3 cursor-pointer group w-fit">
                    <input type="checkbox" name="remember" class="w-3 h-3 rounded bg-zinc-900 border-zinc-800 text-yellow-500 focus:ring-0">
                    <span class="text-[9px] font-bold text-zinc-600 group-hover:text-zinc-400 transition-colors uppercase tracking-widest">Maintain Session</span>
                </label>

                <button type="submit" class="w-full bg-yellow-500 text-black font-black py-4 rounded-xl text-[11px] uppercase tracking-[0.2em] hover:bg-white transition-all transform active:scale-[0.98]">
                    Initialise Login
                </button>
            </form>

            <div class="relative flex py-8 items-center">
                <div class="flex-grow border-t border-zinc-900"></div>
                <span class="flex-shrink mx-4 text-[9px] font-black text-zinc-800 uppercase tracking-widest">Alternate</span>
                <div class="flex-grow border-t border-zinc-900"></div>
            </div>

            <a href="{{ route('google.login') }}" 
                class="flex items-center justify-center gap-3 w-full bg-white text-black font-black py-4 rounded-xl border border-zinc-200 hover:bg-zinc-200 transition-all uppercase text-[10px] tracking-widest">
                <img src="https://www.gstatic.com/images/branding/product/1x/gsa_512dp.png" class="w-5 h-5">
                Auth with Google
            </a>

            <p class="text-center mt-10 text-[9px] font-bold text-zinc-700 uppercase tracking-widest">
                No access? <a href="{{ route('register') }}" class="text-yellow-500 hover:underline">Request Account</a>
            </p>
        </div>
    </div>
</body>
</html>