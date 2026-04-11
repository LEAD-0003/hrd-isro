<div class="relative min-h-screen w-full overflow-hidden flex bg-[#0a192f]">

    <div class="absolute top-8 left-8 z-30">
        <img src="{{ asset('images/logo.png') }}" alt="ISRO" class="h-18 w-auto drop-shadow-lg">
    </div>

    <div class="absolute inset-y-0 left-[-6.5%] bottom-[-13%] w-[50%] z-0 flex items-end justify-center">
        <img src="{{ asset('images/rocket.png') }}" alt="Rocket Launch"
            class="w-full h-auto max-h-[90vh] object-contain drop-shadow-2xl">
    </div>

    <div class="relative z-10 w-full lg:w-[65%] ml-auto h-screen bg-white lg:rounded-l-[4rem] shadow-2xl flex flex-col justify-center px-8 sm:px-12 md:px-20 py-12">

        <div class="w-full max-w-xl space-y-10">
            <div>
                <h2 class="text-4xl font-bold text-gray-900 font-sans mb-4">Reset Password</h2>
                <p class="text-gray-500 text-lg leading-relaxed max-w-md">
                    Secure your account by entering a new password below.
                </p>
            </div>

            <form wire:submit.prevent="resetPassword" class="space-y-10">

                <div class="relative">
                    <input wire:model="email" type="email" id="email" placeholder=" "
                        class="peer w-full border-b border-gray-300 py-3 text-gray-900 focus:border-sky-500 focus:outline-none bg-transparent font-sans text-lg placeholder-transparent" />
                    <label for="email"
                        class="absolute left-0 -top-3.5 text-gray-500 text-sm transition-all peer-placeholder-shown:text-lg peer-placeholder-shown:text-gray-400 peer-placeholder-shown:top-3 peer-focus:-top-3.5 peer-focus:text-sky-500 cursor-text">
                        Confirm Email Address
                    </label>
                    @error('email') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                </div>

                <div class="relative">
                    <input wire:model="password" type="password" id="password" placeholder=" "
                        class="peer w-full border-b border-gray-300 py-3 text-gray-900 focus:border-sky-500 focus:outline-none bg-transparent font-sans text-lg placeholder-transparent" />
                    <label for="password"
                        class="absolute left-0 -top-3.5 text-gray-500 text-sm transition-all peer-placeholder-shown:text-lg peer-placeholder-shown:text-gray-400 peer-placeholder-shown:top-3 peer-focus:-top-3.5 peer-focus:text-sky-500 cursor-text">
                        New Password
                    </label>
                    @error('password') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                </div>

                <div class="relative">
                    <input wire:model="password_confirmation" type="password" id="password_confirmation" placeholder=" "
                        class="peer w-full border-b border-gray-300 py-3 text-gray-900 focus:border-sky-500 focus:outline-none bg-transparent font-sans text-lg placeholder-transparent" />
                    <label for="password_confirmation"
                        class="absolute left-0 -top-3.5 text-gray-500 text-sm transition-all peer-placeholder-shown:text-lg peer-placeholder-shown:text-gray-400 peer-placeholder-shown:top-3 peer-focus:-top-3.5 peer-focus:text-sky-500 cursor-text">
                        Confirm Password
                    </label>
                </div>

                <div class="pt-4">
                    <button type="submit" wire:loading.attr="disabled"
                        class="w-full py-4 bg-[#5BB2F9] text-white font-bold rounded-lg hover:bg-[#4aa3e8] shadow-lg transition-all transform hover:-translate-y-0.5 disabled:opacity-50">
                        <span wire:loading.remove>Update Password</span>
                        <span wire:loading class="flex items-center justify-center">
                            <svg class="animate-spin h-5 w-5 mr-3 border-2 border-white border-t-transparent rounded-full" viewBox="0 0 24 24"></svg>
                            Updating...
                        </span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>