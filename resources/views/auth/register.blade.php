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
        body { font-family: 'JetBrains Mono', monospace; background: black; color: white; }
    </style>
</head>
<body class="antialiased bg-black">
    <div class="min-h-screen flex flex-col items-center justify-center p-6">
        <div class="w-full max-w-md bg-zinc-950 border border-zinc-900 p-10 rounded-3xl shadow-[0_20px_50px_rgba(0,0,0,1)]">
            
            <div class="text-center mb-10">
                <h1 class="text-3xl font-black text-yellow-500 tracking-tighter uppercase">REGISTER</h1>
                <p class="text-zinc-500 text-[10px] font-bold tracking-[0.3em] uppercase mt-2">Join the Repository</p>
            </div>

            <form method="POST" action="{{ route('register') }}" class="space-y-5">
                @csrf

                <div class="space-y-1">
                    <label class="text-[9px] font-black text-zinc-600 uppercase tracking-widest ml-1">Full Name</label>
                    <input type="text" name="name" value="{{ old('name') }}" required
                        class="w-full bg-zinc-900/50 border border-zinc-800 rounded-xl px-4 py-3 text-sm text-white focus:outline-none focus:border-yellow-500 transition-all"
                        placeholder="John Doe">
                    @error('name') <p class="text-[10px] text-red-500 mt-1 font-bold">{{ $message }}</p> @enderror
                </div>

                <div class="space-y-1">
                    <label class="text-[9px] font-black text-zinc-600 uppercase tracking-widest ml-1">Email</label>
                    <input type="email" name="email" value="{{ old('email') }}" required
                        class="w-full bg-zinc-900/50 border border-zinc-800 rounded-xl px-4 py-3 text-sm text-white focus:outline-none focus:border-yellow-500 transition-all"
                        placeholder="email@example.com">
                    @error('email') <p class="text-[10px] text-red-500 mt-1 font-bold">{{ $message }}</p> @enderror
                </div>

                <div class="space-y-1">
                    <label class="text-[9px] font-black text-zinc-600 uppercase tracking-widest ml-1">Password</label>
                    <input type="password" name="password" required
                        class="w-full bg-zinc-900/50 border border-zinc-800 rounded-xl px-4 py-3 text-sm text-white focus:outline-none focus:border-yellow-500 transition-all"
                        placeholder="••••••••">
                    @error('password') <p class="text-[10px] text-red-500 mt-1 font-bold">{{ $message }}</p> @enderror
                </div>

                <div class="space-y-1">
                    <label class="text-[9px] font-black text-zinc-600 uppercase tracking-widest ml-1">Verify Password</label>
                    <input type="password" name="password_confirmation" required
                        class="w-full bg-zinc-900/50 border border-zinc-800 rounded-xl px-4 py-3 text-sm text-white focus:outline-none focus:border-yellow-500 transition-all"
                        placeholder="••••••••">
                </div>

                <button type="submit" class="w-full bg-white text-black font-black py-4 rounded-xl text-[11px] uppercase tracking-[0.2em] hover:bg-yellow-500 transition-all mt-4 transform active:scale-[0.98]">
                    Register Account
                </button>
            </form>

            <p class="text-center mt-10 text-[9px] font-bold text-zinc-700 uppercase tracking-widest">
                Already registered? <a href="{{ route('login') }}" class="text-yellow-500 hover:underline"> Login</a>
            </p>
        </div>
    </div>
</body>
</html>