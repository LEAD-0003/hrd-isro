<div class="relative min-h-screen w-full overflow-hidden flex bg-[#0a192f]">

    <div class="absolute top-8 left-8 z-30">
        <img src="{{ asset('images/logo.png') }}" alt="ISRO" class="h-18 w-auto drop-shadow-lg">
    </div>

    <div class="absolute inset-y-0 left-[-6.5%] bottom-[-13%] w-[50%] z-0 flex items-end justify-center">
        <img src="{{ asset('images/rocket.png') }}" alt="Rocket Launch"
            class="w-full h-auto max-h-[90vh] object-contain drop-shadow-2xl">
    </div>

    <div
        class="relative z-10 w-full lg:w-[65%] ml-auto h-screen bg-white lg:rounded-l-[4rem] shadow-2xl flex flex-col justify-center px-8 sm:px-12 md:px-20 py-12">

        <div class="w-full max-w-xl space-y-12">

            <div>
                <h2 class="text-4xl font-bold text-gray-900 font-sans">Login</h2>
            </div>

            <form wire:submit.prevent="authenticate" class="space-y-10" autocomplete="off">

                <!-- LOGIN FIELD -->
                <div class="relative">
                    <input wire:model="login" type="text" id="login"
                        autocomplete="one-time-code"
                        placeholder=" "
                        class="peer w-full border-b border-gray-300 py-3 text-gray-900 focus:border-sky-500 focus:outline-none bg-transparent font-sans text-lg placeholder-transparent"
                        style="box-shadow: 0 0 0 30px white inset !important;" />

                    <label for="login"
                        class="absolute left-0 -top-3.5 text-gray-500 text-sm transition-all 
                        peer-placeholder-shown:text-lg peer-placeholder-shown:text-gray-400 peer-placeholder-shown:top-3 
                        peer-focus:-top-3.5 peer-focus:text-sky-500 peer-focus:text-sm cursor-text font-sans">
                        Name or Email
                    </label>

                    @error('login')
                        <span class="text-red-500 text-xs block mt-1">{{ $message }}</span>
                    @enderror
                </div>

                <!-- PASSWORD FIELD -->
                <div class="relative" x-data="{ show: false }">

                    <input wire:model="password"
                        :type="show ? 'text' : 'password'"
                        id="password"
                        autocomplete="new-password"
                        placeholder=" "
                        class="peer w-full border-b border-gray-300 py-3 text-gray-900 focus:border-sky-500 focus:outline-none bg-transparent pr-10 font-sans text-lg placeholder-transparent"
                        style="box-shadow: 0 0 0 30px white inset !important;" />

                    <label for="password"
                        class="absolute left-0 -top-3.5 text-gray-500 text-sm transition-all 
                        peer-placeholder-shown:text-lg peer-placeholder-shown:text-gray-400 peer-placeholder-shown:top-3 
                        peer-focus:-top-3.5 peer-focus:text-sky-500 peer-focus:text-sm cursor-text font-sans">
                        Password
                    </label>

                    @error('password')
                        <span class="text-red-500 text-xs block mt-1">{{ $message }}</span>
                    @enderror

                    <!-- SHOW PASSWORD -->
                    <button type="button"
                        @click="show = !show"
                        class="absolute right-0 top-3 text-gray-400 hover:text-gray-600 focus:outline-none">

                        <svg x-show="!show" xmlns="http://www.w3.org/2000/svg"
                            fill="none" viewBox="0 0 24 24"
                            stroke-width="1.5" stroke="currentColor"
                            class="w-5 h-5">

                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M3.98 8.223A10.477 10.477 0 001.934 12C3.226 16.338 7.244 19.5 12 19.5
                                c.993 0 1.953-.138 2.863-.395M6.228 6.228A10.45 10.45 0
                                0112 4.5c4.756 0 8.773 3.162
                                10.065 7.498a10.523 10.523 0
                                01-4.293 5.774M6.228 6.228L3 3m3.228
                                3.228l3.65 3.65m7.894
                                7.894L21 21m-3.228-3.228l-3.65-3.65m0
                                0a3 3 0 10-4.243-4.243m4.242
                                4.242L9.88 9.88" />
                        </svg>

                        <svg x-show="show" xmlns="http://www.w3.org/2000/svg"
                            fill="none" viewBox="0 0 24 24"
                            stroke-width="1.5" stroke="currentColor"
                            class="w-5 h-5" x-cloak>

                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M2.036 12.322a1.012 1.012 0
                                010-.639C3.423 7.51 7.36
                                4.5 12 4.5c4.638 0
                                8.573 3.007 9.963
                                7.178.07.207.07.431
                                0 .639C20.577 16.49
                                16.64 19.5 12
                                19.5c-4.638 0-8.573-3.007-9.963-7.178z" />

                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M15 12a3 3 0 11-6
                                0 3 3 0 016 0z" />
                        </svg>

                    </button>
                </div>

                <!-- FORGOT PASSWORD -->
                <div class="flex justify-end -mt-6">
                    <a href="{{ route('password.request') }}"
                        class="text-sm text-sky-600 hover:text-sky-800 font-medium">
                        Forgot Password?
                    </a>
                </div>

                <!-- LOGIN BUTTON -->
                <div class="pt-4">
                    <button type="submit"
                        class="w-full py-4 px-4 bg-[#5BB2F9] text-white text-lg font-bold rounded-lg hover:bg-[#4aa3e8] shadow-lg transition-all transform hover:-translate-y-0.5">
                        Login
                    </button>
                </div>

            </form>

        </div>
    </div>
</div>