<div class="relative min-h-screen w-full overflow-hidden flex bg-[#0a192f]">

    <div class="absolute top-8 left-8 z-30">
        <img src="{{ asset('images/logo.png') }}" alt="ISRO" class="h-18 w-auto drop-shadow-lg">
    </div>

    <div class="absolute inset-y-0 left-[-6.5%] bottom-[-13%] w-[50%] z-0 flex items-end justify-center">
        <img src="{{ asset('images/rocket.png') }}" 
             alt="Rocket Launch" 
             class="w-full h-auto max-h-[90vh] object-contain drop-shadow-2xl">
    </div>

    <div class="relative z-10 w-full lg:w-[65%] ml-auto h-screen bg-white lg:rounded-l-[4rem] shadow-2xl flex flex-col justify-center px-8 sm:px-12 md:px-20 py-12">
        
        <div class="w-full max-w-xl space-y-12"> 
            
            <div>
                <h2 class="text-4xl font-bold text-gray-900 font-sans mb-4">Forget password</h2>
                <p class="text-gray-500 text-lg leading-relaxed max-w-md">
                    Enter the email address associated with your account and we'll send you link to reset your password.
                </p>
            </div>

            <form wire:submit.prevent="sendResetLink" class="space-y-10">
                
                <div class="relative">
                    <input wire:model="email" 
                           type="email" 
                           id="email" 
                           placeholder=" " 
                           class="peer w-full border-b border-gray-300 py-3 text-gray-900 focus:border-sky-500 focus:outline-none bg-transparent font-sans text-lg placeholder-transparent" 
                           style="box-shadow: 0 0 0 30px white inset !important;" />
                    
                    <label for="email" 
                           class="absolute left-0 -top-3.5 text-gray-500 text-sm transition-all 
                                  peer-placeholder-shown:text-lg peer-placeholder-shown:text-gray-400 peer-placeholder-shown:top-3 
                                  peer-focus:-top-3.5 peer-focus:text-sky-500 peer-focus:text-sm cursor-text font-sans">
                        Email
                    </label>
                    
                    @error('email') <span class="text-red-500 text-xs block mt-1">{{ $message }}</span> @enderror
                </div>

                <div class="pt-4">
                    <button type="submit" class="w-full py-4 px-4 bg-[#5BB2F9] text-white text-lg font-bold rounded-lg hover:bg-[#4aa3e8] shadow-lg transition-all transform hover:-translate-y-0.5">
                        Continue
                    </button>
                    
                 
                </div>

            </form>
        </div>
    </div>
</div>