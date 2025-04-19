<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    @vite('resources/css/app.css')
    <title>Signup</title>
</head>

<body class="h-screen bg-neutral-50 flex flex-col py-4">
    <header class="mx-8 sm:mx-16 md:mx-24 lg:mx-32 xl:mx-40">
        <nav>
            <a
                class="flex items-center justify-center gap-2 btn-outline"
                role="button"
                href="{{ route('index') }}">
                <svg
                    width="24"
                    height="24"
                    viewBox="0 0 24 24"
                    fill="none"
                    xmlns="http://www.w3.org/2000/svg"
                    class="stroke-current">
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
    </header>

    <section
        class="flex-1 flex flex-row items-center justify-center mx-8 sm:mx-16 md:mx-24 lg:mx-32 xl:mx-40">
        <div class="flex flex-col flex-1 w-full sm:max-w-md">
            <div
                class="rounded-3xl bg-[rgba(207,213,224,0.24)] shadow backdrop-blur-[5.9px] border border-[rgba(185,192,207,0.20)]">
                <div class="p-4 md:p-6">
                    <h1
                        class="mb-6 text-xl font-bold leading-tight tracking-tight text-center sm:text-2xl md:text-3xl text-neutral-800 md:mb-12">
                        Be a new member!
                    </h1>

                    <div
                        class="px-4 py-2.5 text-xs text-red-500 rounded-xl bg-red-100/80 font-normal mb-4 hidden"
                        role="alert"
                        id="alert">
                        <p class="text-xs text-red-400 sm:text-sm">
                            Error: <span id="codeError"></span>
                        </p>
                    </div>

                    <form
                        class="block space-y-6 md:space-y-8"
                        action="#"
                        id="signupForm">
                        <div class="space-y-2 md:space-y-4">
                            <div>
                                <label
                                    for="name"
                                    class="block mb-1 sm:mb-2 text-xs md:text-sm font-medium ml-1.5 text-zinc-700">Name</label>
                                <input
                                    type="text"
                                    name="name"
                                    id="name"
                                    class="w-full px-4 py-3 text-sm border rounded-full bg-zinc-50 border-gray-400/80 focus:outline focus:outline-neutral-200"
                                    placeholder="John Doe"
                                    required="" />
                            </div>

                            <div class="space-y-2 md:space-y-4">
                                <div>
                                    <label
                                        for="email"
                                        class="block mb-1 sm:mb-2 text-xs md:text-sm font-medium ml-1.5 text-zinc-700">Email</label>
                                    <input
                                        type="email"
                                        name="email"
                                        id="email"
                                        class="w-full px-4 py-3 text-sm border rounded-full bg-zinc-50 border-gray-400/80 focus:outline focus:outline-neutral-200"
                                        placeholder="your.email@xxx.com"
                                        required="" />
                                </div>
                            </div>

                            <div class="flex flex-col">
                                <label
                                    for="password"
                                    class="block mb-1 sm:mb-2 text-xs md:text-sm font-medium ml-1.5 text-zinc-700">Password</label>
                                <input
                                    type="password"
                                    name="password"
                                    id="password"
                                    placeholder="••••••••"
                                    class="block w-full px-4 py-3 text-sm border rounded-full bg-zinc-50 border-gray-400/80 focus:outline focus:outline-neutral-200"
                                    required="" />
                            </div>

                            <div class="flex flex-col">
                                <label
                                    for="confirm-password"
                                    class="block mb-1 sm:mb-2 text-xs md:text-sm font-medium ml-1.5 text-zinc-700">Confirm password</label>
                                <input
                                    type="password"
                                    name="confirm-password"
                                    id="confirmPassword"
                                    placeholder="••••••••"
                                    class="block w-full px-4 py-3 text-sm border rounded-full bg-zinc-50 border-gray-400/80 focus:outline focus:outline-neutral-200"
                                    required="" />
                            </div>
                        </div>

                        <div class="space-y-6 md:space-y-8">
                            <button type="submit" class="w-full btn-filled">Login</button>

                            <div class="flex items-center justify-center gap-x-1">
                                <p
                                    class="text-xs sm:text-sm text-neutral-700 text-center">
                                    Already have an account?
                                </p>
                                <a href="{{ route('login') }}" class="btn-link text-rose-500 hover:text-rose-700 text-sm">Login</a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>
</body>

</html>