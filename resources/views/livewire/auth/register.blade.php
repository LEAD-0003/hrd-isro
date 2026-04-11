<div class="relative min-h-screen w-full overflow-hidden flex bg-[#0a192f]">

    <div class="absolute top-8 left-8 z-30">
        <img src="{{ asset('images/logo.png') }}" alt="ISRO" class="h-18 w-auto drop-shadow-lg">
    </div>

    <div class="absolute inset-y-0 left-[-6.5%] bottom-[-13%] w-[50%] z-0 flex items-end justify-center">
        <img src="{{ asset('images/rocket.png') }}" alt="Rocket Launch"
            class="w-full h-auto max-h-[90vh] object-contain drop-shadow-2xl">
    </div>

    <div
        class="relative z-10 w-full lg:w-[65%] ml-auto min-h-screen bg-white lg:rounded-l-[4rem] shadow-2xl flex flex-col justify-start lg:justify-center px-8 lg:px-12 md:px-20 py-12">
        <div class="max-w  space-y-12">


            <div class="pt-44 lg:pt-0 mb-8">
                <h2 class="text-4xl font-bold text-black">Personal Details</h2>
            </div>

            <form wire:submit.prevent="register" class="w-full">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-x-16 gap-y-16">

                    <div class="space-y-10 p-5">

                        <div class="flex items-center justify-between">
                            <label class="text-base text-gray-800 font-medium">Prefix</label>
                            <div class="relative min-w-[280px] w-[280px]">
                                <select wire:model="prefix"
                                    class="w-full border border-sky-400 rounded-lg py-3 px-4 appearance-none bg-white cursor-pointer focus:outline-none focus:border-sky-500 text-gray-700"
                                    style="min-width: 210px;">
                                    <option value="">Select </option>
                                    <option value="Mr">Mr.</option>
                                    <option value="Ms">Ms.</option>
                                    <option value="Dr">Dr.</option>
                                </select>
                                <div
                                    class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-4 text-sky-500">
                                    <svg class="h-5 w-5 stroke-[3]" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"></path>
                                    </svg>
                                </div>
                                @error('prefix')
                                    <span class="text-red-500 text-xs block text-right mt-1">{{ $message }}</span>
                                @enderror
                            </div>

                        </div>

                        <div class="flex items-center justify-between">
                            <label class="text-base text-gray-800 font-medium">Gender</label>
                            <div class="relative min-w-[280px] w-[280px]">
                                <select wire:model="gender"
                                    class="w-full border border-sky-400 rounded-lg py-3 px-4 appearance-none bg-white cursor-pointer focus:outline-none focus:border-sky-500 text-gray-700"
                                    style="min-width: 210px;">
                                    <option value="">Select </option>
                                    <option value="Male">Male</option>
                                    <option value="Female">Female</option>
                                    <option value="Other">Other</option>
                                </select>
                                <div
                                    class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-4 text-sky-500">
                                    <svg class="h-5 w-5 stroke-[3]" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"></path>
                                    </svg>
                                </div>
                                @error('gender')
                                    <span class="text-red-500 text-xs block text-right mt-1">{{ $message }}</span>
                                @enderror
                            </div>

                        </div>

                        <div class="flex items-center justify-between">
                            <label class="text-base text-gray-800 font-medium">Employee Code</label>
                            <div class="relative min-w-[280px] w-[280px]">
                             <input type="text" wire:model="employee_code"
                                    class="w-full border border-sky-400 rounded-lg py-3 px-4 focus:outline-none focus:border-sky-500 text-gray-700">
                                @error('employee_code')
                                    <span class="text-red-500 text-xs block text-right mt-1">{{ $message }}</span>
                                @enderror
                            </div>

                        </div>

                        <div class="flex items-center justify-between">
                            <label class="text-base text-gray-800 font-medium">Centre</label>
                            <div class="relative min-w-[280px] w-[280px]">
                                <select wire:model="centre"
                                    class="w-full border border-sky-400 rounded-lg py-3 px-4 appearance-none bg-white cursor-pointer focus:outline-none focus:border-sky-500 text-gray-700"
                                    style="min-width: 210px;">
                                    <option value="">Select </option>
                                    <option value="ISRO HQ">ISRO HQ</option>
                                    <option value="VSSC">VSSC</option>
                                    <option value="SDSC">SDSC</option>
                                </select>
                                <div
                                    class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-4 text-sky-500">
                                    <svg class="h-5 w-5 stroke-[3]" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"></path>
                                    </svg>
                                </div>
                                @error('centre')
                                    <span class="text-red-500 text-xs block text-right mt-1">{{ $message }}</span>
                                @enderror
                            </div>

                        </div>

                        <div class="flex items-center justify-between">
                            <label class="text-base text-gray-800 font-medium">Email Id</label>
                            <div class="min-w-[280px] w-[280px]">
                                <input type="email" wire:model="email"
                                    class="w-full border border-sky-400 rounded-lg py-3 px-4 focus:outline-none focus:border-sky-500 text-gray-700">
                                @error('email')
                                    <span class="text-red-500 text-xs block text-right mt-1">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <div class="flex items-center justify-between">
                            <label class="text-base text-gray-800 font-medium">Password</label>
                            <div class="min-w-[280px] w-[280px]">
                                <input type="password" wire:model="password"
                                    class="w-full border border-sky-400 rounded-lg py-3 px-4 focus:outline-none focus:border-sky-500 text-gray-700">
                                @error('password')
                                    <span class="text-red-500 text-xs block text-right mt-1">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="space-y-10 p-5">

                        <div class="flex items-center justify-between">
                            <label class="text-base text-gray-800 font-medium">Full Name</label>
                            <div class="min-w-[280px] w-[280px]">
                                <input type="text" wire:model="name"
                                    class="w-full border border-sky-400 rounded-lg py-3 px-4 focus:outline-none focus:border-sky-500 text-gray-700">
                                @error('name')
                                    <span class="text-red-500 text-xs block text-right mt-1">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <div class="flex items-center justify-between">
                            <label class="text-base text-gray-800 font-medium">Phone Number</label>
                            <div class="min-w-[280px] w-[280px]">
                                <input type="text" wire:model="phone"
                                    class="w-full border border-sky-400 rounded-lg py-3 px-4 focus:outline-none focus:border-sky-500 text-gray-700">
                                @error('phone')
                                    <span class="text-red-500 text-xs block text-right mt-1">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <div class="flex items-center justify-between">
                            <label class="text-base text-gray-800 font-medium">Designation</label>
                            <div class="relative min-w-[280px] w-[280px]">
                                <select wire:model="designation"
                                    class="w-full border border-sky-400 rounded-lg py-3 px-4 appearance-none bg-white cursor-pointer focus:outline-none focus:border-sky-500 text-gray-700"
                                    style="min-width: 210px;">
                                    <option value="">Select </option>
                                    <option value="Scientist">Scientist</option>
                                    <option value="Engineer">Engineer</option>
                                </select>
                                <div
                                    class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-4 text-sky-500">
                                    <svg class="h-5 w-5 stroke-[3]" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7">
                                        </path>
                                    </svg>
                                </div>
                                @error('designation')
                                    <span class="text-red-500 text-xs block text-right mt-1">{{ $message }}</span>
                                @enderror
                            </div>

                        </div>

                        <div class="flex items-center justify-between">
                            <label class="text-base text-gray-800 font-medium">DOB</label>
                            <div class="min-w-[280px] w-[280px]">
                                <input type="date" wire:model="dob"
                                    class="w-full border border-sky-400 rounded-lg py-3 px-4 focus:outline-none focus:border-sky-500 text-gray-700"
                                    style="min-width: 210px;">
                                @error('dob')
                                    <span class="text-red-500 text-xs block text-right mt-1">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <div class="flex items-center justify-between">
                            <label class="text-base text-gray-800 font-medium">User Name</label>
                            <div class="min-w-[280px] w-[280px]">
                                <input type="text" wire:model="username"
                                    class="w-full border border-sky-400 rounded-lg py-3 px-4 focus:outline-none focus:border-sky-500 text-gray-700">
                                @error('username')
                                    <span class="text-red-500 text-xs block text-right mt-1">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <div class="flex items-center justify-between">
                            <label class="text-base text-gray-800 font-medium">Confirm password</label>
                            <div class="min-w-[280px] w-[280px]">
                                <input type="password" wire:model="password_confirmation"
                                    class="w-full border border-sky-400 rounded-lg py-3 px-4 focus:outline-none focus:border-sky-500 text-gray-700">
                            </div>
                        </div>
                    </div>
                </div>

                <div class="flex justify-end mt-12 pr-2">
                    <button type="submit"
                        class="bg-[#5BB2F9] hover:bg-[#4aa3e8] text-white font-bold py-4 p-5 px-14 rounded-xl shadow-lg transition-all transform hover:scale-105 active:scale-95 text-lg">
                        Create Account
                    </button>
                </div>
            </form>


        </div>
    </div>
</div>
