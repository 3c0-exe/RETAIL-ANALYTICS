<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
<style>
        /* --- 1. Your Existing Fade Animation --- */
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(15px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .animate-fade-in {
            animation: fadeInUp 1.0s ease-in-out forwards;
        }

        /* --- 2. The Lush Background Styles --- */

        /* Base Background & Gradients */
        .lush-bg {
            background-color: #0f172a; /* Deep base color */
            /* Static gradients */
            background-image:
                radial-gradient(at 0% 0%, hsla(253,16%,7%,1) 0, transparent 50%),
                radial-gradient(at 50% 0%, hsla(225,39%,30%,1) 0, transparent 50%),
                radial-gradient(at 100% 0%, hsla(339,49%,30%,1) 0, transparent 50%);
            position: relative;
            overflow: hidden;
        }

        /* Animated Moving Orbs */
        .lush-bg::before {
            content: '';
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background:
                radial-gradient(circle at 50% 50%, rgba(76, 29, 149, 0.4), transparent 50%), /* Purple */
                radial-gradient(circle at 10% 30%, rgba(6, 78, 59, 0.4), transparent 40%),  /* Emerald */
                radial-gradient(circle at 90% 80%, rgba(30, 64, 175, 0.4), transparent 40%); /* Blue */
            filter: blur(80px); /* Heavy blur for "lush" look */
            animation: moveBackground 20s infinite alternate ease-in-out;
            z-index: 0;
        }

        /* Noisy Texture Overlay */
        .lush-bg::after {
            content: "";
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            /* Tiny SVG noise pattern */
            background-image: url("data:image/svg+xml,%3Csvg viewBox='0 0 200 200' xmlns='http://www.w3.org/2000/svg'%3E%3Cfilter id='noiseFilter'%3E%3CfeTurbulence type='fractalNoise' baseFrequency='0.7' numOctaves='3' stitchTiles='stitch'/%3E%3C/filter%3E%3Crect width='100%25' height='100%25' filter='url(%23noiseFilter)' opacity='0.05'/%3E%3C/svg%3E");
            pointer-events: none;
            z-index: 1;
            opacity: 0.5; /* Noise intensity */
        }

        /* Slow Movement Animation */
        @keyframes moveBackground {
            0% { transform: translate(0, 0) scale(1); }
            50% { transform: translate(-3%, 2%) scale(1.1); }
            100% { transform: translate(3%, -3%) scale(1); }
        }

        /* Ensures the login form stays above the background layers */
        .content-wrapper {
            position: relative;
            z-index: 10;
        }
    </style>


    <body class="font-sans text-gray-900 antialiased">
        <div class="min-h-screen flex flex-col justify-center items-center p-4 lush-bg ">

            <div class="w-full sm:max-w-md mt-6 px-6 py-4 bg-white shadow-md overflow-hidden sm:rounded-lg content-wrapper animate-fade-in">
                {{ $slot }}
            </div>

        </div>
    </body>


</html>
