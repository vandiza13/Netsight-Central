<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>NETSIGHT Central - License Dashboard</title>
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <!-- Tailwind CSS Play CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Outfit', 'sans-serif'],
                    },
                }
            }
        }
    </script>
    <style>
        body {
            background-color: #0b0f19;
            color: #f3f4f6;
        }
        /* Custom scrollbar */
        ::-webkit-scrollbar {
            width: 8px;
            height: 8px;
        }
        ::-webkit-scrollbar-track {
            background: #0f172a;
        }
        ::-webkit-scrollbar-thumb {
            background: #334155;
            border-radius: 4px;
        }
        ::-webkit-scrollbar-thumb:hover {
            background: #475569;
        }
    </style>
</head>
<body class="min-h-screen antialiased bg-[#0b0f19] text-gray-100 font-sans pb-12">

    <!-- Top Navigation -->
    <nav class="border-b border-gray-800 bg-[#0f172a]/80 backdrop-blur-md sticky top-0 z-40">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16">
                <div class="flex items-center space-x-3">
                    <!-- Shield SVG Icon -->
                    <div class="p-2 bg-indigo-600/20 rounded-lg text-indigo-400">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                        </svg>
                    </div>
                    <div>
                        <span class="text-xl font-bold tracking-tight bg-gradient-to-r from-indigo-400 via-purple-400 to-pink-400 bg-clip-text text-transparent">NETSIGHT Central</span>
                        <span class="text-xs block text-gray-400 font-medium -mt-1">License Server</span>
                    </div>
                </div>
                <div class="flex items-center space-x-4">
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-emerald-500/10 text-emerald-400 border border-emerald-500/20">
                        <span class="w-1.5 h-1.5 mr-1.5 bg-emerald-400 rounded-full animate-pulse"></span>
                        Admin System Online
                    </span>
                </div>
            </div>
        </div>
    </nav>

    <!-- Main Container -->
    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-8">

        <!-- Banner Alerts / Flash Messages -->
        @if (session('success'))
            <div id="flash-message" class="mb-6 p-4 rounded-xl bg-emerald-500/10 border border-emerald-500/20 text-emerald-400 flex items-start space-x-3 shadow-lg">
                <svg class="w-5 h-5 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <div class="flex-1">
                    <p class="font-medium text-sm">{{ session('success') }}</p>
                </div>
                <button onclick="document.getElementById('flash-message').remove()" class="text-emerald-400 hover:text-emerald-300">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
        @endif

        @if ($errors->any())
            <div id="flash-errors" class="mb-6 p-4 rounded-xl bg-rose-500/10 border border-rose-500/20 text-rose-400 shadow-lg">
                <div class="flex items-start space-x-3 mb-2">
                    <svg class="w-5 h-5 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                    </svg>
                    <span class="font-semibold text-sm">Please correct the following errors:</span>
                </div>
                <ul class="list-disc list-inside text-xs space-y-1 ml-8">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- Stats Overview Grid -->
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-6 mb-8">
            <!-- Total Licenses -->
            <div class="bg-[#0f172a] p-5 rounded-2xl border border-gray-800 flex items-center justify-between shadow-xl">
                <div>
                    <span class="text-xs font-semibold text-gray-400 uppercase tracking-wider block">Total Licenses</span>
                    <span class="text-2xl sm:text-3xl font-extrabold text-white mt-1 block" id="stat-total">{{ $stats['total'] }}</span>
                </div>
                <div class="p-3 bg-indigo-500/10 text-indigo-400 rounded-xl">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                </div>
            </div>
            <!-- Active Licenses -->
            <div class="bg-[#0f172a] p-5 rounded-2xl border border-gray-800 flex items-center justify-between shadow-xl">
                <div>
                    <span class="text-xs font-semibold text-gray-400 uppercase tracking-wider block">Active</span>
                    <span class="text-2xl sm:text-3xl font-extrabold text-emerald-400 mt-1 block" id="stat-active">{{ $stats['active'] }}</span>
                </div>
                <div class="p-3 bg-emerald-500/10 text-emerald-400 rounded-xl">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
            </div>
            <!-- Suspended Licenses -->
            <div class="bg-[#0f172a] p-5 rounded-2xl border border-gray-800 flex items-center justify-between shadow-xl">
                <div>
                    <span class="text-xs font-semibold text-gray-400 uppercase tracking-wider block">Suspended</span>
                    <span class="text-2xl sm:text-3xl font-extrabold text-amber-500 mt-1 block" id="stat-suspended">{{ $stats['suspended'] }}</span>
                </div>
                <div class="p-3 bg-amber-500/10 text-amber-500 rounded-xl">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                    </svg>
                </div>
            </div>
            <!-- Expired Licenses -->
            <div class="bg-[#0f172a] p-5 rounded-2xl border border-gray-800 flex items-center justify-between shadow-xl">
                <div>
                    <span class="text-xs font-semibold text-gray-400 uppercase tracking-wider block">Expired</span>
                    <span class="text-2xl sm:text-3xl font-extrabold text-rose-500 mt-1 block" id="stat-expired">{{ $stats['expired'] }}</span>
                </div>
                <div class="p-3 bg-rose-500/10 text-rose-400 rounded-xl">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
            </div>
        </div>

        <!-- Split Grid (Form & Table) -->
        <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">
            <!-- Left Side: Create Form Card -->
            <div class="lg:col-span-1">
                <div class="bg-[#0f172a] p-6 rounded-2xl border border-gray-800 shadow-xl sticky top-24">
                    <h2 class="text-lg font-bold text-white mb-6 flex items-center">
                        <svg class="w-5 h-5 mr-2 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v3m0 0v3m0-3h3m-3 0H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        Issue New License
                    </h2>
                    <form action="{{ route('admin.licenses.store') }}" method="POST" class="space-y-4">
                        @csrf
                        <div>
                            <label class="block text-xs font-semibold text-gray-400 uppercase tracking-wider mb-2">Customer / ISP Name</label>
                            <input type="text" name="customer_name" required placeholder="e.g. Jaringan Jaya Pratama" 
                                   class="w-full bg-[#0b0f19] border border-gray-800 rounded-xl px-4 py-2.5 text-sm text-white focus:outline-none focus:border-indigo-500 transition-colors">
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-gray-400 uppercase tracking-wider mb-2">Target Domain</label>
                            <input type="text" name="target_domain" required placeholder="e.g. netsight.jaya.net" 
                                   class="w-full bg-[#0b0f19] border border-gray-800 rounded-xl px-4 py-2.5 text-sm text-white focus:outline-none focus:border-indigo-500 transition-colors">
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-gray-400 uppercase tracking-wider mb-2">Target IP (Optional)</label>
                            <input type="text" name="target_ip" placeholder="e.g. 103.155.22.4" 
                                   class="w-full bg-[#0b0f19] border border-gray-800 rounded-xl px-4 py-2.5 text-sm text-white focus:outline-none focus:border-indigo-500 transition-colors">
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-gray-400 uppercase tracking-wider mb-2">Max Monitored Routers</label>
                            <input type="number" name="max_routers" value="5" min="1" required 
                                   class="w-full bg-[#0b0f19] border border-gray-800 rounded-xl px-4 py-2.5 text-sm text-white focus:outline-none focus:border-indigo-500 transition-colors">
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-gray-400 uppercase tracking-wider mb-2">Expiration Date</label>
                            <input type="date" name="expires_at" required 
                                   class="w-full bg-[#0b0f19] border border-gray-800 rounded-xl px-4 py-2.5 text-sm text-white focus:outline-none focus:border-indigo-500 transition-colors">
                        </div>
                        <button type="submit" 
                                class="w-full bg-indigo-600 hover:bg-indigo-500 active:bg-indigo-700 text-white text-sm font-semibold py-3 px-4 rounded-xl transition-all duration-200 shadow-lg shadow-indigo-600/20 hover:shadow-indigo-600/30 flex items-center justify-center space-x-2 mt-2">
                            <span>Generate License Key</span>
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                            </svg>
                        </button>
                    </form>
                </div>
            </div>

            <!-- Right Side: Licenses Table -->
            <div class="lg:col-span-3">
                <div class="bg-[#0f172a] rounded-2xl border border-gray-800 shadow-xl overflow-hidden">
                    <div class="px-6 py-5 border-b border-gray-800 flex justify-between items-center bg-[#0f172a]">
                        <h3 class="text-lg font-bold text-white">Active Licenses Registry</h3>
                        <span class="text-xs text-gray-400 font-medium">Click buttons to perform dynamic state modifications</span>
                    </div>

                    <!-- Responsive Table Wrapper -->
                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr class="bg-[#0b0f19]/50 text-xs font-semibold uppercase tracking-wider text-gray-400 border-b border-gray-800">
                                    <th class="px-6 py-4">Client Detail</th>
                                    <th class="px-6 py-4">License Key</th>
                                    <th class="px-6 py-4">Max Routers</th>
                                    <th class="px-6 py-4">Status</th>
                                    <th class="px-6 py-4">Last Verification Ping</th>
                                    <th class="px-6 py-4">Expires At</th>
                                    <th class="px-6 py-4 text-center">Quick Actions</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-800" id="licenses-table-body">
                                @forelse ($licenses as $license)
                                    <tr class="hover:bg-gray-800/25 transition-colors" id="license-row-{{ $license->id }}">
                                        <!-- Client Detail -->
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="font-semibold text-sm text-white">{{ $license->customer_name }}</div>
                                            <div class="text-xs text-gray-400 flex items-center space-x-1 mt-0.5">
                                                <svg class="w-3.5 h-3.5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9"></path>
                                                </svg>
                                                <span>{{ $license->target_domain }}</span>
                                                @if($license->target_ip)
                                                    <span class="text-gray-600">•</span>
                                                    <span>{{ $license->target_ip }}</span>
                                                @endif
                                            </div>
                                        </td>
                                        <!-- License Key -->
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="flex items-center space-x-2">
                                                <code class="text-xs font-mono font-bold bg-[#0b0f19] px-2.5 py-1.5 rounded-lg border border-gray-800 text-indigo-400 select-all">{{ $license->license_key }}</code>
                                                <!-- Copy Button -->
                                                <button onclick="copyToClipboard('{{ $license->license_key }}')" class="p-1 hover:text-white text-gray-500 rounded hover:bg-gray-800 transition-colors" title="Copy Key">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 5H6a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2v-1M8 5a2 2 0 002 2h2a2 2 0 002-2M8 5a2 2 0 012-2h2a2 2 0 012 2m0 0h2a2 2 0 012 2v3m2 4H10m0 0l3-3m-3 3l3 3"></path>
                                                    </svg>
                                                </button>
                                            </div>
                                        </td>
                                        <!-- Max Routers -->
                                        <td class="px-6 py-4 whitespace-nowrap font-semibold text-sm">
                                            {{ $license->max_routers }} <span class="text-xs text-gray-500 font-normal">nodes</span>
                                        </td>
                                        <!-- Status Badge -->
                                        <td class="px-6 py-4 whitespace-nowrap" id="status-cell-{{ $license->id }}">
                                            @if($license->status === 'active')
                                                <span class="px-2.5 py-1 rounded-full text-xs font-semibold bg-emerald-500/10 text-emerald-400 border border-emerald-500/20 uppercase tracking-wider">Active</span>
                                            @elseif($license->status === 'suspended')
                                                <span class="px-2.5 py-1 rounded-full text-xs font-semibold bg-amber-500/10 text-amber-400 border border-amber-500/20 uppercase tracking-wider">Suspended</span>
                                            @else
                                                <span class="px-2.5 py-1 rounded-full text-xs font-semibold bg-rose-500/10 text-rose-400 border border-rose-500/20 uppercase tracking-wider">Expired</span>
                                            @endif
                                        </td>
                                        <!-- Last Verification Ping -->
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            @if($license->last_ping_at)
                                                <div class="text-sm font-medium text-white">{{ $license->last_ping_ip }}</div>
                                                <div class="text-xs text-gray-400 mt-0.5" title="{{ $license->last_ping_at }}">{{ $license->last_ping_at->diffForHumans() }}</div>
                                            @else
                                                <span class="text-xs text-gray-500 italic">Never connected</span>
                                            @endif
                                        </td>
                                        <!-- Expires At -->
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium" id="expiry-cell-{{ $license->id }}">
                                            <span class="text-white">{{ $license->expires_at->format('M d, Y') }}</span>
                                            <span class="text-xs block text-gray-400 font-normal mt-0.5">{{ $license->expires_at->diffForHumans() }}</span>
                                        </td>
                                        <!-- Quick Actions -->
                                        <td class="px-6 py-4 whitespace-nowrap text-center">
                                            <div class="flex items-center justify-center space-x-2">
                                                <!-- Toggle Status -->
                                                <button id="toggle-btn-{{ $license->id }}" onclick="toggleStatus({{ $license->id }})" 
                                                        class="inline-flex items-center px-3 py-1.5 border border-gray-700 hover:border-gray-600 rounded-lg text-xs font-semibold text-gray-300 hover:text-white hover:bg-gray-800 transition-all duration-150">
                                                    {{ $license->status === 'active' ? 'Suspend' : 'Activate' }}
                                                </button>
                                                <!-- Extend Validity (+30 days) -->
                                                <button onclick="extendValidity({{ $license->id }})" 
                                                        class="inline-flex items-center px-3 py-1.5 bg-indigo-600/10 hover:bg-indigo-600/20 border border-indigo-500/20 hover:border-indigo-500/30 text-indigo-400 hover:text-indigo-300 rounded-lg text-xs font-semibold transition-all duration-150">
                                                    +30 Days
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="px-6 py-12 text-center">
                                            <div class="flex flex-col items-center justify-center space-y-3">
                                                <svg class="w-12 h-12 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0a2 2 0 01-2 2H6a2 2 0 01-2-2m16 0V9a2 2 0 00-2-2H6a2 2 0 00-2 2v4m16 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3v-1m16 0H3"></path>
                                                </svg>
                                                <div class="text-sm font-semibold text-gray-400">No licenses found in central database</div>
                                                <p class="text-xs text-gray-500 max-w-sm">Use the form on the left to issue a new client license key.</p>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <!-- Toast Notifications Area -->
    <div id="toast-container" class="fixed bottom-5 right-5 space-y-3 z-50"></div>

    <!-- Scripts -->
    <script>
        // Global AJAX setup with CSRF Token
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

        // Copy to clipboard helper
        function copyToClipboard(text) {
            navigator.clipboard.writeText(text).then(() => {
                showToast('License key copied to clipboard!', 'info');
            }).catch(err => {
                console.error('Could not copy key: ', err);
            });
        }

        // Display beautiful toast notifications
        function showToast(message, type = 'success') {
            const container = document.getElementById('toast-container');
            const toast = document.createElement('div');
            
            // Set styles based on type
            let bgClass = 'bg-indigo-600/90 text-white';
            let borderClass = 'border-indigo-500';
            let iconSvg = '';

            if (type === 'success') {
                bgClass = 'bg-emerald-950/90 text-emerald-400';
                borderClass = 'border-emerald-500/30';
                iconSvg = `<svg class="w-5 h-5 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>`;
            } else if (type === 'warning') {
                bgClass = 'bg-amber-950/90 text-amber-400';
                borderClass = 'border-amber-500/30';
                iconSvg = `<svg class="w-5 h-5 text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>`;
            } else if (type === 'error') {
                bgClass = 'bg-rose-950/90 text-rose-400';
                borderClass = 'border-rose-500/30';
                iconSvg = `<svg class="w-5 h-5 text-rose-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>`;
            } else {
                iconSvg = `<svg class="w-5 h-5 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>`;
            }

            toast.className = `flex items-center space-x-3 px-4 py-3 rounded-xl border ${bgClass} ${borderClass} shadow-xl transform translate-y-2 opacity-0 transition-all duration-300 backdrop-blur-md`;
            toast.innerHTML = `
                ${iconSvg}
                <span class="text-sm font-medium">${message}</span>
            `;

            container.appendChild(toast);

            // Trigger slide in animation
            setTimeout(() => {
                toast.classList.remove('translate-y-2', 'opacity-0');
            }, 10);

            // Remove toast after 3 seconds
            setTimeout(() => {
                toast.classList.add('translate-y-2', 'opacity-0');
                setTimeout(() => {
                    toast.remove();
                }, 300);
            }, 3000);
        }

        // AJAX: Toggle Status
        function toggleStatus(id) {
            const btn = document.getElementById(`toggle-btn-${id}`);
            const originalText = btn.innerHTML;
            btn.disabled = true;
            btn.innerHTML = `<span class="inline-block w-4 h-4 border-2 border-gray-400 border-t-white rounded-full animate-spin"></span>`;

            fetch(`/admin/licenses/${id}/toggle-status`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json'
                }
            })
            .then(res => {
                if (!res.ok) throw new Error('Network error');
                return res.json();
            })
            .then(data => {
                if (data.success) {
                    // Update Toggle Button Text
                    btn.innerHTML = data.status === 'active' ? 'Suspend' : 'Activate';
                    
                    // Update Status Badge Cell
                    const statusCell = document.getElementById(`status-cell-${id}`);
                    if (data.status === 'active') {
                        statusCell.innerHTML = `<span class="px-2.5 py-1 rounded-full text-xs font-semibold bg-emerald-500/10 text-emerald-400 border border-emerald-500/20 uppercase tracking-wider">Active</span>`;
                    } else if (data.status === 'suspended') {
                        statusCell.innerHTML = `<span class="px-2.5 py-1 rounded-full text-xs font-semibold bg-amber-500/10 text-amber-400 border border-amber-500/20 uppercase tracking-wider">Suspended</span>`;
                    } else {
                        statusCell.innerHTML = `<span class="px-2.5 py-1 rounded-full text-xs font-semibold bg-rose-500/10 text-rose-400 border border-rose-500/20 uppercase tracking-wider">Expired</span>`;
                    }

                    // Update stats counters
                    updateStats();
                    showToast(data.message, data.status === 'active' ? 'success' : 'warning');
                }
            })
            .catch(err => {
                console.error(err);
                btn.innerHTML = originalText;
                showToast('Failed to update status. Please try again.', 'error');
            })
            .finally(() => {
                btn.disabled = false;
            });
        }

        // AJAX: Extend Validity (+30 days)
        function extendValidity(id) {
            showToast('Extending license validity...', 'info');

            fetch(`/admin/licenses/${id}/extend-validity`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json'
                }
            })
            .then(res => {
                if (!res.ok) throw new Error('Network error');
                return res.json();
            })
            .then(data => {
                if (data.success) {
                    // Update Expiry Cell Content
                    const expiryCell = document.getElementById(`expiry-cell-${id}`);
                    const diffText = getRelativeTime(new Date(data.expires_at));
                    expiryCell.innerHTML = `
                        <span class="text-white">${data.formatted_expires_at}</span>
                        <span class="text-xs block text-gray-400 font-normal mt-0.5">${diffText}</span>
                    `;

                    // If status was changed (e.g. from expired to active)
                    const statusCell = document.getElementById(`status-cell-${id}`);
                    const btn = document.getElementById(`toggle-btn-${id}`);
                    if (data.status === 'active') {
                        statusCell.innerHTML = `<span class="px-2.5 py-1 rounded-full text-xs font-semibold bg-emerald-500/10 text-emerald-400 border border-emerald-500/20 uppercase tracking-wider">Active</span>`;
                        btn.innerHTML = 'Suspend';
                    }

                    updateStats();
                    showToast(data.message, 'success');
                }
            })
            .catch(err => {
                console.error(err);
                showToast('Failed to extend validity.', 'error');
            });
        }

        // Recalculate stats dynamically from the table status badges
        function updateStats() {
            const table = document.getElementById('licenses-table-body');
            const badges = table.querySelectorAll('span.uppercase');
            
            let active = 0;
            let suspended = 0;
            let expired = 0;
            let total = 0;

            badges.forEach(badge => {
                const text = badge.textContent.trim().toLowerCase();
                if (text === 'active') active++;
                else if (text === 'suspended') suspended++;
                else if (text === 'expired') expired++;
                total++;
            });

            document.getElementById('stat-total').textContent = total;
            document.getElementById('stat-active').textContent = active;
            document.getElementById('stat-suspended').textContent = suspended;
            document.getElementById('stat-expired').textContent = expired;
        }

        // Mini relative time helper
        function getRelativeTime(date) {
            const rtf = new Intl.RelativeTimeFormat('en', { numeric: 'auto' });
            const elapsed = date - new Date();
            
            const units = {
                day: 24 * 60 * 60 * 1000,
                hour: 60 * 60 * 1000,
                minute: 60 * 1000
            };

            for (const unit in units) {
                if (Math.abs(elapsed) > units[unit] || unit === 'minute') {
                    return rtf.format(Math.round(elapsed / units[unit]), unit);
                }
            }
            return 'just now';
        }
    </script>
</body>
</html>
