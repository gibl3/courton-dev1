<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    @vite('resources/css/app.css')
    <title>Login</title>
</head>

<body class="h-screen bg-neutral-50 flex flex-col py-4">
    <section
        class="flex-1 flex flex-row items-center justify-center mx-8 sm:mx-16 md:mx-24 lg:mx-32 xl:mx-40">
        <header class="flex flex-col flex-1 h-full items-center justify-center">
            <!-- Login form -->
            <nav class="w-full">
                <a
                    class="flex items-center justify-center gap-2 btn-outline"
                    role="button"
                    href="{{ route('index') }}">
                    <svg
                        viewBox="0 0 24 24"
                        fill="none"
                        xmlns="http://www.w3.org/2000/svg"
                        class="stroke-current size-5">
                        <path
                            d="M9.57 5.93005L3.5 12.0001L9.57 18.0701"
                            stroke-width="2"
                            stroke-miterlimit="10"
                            stroke-linecap="round"
                            stroke-linejoin="round" />
                        <path
                            d="M20.5 12H3.67"
                            stroke-width="2"
                            stroke-miterlimit="10"
                            stroke-linecap="round"
                            stroke-linejoin="round" />
                    </svg>
                    Back
                </a>
            </nav>

            <div class="flex flex-1 flex-col w-full  sm:max-w-md gap-8 justify-center">
                <div
                    class="rounded-xl bg-neutral-100/30 shadow backdrop-blur-[5.9px] border border-neutral-200">
                    <div class="p-4 md:p-6">
                        <h1
                            class="text-xl sm:text-2xl md:text-3xl font-bold leading-tight tracking-tight text-emerald-950 text-center mb-6 md:mb-12">
                            Login to your account
                        </h1>

                        <div
                            class="px-4 py-2.5 text-xs text-red-500 rounded-xl bg-red-100/80 font-normal mb-4 hidden"
                            role="alert"
                            id="alert">
                            <p class="text-red-400 text-xs sm:text-sm">
                                Error: <span id="codeError"></span>
                            </p>
                        </div>

                        <form
                            class="block space-y-4 md:space-y-8"
                            action="#"
                            id="loginForm">
                            <div class="space-y-4 md:space-y-6">
                                <div>
                                    <label
                                        for="email"
                                        class="block mb-2 text-xs md:text-sm font-medium ml-1.5 text-neutral-700">Email</label>
                                    <input
                                        type="email"
                                        name="email"
                                        id="email"
                                        class="bg-neutral-50 border border-gray-400/80 rounded-full w-full py-3 px-4 text-sm focus:outline focus:outline-emerald-200"
                                        placeholder="your.email@xxx.com"
                                        required="" />
                                </div>

                                <div class="flex flex-col">
                                    <label
                                        for="password"
                                        class="block mb-2 text-xs md:text-sm font-medium ml-1.5 text-neutral-700">Password</label>

                                    <input
                                        type="password"
                                        name="password"
                                        id="password"
                                        placeholder="••••••••"
                                        class="bg-neutral-50 border border-gray-400/80 rounded-full block w-full py-3 px-4 text-sm focus:outline focus:outline-emerald-200"
                                        required="" />
                                    <a
                                        href="{{ route('forgotPass') }}"
                                        class="btn-link place-self-end mt-2   mr-1.5">Forgot password?</a>
                                </div>
                            </div>

                            <div class="space-y-6 md:space-y-8">
                                <button type="submit" class="w-full btn-filled">Login</button>

                                <div class="flex items-center justify-center gap-x-1">
                                    <p
                                        class="text-xs sm:text-sm text-neutral-700 text-center">
                                        Don’t have an account yet?
                                    </p>
                                    <a href="{{ route('signup') }}" class="btn-link text-rose-500 hover:text-rose-700 text-sm">Sign up</a>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </header>
    </section>
</body>

</html>