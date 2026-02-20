<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>LexiCode | Profile Settings</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=JetBrains+Mono:wght@400;700&display=swap');
        body { font-family: 'JetBrains Mono', monospace; background: black; color: white; }
        [x-cloak] { display: none !important; }
        
        .glow-border:focus-within {
            border-color: #eab308;
            box-shadow: 0 0 15px rgba(234, 179, 8, 0.2);
        }
    </style>
</head>
<body class="bg-black text-zinc-300">

    <nav class="border-b border-yellow-500/20 py-4 px-6 bg-zinc-950/50 backdrop-blur-md sticky top-0 z-50">
        <div class="max-w-4xl mx-auto flex justify-between items-center">
            <a href="{{ route('dashboard') }}" class="text-zinc-500 hover:text-yellow-500 transition-colors text-[10px] font-black uppercase tracking-widest">
                << Back to Terminal
            </a>
            <span class="text-yellow-500 font-black text-sm uppercase tracking-tighter italic">User_Settings</span>
        </div>
    </nav>

    <main class="max-w-4xl mx-auto py-12 px-6 space-y-12">
        
        <section>
            <h1 class="text-4xl font-black text-white tracking-tighter uppercase mb-2">Profile_Management<span class="text-yellow-500">.</span></h1>
            <p class="text-zinc-600 text-xs uppercase tracking-widest font-bold">Manage your identity and security protocols.</p>
        </section>

        @if (session('status') === 'profile-updated')
            <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 5000)" 
                 class="p-4 bg-yellow-500/10 border border-yellow-500/50 text-yellow-500 text-[10px] font-black uppercase tracking-widest">
                >> DATA_PATCH_SUCCESSFUL: USER_METADATA_SYNCHRONIZED
            </div>
        @endif

        @if (session('status') === 'password-updated')
            <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 5000)" 
                 class="p-4 bg-green-500/10 border border-green-500/50 text-green-500 text-[10px] font-black uppercase tracking-widest">
                >> SECURITY_PATCH_SUCCESSFUL: PASSWORD_ENCRYPTED
            </div>
        @endif

        <div class="bg-zinc-950 border border-zinc-900 p-8 rounded-2xl shadow-xl">
            <h3 class="text-yellow-500 text-[10px] font-black uppercase tracking-[0.3em] mb-6">Identity_Information</h3>
            
            <form method="post" action="{{ route('profile.update') }}" class="space-y-6">
                @csrf
                @method('patch')

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="space-y-2">
                        <label class="text-[9px] font-black text-zinc-600 uppercase tracking-widest ml-1">Display Name</label>
                        <input type="text" name="name" value="{{ old('name', $user->name) }}" required
                            class="w-full bg-zinc-900 border border-zinc-800 rounded-xl px-4 py-3 text-sm text-white focus:outline-none focus:border-yellow-500 transition-all">
                        @error('name') <p class="text-red-500 text-[9px] font-bold uppercase">{{ $message }}</p> @enderror
                    </div>

                    <div class="space-y-2">
                        <label class="text-[9px] font-black text-zinc-600 uppercase tracking-widest ml-1">Email Address</label>
                        <input type="email" name="email" value="{{ old('email', $user->email) }}" required
                            class="w-full bg-zinc-900 border border-zinc-800 rounded-xl px-4 py-3 text-sm text-white opacity-50 cursor-not-allowed" readonly>
                    </div>
                </div>

                <div class="flex items-center gap-4">
                    <button type="submit" class="bg-yellow-500 text-black font-black px-6 py-3 rounded-xl text-[10px] uppercase tracking-widest hover:bg-white transition-all shadow-lg shadow-yellow-500/10">
                        Update_Metadata
                    </button>
                </div>
            </form>
        </div>

        <div class="bg-zinc-950 border border-zinc-900 p-8 rounded-2xl shadow-xl">
            <h3 class="text-blue-500 text-[10px] font-black uppercase tracking-[0.3em] mb-6">Security_Override</h3>
            
            <form method="post" action="{{ route('password.update') }}" class="space-y-6">
                @csrf
                @method('put')

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="space-y-2">
                        <label class="text-[9px] font-black text-zinc-600 uppercase tracking-widest ml-1">Current Password</label>
                        <input type="password" name="current_password" class="w-full bg-zinc-900 border border-zinc-800 rounded-xl px-4 py-3 text-sm text-white focus:outline-none focus:border-blue-500 transition-all">
                        @error('current_password', 'updatePassword') <p class="text-red-500 text-[9px] font-bold uppercase">{{ $message }}</p> @enderror
                    </div>

                    <div class="space-y-2">
                        <label class="text-[9px] font-black text-zinc-600 uppercase tracking-widest ml-1">New Secret Key</label>
                        <input type="password" name="password" class="w-full bg-zinc-900 border border-zinc-800 rounded-xl px-4 py-3 text-sm text-white focus:outline-none focus:border-blue-500 transition-all">
                        @error('password', 'updatePassword') <p class="text-red-500 text-[9px] font-bold uppercase">{{ $message }}</p> @enderror
                    </div>
                </div>

                <button type="submit" class="border border-blue-500/50 text-blue-500 font-black px-6 py-3 rounded-xl text-[10px] uppercase tracking-widest hover:bg-blue-500 hover:text-white transition-all">
                    Patch_Security
                </button>
            </form>
        </div>

        <div class="border border-red-900/30 bg-red-900/5 p-8 rounded-2xl" x-data="{ confirmDelete: false }">
            <h3 class="text-red-600 text-[10px] font-black uppercase tracking-[0.3em] mb-4">Danger_Zone</h3>
            <p class="text-zinc-500 text-xs mb-6 max-w-xl">Permanently purge your account and all stored repositories. Once the deletion process is initiated, it cannot be intercepted.</p>
            
            <button @click="confirmDelete = true" class="text-red-900 text-[10px] font-bold hover:text-red-500 uppercase tracking-widest transition-colors">
                Initiate_Self_Destruct >>
            </button>

            <div x-show="confirmDelete" x-cloak class="fixed inset-0 z-[100] flex items-center justify-center p-4 bg-black/95 backdrop-blur-md">
                <div @click.away="confirmDelete = false" class="bg-zinc-950 border border-red-900/50 p-8 rounded-2xl max-w-md w-full shadow-2xl">
                    <h2 class="text-xl font-black text-red-600 uppercase mb-4 tracking-tighter italic">Final_Confirmation</h2>
                    <p class="text-zinc-400 text-xs mb-6 leading-relaxed">Are you absolutely sure? All code snippets, modules, and project data will be wiped from the mainframes.</p>

                    <form method="post" action="{{ route('profile.destroy') }}" class="space-y-4">
                        @csrf
                        @method('delete')

                        @if($user->password)
                        <div class="space-y-2">
                            <label class="text-[9px] font-black text-zinc-600 uppercase tracking-widest">Verify Password</label>
                            <input type="password" name="password" required 
                                   class="w-full bg-zinc-900 border border-red-900/30 rounded-xl px-4 py-3 text-sm text-white focus:outline-none focus:border-red-600 transition-all"
                                   placeholder="Enter password to authorize...">
                        </div>
                        @endif

                        <div class="flex gap-4 pt-4">
                            <button type="button" @click="confirmDelete = false" class="flex-1 px-6 py-3 border border-zinc-800 text-[10px] font-black uppercase tracking-widest hover:bg-zinc-900 rounded-xl">Cancel</button>
                            <button type="submit" class="flex-1 bg-red-600 text-white font-black px-6 py-3 rounded-xl text-[10px] uppercase tracking-widest hover:bg-red-700 shadow-lg shadow-red-900/20 transition-all">TERMINATE</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

    </main>

    <footer class="py-12 text-center">
        <p class="text-[9px] font-bold text-zinc-800 uppercase tracking-[0.5em]">System_Version_2.0 // Secured_Access</p>
    </footer>

</body>
</html>