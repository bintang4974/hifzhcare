<!DOCTYPE html>
<html lang="id" class="scroll-smooth">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>HifzhCare — Kelola Hafalan Santri Lebih Mudah & Terstruktur</title>
    <meta name="description"
        content="Aplikasi monitoring hafalan santri berbasis web untuk pondok pesantren. Kelola hafalan santri dengan mudah, terstruktur, dan transparan.">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Inter', 'system-ui', 'sans-serif'],
                    }
                }
            }
        }
    </script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap"
        rel="stylesheet">
    <style>
        ::-webkit-scrollbar {
            width: 6px;
        }

        ::-webkit-scrollbar-track {
            background: #f1f5f9;
        }

        ::-webkit-scrollbar-thumb {
            background: #059669;
            border-radius: 3px;
        }

        .fade-up {
            opacity: 0;
            transform: translateY(30px);
            transition: opacity 0.7s ease, transform 0.7s ease;
        }

        .fade-up.visible {
            opacity: 1;
            transform: translateY(0);
        }

        .fade-up.delay-1 {
            transition-delay: 0.1s;
        }

        .fade-up.delay-2 {
            transition-delay: 0.2s;
        }

        .fade-up.delay-3 {
            transition-delay: 0.3s;
        }

        .fade-up.delay-4 {
            transition-delay: 0.4s;
        }

        .gradient-text {
            background: linear-gradient(135deg, #059669 0%, #7C3AED 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .hero-pattern {
            background-image:
                radial-gradient(circle at 20% 50%, rgba(5, 150, 105, 0.08) 0%, transparent 50%),
                radial-gradient(circle at 80% 20%, rgba(124, 58, 237, 0.06) 0%, transparent 50%),
                radial-gradient(circle at 60% 80%, rgba(5, 150, 105, 0.05) 0%, transparent 50%);
        }

        .islamic-dots {
            background-image: radial-gradient(circle, rgba(5, 150, 105, 0.12) 1px, transparent 1px);
            background-size: 24px 24px;
        }

        .mockup-shadow {
            box-shadow: 0 25px 60px -12px rgba(5, 150, 105, 0.2), 0 0 0 1px rgba(5, 150, 105, 0.05);
        }

        .card-hover {
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .card-hover:hover {
            transform: translateY(-4px);
            box-shadow: 0 20px 40px -12px rgba(0, 0, 0, 0.1);
        }

        .bar-animate {
            animation: barGrow 1.5s ease-out forwards;
            transform-origin: bottom;
        }

        @keyframes barGrow {
            from {
                transform: scaleY(0);
            }

            to {
                transform: scaleY(1);
            }
        }

        @keyframes float {

            0%,
            100% {
                transform: translateY(0px);
            }

            50% {
                transform: translateY(-10px);
            }
        }

        .float-anim {
            animation: float 6s ease-in-out infinite;
        }

        .pricing-popular {
            box-shadow: 0 0 0 2px #059669, 0 20px 40px -12px rgba(5, 150, 105, 0.25);
        }
    </style>
</head>

<body class="bg-white font-sans text-slate-800 antialiased">

    <!-- ========== NAVBAR ========== -->
    <nav id="navbar"
        class="fixed top-0 left-0 right-0 z-50 bg-white/80 backdrop-blur-lg border-b border-slate-100 transition-all duration-300">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16 md:h-20">
                <!-- Logo -->
                <a href="#" class="flex items-center gap-2.5">
                    <div
                        class="w-9 h-9 rounded-xl bg-gradient-to-br from-emerald-500 to-emerald-700 flex items-center justify-center shadow-md shadow-emerald-200">
                        <svg class="w-5 h-5 text-white" fill="none" viewBox="0 0 24 24" stroke-width="2"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                        </svg>
                    </div>
                    <span class="text-lg font-bold text-emerald-800 tracking-tight">HifzhCare</span>
                </a>
                <!-- Desktop Nav -->
                <div class="hidden md:flex items-center gap-8">
                    <a href="#masalah"
                        class="text-sm font-medium text-slate-500 hover:text-emerald-600 transition-colors">Masalah</a>
                    <a href="#solusi"
                        class="text-sm font-medium text-slate-500 hover:text-emerald-600 transition-colors">Solusi</a>
                    <a href="#fitur"
                        class="text-sm font-medium text-slate-500 hover:text-emerald-600 transition-colors">Fitur</a>
                    <a href="#harga"
                        class="text-sm font-medium text-slate-500 hover:text-emerald-600 transition-colors">Harga</a>
                </div>
                <!-- CTA -->
                <div class="hidden md:flex items-center gap-3">
                    <a href="https://wa.me/6281234567890?text=Assalamualaikum%2C%20saya%20tertarik%20dengan%20HifzhCare"
                        target="_blank"
                        class="text-sm font-medium text-slate-600 hover:text-emerald-600 transition-colors px-4 py-2">Hubungi
                        Kami</a>
                    <a href="https://wa.me/6281234567890?text=Assalamualaikum%2C%20saya%20ingin%20minta%20demo%20HifzhCare"
                        target="_blank"
                        class="text-sm font-semibold text-white bg-gradient-to-r from-emerald-600 to-emerald-700 hover:from-emerald-700 hover:to-emerald-800 px-5 py-2.5 rounded-xl shadow-md shadow-emerald-200 transition-all duration-200">Minta
                        Demo</a>
                </div>
                <!-- Mobile Menu Button -->
                <button id="mobileMenuBtn" class="md:hidden p-2 rounded-lg hover:bg-slate-100 transition-colors"
                    aria-label="Menu">
                    <svg id="menuIconOpen" class="w-6 h-6 text-slate-700" fill="none" viewBox="0 0 24 24"
                        stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5" />
                    </svg>
                    <svg id="menuIconClose" class="w-6 h-6 text-slate-700 hidden" fill="none" viewBox="0 0 24 24"
                        stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
        <!-- Mobile Menu -->
        <div id="mobileMenu" class="md:hidden hidden bg-white border-t border-slate-100">
            <div class="px-4 py-4 space-y-1">
                <a href="#masalah"
                    class="block px-4 py-3 text-sm font-medium text-slate-600 hover:bg-emerald-50 hover:text-emerald-700 rounded-xl transition-colors">Masalah</a>
                <a href="#solusi"
                    class="block px-4 py-3 text-sm font-medium text-slate-600 hover:bg-emerald-50 hover:text-emerald-700 rounded-xl transition-colors">Solusi</a>
                <a href="#fitur"
                    class="block px-4 py-3 text-sm font-medium text-slate-600 hover:bg-emerald-50 hover:text-emerald-700 rounded-xl transition-colors">Fitur</a>
                <a href="#harga"
                    class="block px-4 py-3 text-sm font-medium text-slate-600 hover:bg-emerald-50 hover:text-emerald-700 rounded-xl transition-colors">Harga</a>
                <div class="pt-3 border-t border-slate-100">
                    <a href="https://wa.me/6281234567890?text=Assalamualaikum%2C%20saya%20ingin%20minta%20demo%20HifzhCare"
                        target="_blank"
                        class="block text-center text-sm font-semibold text-white bg-gradient-to-r from-emerald-600 to-emerald-700 px-5 py-3 rounded-xl">Minta
                        Demo Gratis</a>
                </div>
            </div>
        </div>
    </nav>

    <!-- ========== HERO SECTION ========== -->
    <section id="hero" class="relative min-h-screen flex items-center pt-20 md:pt-0 overflow-hidden hero-pattern">
        <!-- Decorative Elements -->
        <div class="absolute top-32 left-10 w-72 h-72 bg-emerald-200/30 rounded-full blur-3xl"></div>
        <div class="absolute bottom-20 right-10 w-96 h-96 bg-violet-200/20 rounded-full blur-3xl"></div>
        <div class="absolute top-1/2 right-1/4 islamic-dots w-48 h-48 rounded-2xl opacity-30"></div>

        <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12 md:py-20">
            <div class="grid lg:grid-cols-2 gap-12 lg:gap-16 items-center">
                <!-- Left: Copy -->
                <div class="text-center lg:text-left">
                    <div
                        class="fade-up inline-flex items-center gap-2 bg-emerald-50 border border-emerald-200 text-emerald-700 text-xs font-semibold px-4 py-2 rounded-full mb-6">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M9.813 15.904L9 18.75l-.813-2.846a4.5 4.5 0 00-3.09-3.09L2.25 12l2.846-.813a4.5 4.5 0 003.09-3.09L9 5.25l.813 2.846a4.5 4.5 0 003.09 3.09L15.75 12l-2.846.813a4.5 4.5 0 00-3.09 3.09zM18.259 8.715L18 9.75l-.259-1.035a3.375 3.375 0 00-2.455-2.456L14.25 6l1.036-.259a3.375 3.375 0 002.455-2.456L18 2.25l.259 1.035a3.375 3.375 0 002.455 2.456L21.75 6l-1.036.259a3.375 3.375 0 00-2.455 2.456z" />
                        </svg>
                        Solusi Digital untuk Pondok Pesantren
                    </div>

                    <h1
                        class="fade-up delay-1 text-4xl sm:text-5xl lg:text-6xl font-extrabold leading-tight tracking-tight text-slate-900 mb-6">
                        Kelola Hafalan Santri Lebih
                        <span class="gradient-text">Mudah, Terstruktur,</span>
                        dan Transparan
                    </h1>

                    <p
                        class="fade-up delay-2 text-lg sm:text-xl text-slate-500 leading-relaxed mb-8 max-w-xl mx-auto lg:mx-0">
                        HifzhCare membantu pondok pesantren dan lembaga tahfidz memantau progres hafalan santri secara
                        digital — dari input setoran harian hingga laporan untuk wali santri.
                    </p>

                    <div class="fade-up delay-3 flex flex-col sm:flex-row gap-4 justify-center lg:justify-start">
                        <a href="https://wa.me/6281234567890?text=Assalamualaikum%2C%20saya%20ingin%20minta%20demo%20HifzhCare"
                            target="_blank"
                            class="inline-flex items-center justify-center gap-2 text-base font-semibold text-white bg-gradient-to-r from-emerald-600 to-emerald-700 hover:from-emerald-700 hover:to-emerald-800 px-8 py-4 rounded-2xl shadow-lg shadow-emerald-200 transition-all duration-200 hover:shadow-xl hover:shadow-emerald-300 hover:-translate-y-0.5">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="2"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M5.25 5.653c0-.856.917-1.398 1.667-.986l11.54 6.348a1.125 1.125 0 010 1.971l-11.54 6.347a1.125 1.125 0 01-1.667-.985V5.653z" />
                            </svg>
                            Coba Demo Gratis
                        </a>
                        <a href="#masalah"
                            class="inline-flex items-center justify-center gap-2 text-base font-semibold text-slate-600 bg-slate-100 hover:bg-slate-200 px-8 py-4 rounded-2xl transition-all duration-200">
                            Pelajari Lebih Lanjut
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M19.5 13.5L12 21m0 0l-7.5-7.5M12 21V3" />
                            </svg>
                        </a>
                    </div>

                    <!-- Trust Badges -->
                    <div
                        class="fade-up delay-4 mt-10 flex items-center gap-6 justify-center lg:justify-start text-sm text-slate-400">
                        <div class="flex items-center gap-1.5">
                            <svg class="w-4 h-4 text-emerald-500" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                    d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.857-9.809a.75.75 0 00-1.214-.882l-3.483 4.79-1.88-1.88a.75.75 0 10-1.06 1.061l2.5 2.5a.75.75 0 001.137-.089l4-5.5z"
                                    clip-rule="evenodd" />
                            </svg>
                            <span>Tanpa instalasi</span>
                        </div>
                        <div class="flex items-center gap-1.5">
                            <svg class="w-4 h-4 text-emerald-500" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                    d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.857-9.809a.75.75 0 00-1.214-.882l-3.483 4.79-1.88-1.88a.75.75 0 10-1.06 1.061l2.5 2.5a.75.75 0 001.137-.089l4-5.5z"
                                    clip-rule="evenodd" />
                            </svg>
                            <span>Data aman</span>
                        </div>
                        <div class="flex items-center gap-1.5">
                            <svg class="w-4 h-4 text-emerald-500" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                    d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.857-9.809a.75.75 0 00-1.214-.882l-3.483 4.79-1.88-1.88a.75.75 0 10-1.06 1.061l2.5 2.5a.75.75 0 001.137-.089l4-5.5z"
                                    clip-rule="evenodd" />
                            </svg>
                            <span>Support 24/7</span>
                        </div>
                    </div>
                </div>

                <!-- Right: Dashboard Mockup -->
                <div class="fade-up delay-2 float-anim">
                    <div class="relative">
                        <!-- Glow -->
                        <div
                            class="absolute -inset-4 bg-gradient-to-r from-emerald-200 to-violet-200 rounded-3xl blur-2xl opacity-40">
                        </div>
                        <!-- Mockup Card -->
                        <div
                            class="relative bg-white rounded-2xl lg:rounded-3xl border border-slate-200/80 mockup-shadow overflow-hidden">
                            <!-- Mockup Header -->
                            <div
                                class="bg-gradient-to-r from-emerald-600 to-emerald-700 px-5 py-3 flex items-center justify-between">
                                <div class="flex items-center gap-2">
                                    <div class="w-6 h-6 rounded-lg bg-white/20 flex items-center justify-center">
                                        <svg class="w-3.5 h-3.5 text-white" fill="none" viewBox="0 0 24 24"
                                            stroke-width="2" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                                        </svg>
                                    </div>
                                    <span class="text-white text-sm font-semibold">HifzhCare</span>
                                </div>
                                <div class="flex items-center gap-2">
                                    <div class="w-2 h-2 rounded-full bg-emerald-300"></div>
                                    <div class="w-2 h-2 rounded-full bg-yellow-300"></div>
                                    <div class="w-2 h-2 rounded-full bg-red-300"></div>
                                </div>
                            </div>
                            <!-- Mockup Body -->
                            <div class="p-5 space-y-4">
                                <!-- Greeting -->
                                <div>
                                    <p class="text-xs text-slate-400">Selamat Datang,</p>
                                    <p class="text-sm font-bold text-slate-800">Admin Pesantren!</p>
                                </div>
                                <!-- Stat Cards -->
                                <div class="grid grid-cols-2 sm:grid-cols-4 gap-2.5">
                                    <div class="bg-emerald-50 rounded-xl p-3 border border-emerald-100">
                                        <p class="text-[10px] text-emerald-600 font-medium">Total Santri</p>
                                        <p class="text-lg font-bold text-emerald-800">120</p>
                                    </div>
                                    <div class="bg-violet-50 rounded-xl p-3 border border-violet-100">
                                        <p class="text-[10px] text-violet-600 font-medium">Ustadz</p>
                                        <p class="text-lg font-bold text-violet-800">8</p>
                                    </div>
                                    <div class="bg-amber-50 rounded-xl p-3 border border-amber-100">
                                        <p class="text-[10px] text-amber-600 font-medium">Pending</p>
                                        <p class="text-lg font-bold text-amber-800">24</p>
                                    </div>
                                    <div class="bg-blue-50 rounded-xl p-3 border border-blue-100">
                                        <p class="text-[10px] text-blue-600 font-medium">Total Hafalan</p>
                                        <p class="text-lg font-bold text-blue-800">1.847</p>
                                    </div>
                                </div>
                                <!-- Chart Area -->
                                <div class="bg-slate-50 rounded-xl p-4 border border-slate-100">
                                    <p class="text-xs font-semibold text-slate-600 mb-3">Progres Bulanan</p>
                                    <div class="flex items-end gap-2 h-20">
                                        <div class="flex-1 bg-emerald-200 rounded-t-md bar-animate"
                                            style="height:40%"></div>
                                        <div class="flex-1 bg-emerald-300 rounded-t-md bar-animate"
                                            style="height:55%;animation-delay:0.1s"></div>
                                        <div class="flex-1 bg-emerald-300 rounded-t-md bar-animate"
                                            style="height:45%;animation-delay:0.2s"></div>
                                        <div class="flex-1 bg-emerald-400 rounded-t-md bar-animate"
                                            style="height:70%;animation-delay:0.3s"></div>
                                        <div class="flex-1 bg-emerald-400 rounded-t-md bar-animate"
                                            style="height:60%;animation-delay:0.4s"></div>
                                        <div class="flex-1 bg-emerald-500 rounded-t-md bar-animate"
                                            style="height:85%;animation-delay:0.5s"></div>
                                        <div class="flex-1 bg-emerald-600 rounded-t-md bar-animate"
                                            style="height:100%;animation-delay:0.6s"></div>
                                    </div>
                                    <div class="flex gap-2 mt-2">
                                        <span class="flex-1 text-[8px] text-slate-400 text-center">Jul</span>
                                        <span class="flex-1 text-[8px] text-slate-400 text-center">Agu</span>
                                        <span class="flex-1 text-[8px] text-slate-400 text-center">Sep</span>
                                        <span class="flex-1 text-[8px] text-slate-400 text-center">Okt</span>
                                        <span class="flex-1 text-[8px] text-slate-400 text-center">Nov</span>
                                        <span class="flex-1 text-[8px] text-slate-400 text-center">Des</span>
                                        <span
                                            class="flex-1 text-[8px] text-slate-400 text-center font-semibold text-emerald-600">Jan</span>
                                    </div>
                                </div>
                                <!-- Recent List -->
                                <div class="space-y-2">
                                    <p class="text-xs font-semibold text-slate-600">Hafalan Terbaru</p>
                                    <div
                                        class="flex items-center gap-3 bg-white rounded-xl p-2.5 border border-slate-100">
                                        <div
                                            class="w-8 h-8 rounded-full bg-emerald-100 flex items-center justify-center text-[10px] font-bold text-emerald-700">
                                            MR</div>
                                        <div class="flex-1 min-w-0">
                                            <p class="text-xs font-semibold text-slate-700 truncate">Muhammad Rizki</p>
                                            <p class="text-[10px] text-slate-400">Juz 30 — An-Nas</p>
                                        </div>
                                        <span
                                            class="text-[9px] font-medium bg-emerald-100 text-emerald-700 px-2 py-0.5 rounded-full">Verified</span>
                                    </div>
                                    <div
                                        class="flex items-center gap-3 bg-white rounded-xl p-2.5 border border-slate-100">
                                        <div
                                            class="w-8 h-8 rounded-full bg-amber-100 flex items-center justify-center text-[10px] font-bold text-amber-700">
                                            AH</div>
                                        <div class="flex-1 min-w-0">
                                            <p class="text-xs font-semibold text-slate-700 truncate">Ahmad Hasan</p>
                                            <p class="text-[10px] text-slate-400">Juz 1 — Al-Baqarah 1-50</p>
                                        </div>
                                        <span
                                            class="text-[9px] font-medium bg-amber-100 text-amber-700 px-2 py-0.5 rounded-full">Pending</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- ========== PROBLEM SECTION ========== -->
    <section id="masalah" class="relative py-20 md:py-28 bg-slate-50 overflow-hidden">
        <div
            class="absolute top-0 left-0 right-0 h-px bg-gradient-to-r from-transparent via-emerald-200 to-transparent">
        </div>
        <div
            class="absolute bottom-0 left-0 right-0 h-px bg-gradient-to-r from-transparent via-slate-200 to-transparent">
        </div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Section Header -->
            <div class="text-center max-w-3xl mx-auto mb-16">
                <div
                    class="fade-up inline-flex items-center gap-2 bg-red-50 border border-red-200 text-red-600 text-xs font-semibold px-4 py-2 rounded-full mb-6">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z" />
                    </svg>
                    Masalah yang Sering Terjadi
                </div>
                <h2 class="fade-up delay-1 text-3xl sm:text-4xl font-extrabold text-slate-900 tracking-tight mb-5">
                    Masih Mencatat Hafalan Santri<br class="hidden sm:block"> Secara Manual?
                </h2>
                <p class="fade-up delay-2 text-lg text-slate-500 leading-relaxed">
                    Banyak pondok pesantren masih mengandalkan buku tulis dan spreadsheet untuk mencatat hafalan.
                    Padahal, setiap hari ada ratusan setoran yang harus dicatat, diverifikasi, dan dilaporkan.
                </p>
            </div>

            <!-- Pain Points Grid -->
            <div class="grid sm:grid-cols-2 lg:grid-cols-4 gap-6">
                <!-- Pain 1 -->
                <div class="fade-up delay-1 bg-white rounded-2xl p-6 border border-slate-100 card-hover">
                    <div class="w-12 h-12 rounded-2xl bg-red-50 flex items-center justify-center mb-5">
                        <svg class="w-6 h-6 text-red-500" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z" />
                        </svg>
                    </div>
                    <h3 class="text-base font-bold text-slate-800 mb-2">Data Tidak Terstruktur</h3>
                    <p class="text-sm text-slate-500 leading-relaxed">Hafalan dicatat di buku tulis, buku cadangan,
                        atau spreadsheet yang tersebar. Susah dicari, susah diolah.</p>
                </div>
                <!-- Pain 2 -->
                <div class="fade-up delay-2 bg-white rounded-2xl p-6 border border-slate-100 card-hover">
                    <div class="w-12 h-12 rounded-2xl bg-orange-50 flex items-center justify-center mb-5">
                        <svg class="w-6 h-6 text-orange-500" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z" />
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                        </svg>
                    </div>
                    <h3 class="text-base font-bold text-slate-800 mb-2">Sulit Memantau Progres</h3>
                    <p class="text-sm text-slate-500 leading-relaxed">Ustadz kesulitan melihat perkembangan tiap santri
                        secara keseluruhan. Siapa yang tertinggal? Siapa yang perlu dibina?</p>
                </div>
                <!-- Pain 3 -->
                <div class="fade-up delay-3 bg-white rounded-2xl p-6 border border-slate-100 card-hover">
                    <div class="w-12 h-12 rounded-2xl bg-blue-50 flex items-center justify-center mb-5">
                        <svg class="w-6 h-6 text-blue-500" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-3.07M12 6.375a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zm8.25 2.25a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0z" />
                        </svg>
                    </div>
                    <h3 class="text-base font-bold text-slate-800 mb-2">Wali Santri Gelisah</h3>
                    <p class="text-sm text-slate-500 leading-relaxed">Wali santri sering bertanya "anak saya sudah
                        sampai mana?" tapi pondok belum bisa memberikan laporan yang jelas dan update.</p>
                </div>
                <!-- Pain 4 -->
                <div class="fade-up delay-4 bg-white rounded-2xl p-6 border border-slate-100 card-hover">
                    <div class="w-12 h-12 rounded-2xl bg-purple-50 flex items-center justify-center mb-5">
                        <svg class="w-6 h-6 text-purple-500" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <h3 class="text-base font-bold text-slate-800 mb-2">Tidak Ada Histori Data</h3>
                    <p class="text-sm text-slate-500 leading-relaxed">Buku hilang, spreadsheet rusak, atau ustadz
                        pindah — data hafalan santri ikut hilang. Tidak ada backup, tidak ada jejak.</p>
                </div>
            </div>

            <!-- Transition Statement -->
            <div class="fade-up mt-12 text-center">
                <p class="text-slate-400 text-sm">Jika salah satu dari masalah di atas terasa familiar, Anda tidak
                    sendirian.</p>
            </div>
        </div>
    </section>

    <!-- ========== SOLUTION SECTION ========== -->
    <section id="solusi" class="relative py-20 md:py-28 bg-white overflow-hidden">
        <div class="absolute top-20 right-0 w-80 h-80 bg-emerald-50 rounded-full blur-3xl opacity-60"></div>
        <div class="absolute bottom-20 left-0 w-64 h-64 bg-violet-50 rounded-full blur-3xl opacity-60"></div>

        <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid lg:grid-cols-2 gap-16 items-center">
                <!-- Left: Visual -->
                <div class="fade-up order-2 lg:order-1">
                    <div class="relative">
                        <div
                            class="absolute -inset-6 bg-gradient-to-br from-emerald-100 to-violet-100 rounded-3xl blur-2xl opacity-50">
                        </div>
                        <div class="relative grid grid-cols-2 gap-4">
                            <!-- Card 1 -->
                            <div class="bg-white rounded-2xl p-5 border border-slate-100 shadow-lg shadow-slate-100">
                                <div class="w-10 h-10 rounded-xl bg-emerald-100 flex items-center justify-center mb-3">
                                    <svg class="w-5 h-5 text-emerald-600" fill="none" viewBox="0 0 24 24"
                                        stroke-width="2" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M3.75 13.5l10.5-11.25L12 10.5h8.25L9.75 21.75 12 13.5H3.75z" />
                                    </svg>
                                </div>
                                <p class="text-sm font-bold text-slate-800">Monitoring Real-Time</p>
                                <p class="text-xs text-slate-400 mt-1">Setoran langsung tercatat</p>
                            </div>
                            <!-- Card 2 -->
                            <div
                                class="bg-white rounded-2xl p-5 border border-slate-100 shadow-lg shadow-slate-100 mt-6">
                                <div class="w-10 h-10 rounded-xl bg-violet-100 flex items-center justify-center mb-3">
                                    <svg class="w-5 h-5 text-violet-600" fill="none" viewBox="0 0 24 24"
                                        stroke-width="2" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M20.25 6.375c0 2.278-3.694 4.125-8.25 4.125S3.75 8.653 3.75 6.375m16.5 0c0-2.278-3.694-4.125-8.25-4.125S3.75 4.097 3.75 6.375m16.5 0v11.25c0 2.278-3.694 4.125-8.25 4.125s-8.25-1.847-8.25-4.125V6.375m16.5 0v3.75m-16.5-3.75v3.75m16.5 0v3.75C20.25 16.153 16.556 18 12 18s-8.25-1.847-8.25-4.125v-3.75m16.5 0c0 2.278-3.694 4.125-8.25 4.125s-8.25-1.847-8.25-4.125" />
                                    </svg>
                                </div>
                                <p class="text-sm font-bold text-slate-800">Data Terpusat</p>
                                <p class="text-xs text-slate-400 mt-1">Satu tempat untuk semua</p>
                            </div>
                            <!-- Card 3 -->
                            <div class="bg-white rounded-2xl p-5 border border-slate-100 shadow-lg shadow-slate-100">
                                <div class="w-10 h-10 rounded-xl bg-blue-100 flex items-center justify-center mb-3">
                                    <svg class="w-5 h-5 text-blue-600" fill="none" viewBox="0 0 24 24"
                                        stroke-width="2" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M10.5 1.5H8.25A2.25 2.25 0 006 3.75v16.5a2.25 2.25 0 002.25 2.25h7.5A2.25 2.25 0 0018 20.25V3.75a2.25 2.25 0 00-2.25-2.25H13.5m-3 0V3h3V1.5m-3 0h3m-3 18.75h3" />
                                    </svg>
                                </div>
                                <p class="text-sm font-bold text-slate-800">Akses Dimana Saja</p>
                                <p class="text-xs text-slate-400 mt-1">Cukup buka browser</p>
                            </div>
                            <!-- Card 4 -->
                            <div
                                class="bg-white rounded-2xl p-5 border border-slate-100 shadow-lg shadow-slate-100 mt-6">
                                <div class="w-10 h-10 rounded-xl bg-amber-100 flex items-center justify-center mb-3">
                                    <svg class="w-5 h-5 text-amber-600" fill="none" viewBox="0 0 24 24"
                                        stroke-width="2" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M7.5 21L3 16.5m0 0L7.5 12M3 16.5h13.5m0-13.5L21 7.5m0 0L16.5 12M21 7.5H7.5" />
                                    </svg>
                                </div>
                                <p class="text-sm font-bold text-slate-800">Transparan</p>
                                <p class="text-xs text-slate-400 mt-1">Ustadz, wali, & pimpinan</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Right: Copy -->
                <div class="order-1 lg:order-2">
                    <div
                        class="fade-up inline-flex items-center gap-2 bg-emerald-50 border border-emerald-200 text-emerald-700 text-xs font-semibold px-4 py-2 rounded-full mb-6">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M9.813 15.904L9 18.75l-.813-2.846a4.5 4.5 0 00-3.09-3.09L2.25 12l2.846-.813a4.5 4.5 0 003.09-3.09L9 5.25l.813 2.846a4.5 4.5 0 003.09 3.09L15.75 12l-2.846.813a4.5 4.5 0 00-3.09 3.09z" />
                        </svg>
                        Solusi dari HifzhCare
                    </div>
                    <h2 class="fade-up delay-1 text-3xl sm:text-4xl font-extrabold text-slate-900 tracking-tight mb-6">
                        Kenapa Pondok Pesantren<br>Butuh <span class="gradient-text">HifzhCare</span>?
                    </h2>
                    <p class="fade-up delay-2 text-lg text-slate-500 leading-relaxed mb-8">
                        HifzhCare hadir bukan untuk menggantikan peran ustadz, melainkan menjadi <strong
                            class="text-slate-700">asisten digital</strong> yang membantu mencatat, mengelola, dan
                        menyajikan data hafalan santri dengan rapi dan akurat.
                    </p>
                    <div class="fade-up delay-3 space-y-4">
                        <div class="flex items-start gap-3">
                            <div
                                class="w-6 h-6 rounded-full bg-emerald-100 flex items-center justify-center flex-shrink-0 mt-0.5">
                                <svg class="w-3.5 h-3.5 text-emerald-600" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                        d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                        clip-rule="evenodd" />
                                </svg>
                            </div>
                            <p class="text-sm text-slate-600">Ustadz cukup input setoran — sistem yang mengelola
                                datanya</p>
                        </div>
                        <div class="flex items-start gap-3">
                            <div
                                class="w-6 h-6 rounded-full bg-emerald-100 flex items-center justify-center flex-shrink-0 mt-0.5">
                                <svg class="w-3.5 h-3.5 text-emerald-600" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                        d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                        clip-rule="evenodd" />
                                </svg>
                            </div>
                            <p class="text-sm text-slate-600">Wali santri bisa memantau progres anak kapan saja</p>
                        </div>
                        <div class="flex items-start gap-3">
                            <div
                                class="w-6 h-6 rounded-full bg-emerald-100 flex items-center justify-center flex-shrink-0 mt-0.5">
                                <svg class="w-3.5 h-3.5 text-emerald-600" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                        d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                        clip-rule="evenodd" />
                                </svg>
                            </div>
                            <p class="text-sm text-slate-600">Pimpinan pondok mendapat laporan lengkap untuk
                                pengambilan keputusan</p>
                        </div>
                        <div class="flex items-start gap-3">
                            <div
                                class="w-6 h-6 rounded-full bg-emerald-100 flex items-center justify-center flex-shrink-0 mt-0.5">
                                <svg class="w-3.5 h-3.5 text-emerald-600" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                        d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                        clip-rule="evenodd" />
                                </svg>
                            </div>
                            <p class="text-sm text-slate-600">Data tersimpan aman, bisa diakses kapan saja, tidak
                                hilang</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- ========== FEATURES SECTION ========== -->
    <section id="fitur" class="relative py-20 md:py-28 bg-slate-50 overflow-hidden">
        <div
            class="absolute top-0 left-0 right-0 h-px bg-gradient-to-r from-transparent via-emerald-200 to-transparent">
        </div>
        <div class="absolute top-40 left-10 islamic-dots w-40 h-40 rounded-2xl opacity-20"></div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Section Header -->
            <div class="text-center max-w-3xl mx-auto mb-16">
                <div
                    class="fade-up inline-flex items-center gap-2 bg-violet-50 border border-violet-200 text-violet-600 text-xs font-semibold px-4 py-2 rounded-full mb-6">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M9.594 3.94c.09-.542.56-.94 1.11-.94h2.593c.55 0 1.02.398 1.11.94l.213 1.281c.063.374.313.686.645.87.074.04.147.083.22.127.324.196.72.257 1.075.124l1.217-.456a1.125 1.125 0 011.37.49l1.296 2.247a1.125 1.125 0 01-.26 1.431l-1.003.827c-.293.24-.438.613-.431.992a6.759 6.759 0 010 .255c-.007.378.138.75.43.99l1.005.828c.424.35.534.954.26 1.43l-1.298 2.247a1.125 1.125 0 01-1.369.491l-1.217-.456c-.355-.133-.75-.072-1.076.124a6.57 6.57 0 01-.22.128c-.331.183-.581.495-.644.869l-.213 1.28c-.09.543-.56.941-1.11.941h-2.594c-.55 0-1.02-.398-1.11-.94l-.213-1.281c-.062-.374-.312-.686-.644-.87a6.52 6.52 0 01-.22-.127c-.325-.196-.72-.257-1.076-.124l-1.217.456a1.125 1.125 0 01-1.369-.49l-1.297-2.247a1.125 1.125 0 01.26-1.431l1.004-.827c.292-.24.437-.613.43-.992a6.932 6.932 0 010-.255c.007-.378-.138-.75-.43-.99l-1.004-.828a1.125 1.125 0 01-.26-1.43l1.297-2.247a1.125 1.125 0 011.37-.491l1.216.456c.356.133.751.072 1.076-.124.072-.044.146-.087.22-.128.332-.183.582-.495.644-.869l.214-1.281z" />
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                    </svg>
                    Fitur Lengkap per Peran
                </div>
                <h2 class="fade-up delay-1 text-3xl sm:text-4xl font-extrabold text-slate-900 tracking-tight mb-5">
                    Satu Platform untuk<br>Semua Peran
                </h2>
                <p class="fade-up delay-2 text-lg text-slate-500 leading-relaxed">
                    Setiap pengguna punya dashboard sendiri sesuai kebutuhannya. Tidak ada fitur yang berantakan — semua
                    dirancang spesifik untuk perannya.
                </p>
            </div>

            <!-- Role Cards -->
            <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-6">
                <!-- Admin -->
                <div class="fade-up delay-1 bg-white rounded-2xl p-7 border border-slate-100 card-hover group">
                    <div
                        class="w-12 h-12 rounded-2xl bg-emerald-100 flex items-center justify-center mb-5 group-hover:bg-emerald-600 transition-colors duration-300">
                        <svg class="w-6 h-6 text-emerald-600 group-hover:text-white transition-colors duration-300"
                            fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M3.75 21h16.5M4.5 3h15M5.25 3v18m13.5-18v18M9 6.75h1.5m-1.5 3h1.5m-1.5 3h1.5m3-6H15m-1.5 3H15m-1.5 3H15M9 21v-3.375c0-.621.504-1.125 1.125-1.125h3.75c.621 0 1.125.504 1.125 1.125V21" />
                        </svg>
                    </div>
                    <h3 class="text-lg font-bold text-slate-800 mb-1">Admin Pondok</h3>
                    <p class="text-sm text-slate-400 mb-4">Pengelola sistem harian</p>
                    <ul class="space-y-2.5">
                        <li class="flex items-start gap-2.5 text-sm text-slate-600">
                            <svg class="w-4 h-4 text-emerald-500 flex-shrink-0 mt-0.5" fill="currentColor"
                                viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                    d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                    clip-rule="evenodd" />
                            </svg>
                            Kelola data santri, ustadz, dan kelas
                        </li>
                        <li class="flex items-start gap-2.5 text-sm text-slate-600">
                            <svg class="w-4 h-4 text-emerald-500 flex-shrink-0 mt-0.5" fill="currentColor"
                                viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                    d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                    clip-rule="evenodd" />
                            </svg>
                            Laporan lengkap & ekspor data
                        </li>
                        <li class="flex items-start gap-2.5 text-sm text-slate-600">
                            <svg class="w-4 h-4 text-emerald-500 flex-shrink-0 mt-0.5" fill="currentColor"
                                viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                    d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                    clip-rule="evenodd" />
                            </svg>
                            Generate & cetak sertifikat hafalan
                        </li>
                    </ul>
                </div>

                <!-- Ustadz -->
                <div class="fade-up delay-2 bg-white rounded-2xl p-7 border border-slate-100 card-hover group">
                    <div
                        class="w-12 h-12 rounded-2xl bg-violet-100 flex items-center justify-center mb-5 group-hover:bg-violet-600 transition-colors duration-300">
                        <svg class="w-6 h-6 text-violet-600 group-hover:text-white transition-colors duration-300"
                            fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M4.26 10.147a60.436 60.436 0 00-.491 6.347A48.627 48.627 0 0112 20.904a48.627 48.627 0 018.232-4.41 60.46 60.46 0 00-.491-6.347m-15.482 0a50.57 50.57 0 00-2.658-.813A59.905 59.905 0 0112 3.493a59.902 59.902 0 0110.399 5.84c-.896.248-1.783.52-2.658.814m-15.482 0A50.697 50.697 0 0112 13.489a50.702 50.702 0 017.74-3.342" />
                        </svg>
                    </div>
                    <h3 class="text-lg font-bold text-slate-800 mb-1">Ustadz / Ustadzah</h3>
                    <p class="text-sm text-slate-400 mb-4">Pembimbing hafalan</p>
                    <ul class="space-y-2.5">
                        <li class="flex items-start gap-2.5 text-sm text-slate-600">
                            <svg class="w-4 h-4 text-violet-500 flex-shrink-0 mt-0.5" fill="currentColor"
                                viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                    d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                    clip-rule="evenodd" />
                            </svg>
                            Input & verifikasi hafalan santri
                        </li>
                        <li class="flex items-start gap-2.5 text-sm text-slate-600">
                            <svg class="w-4 h-4 text-violet-500 flex-shrink-0 mt-0.5" fill="currentColor"
                                viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                    d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                    clip-rule="evenodd" />
                            </svg>
                            Kelola kelas & jadwal setoran
                        </li>
                        <li class="flex items-start gap-2.5 text-sm text-slate-600">
                            <svg class="w-4 h-4 text-violet-500 flex-shrink-0 mt-0.5" fill="currentColor"
                                viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                    d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                    clip-rule="evenodd" />
                            </svg>
                            Rekam audio setoran santri
                        </li>
                    </ul>
                </div>

                <!-- Wali Santri -->
                <div class="fade-up delay-3 bg-white rounded-2xl p-7 border border-slate-100 card-hover group">
                    <div
                        class="w-12 h-12 rounded-2xl bg-blue-100 flex items-center justify-center mb-5 group-hover:bg-blue-600 transition-colors duration-300">
                        <svg class="w-6 h-6 text-blue-600 group-hover:text-white transition-colors duration-300"
                            fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-3.07M12 6.375a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zm8.25 2.25a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0z" />
                        </svg>
                    </div>
                    <h3 class="text-lg font-bold text-slate-800 mb-1">Wali Santri</h3>
                    <p class="text-sm text-slate-400 mb-4">Orang tua / wali murid</p>
                    <ul class="space-y-2.5">
                        <li class="flex items-start gap-2.5 text-sm text-slate-600">
                            <svg class="w-4 h-4 text-blue-500 flex-shrink-0 mt-0.5" fill="currentColor"
                                viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                    d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                    clip-rule="evenodd" />
                            </svg>
                            Pantau progres hafalan anak secara real-time
                        </li>
                        <li class="flex items-start gap-2.5 text-sm text-slate-600">
                            <svg class="w-4 h-4 text-blue-500 flex-shrink-0 mt-0.5" fill="currentColor"
                                viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                    d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                    clip-rule="evenodd" />
                            </svg>
                            Lihat detail per juz & per surah
                        </li>
                        <li class="flex items-start gap-2.5 text-sm text-slate-600">
                            <svg class="w-4 h-4 text-blue-500 flex-shrink-0 mt-0.5" fill="currentColor"
                                viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                    d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                    clip-rule="evenodd" />
                            </svg>
                            Beri donasi apresiasi ke ustadz
                        </li>
                    </ul>
                </div>

                <!-- Santri -->
                <div class="fade-up delay-1 bg-white rounded-2xl p-7 border border-slate-100 card-hover group">
                    <div
                        class="w-12 h-12 rounded-2xl bg-amber-100 flex items-center justify-center mb-5 group-hover:bg-amber-600 transition-colors duration-300">
                        <svg class="w-6 h-6 text-amber-600 group-hover:text-white transition-colors duration-300"
                            fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z" />
                        </svg>
                    </div>
                    <h3 class="text-lg font-bold text-slate-800 mb-1">Santri</h3>
                    <p class="text-sm text-slate-400 mb-4">Penghafal Al-Quran</p>
                    <ul class="space-y-2.5">
                        <li class="flex items-start gap-2.5 text-sm text-slate-600">
                            <svg class="w-4 h-4 text-amber-500 flex-shrink-0 mt-0.5" fill="currentColor"
                                viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                    d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                    clip-rule="evenodd" />
                            </svg>
                            Lihat progres hafalan pribadi
                        </li>
                        <li class="flex items-start gap-2.5 text-sm text-slate-600">
                            <svg class="w-4 h-4 text-amber-500 flex-shrink-0 mt-0.5" fill="currentColor"
                                viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                    d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                    clip-rule="evenodd" />
                            </svg>
                            Tracking per juz & pencapaian
                        </li>
                        <li class="flex items-start gap-2.5 text-sm text-slate-600">
                            <svg class="w-4 h-4 text-amber-500 flex-shrink-0 mt-0.5" fill="currentColor"
                                viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                    d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                    clip-rule="evenodd" />
                            </svg>
                            Akses & unduh sertifikat
                        </li>
                    </ul>
                </div>

                <!-- Kyai / Pimpinan -->
                <div
                    class="fade-up delay-2 bg-gradient-to-br from-emerald-600 to-emerald-800 rounded-2xl p-7 border border-emerald-500 card-hover group sm:col-span-2 lg:col-span-2">
                    <div class="flex flex-col md:flex-row md:items-start gap-6">
                        <div>
                            <div class="w-12 h-12 rounded-2xl bg-white/10 flex items-center justify-center mb-5">
                                <svg class="w-6 h-6 text-emerald-200" fill="none" viewBox="0 0 24 24"
                                    stroke-width="1.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M3 13.125C3 12.504 3.504 12 4.125 12h2.25c.621 0 1.125.504 1.125 1.125v6.75C7.5 20.496 6.996 21 6.375 21h-2.25A1.125 1.125 0 013 19.875v-6.75zM9.75 8.625c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125v11.25c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V8.625zM16.5 4.125c0-.621.504-1.125 1.125-1.125h2.25C20.496 3 21 3.504 21 4.125v15.75c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V4.125z" />
                                </svg>
                            </div>
                            <h3 class="text-lg font-bold text-white mb-1">Kyai / Pimpinan Pondok</h3>
                            <p class="text-sm text-emerald-200 mb-4">Pengambil keputusan strategis</p>
                        </div>
                        <div class="flex-1 grid sm:grid-cols-2 gap-4">
                            <div class="bg-white/10 rounded-xl p-4 border border-white/10">
                                <p class="text-white text-sm font-medium mb-1">Dashboard Analitik</p>
                                <p class="text-emerald-200 text-xs">Pantau kinerja seluruh pondok dari satu layar</p>
                            </div>
                            <div class="bg-white/10 rounded-xl p-4 border border-white/10">
                                <p class="text-white text-sm font-medium mb-1">Insight Keputusan</p>
                                <p class="text-emerald-200 text-xs">Data visual untuk evaluasi program tahfidz</p>
                            </div>
                            <div class="bg-white/10 rounded-xl p-4 border border-white/10">
                                <p class="text-white text-sm font-medium mb-1">Laporan Eksekutif</p>
                                <p class="text-emerald-200 text-xs">Unduh laporan lengkap untuk rapat & laporan donatur
                                </p>
                            </div>
                            <div class="bg-white/10 rounded-xl p-4 border border-white/10">
                                <p class="text-white text-sm font-medium mb-1">Manajemen Sertifikat</p>
                                <p class="text-emerald-200 text-xs">Pantau & approve sertifikat hafalan santri</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- ========== VALUE PROPOSITION ========== -->
    <section id="keunggulan" class="relative py-20 md:py-28 bg-white overflow-hidden">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Section Header -->
            <div class="text-center max-w-3xl mx-auto mb-16">
                <div
                    class="fade-up inline-flex items-center gap-2 bg-amber-50 border border-amber-200 text-amber-700 text-xs font-semibold px-4 py-2 rounded-full mb-6">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M11.48 3.499a.562.562 0 011.04 0l2.125 5.111a.563.563 0 00.475.345l5.518.442c.499.04.701.663.321.988l-4.204 3.602a.563.563 0 00-.182.557l1.285 5.385a.562.562 0 01-.84.61l-4.725-2.885a.563.563 0 00-.586 0L6.982 20.54a.562.562 0 01-.84-.61l1.285-5.386a.562.562 0 00-.182-.557l-4.204-3.602a.563.563 0 01.321-.988l5.518-.442a.563.563 0 00.475-.345L11.48 3.5z" />
                    </svg>
                    Keunggulan HifzhCare
                </div>
                <h2 class="fade-up delay-1 text-3xl sm:text-4xl font-extrabold text-slate-900 tracking-tight mb-5">
                    Lebih dari Sekadar<br>Aplikasi Hafalan
                </h2>
                <p class="fade-up delay-2 text-lg text-slate-500 leading-relaxed">
                    HifzhCare dirancang khusus untuk dunia pesantren — bukan software umum yang dipaksakan.
                </p>
            </div>

            <!-- Value Grid -->
            <div class="grid sm:grid-cols-2 lg:grid-cols-4 gap-8">
                <!-- Value 1 -->
                <div class="fade-up delay-1 text-center">
                    <div
                        class="w-16 h-16 rounded-2xl bg-gradient-to-br from-emerald-50 to-emerald-100 flex items-center justify-center mx-auto mb-5 border border-emerald-200">
                        <svg class="w-7 h-7 text-emerald-600" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M15.042 21.672L13.684 16.6m0 0l-2.51 2.225.569-9.47 5.227 7.917-3.286-.672zM12 2.25V4.5m5.834.166l-1.591 1.591M20.25 10.5H18M7.757 14.743l-1.59 1.59M6 10.5H3.75m4.007-4.243l-1.59-1.59" />
                        </svg>
                    </div>
                    <h3 class="text-base font-bold text-slate-800 mb-2">Mudah Digunakan</h3>
                    <p class="text-sm text-slate-500 leading-relaxed">Interface sederhana, tidak perlu pelatihan
                        khusus. Ustadz yang baru pakai smartphone pun bisa langsung mengoperasikan.</p>
                </div>
                <!-- Value 2 -->
                <div class="fade-up delay-2 text-center">
                    <div
                        class="w-16 h-16 rounded-2xl bg-gradient-to-br from-blue-50 to-blue-100 flex items-center justify-center mx-auto mb-5 border border-blue-200">
                        <svg class="w-7 h-7 text-blue-600" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M12 21a9.004 9.004 0 008.716-6.747M12 21a9.004 9.004 0 01-8.716-6.747M12 21c2.485 0 4.5-4.03 4.5-9S14.485 3 12 3m0 18c-2.485 0-4.5-4.03-4.5-9S9.515 3 12 3m0 0a8.997 8.997 0 017.843 4.582M12 3a8.997 8.997 0 00-7.843 4.582m15.686 0A11.953 11.953 0 0112 10.5c-2.998 0-5.74-1.1-7.843-2.918m15.686 0A8.959 8.959 0 0121 12c0 .778-.099 1.533-.284 2.253m0 0A17.919 17.919 0 0112 16.5c-3.162 0-6.133-.815-8.716-2.247m0 0A9.015 9.015 0 013 12c0-1.605.42-3.113 1.157-4.418" />
                        </svg>
                    </div>
                    <h3 class="text-base font-bold text-slate-800 mb-2">Berbasis Web</h3>
                    <p class="text-sm text-slate-500 leading-relaxed">Tidak perlu instal apapun. Cukup buka browser di
                        HP, tablet, atau laptop. Bisa diakses dari mana saja, kapan saja.</p>
                </div>
                <!-- Value 3 -->
                <div class="fade-up delay-3 text-center">
                    <div
                        class="w-16 h-16 rounded-2xl bg-gradient-to-br from-violet-50 to-violet-100 flex items-center justify-center mx-auto mb-5 border border-violet-200">
                        <svg class="w-7 h-7 text-violet-600" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M9 12.75L11.25 15 15 9.75m-3-7.036A11.959 11.959 0 013.598 6 11.99 11.99 0 003 9.749c0 5.592 3.824 10.29 9 11.623 5.176-1.332 9-6.03 9-11.622 0-1.31-.21-2.571-.598-3.751h-.152c-3.196 0-6.1-1.248-8.25-3.285z" />
                        </svg>
                    </div>
                    <h3 class="text-base font-bold text-slate-800 mb-2">Data Aman</h3>
                    <p class="text-sm text-slate-500 leading-relaxed">Data tersimpan di server yang aman dengan backup
                        otomatis. Tidak perlu khawatir data hilang lagi seperti buku yang tercecer.</p>
                </div>
                <!-- Value 4 -->
                <div class="fade-up delay-4 text-center">
                    <div
                        class="w-16 h-16 rounded-2xl bg-gradient-to-br from-amber-50 to-amber-100 flex items-center justify-center mx-auto mb-5 border border-amber-200">
                        <svg class="w-7 h-7 text-amber-600" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M21 8.25c0-2.485-2.099-4.5-4.688-4.5-1.935 0-3.597 1.126-4.312 2.733-.715-1.607-2.377-2.733-4.313-2.733C5.1 3.75 3 5.765 3 8.25c0 7.22 9 12 9 12s9-4.78 9-12z" />
                        </svg>
                    </div>
                    <h3 class="text-base font-bold text-slate-800 mb-2">Sistem Donasi</h3>
                    <p class="text-sm text-slate-500 leading-relaxed">Fitur dana apresiasi opsional — wali santri bisa
                        memberikan apresiasi langsung ke ustadz pembimbing melalui platform.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- ========== PRICING SECTION ========== -->
    <section id="harga" class="relative py-20 md:py-28 bg-slate-50 overflow-hidden">
        <div
            class="absolute top-0 left-0 right-0 h-px bg-gradient-to-r from-transparent via-emerald-200 to-transparent">
        </div>
        <div class="absolute top-32 right-10 w-64 h-64 bg-emerald-100 rounded-full blur-3xl opacity-40"></div>
        <div class="absolute bottom-20 left-10 w-56 h-56 bg-violet-100 rounded-full blur-3xl opacity-40"></div>

        <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Section Header -->
            <div class="text-center max-w-3xl mx-auto mb-6">
                <div
                    class="fade-up inline-flex items-center gap-2 bg-emerald-50 border border-emerald-200 text-emerald-700 text-xs font-semibold px-4 py-2 rounded-full mb-6">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M2.25 18.75a60.07 60.07 0 0115.797 2.101c.727.198 1.453-.342 1.453-1.096V18.75M3.75 4.5v.75A.75.75 0 013 6h-.75m0 0v-.375c0-.621.504-1.125 1.125-1.125H20.25M2.25 6v9m18-10.5v.75c0 .414.336.75.75.75h.75m-1.5-1.5h.375c.621 0 1.125.504 1.125 1.125v9.75c0 .621-.504 1.125-1.125 1.125h-.375m1.5-1.5H21a.75.75 0 00-.75.75v.75m0 0H3.75m0 0h-.375a1.125 1.125 0 01-1.125-1.125V15m1.5 1.5v-.75A.75.75 0 003 15h-.75M15 10.5a3 3 0 11-6 0 3 3 0 016 0zm3 0h.008v.008H18V10.5zm-12 0h.008v.008H6V10.5z" />
                    </svg>
                    Paket Harga
                </div>
                <h2 class="fade-up delay-1 text-3xl sm:text-4xl font-extrabold text-slate-900 tracking-tight mb-5">
                    Investasi untuk Kualitas<br>Hafalan Santri
                </h2>
                <p class="fade-up delay-2 text-lg text-slate-500 leading-relaxed">
                    Pilih paket yang sesuai dengan skala pondok Anda. Semua paket sudah termasuk fitur lengkap.
                </p>
            </div>

            <!-- Highlight Badge -->
            <div class="fade-up delay-2 flex justify-center mb-12">
                <div
                    class="inline-flex items-center gap-2 bg-gradient-to-r from-emerald-50 to-violet-50 border border-emerald-200 text-emerald-800 text-sm font-semibold px-6 py-3 rounded-full">
                    <svg class="w-5 h-5 text-emerald-500" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd"
                            d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a.75.75 0 000 1.5h.253a.25.25 0 01.244.304l-.459 2.066A1.75 1.75 0 0010.747 15H11a.75.75 0 000-1.5h-.253a.25.25 0 01-.244-.304l.459-2.066A1.75 1.75 0 009.253 9H9z"
                            clip-rule="evenodd" />
                    </svg>
                    Hanya sekitar <span class="text-emerald-600 font-bold">Rp 10.000</span> per santri per bulan
                </div>
            </div>

            <!-- Pricing Cards -->
            <div class="grid sm:grid-cols-2 lg:grid-cols-4 gap-6">
                <!-- Small -->
                <div class="fade-up delay-1 bg-white rounded-2xl p-7 border border-slate-200 card-hover flex flex-col">
                    <div class="mb-6">
                        <h3 class="text-base font-bold text-slate-800 mb-1">Small</h3>
                        <p class="text-xs text-slate-400">Cocok untuk pondok pemula</p>
                    </div>
                    <div class="mb-6">
                        <div class="flex items-baseline gap-1">
                            <span class="text-sm text-slate-400">Rp</span>
                            <span class="text-3xl font-extrabold text-slate-900">1.500.000</span>
                        </div>
                        <p class="text-xs text-slate-400 mt-1">per bulan</p>
                    </div>
                    <div class="mb-6 py-3 px-4 bg-emerald-50 rounded-xl text-center">
                        <p class="text-sm font-semibold text-emerald-700">0 – 150 Santri</p>
                    </div>
                    <ul class="space-y-3 mb-8 flex-1">
                        <li class="flex items-center gap-2 text-sm text-slate-600">
                            <svg class="w-4 h-4 text-emerald-500 flex-shrink-0" fill="currentColor"
                                viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                    d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                    clip-rule="evenodd" />
                            </svg>
                            Semua fitur dasar
                        </li>
                        <li class="flex items-center gap-2 text-sm text-slate-600">
                            <svg class="w-4 h-4 text-emerald-500 flex-shrink-0" fill="currentColor"
                                viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                    d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                    clip-rule="evenodd" />
                            </svg>
                            Akses 5 peran pengguna
                        </li>
                        <li class="flex items-center gap-2 text-sm text-slate-600">
                            <svg class="w-4 h-4 text-emerald-500 flex-shrink-0" fill="currentColor"
                                viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                    d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                    clip-rule="evenodd" />
                            </svg>
                            Support via WhatsApp
                        </li>
                    </ul>
                    <a href="https://wa.me/6281234567890?text=Assalamualaikum%2C%20saya%20tertarik%20paket%20Small%20HifzhCare"
                        target="_blank"
                        class="block text-center text-sm font-semibold text-emerald-700 bg-emerald-50 hover:bg-emerald-100 border border-emerald-200 px-6 py-3 rounded-xl transition-colors">
                        Pilih Paket
                    </a>
                </div>

                <!-- Medium (Popular) -->
                <div class="fade-up delay-2 bg-white rounded-2xl p-7 pricing-popular flex flex-col relative">
                    <div
                        class="absolute -top-3 left-1/2 -translate-x-1/2 bg-gradient-to-r from-emerald-600 to-emerald-700 text-white text-xs font-bold px-4 py-1 rounded-full shadow-md">
                        Paling Populer
                    </div>
                    <div class="mb-6">
                        <h3 class="text-base font-bold text-slate-800 mb-1">Medium</h3>
                        <p class="text-xs text-slate-400">Untuk pondok berkembang</p>
                    </div>
                    <div class="mb-6">
                        <div class="flex items-baseline gap-1">
                            <span class="text-sm text-emerald-600">Rp</span>
                            <span class="text-3xl font-extrabold text-emerald-700">2.500.000</span>
                        </div>
                        <p class="text-xs text-slate-400 mt-1">per bulan</p>
                    </div>
                    <div class="mb-6 py-3 px-4 bg-emerald-50 rounded-xl text-center">
                        <p class="text-sm font-semibold text-emerald-700">0 – 250 Santri</p>
                    </div>
                    <ul class="space-y-3 mb-8 flex-1">
                        <li class="flex items-center gap-2 text-sm text-slate-600">
                            <svg class="w-4 h-4 text-emerald-500 flex-shrink-0" fill="currentColor"
                                viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                    d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                    clip-rule="evenodd" />
                            </svg>
                            Semua fitur Small
                        </li>
                        <li class="flex items-center gap-2 text-sm text-slate-600">
                            <svg class="w-4 h-4 text-emerald-500 flex-shrink-0" fill="currentColor"
                                viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                    d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                    clip-rule="evenodd" />
                            </svg>
                            Fitur donasi apresiasi
                        </li>
                        <li class="flex items-center gap-2 text-sm text-slate-600">
                            <svg class="w-4 h-4 text-emerald-500 flex-shrink-0" fill="currentColor"
                                viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                    d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                    clip-rule="evenodd" />
                            </svg>
                            Laporan & ekspor data
                        </li>
                        <li class="flex items-center gap-2 text-sm text-slate-600">
                            <svg class="w-4 h-4 text-emerald-500 flex-shrink-0" fill="currentColor"
                                viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                    d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                    clip-rule="evenodd" />
                            </svg>
                            Prioritas support
                        </li>
                    </ul>
                    <a href="https://wa.me/6281234567890?text=Assalamualaikum%2C%20saya%20tertarik%20paket%20Medium%20HifzhCare"
                        target="_blank"
                        class="block text-center text-sm font-semibold text-white bg-gradient-to-r from-emerald-600 to-emerald-700 hover:from-emerald-700 hover:to-emerald-800 px-6 py-3 rounded-xl shadow-md shadow-emerald-200 transition-all">
                        Pilih Paket
                    </a>
                </div>

                <!-- Large -->
                <div class="fade-up delay-3 bg-white rounded-2xl p-7 border border-slate-200 card-hover flex flex-col">
                    <div class="mb-6">
                        <h3 class="text-base font-bold text-slate-800 mb-1">Large</h3>
                        <p class="text-xs text-slate-400">Untuk pondok besar</p>
                    </div>
                    <div class="mb-6">
                        <div class="flex items-baseline gap-1">
                            <span class="text-sm text-slate-400">Rp</span>
                            <span class="text-3xl font-extrabold text-slate-900">5.000.000</span>
                        </div>
                        <p class="text-xs text-slate-400 mt-1">per bulan</p>
                    </div>
                    <div class="mb-6 py-3 px-4 bg-emerald-50 rounded-xl text-center">
                        <p class="text-sm font-semibold text-emerald-700">0 – 500 Santri</p>
                    </div>
                    <ul class="space-y-3 mb-8 flex-1">
                        <li class="flex items-center gap-2 text-sm text-slate-600">
                            <svg class="w-4 h-4 text-emerald-500 flex-shrink-0" fill="currentColor"
                                viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                    d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                    clip-rule="evenodd" />
                            </svg>
                            Semua fitur Medium
                        </li>
                        <li class="flex items-center gap-2 text-sm text-slate-600">
                            <svg class="w-4 h-4 text-emerald-500 flex-shrink-0" fill="currentColor"
                                viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                    d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                    clip-rule="evenodd" />
                            </svg>
                            Multi-pondok support
                        </li>
                        <li class="flex items-center gap-2 text-sm text-slate-600">
                            <svg class="w-4 h-4 text-emerald-500 flex-shrink-0" fill="currentColor"
                                viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                    d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                    clip-rule="evenodd" />
                            </svg>
                            Dashboard analitik lanjutan
                        </li>
                        <li class="flex items-center gap-2 text-sm text-slate-600">
                            <svg class="w-4 h-4 text-emerald-500 flex-shrink-0" fill="currentColor"
                                viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                    d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                    clip-rule="evenodd" />
                            </svg>
                            Dedicated support
                        </li>
                    </ul>
                    <a href="https://wa.me/6281234567890?text=Assalamualaikum%2C%20saya%20tertarik%20paket%20Large%20HifzhCare"
                        target="_blank"
                        class="block text-center text-sm font-semibold text-emerald-700 bg-emerald-50 hover:bg-emerald-100 border border-emerald-200 px-6 py-3 rounded-xl transition-colors">
                        Pilih Paket
                    </a>
                </div>

                <!-- Enterprise -->
                <div
                    class="fade-up delay-4 bg-gradient-to-br from-slate-800 to-slate-900 rounded-2xl p-7 card-hover flex flex-col">
                    <div class="mb-6">
                        <h3 class="text-base font-bold text-white mb-1">Enterprise</h3>
                        <p class="text-xs text-slate-400">Untuk jaringan pesantren</p>
                    </div>
                    <div class="mb-6">
                        <div class="flex items-baseline gap-1">
                            <span class="text-sm text-slate-400">Rp</span>
                            <span class="text-3xl font-extrabold text-white">Custom</span>
                        </div>
                        <p class="text-xs text-slate-500 mt-1">sesuai kebutuhan</p>
                    </div>
                    <div class="mb-6 py-3 px-4 bg-white/5 rounded-xl text-center border border-white/10">
                        <p class="text-sm font-semibold text-slate-300">0 – 1000+ Santri</p>
                    </div>
                    <ul class="space-y-3 mb-8 flex-1">
                        <li class="flex items-center gap-2 text-sm text-slate-300">
                            <svg class="w-4 h-4 text-emerald-400 flex-shrink-0" fill="currentColor"
                                viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                    d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                    clip-rule="evenodd" />
                            </svg>
                            Semua fitur Large
                        </li>
                        <li class="flex items-center gap-2 text-sm text-slate-300">
                            <svg class="w-4 h-4 text-emerald-400 flex-shrink-0" fill="currentColor"
                                viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                    d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                    clip-rule="evenodd" />
                            </svg>
                            Kustomisasi fitur
                        </li>
                        <li class="flex items-center gap-2 text-sm text-slate-300">
                            <svg class="w-4 h-4 text-emerald-400 flex-shrink-0" fill="currentColor"
                                viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                    d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                    clip-rule="evenodd" />
                            </svg>
                            Training & onboarding
                        </li>
                        <li class="flex items-center gap-2 text-sm text-slate-300">
                            <svg class="w-4 h-4 text-emerald-400 flex-shrink-0" fill="currentColor"
                                viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                    d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                    clip-rule="evenodd" />
                            </svg>
                            Account manager khusus
                        </li>
                    </ul>
                    <a href="https://wa.me/6281234567890?text=Assalamualaikum%2C%20saya%20tertarik%20paket%20Enterprise%20HifzhCare"
                        target="_blank"
                        class="block text-center text-sm font-semibold text-slate-800 bg-white hover:bg-slate-100 px-6 py-3 rounded-xl transition-colors">
                        Hubungi Kami
                    </a>
                </div>
            </div>

            <!-- Pricing Note -->
            <p class="fade-up text-center text-sm text-slate-400 mt-10">
                * Semua harga belum termasuk PPN. Tersedia diskon khusus untuk pembayaran tahunan.
            </p>
        </div>
    </section>

    <!-- ========== CTA SECTION ========== -->
    <section id="cta" class="relative py-20 md:py-28 overflow-hidden">
        <!-- Background -->
        <div class="absolute inset-0 bg-gradient-to-br from-emerald-700 via-emerald-800 to-emerald-950"></div>
        <div class="absolute inset-0 islamic-dots opacity-10"></div>
        <div
            class="absolute top-0 left-0 right-0 h-px bg-gradient-to-r from-transparent via-emerald-400 to-transparent">
        </div>
        <!-- Decorative circles -->
        <div class="absolute -top-20 -right-20 w-80 h-80 bg-emerald-600/30 rounded-full blur-3xl"></div>
        <div class="absolute -bottom-20 -left-20 w-96 h-96 bg-violet-600/10 rounded-full blur-3xl"></div>

        <div class="relative max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <div
                class="fade-up inline-flex items-center gap-2 bg-white/10 border border-white/20 text-emerald-100 text-xs font-semibold px-4 py-2 rounded-full mb-8 backdrop-blur-sm">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M9.813 15.904L9 18.75l-.813-2.846a4.5 4.5 0 00-3.09-3.09L2.25 12l2.846-.813a4.5 4.5 0 003.09-3.09L9 5.25l.813 2.846a4.5 4.5 0 003.09 3.09L15.75 12l-2.846.813a4.5 4.5 0 00-3.09 3.09z" />
                </svg>
                Mulai Sekarang
            </div>

            <h2
                class="fade-up delay-1 text-3xl sm:text-4xl lg:text-5xl font-extrabold text-white tracking-tight mb-6 leading-tight">
                Siap Memulai Digitalisasi<br>Hafalan di Pondok Anda?
            </h2>
            <p class="fade-up delay-2 text-lg text-emerald-100/80 leading-relaxed mb-10 max-w-2xl mx-auto">
                Jangan biarkan data hafalan santri terus tercecer. Dengan HifzhCare, pondok Anda bisa memiliki sistem
                pencatatan yang rapi, transparan, dan bermanfaat untuk semua pihak.
            </p>

            <div class="fade-up delay-3 flex flex-col sm:flex-row gap-4 justify-center">
                <a href="https://wa.me/6281234567890?text=Assalamualaikum%2C%20saya%20ingin%20minta%20demo%20HifzhCare%20untuk%20pondok%20kami"
                    target="_blank"
                    class="inline-flex items-center justify-center gap-3 text-base font-semibold text-emerald-800 bg-white hover:bg-slate-50 px-8 py-4 rounded-2xl shadow-xl shadow-black/20 transition-all duration-200 hover:-translate-y-0.5">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                        <path
                            d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z" />
                    </svg>
                    Hubungi via WhatsApp
                </a>
                <a href="https://wa.me/6281234567890?text=Assalamualaikum%2C%20saya%20ingin%20request%20demo%20HifzhCare"
                    target="_blank"
                    class="inline-flex items-center justify-center gap-2 text-base font-semibold text-white bg-white/10 hover:bg-white/20 border border-white/20 backdrop-blur-sm px-8 py-4 rounded-2xl transition-all duration-200">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M5.25 5.653c0-.856.917-1.398 1.667-.986l11.54 6.348a1.125 1.125 0 010 1.971l-11.54 6.347a1.125 1.125 0 01-1.667-.985V5.653z" />
                    </svg>
                    Request Demo
                </a>
            </div>

            <!-- Social Proof Micro -->
            <div
                class="fade-up delay-4 mt-12 flex flex-wrap items-center justify-center gap-6 text-emerald-200/60 text-sm">
                <div class="flex items-center gap-1.5">
                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd"
                            d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.857-9.809a.75.75 0 00-1.214-.882l-3.483 4.79-1.88-1.88a.75.75 0 10-1.06 1.061l2.5 2.5a.75.75 0 001.137-.089l4-5.5z"
                            clip-rule="evenodd" />
                    </svg>
                    <span>Gratis konsultasi</span>
                </div>
                <div class="flex items-center gap-1.5">
                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd"
                            d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.857-9.809a.75.75 0 00-1.214-.882l-3.483 4.79-1.88-1.88a.75.75 0 10-1.06 1.061l2.5 2.5a.75.75 0 001.137-.089l4-5.5z"
                            clip-rule="evenodd" />
                    </svg>
                    <span>Setup oleh tim kami</span>
                </div>
                <div class="flex items-center gap-1.5">
                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd"
                            d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.857-9.809a.75.75 0 00-1.214-.882l-3.483 4.79-1.88-1.88a.75.75 0 10-1.06 1.061l2.5 2.5a.75.75 0 001.137.089l4-5.5z"
                            clip-rule="evenodd" />
                    </svg>
                    <span>Tidak ada komitmen awal</span>
                </div>
            </div>
        </div>
    </section>

    <!-- ========== TESTIMONIAL SECTION ========== -->
    <section class="relative py-20 md:py-28 bg-white overflow-hidden">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Section Header -->
            <div class="text-center max-w-3xl mx-auto mb-16">
                <div
                    class="fade-up inline-flex items-center gap-2 bg-emerald-50 border border-emerald-200 text-emerald-700 text-xs font-semibold px-4 py-2 rounded-full mb-6">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M7.5 8.25h9m-9 3H12m-9.75 1.51c0 1.6 1.123 2.994 2.707 3.227 1.129.166 2.27.293 3.423.379.35.026.67.21.865.501L12 21l2.755-4.133a1.14 1.14 0 01.865-.501 48.172 48.172 0 003.423-.379c1.584-.233 2.707-1.626 2.707-3.228V6.741c0-1.602-1.123-2.995-2.707-3.228A48.394 48.394 0 0012 3c-2.392 0-4.744.175-7.043.513C3.373 3.746 2.25 5.14 2.25 6.741v6.018z" />
                    </svg>
                    Apa Kata Mereka
                </div>
                <h2 class="fade-up delay-1 text-3xl sm:text-4xl font-extrabold text-slate-900 tracking-tight mb-5">
                    Dipercaya Pondok Pesantren<br>di Berbagai Daerah
                </h2>
                <p class="fade-up delay-2 text-lg text-slate-500 leading-relaxed">
                    Beberapa pondok pesantren dan lembaga tahfidz yang sudah merasakan manfaat HifzhCare.
                </p>
            </div>

            <!-- Testimonial Cards -->
            <div class="grid md:grid-cols-3 gap-6">
                <!-- Testimonial 1 -->
                <div class="fade-up delay-1 bg-slate-50 rounded-2xl p-7 border border-slate-100 card-hover">
                    <div class="flex items-center gap-1 mb-4">
                        <svg class="w-4 h-4 text-amber-400" fill="currentColor" viewBox="0 0 20 20">
                            <path
                                d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                        </svg>
                        <svg class="w-4 h-4 text-amber-400" fill="currentColor" viewBox="0 0 20 20">
                            <path
                                d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                        </svg>
                        <svg class="w-4 h-4 text-amber-400" fill="currentColor" viewBox="0 0 20 20">
                            <path
                                d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                        </svg>
                        <svg class="w-4 h-4 text-amber-400" fill="currentColor" viewBox="0 0 20 20">
                            <path
                                d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                        </svg>
                        <svg class="w-4 h-4 text-amber-400" fill="currentColor" viewBox="0 0 20 20">
                            <path
                                d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                        </svg>
                    </div>
                    <p class="text-sm text-slate-600 leading-relaxed mb-6">
                        "Dulu kita pakai buku tulis untuk catat setoran. Kalau buku hilang, data ikut hilang. Sekarang
                        dengan HifzhCare, semua tercatat rapi. Wali santri juga bisa lihat progres anaknya langsung dari
                        HP."
                    </p>
                    <div class="flex items-center gap-3 pt-4 border-t border-slate-200">
                        <div
                            class="w-10 h-10 rounded-full bg-emerald-100 flex items-center justify-center text-sm font-bold text-emerald-700">
                            UA</div>
                        <div>
                            <p class="text-sm font-semibold text-slate-800">Ustadz Ahmad Fauzi</p>
                            <p class="text-xs text-slate-400">Pondok Tahfidz Nurul Ilmi, Jawa Timur</p>
                        </div>
                    </div>
                </div>

                <!-- Testimonial 2 -->
                <div class="fade-up delay-2 bg-slate-50 rounded-2xl p-7 border border-slate-100 card-hover">
                    <div class="flex items-center gap-1 mb-4">
                        <svg class="w-4 h-4 text-amber-400" fill="currentColor" viewBox="0 0 20 20">
                            <path
                                d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                        </svg>
                        <svg class="w-4 h-4 text-amber-400" fill="currentColor" viewBox="0 0 20 20">
                            <path
                                d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                        </svg>
                        <svg class="w-4 h-4 text-amber-400" fill="currentColor" viewBox="0 0 20 20">
                            <path
                                d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                        </svg>
                        <svg class="w-4 h-4 text-amber-400" fill="currentColor" viewBox="0 0 20 20">
                            <path
                                d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                        </svg>
                        <svg class="w-4 h-4 text-amber-400" fill="currentColor" viewBox="0 0 20 20">
                            <path
                                d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                        </svg>
                    </div>
                    <p class="text-sm text-slate-600 leading-relaxed mb-6">
                        "Saya sebagai wali santri dulu selalu khawatir tidak tahu progres anak. Sekarang tinggal buka
                        HP, sudah bisa lihat anak sudah sampai juz berapa. Fitur donasi apresiasinya juga bagus untuk
                        motivasi ustadz."
                    </p>
                    <div class="flex items-center gap-3 pt-4 border-t border-slate-200">
                        <div
                            class="w-10 h-10 rounded-full bg-blue-100 flex items-center justify-center text-sm font-bold text-blue-700">
                            IH</div>
                        <div>
                            <p class="text-sm font-semibold text-slate-800">Ibu Hani Rahmawati</p>
                            <p class="text-xs text-slate-400">Wali Santri, Pondok Al-Hikmah, Jawa Barat</p>
                        </div>
                    </div>
                </div>

                <!-- Testimonial 3 -->
                <div class="fade-up delay-3 bg-slate-50 rounded-2xl p-7 border border-slate-100 card-hover">
                    <div class="flex items-center gap-1 mb-4">
                        <svg class="w-4 h-4 text-amber-400" fill="currentColor" viewBox="0 0 20 20">
                            <path
                                d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                        </svg>
                        <svg class="w-4 h-4 text-amber-400" fill="currentColor" viewBox="0 0 20 20">
                            <path
                                d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                        </svg>
                        <svg class="w-4 h-4 text-amber-400" fill="currentColor" viewBox="0 0 20 20">
                            <path
                                d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                        </svg>
                        <svg class="w-4 h-4 text-amber-400" fill="currentColor" viewBox="0 0 20 20">
                            <path
                                d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                        </svg>
                        <svg class="w-4 h-4 text-amber-400" fill="currentColor" viewBox="0 0 20 20">
                            <path
                                d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                        </svg>
                    </div>
                    <p class="text-sm text-slate-600 leading-relaxed mb-6">
                        "Sebagai pimpinan pondok, saya butuh data yang cepat dan akurat untuk evaluasi. HifzhCare
                        memberikan dashboard yang lengkap. Saya bisa lihat santri mana yang perlu dibina lebih
                        intensif."
                    </p>
                    <div class="flex items-center gap-3 pt-4 border-t border-slate-200">
                        <div
                            class="w-10 h-10 rounded-full bg-violet-100 flex items-center justify-center text-sm font-bold text-violet-700">
                            KS</div>
                        <div>
                            <p class="text-sm font-semibold text-slate-800">KH. Syamsul Arifin</p>
                            <p class="text-xs text-slate-400">Pimpinan Ponpes Darussalam, Kalimantan Selatan</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- ========== FAQ SECTION ========== -->
    <section class="relative py-20 md:py-28 bg-slate-50 overflow-hidden">
        <div class="absolute top-0 left-0 right-0 h-px bg-gradient-to-r from-transparent via-slate-200 to-transparent">
        </div>

        <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Section Header -->
            <div class="text-center mb-14">
                <div
                    class="fade-up inline-flex items-center gap-2 bg-blue-50 border border-blue-200 text-blue-600 text-xs font-semibold px-4 py-2 rounded-full mb-6">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M9.879 7.519c1.171-1.025 3.071-1.025 4.242 0 1.172 1.025 1.172 2.687 0 3.712-.203.179-.43.326-.67.442-.745.361-1.45.999-1.45 1.827v.75M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-9 5.25h.008v.008H12v-.008z" />
                    </svg>
                    Pertanyaan Umum
                </div>
                <h2 class="fade-up delay-1 text-3xl sm:text-4xl font-extrabold text-slate-900 tracking-tight mb-5">
                    Masih Ada Pertanyaan?
                </h2>
                <p class="fade-up delay-2 text-lg text-slate-500">
                    Berikut jawaban atas pertanyaan yang sering ditanyakan.
                </p>
            </div>

            <!-- FAQ Items -->
            <div class="space-y-3">
                <!-- FAQ 1 -->
                <div class="fade-up delay-1 faq-item bg-white rounded-2xl border border-slate-100 overflow-hidden">
                    <button class="faq-btn w-full flex items-center justify-between px-6 py-5 text-left"
                        onclick="toggleFaq(this)">
                        <span class="text-sm font-semibold text-slate-800 pr-4">Apakah HifzhCare perlu diinstal di
                            komputer?</span>
                        <svg class="faq-icon w-5 h-5 text-slate-400 flex-shrink-0 transition-transform duration-300"
                            fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5" />
                        </svg>
                    </button>
                    <div class="faq-content hidden px-6 pb-5">
                        <p class="text-sm text-slate-500 leading-relaxed">Tidak perlu. HifzhCare berbasis web, jadi
                            cukup buka di browser (Chrome, Firefox, Safari, dll) dari HP, tablet, atau laptop. Tidak
                            perlu download atau instalasi apapun.</p>
                    </div>
                </div>

                <!-- FAQ 2 -->
                <div class="fade-up delay-2 faq-item bg-white rounded-2xl border border-slate-100 overflow-hidden">
                    <button class="faq-btn w-full flex items-center justify-between px-6 py-5 text-left"
                        onclick="toggleFaq(this)">
                        <span class="text-sm font-semibold text-slate-800 pr-4">Bagaimana jika ustadz belum terbiasa
                            pakai aplikasi?</span>
                        <svg class="faq-icon w-5 h-5 text-slate-400 flex-shrink-0 transition-transform duration-300"
                            fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5" />
                        </svg>
                    </button>
                    <div class="faq-content hidden px-6 pb-5">
                        <p class="text-sm text-slate-500 leading-relaxed">Kami mendesain HifzhCare sesederhana mungkin.
                            Proses input hafalan hanya butuh beberapa klik. Selain itu, tim kami akan membantu proses
                            onboarding dan pelatihan hingga ustadz nyaman menggunakan sistem.</p>
                    </div>
                </div>

                <!-- FAQ 3 -->
                <div class="fade-up delay-3 faq-item bg-white rounded-2xl border border-slate-100 overflow-hidden">
                    <button class="faq-btn w-full flex items-center justify-between px-6 py-5 text-left"
                        onclick="toggleFaq(this)">
                        <span class="text-sm font-semibold text-slate-800 pr-4">Apakah data santri aman?</span>
                        <svg class="faq-icon w-5 h-5 text-slate-400 flex-shrink-0 transition-transform duration-300"
                            fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5" />
                        </svg>
                    </button>
                    <div class="faq-content hidden px-6 pb-5">
                        <p class="text-sm text-slate-500 leading-relaxed">Keamanan data adalah prioritas kami. Data
                            disimpan di server yang aman dengan enkripsi, backup otomatis harian, dan akses terbatas
                            berdasarkan peran. Kami juga siap menandatangani MoU kerahasiaan data jika diperlukan.</p>
                    </div>
                </div>

                <!-- FAQ 4 -->
                <div class="fade-up delay-4 faq-item bg-white rounded-2xl border border-slate-100 overflow-hidden">
                    <button class="faq-btn w-full flex items-center justify-between px-6 py-5 text-left"
                        onclick="toggleFaq(this)">
                        <span class="text-sm font-semibold text-slate-800 pr-4">Apakah bisa dicoba dulu sebelum
                            berlangganan?</span>
                        <svg class="faq-icon w-5 h-5 text-slate-400 flex-shrink-0 transition-transform duration-300"
                            fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5" />
                        </svg>
                    </button>
                    <div class="faq-content hidden px-6 pb-5">
                        <p class="text-sm text-slate-500 leading-relaxed">Tentu! Kami menyediakan masa trial gratis 14
                            hari untuk semua paket. Anda bisa mencoba semua fitur tanpa komitmen. Jika merasa cocok,
                            baru lanjut berlangganan.</p>
                    </div>
                </div>

                <!-- FAQ 5 -->
                <div class="fade-up faq-item bg-white rounded-2xl border border-slate-100 overflow-hidden">
                    <button class="faq-btn w-full flex items-center justify-between px-6 py-5 text-left"
                        onclick="toggleFaq(this)">
                        <span class="text-sm font-semibold text-slate-800 pr-4">Bagaimana sistem donasi apresiasinya
                            bekerja?</span>
                        <svg class="faq-icon w-5 h-5 text-slate-400 flex-shrink-0 transition-transform duration-300"
                            fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5" />
                        </svg>
                    </button>
                    <div class="faq-content hidden px-6 pb-5">
                        <p class="text-sm text-slate-500 leading-relaxed">Fitur ini opsional. Wali santri bisa
                            memberikan donasi apresiasi kepada ustadz pembimbing anaknya melalui platform. Donasi akan
                            diteruskan ke rekening ustadz dengan potongan kecil untuk biaya platform dan pondok. Fitur
                            ini bisa diaktifkan/nonaktifkan oleh admin.</p>
                    </div>
                </div>

                <!-- FAQ 6 -->
                <div class="fade-up faq-item bg-white rounded-2xl border border-slate-100 overflow-hidden">
                    <button class="faq-btn w-full flex items-center justify-between px-6 py-5 text-left"
                        onclick="toggleFaq(this)">
                        <span class="text-sm font-semibold text-slate-800 pr-4">Bagaimana jika jumlah santri melebihi
                            batas paket?</span>
                        <svg class="faq-icon w-5 h-5 text-slate-400 flex-shrink-0 transition-transform duration-300"
                            fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5" />
                        </svg>
                    </button>
                    <div class="faq-content hidden px-6 pb-5">
                        <p class="text-sm text-slate-500 leading-relaxed">Anda bisa upgrade ke paket yang lebih tinggi
                            kapan saja. Tim kami akan menghubungi Anda ketika jumlah santri mendekati batas paket,
                            sehingga bisa diputuskan apakah ingin upgrade atau tetap di paket yang sama.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- ========== FOOTER ========== -->
    <footer class="relative bg-slate-900 text-slate-300 overflow-hidden">
        <div
            class="absolute top-0 left-0 right-0 h-px bg-gradient-to-r from-transparent via-emerald-600/50 to-transparent">
        </div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Footer Top -->
            <div class="py-16 grid sm:grid-cols-2 lg:grid-cols-4 gap-10">
                <!-- Brand -->
                <div class="lg:col-span-1">
                    <a href="#" class="flex items-center gap-2.5 mb-5">
                        <div
                            class="w-9 h-9 rounded-xl bg-gradient-to-br from-emerald-500 to-emerald-700 flex items-center justify-center">
                            <svg class="w-5 h-5 text-white" fill="none" viewBox="0 0 24 24" stroke-width="2"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                            </svg>
                        </div>
                        <span class="text-lg font-bold text-white">HifzhCare</span>
                    </a>
                    <p class="text-sm text-slate-400 leading-relaxed mb-6">
                        Aplikasi monitoring hafalan santri berbasis web. Membantu pondok pesantren mengelola data
                        hafalan dengan mudah, terstruktur, dan transparan.
                    </p>
                    <!-- Social -->
                    <div class="flex items-center gap-3">
                        <a href="#"
                            class="w-9 h-9 rounded-lg bg-slate-800 hover:bg-emerald-600 flex items-center justify-center transition-colors"
                            aria-label="Instagram">
                            <svg class="w-4 h-4 text-slate-400 hover:text-white transition-colors"
                                fill="currentColor" viewBox="0 0 24 24">
                                <path
                                    d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zM12 0C8.741 0 8.333.014 7.053.072 2.695.272.273 2.69.073 7.052.014 8.333 0 8.741 0 12c0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98C8.333 23.986 8.741 24 12 24c3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98C15.668.014 15.259 0 12 0zm0 5.838a6.162 6.162 0 100 12.324 6.162 6.162 0 000-12.324zM12 16a4 4 0 110-8 4 4 0 010 8zm6.406-11.845a1.44 1.44 0 100 2.881 1.44 1.44 0 000-2.881z" />
                            </svg>
                        </a>
                        <a href="#"
                            class="w-9 h-9 rounded-lg bg-slate-800 hover:bg-emerald-600 flex items-center justify-center transition-colors"
                            aria-label="Facebook">
                            <svg class="w-4 h-4 text-slate-400 hover:text-white transition-colors"
                                fill="currentColor" viewBox="0 0 24 24">
                                <path
                                    d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z" />
                            </svg>
                        </a>
                        <a href="#"
                            class="w-9 h-9 rounded-lg bg-slate-800 hover:bg-emerald-600 flex items-center justify-center transition-colors"
                            aria-label="YouTube">
                            <svg class="w-4 h-4 text-slate-400 hover:text-white transition-colors"
                                fill="currentColor" viewBox="0 0 24 24">
                                <path
                                    d="M23.498 6.186a3.016 3.016 0 00-2.122-2.136C19.505 3.545 12 3.545 12 3.545s-7.505 0-9.377.505A3.017 3.017 0 00.502 6.186C0 8.07 0 12 0 12s0 3.93.502 5.814a3.016 3.016 0 002.122 2.136c1.871.505 9.376.505 9.376.505s7.505 0 9.377-.505a3.015 3.015 0 002.122-2.136C24 15.93 24 12 24 12s0-3.93-.502-5.814zM9.545 15.568V8.432L15.818 12l-6.273 3.568z" />
                            </svg>
                        </a>
                        <a href="#"
                            class="w-9 h-9 rounded-lg bg-slate-800 hover:bg-emerald-600 flex items-center justify-center transition-colors"
                            aria-label="TikTok">
                            <svg class="w-4 h-4 text-slate-400 hover:text-white transition-colors"
                                fill="currentColor" viewBox="0 0 24 24">
                                <path
                                    d="M12.525.02c1.31-.02 2.61-.01 3.91-.02.08 1.53.63 3.09 1.75 4.17 1.12 1.11 2.7 1.62 4.24 1.79v4.03c-1.44-.05-2.89-.35-4.2-.97-.57-.26-1.1-.59-1.62-.93-.01 2.92.01 5.84-.02 8.75-.08 1.4-.54 2.79-1.35 3.94-1.31 1.92-3.58 3.17-5.91 3.21-1.43.08-2.86-.31-4.08-1.03-2.02-1.19-3.44-3.37-3.65-5.71-.02-.5-.03-1-.01-1.49.18-1.9 1.12-3.72 2.58-4.96 1.66-1.44 3.98-2.13 6.15-1.72.02 1.48-.04 2.96-.04 4.44-.99-.32-2.15-.23-3.02.37-.63.41-1.11 1.04-1.36 1.75-.21.51-.15 1.07-.14 1.61.24 1.64 1.82 3.02 3.5 2.87 1.12-.01 2.19-.66 2.77-1.61.19-.33.4-.67.41-1.06.1-1.79.06-3.57.07-5.36.01-4.03-.01-8.05.02-12.07z" />
                            </svg>
                        </a>
                    </div>
                </div>

                <!-- Product -->
                <div>
                    <h4 class="text-sm font-semibold text-white mb-5 uppercase tracking-wider">Produk</h4>
                    <ul class="space-y-3">
                        <li><a href="#fitur"
                                class="text-sm text-slate-400 hover:text-emerald-400 transition-colors">Fitur</a></li>
                        <li><a href="#harga"
                                class="text-sm text-slate-400 hover:text-emerald-400 transition-colors">Harga</a></li>
                        <li><a href="#"
                                class="text-sm text-slate-400 hover:text-emerald-400 transition-colors">Cara Kerja</a>
                        </li>
                        <li><a href="#"
                                class="text-sm text-slate-400 hover:text-emerald-400 transition-colors">Changelog</a>
                        </li>
                        <li><a href="#"
                                class="text-sm text-slate-400 hover:text-emerald-400 transition-colors">Roadmap</a>
                        </li>
                    </ul>
                </div>

                <!-- Resources -->
                <div>
                    <h4 class="text-sm font-semibold text-white mb-5 uppercase tracking-wider">Sumber Daya</h4>
                    <ul class="space-y-3">
                        <li><a href="#"
                                class="text-sm text-slate-400 hover:text-emerald-400 transition-colors">Panduan
                                Pengguna</a></li>
                        <li><a href="#"
                                class="text-sm text-slate-400 hover:text-emerald-400 transition-colors">Video
                                Tutorial</a></li>
                        <li><a href="#"
                                class="text-sm text-slate-400 hover:text-emerald-400 transition-colors">FAQ</a></li>
                        <li><a href="#"
                                class="text-sm text-slate-400 hover:text-emerald-400 transition-colors">Blog</a></li>
                        <li><a href="#"
                                class="text-sm text-slate-400 hover:text-emerald-400 transition-colors">Syarat &
                                Ketentuan</a></li>
                    </ul>
                </div>

                <!-- Contact -->
                <div>
                    <h4 class="text-sm font-semibold text-white mb-5 uppercase tracking-wider">Kontak</h4>
                    <ul class="space-y-4">
                        <li class="flex items-start gap-3">
                            <svg class="w-4 h-4 text-emerald-500 flex-shrink-0 mt-0.5" fill="none"
                                viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M2.25 6.75c0 8.284 6.716 15 15 15h2.25a2.25 2.25 0 002.25-2.25v-1.372c0-.516-.351-.966-.852-1.091l-4.423-1.106c-.44-.11-.902.055-1.173.417l-.97 1.293c-.282.376-.769.542-1.21.38a12.035 12.035 0 01-7.143-7.143c-.162-.441.004-.928.38-1.21l1.293-.97c.363-.271.527-.734.417-1.173L6.963 3.102a1.125 1.125 0 00-1.091-.852H4.5A2.25 2.25 0 002.25 4.5v2.25z" />
                            </svg>
                            <div>
                                <p class="text-sm text-slate-400">WhatsApp</p>
                                <p class="text-sm text-slate-300 font-medium">0812-3456-7890</p>
                            </div>
                        </li>
                        <li class="flex items-start gap-3">
                            <svg class="w-4 h-4 text-emerald-500 flex-shrink-0 mt-0.5" fill="none"
                                viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M21.75 6.75v10.5a2.25 2.25 0 01-2.25 2.25h-15a2.25 2.25 0 01-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25m19.5 0v.243a2.25 2.25 0 01-1.07 1.916l-7.5 4.615a2.25 2.25 0 01-2.36 0L3.32 8.91a2.25 2.25 0 01-1.07-1.916V6.75" />
                            </svg>
                            <div>
                                <p class="text-sm text-slate-400">Email</p>
                                <p class="text-sm text-slate-300 font-medium">info@hifzhcare.com</p>
                            </div>
                        </li>
                        <li class="flex items-start gap-3">
                            <svg class="w-4 h-4 text-emerald-500 flex-shrink-0 mt-0.5" fill="none"
                                viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M15 10.5a3 3 0 11-6 0 3 3 0 016 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1115 0z" />
                            </svg>
                            <div>
                                <p class="text-sm text-slate-400">Lokasi</p>
                                <p class="text-sm text-slate-300 font-medium">Indonesia</p>
                            </div>
                        </li>
                    </ul>
                </div>
            </div>

            <!-- Footer Bottom -->
            <div class="py-6 border-t border-slate-800 flex flex-col sm:flex-row items-center justify-between gap-4">
                <p class="text-xs text-slate-500">
                    &copy; {{ date('Y') }} HifzhCare. Hak cipta dilindungi.
                </p>
                <div class="flex items-center gap-6">
                    <a href="#"
                        class="text-xs text-slate-500 hover:text-slate-300 transition-colors">Kebijakan Privasi</a>
                    <a href="#" class="text-xs text-slate-500 hover:text-slate-300 transition-colors">Syarat
                        Layanan</a>
                </div>
            </div>
        </div>
    </footer>

    <!-- ========== FLOATING WhatsApp BUTTON ========== -->
    <a href="https://wa.me/6281234567890?text=Assalamualaikum%2C%20saya%20tertarik%20dengan%20HifzhCare"
        target="_blank"
        class="fixed bottom-6 right-6 z-50 w-14 h-14 bg-green-500 hover:bg-green-600 rounded-full flex items-center justify-center shadow-lg shadow-green-500/30 transition-all duration-200 hover:scale-110 group"
        aria-label="Chat WhatsApp">
        <svg class="w-7 h-7 text-white" fill="currentColor" viewBox="0 0 24 24">
            <path
                d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z" />
        </svg>
        <span
            class="absolute -top-10 right-0 bg-slate-800 text-white text-xs font-medium px-3 py-1.5 rounded-lg opacity-0 group-hover:opacity-100 transition-opacity whitespace-nowrap pointer-events-none">Chat
            Kami</span>
    </a>

    <!-- ========== SCRIPTS ========== -->
    <script>
        // Mobile Menu Toggle
        const mobileMenuBtn = document.getElementById('mobileMenuBtn');
        const mobileMenu = document.getElementById('mobileMenu');
        const menuIconOpen = document.getElementById('menuIconOpen');
        const menuIconClose = document.getElementById('menuIconClose');

        mobileMenuBtn.addEventListener('click', () => {
            mobileMenu.classList.toggle('hidden');
            menuIconOpen.classList.toggle('hidden');
            menuIconClose.classList.toggle('hidden');
        });

        // Close mobile menu on link click
        mobileMenu.querySelectorAll('a').forEach(link => {
            link.addEventListener('click', () => {
                mobileMenu.classList.add('hidden');
                menuIconOpen.classList.remove('hidden');
                menuIconClose.classList.add('hidden');
            });
        });

        // Scroll-based fade-up animation
        const observerOptions = {
            root: null,
            rootMargin: '0px 0px -60px 0px',
            threshold: 0.1
        };

        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('visible');
                    observer.unobserve(entry.target);
                }
            });
        }, observerOptions);

        document.querySelectorAll('.fade-up').forEach(el => observer.observe(el));

        // Navbar scroll effect
        const navbar = document.getElementById('navbar');
        let lastScroll = 0;

        window.addEventListener('scroll', () => {
            const currentScroll = window.pageYOffset;

            if (currentScroll > 50) {
                navbar.classList.add('shadow-sm');
            } else {
                navbar.classList.remove('shadow-sm');
            }

            lastScroll = currentScroll;
        }, {
            passive: true
        });

        // FAQ Toggle
        function toggleFaq(btn) {
            const item = btn.closest('.faq-item');
            const content = item.querySelector('.faq-content');
            const icon = item.querySelector('.faq-icon');
            const isOpen = !content.classList.contains('hidden');

            // Close all
            document.querySelectorAll('.faq-item').forEach(faq => {
                faq.querySelector('.faq-content').classList.add('hidden');
                faq.querySelector('.faq-icon').style.transform = 'rotate(0deg)';
            });

            // Open clicked if was closed
            if (!isOpen) {
                content.classList.remove('hidden');
                icon.style.transform = 'rotate(180deg)';
            }
        }

        // Smooth scroll for anchor links (fallback for older browsers)
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function(e) {
                const href = this.getAttribute('href');
                if (href === '#') return;
                e.preventDefault();
                const target = document.querySelector(href);
                if (target) {
                    const offset = 80;
                    const top = target.getBoundingClientRect().top + window.pageYOffset - offset;
                    window.scrollTo({
                        top,
                        behavior: 'smooth'
                    });
                }
            });
        });
    </script>
</body>

</html>
