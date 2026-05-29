<x-guest-layout>
    <x-auth-session-status class="mb-4 absolute top-5 left-5 z-50" :status="session('status')" />


    <div class="h-screen w-full flex flex-col md:flex-row bg-white overflow-hidden select-none">

        {{-- LOGIN FORM --}}
        <div class="w-full md:w-1/2 h-full flex flex-col justify-center items-center px-6 py-4 lg:px-16 bg-white">

        

            <div class="w-full max-w-[320px] flex flex-col justify-center">

                {{-- KMD LOGO IMAGE --}}
                <div class="flex justify-center mb-31">
                    <img src="{{ asset('images/kmd.jpeg') }}" class="w-56 h-44 object-contain" alt="KMD Logo">
                </div>

                <form method="POST" action="{{ route('login') }}" class="space-y-3">
                    @csrf

                    {{-- SUBTITLE --}}
                    <p class="text-sm text-slate-400 font-medium mb-1">
                        Enter your email and password to sign in!
                    </p>

                    {{-- Email Input --}}
                    <div>
                        <x-input-label for="email" :value="__('Email')" class="text-sm font-semibold text-slate-700 mb-1 block" />
                        <x-text-input id="email" class="block w-full px-3.5 py-1.5 text-sm bg-white border border-gray-400 rounded-lg text-slate-700 focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500 shadow-none transition-all" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" />
                        
                     
                        <div class="min-h-[32px] pt-1 flex items-start">
                            @if($errors->has('email'))
                                <span class="text-xs text-red-600 font-medium leading-tight">
                                    {{ $errors->first('email') }}
                                </span>
                            @endif
                        </div>
                    </div>

                    {{-- Password Input --}}
                    <div>
                        <x-input-label for="password" :value="__('Password')" class="text-sm font-semibold text-slate-700 mb-1 block" />
                        <x-text-input id="password" class="block w-full px-3.5 py-1.5 text-sm bg-white border border-gray-400 rounded-lg text-slate-700 focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500 shadow-none transition-all" type="password" name="password" required autocomplete="current-password" />
                        
                 
                        <div class="min-h-[20px] pt-1 flex items-start">
                            <x-input-error :messages="$errors->get('password')" class="text-xs m-0" />
                        </div>
                    </div>

                    {{-- Remember Me --}}
                    <div class="block pt-0.5">
                        <label for="remember_me" class="inline-flex items-center cursor-pointer">
                            <input id="remember_me" type="checkbox" class="rounded border-gray-400 text-blue-600 shadow-sm focus:ring-blue-500 w-4 h-4" name="remember">
                            <span class="ms-2 text-sm text-slate-500 font-medium">{{ __('Remember me') }}</span>
                        </label>
                    </div>

                    {{-- Buttons --}}
                    <div class="flex items-center justify-between pt-2">
                        <div>
                            @if (Route::has('password.request'))
                            <a class="text-sm text-slate-400 hover:text-slate-600 transition-colors underline focus:outline-none" href="{{ route('password.request') }}">
                                {{ __('Forgot your password?') }}
                            </a>
                            @endif
                        </div>

                        <div>
                            <button type="submit" class="w-32 py-2 bg-[#0D1527] hover:bg-[#1a2744] text-white text-sm font-semibold rounded-lg shadow-md active:scale-95 transition-all text-center">
                                {{ __('Log in') }}
                            </button>
                        </div>
                    </div>
                </form>

            </div>

        </div>

        {{-- FULL BACKGROUND IMAGE BANNER --}}
        <div class="hidden md:block w-1/2 h-full relative bg-slate-900 pointer-events-none">
            <img src="/images/login.jpeg" class="w-full h-full object-cover" alt="Login Banner">
            <div class="absolute inset-0 bg-gradient-to-t from-[#0C1236]/60 via-transparent to-transparent"></div>
        </div>

    </div>
</x-guest-layout>