<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Dashboard') - Location Server</title>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" rel="stylesheet">
    
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com?plugins=forms,typography"></script>
    <script>
        tailwind.config = {
            darkMode: "class",
            theme: {
                extend: {
                    colors: {
                        primary: "#3B82F6",
                        "background-light": "#F8FAFC",
                        "background-dark": "#0f172a", // Slate-900 - Dark blue instead of black
                        "card-dark": "#1e293b", // Slate-800 - For cards in dark mode
                        "sidebar-dark": "#0f1729", // Even darker blue for sidebar
                    },
                    fontFamily: {
                        display: ["Inter", "sans-serif"],
                    },
                    borderRadius: {
                        DEFAULT: "0.75rem",
                    },
                },
            },
        };
    </script>
    
    <style>
        .material-symbols-outlined {
            font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24;
        }
        
        /* Dark mode transition */
        * {
            transition: background-color 0.3s ease, border-color 0.3s ease, color 0.3s ease;
        }
        
        /* Dark mode color overrides - Modern dark blue theme */
        .dark {
            color-scheme: dark;
        }
        
        /* Main background - Use sidebar color for consistency */
        .dark body {
            background-color: #0f1729 !important; /* Same as sidebar - dark navy */
        }
        
        /* Content area background */
        .dark main {
            background-color: #0f1729 !important; /* Same as sidebar */
        }
        
        /* Override Tailwind dark mode colors with modern dark blue */
        .dark .dark\:bg-slate-900,
        .dark .dark\:bg-zinc-900 {
            background-color: #1e293b !important; /* Slate-800 - Card background */
        }
        
        .dark .dark\:bg-slate-900\/70,
        .dark .dark\:bg-zinc-900\/50 {
            background-color: rgba(30, 41, 59, 0.7) !important;
        }
        
        .dark .dark\:bg-slate-800 {
            background-color: #334155 !important; /* Slate-700 */
        }
        
        .dark .dark\:bg-slate-800\/50 {
            background-color: rgba(51, 65, 85, 0.5) !important;
        }
        
        /* Header specific - Same as sidebar for consistency */
        .dark header {
            background-color: #0f1729 !important; /* Same as sidebar - dark navy */
        }
        
        /* Sidebar specific - Darkest navy */
        .dark aside {
            background-color: #0f1729 !important; /* sidebar-dark */
        }
        
        .dark .dark\:border-slate-800,
        .dark .dark\:border-zinc-800 {
            border-color: rgba(71, 85, 105, 0.3) !important; /* Slate-600 with opacity */
        }
        
        .dark .dark\:border-slate-700,
        .dark .dark\:border-zinc-700 {
            border-color: rgba(71, 85, 105, 0.5) !important;
        }
        
        .dark .dark\:text-slate-100 {
            color: #f1f5f9 !important; /* Slate-100 */
        }
        
        .dark .dark\:text-slate-200 {
            color: #e2e8f0 !important; /* Slate-200 */
        }
        
        .dark .dark\:text-slate-300 {
            color: #cbd5e1 !important; /* Slate-300 */
        }
        
        .dark .dark\:text-slate-400 {
            color: #94a3b8 !important; /* Slate-400 */
        }
        
        /* Theme toggle button animation */
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
    
    @stack('styles')
</head>
<body class="font-display bg-background-light dark:bg-background-dark text-slate-700 dark:text-slate-300">
    <div class="flex h-screen">
        <!-- Sidebar -->
        <aside class="w-64 flex-shrink-0 bg-white dark:bg-sidebar-dark border-r border-slate-200 dark:border-slate-700/50 flex flex-col">
            <div class="h-16 flex items-center px-6 border-b border-slate-200 dark:border-slate-700/50">
                <span class="material-symbols-outlined text-primary mr-2 text-2xl">location_on</span>
                <h1 class="text-lg font-bold text-slate-800 dark:text-white">Location Server</h1>
            </div>
            <nav class="flex-1 px-4 py-6 space-y-2">
                <a class="flex items-center px-4 py-2.5 {{ request()->routeIs('admin.dashboard') ? 'bg-primary text-white font-semibold shadow-lg shadow-primary/20' : 'text-slate-600 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-800/50' }} rounded-lg transition-all" href="{{ route('admin.dashboard') }}">
                    <span class="material-symbols-outlined mr-3">dashboard</span>
                    Dashboard
                </a>
                <a class="flex items-center px-4 py-2.5 {{ request()->routeIs('admin.users.*') ? 'bg-primary text-white font-semibold shadow-lg shadow-primary/20' : 'text-slate-600 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-800/50' }} rounded-lg transition-all" href="{{ route('admin.users.index') }}">
                    <span class="material-symbols-outlined mr-3">group</span>
                    Users
                </a>
                <a class="flex items-center px-4 py-2.5 {{ request()->routeIs('admin.locations.*') ? 'bg-primary text-white font-semibold shadow-lg shadow-primary/20' : 'text-slate-600 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-800/50' }} rounded-lg transition-all" href="{{ route('admin.locations.index') }}">
                    <span class="material-symbols-outlined mr-3">pin_drop</span>
                    Locations
                </a>
                <a class="flex items-center px-4 py-2.5 {{ request()->routeIs('admin.app-versions.*') ? 'bg-primary text-white font-semibold shadow-lg shadow-primary/20' : 'text-slate-600 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-800/50' }} rounded-lg transition-all" href="{{ route('admin.app-versions.index') }}">
                    <span class="material-symbols-outlined mr-3">system_update</span>
                    App Updates
                </a>
            </nav>
        </aside>

        <!-- Main Content -->
        <main class="flex-1 flex flex-col">
            <!-- Header -->
            <header class="h-16 bg-white dark:bg-card-dark border-b border-slate-200 dark:border-slate-700/50 flex-shrink-0 flex items-center justify-between px-8">
                <h2 class="text-2xl font-bold text-slate-800 dark:text-white flex items-center">
                    @yield('page-icon', '<span class="material-symbols-outlined mr-3 text-3xl text-slate-500 dark:text-slate-400">dashboard</span>')
                    @yield('page-title', 'Dashboard')
                </h2>
                <div class="flex items-center space-x-3">
                    @yield('header-actions')
                    
                    <div class="h-8 w-px bg-slate-300 dark:bg-slate-600"></div>
                    <!-- Dark Mode Toggle -->
                    <button id="theme-toggle" class="theme-toggle flex items-center justify-center w-10 h-10 rounded-lg border border-slate-300 dark:border-slate-600 hover:bg-slate-100 dark:hover:bg-slate-700/50 transition-colors" title="Toggle dark mode">
                        <span class="material-symbols-outlined icon sun-icon text-xl text-amber-500">light_mode</span>
                        <span class="material-symbols-outlined icon moon-icon text-xl text-blue-400">dark_mode</span>
                    </button>
                    
                    <div class="flex items-center space-x-2 text-sm text-slate-600 dark:text-slate-300">
                        <span class="material-symbols-outlined text-xl">admin_panel_settings</span>
                        <span>{{ auth('admin')->user()->name ?? 'Administrator' }}</span>
                    </div>
                    <form method="POST" action="{{ route('admin.logout') }}" class="inline">
                        @csrf
                        <button type="submit" class="flex items-center space-x-2 px-4 py-2 text-sm border border-slate-300 dark:border-slate-600 rounded-lg hover:bg-slate-100 dark:hover:bg-slate-700/50 transition-colors">
                            <span class="material-symbols-outlined text-xl">logout</span>
                            <span>Logout</span>
                        </button>
                    </form>
                </div>
            </header>

            <!-- Content Area -->
            <div class="flex-1 overflow-y-auto p-8">
                <div class="max-w-7xl mx-auto">
                    <!-- Flash Messages -->
                    @if (session('success'))
                        <div class="mb-6 bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 text-green-800 dark:text-green-200 px-4 py-3 rounded-lg flex items-center justify-between" role="alert">
                            <div class="flex items-center">
                                <span class="material-symbols-outlined mr-2">check_circle</span>
                                <span>{{ session('success') }}</span>
                            </div>
                            <button type="button" class="text-green-800 dark:text-green-200 hover:text-green-900 dark:hover:text-green-100" onclick="this.parentElement.remove()">
                                <span class="material-symbols-outlined">close</span>
                            </button>
                        </div>
                    @endif

                    @if (session('error'))
                        <div class="mb-6 bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 text-red-800 dark:text-red-200 px-4 py-3 rounded-lg flex items-center justify-between" role="alert">
                            <div class="flex items-center">
                                <span class="material-symbols-outlined mr-2">error</span>
                                <span>{{ session('error') }}</span>
                            </div>
                            <button type="button" class="text-red-800 dark:text-red-200 hover:text-red-900 dark:hover:text-red-100" onclick="this.parentElement.remove()">
                                <span class="material-symbols-outlined">close</span>
                            </button>
                        </div>
                    @endif

                    @if (session('warning'))
                        <div class="mb-6 bg-yellow-50 dark:bg-yellow-900/20 border border-yellow-200 dark:border-yellow-800 text-yellow-800 dark:text-yellow-200 px-4 py-3 rounded-lg flex items-center justify-between" role="alert">
                            <div class="flex items-center">
                                <span class="material-symbols-outlined mr-2">warning</span>
                                <span>{{ session('warning') }}</span>
                            </div>
                            <button type="button" class="text-yellow-800 dark:text-yellow-200 hover:text-yellow-900 dark:hover:text-yellow-100" onclick="this.parentElement.remove()">
                                <span class="material-symbols-outlined">close</span>
                            </button>
                        </div>
                    @endif

                    @if (session('info'))
                        <div class="mb-6 bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 text-blue-800 dark:text-blue-200 px-4 py-3 rounded-lg flex items-center justify-between" role="alert">
                            <div class="flex items-center">
                                <span class="material-symbols-outlined mr-2">info</span>
                                <span>{{ session('info') }}</span>
                            </div>
                            <button type="button" class="text-blue-800 dark:text-blue-200 hover:text-blue-900 dark:hover:text-blue-100" onclick="this.parentElement.remove()">
                                <span class="material-symbols-outlined">close</span>
                            </button>
                        </div>
                    @endif

                    <!-- Page Content -->
                    @yield('content')
                </div>
            </div>
        </main>
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
                    this.style.transform = 'scale(0.95)';
                    setTimeout(() => {
                        this.style.transform = 'scale(1)';
                    }, 100);
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
    
    @stack('scripts')
</body>
</html>
