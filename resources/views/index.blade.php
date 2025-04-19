<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    @vite('resources/css/app.css')
    <title>Welcome to Courton</title>
</head>

<body class="flex flex-col h-screen px-36 py-4 bg-neutral-50 text-neutral-900 font-normal">
    <header class="flex flex-row justify-between items-center">
        <div class="flex-1 flex">
            <a href="" class="text-xl font-semibold">Courton</a>
        </div>

        <nav class="flex-1 flex justify-center">
            <ul class="flex flex-row gap-x-6">
                <li><a href="{{ route('index') }}" class="btn-base hover:text-neutral-900/70">Home</a></li>
                <li><a href="#court-section" class="btn-base hover:text-neutral-900/70">Court</a></li>
                <li><a href="#pricing-section" class="btn-base hover:text-neutral-900/70">Pricing</a></li>
            </ul>
        </nav>

        <div class="flex-1 flex justify-end gap-x-2.5">
            <a href="{{ route('login') }}" class="btn-filled-tonal">Login</a>
            <a href="{{ route('signup') }}" class="btn-filled">Signup</a>
        </div>
    </header>

    <main class="flex-1 mt-32 space-y-32">
        <!-- HOME -->
        <section class="relative flex justify-center h-96 rounded-4xl bg-[url('/public/images/hero-bg.jpg')] bg-cover bg-center">
            <!-- Gradient Overlay -->
            <div class="absolute inset-0 bg-gradient-to-b from-neutral-900/40 to-neutral-900/0 backdrop-blur-[4px] rounded-4xl"></div>

            <!-- Content -->
            <div class="relative z-10 flex flex-col gap-y-8 items-center justify-center">
                <div class="flex flex-col gap-6  text-neutral-50">
                    <div class="flex flex-col items-center gap-2">
                        <a href="https://maps.app.goo.gl/kEfPXqaSkzZq7VNP7" target="_blank" class="flex items-center gap-x-1 text-sm size-fit bg-neutral-500/50 py-2 px-4 rounded-full">
                            <span class="material-symbols-rounded">
                                pin_drop
                            </span>
                            Brgy. Linao Indoor Badminton Court
                        </a>

                        <h1 class="text-8xl font-bold">Game on. Court ready.</h1>
                    </div>

                    <p class="text-center">Lorem ipsum dolor sit amet consectetur, adipisicing elit. In voluptatum deleniti voluptate.</p>
                </div>

                <div class="flex">
                    <a href="{{ route('reservation') }}" class="btn-filled pl-6 pr-[16px]">
                        Book now
                        <span class="material-symbols-rounded md-icon-24">
                            chevron_right
                        </span>
                    </a>
                </div>
            </div>
        </section>

        <!-- COURT -->
        <section class="flex flex-col bg-blue-50 h-full" id="court-section">
            court
        </section>

        <!-- PRICING -->
        <section class="flex flex-col bg-yellow-50 h-full" id="pricing-section">
            pricing
        </section>
    </main>
</body>

</html>