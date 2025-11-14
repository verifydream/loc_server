<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Admin Login - Location Server</title>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" rel="stylesheet">
    
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com?plugins=forms"></script>
    <script>
        tailwind.config = {
            darkMode: "class",
            theme: {
                extend: {
                    colors: {
                        primary: "#3B82F6",
                        "card-dark": "#1e293b",
                    },
                    fontFamily: {
                        display: ["Inter", "sans-serif"],
                    },
                },
            },
        };
    </script>
    
    <style>
        .material-symbols-outlined {
            font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24;
        }
        
        /* Smooth transitions */
        * {
            transition: background-color 0.3s ease, border-color 0.3s ease, color 0.3s ease;
        }
        
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }
        
        .dark body {
            background: linear-gradient(135deg, #1e3a8a 0%, #0f172a 100%);
        }
        
        /* Theme toggle animation */
        .theme-toggle {
            position: relative;
            overflow: hidden;
        }
        
        .theme-toggle .icon {
            transition: transform 0.3s ease, opacity 0.3s ease;
        }
        
        .theme-toggle .sun-icon {
            transform: rotate(0deg);
        }
        
        .theme-toggle .moon-icon {
            transform: rotate(180deg);
            opacity: 0;
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%) rotate(180deg);
        }
        
        .dark .theme-toggle .sun-icon {
            transform: rotate(-180deg);
            opacity: 0;
        }
        
        .dark .theme-toggle .moon-icon {
            transform: translate(-50%, -50%) rotate(0deg);
            opacity: 1;
        }
    </style>
</head>
<body class="font-display min-h-screen flex items-center justify-center p-4">
    <!-- Dark Mode Toggle (Fixed Position) -->
    <button id="theme-toggle" class="theme-toggle fixed top-4 right-4 flex items-center justify-center w-12 h-12 rounded-full bg-white/20 dark:bg-slate-800/50 backdrop-blur-sm border border-white/30 dark:border-slate-700 hover:bg-white/30 dark:hover:bg-slate-700/50 transition-all shadow-lg" title="Toggle dark mode">
        <span class="material-symbols-outlined icon sun-icon text-2xl text-white">light_mode</span>
        <span class="material-symbols-outlined icon moon-icon text-2xl text-blue-300">dark_mode</span>
    </button>

    <div class="w-full max-w-md">
        <!-- Login Card -->
        <div class="bg-white dark:bg-slate-900 rounded-2xl shadow-2xl overflow-hidden">
            <!-- Header -->
            <div class="bg-gradient-to-r from-blue-500 to-blue-600 p-8 text-center">
                <div class="inline-flex items-center justify-center w-16 h-16 bg-white/20 rounded-full mb-4">
                    <span class="material-symbols-outlined text-white text-4xl">location_on</span>
                </div>
                <h1 class="text-2xl font-bold text-white mb-1">Location Server</h1>
                <p class="text-blue-100 text-sm">Admin Dashboard Login</p>
            </div>

            <!-- Form -->
            <div class="p-8">
                <!-- Flash Messages -->
                @if (session('error'))
                    <div class="mb-6 bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 text-red-800 dark:text-red-200 px-4 py-3 rounded-lg flex items-start" role="alert">
                        <span class="material-symbols-outlined mr-2 mt-0.5">error</span>
                        <div class="flex-1">
                            <span>{{ session('error') }}</span>
                        </div>
                        <button type="button" class="text-red-800 dark:text-red-200 hover:text-red-900 dark:hover:text-red-100" onclick="this.parentElement.remove()">
                            <span class="material-symbols-outlined">close</span>
                        </button>
                    </div>
                @endif

                @if (session('message'))
                    <div class="mb-6 bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 text-blue-800 dark:text-blue-200 px-4 py-3 rounded-lg flex items-start" role="alert">
                        <span class="material-symbols-outlined mr-2 mt-0.5">info</span>
                        <div class="flex-1">
                            <span>{{ session('message') }}</span>
                        </div>
                        <button type="button" class="text-blue-800 dark:text-blue-200 hover:text-blue-900 dark:hover:text-blue-100" onclick="this.parentElement.remove()">
                            <span class="material-symbols-outlined">close</span>
                        </button>
                    </div>
                @endif

                <form method="POST" action="{{ route('admin.login.submit') }}" class="space-y-6">
                    @csrf

                    <!-- Email -->
                    <div>
                        <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2" for="email">
                            Email Address
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <span class="material-symbols-outlined text-slate-400">email</span>
                            </div>
                            <input class="block w-full pl-10 pr-3 py-3 border border-slate-300 dark:border-slate-600 rounded-lg bg-slate-50 dark:bg-slate-800 text-slate-900 dark:text-slate-100 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent transition-all @error('email') border-red-500 @enderror" 
                                   id="email" 
                                   name="email" 
                                   type="email" 
                                   value="{{ old('email') }}" 
                                   placeholder="Enter your email"
                                   required 
                                   autofocus/>
                        </div>
                        @error('email')
                            <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Password -->
                    <div>
                        <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2" for="password">
                            Password
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <span class="material-symbols-outlined text-slate-400">lock</span>
                            </div>
                            <input class="block w-full pl-10 pr-3 py-3 border border-slate-300 dark:border-slate-600 rounded-lg bg-slate-50 dark:bg-slate-800 text-slate-900 dark:text-slate-100 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent transition-all @error('password') border-red-500 @enderror" 
                                   id="password" 
                                   name="password" 
                                   type="password" 
                                   placeholder="Enter your password"
                                   required/>
                        </div>
                        @error('password')
                            <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Remember Me -->
                    <div class="flex items-center">
                        <input class="h-4 w-4 text-primary border-slate-300 rounded focus:ring-primary" 
                               id="remember" 
                               name="remember" 
                               type="checkbox"/>
                        <label class="ml-2 block text-sm text-slate-700 dark:text-slate-300" for="remember">
                            Remember me
                        </label>
                    </div>

                    <!-- Submit Button -->
                    <button class="w-full flex items-center justify-center px-4 py-3 bg-gradient-to-r from-blue-500 to-blue-600 hover:from-blue-600 hover:to-blue-700 text-white font-semibold rounded-lg shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 transition-all duration-200" type="submit">
                        <span class="material-symbols-outlined mr-2">login</span>
                        Sign In
                    </button>
                </form>
            </div>

            <!-- Footer -->
            <div class="px-8 py-4 bg-slate-50 dark:bg-slate-800 border-t border-slate-200 dark:border-slate-700">
                <p class="text-center text-xs text-slate-500 dark:text-slate-400">
                    © 2025 Location Server. All rights reserved.
                </p>
            </div>
        </div>

        <!-- Info Card -->
        <div class="mt-6 bg-white/10 backdrop-blur-sm rounded-lg p-4 text-center">
            <p class="text-white text-sm">
                <span class="material-symbols-outlined text-base align-middle mr-1">info</span>
                Need help? Contact your system administrator
            </p>
        </div>
    </div>

    <script>
        // Dark Mode Toggle
        (function() {
            // Check for saved theme preference or default to light mode
            const theme = localStorage.getItem('theme') || 'light';
            
            // Apply theme on page load
            if (theme === 'dark') {
                document.documentElement.classList.add('dark');
            } else {
                document.documentElement.classList.remove('dark');
            }
        })();

        document.addEventListener('DOMContentLoaded', function() {
            const themeToggle = document.getElementById('theme-toggle');
            
            if (themeToggle) {
                themeToggle.addEventListener('click', function() {
                    const html = document.documentElement;
                    const isDark = html.classList.contains('dark');
                    
                    if (isDark) {
                        html.classList.remove('dark');
                        localStorage.setItem('theme', 'light');
                    } else {
                        html.classList.add('dark');
                        localStorage.setItem('theme', 'dark');
                    }
                    
                    // Add a subtle scale animation to the button
                    this.style.transform = 'scale(0.9)';
                    setTimeout(() => {
                        this.style.transform = 'scale(1)';
                    }, 150);
                });
            }
            
            // Auto-dismiss alerts after 5 seconds
            const alerts = document.querySelectorAll('[role="alert"]');
            alerts.forEach(function(alert) {
                setTimeout(function() {
                    alert.remove();
                }, 5000);
            });
        });
    </script>
</body>
</html>
