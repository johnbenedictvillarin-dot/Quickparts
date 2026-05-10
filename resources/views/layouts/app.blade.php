<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>QuickParts - Motorcycle Parts Store</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">
    <nav class="bg-white shadow-lg">
        <div class="max-w-7xl mx-auto px-4">
            <div class="flex justify-between items-center h-16">
                <div class="flex items-center">
                    <a href="/" class="text-xl font-bold text-gray-800">🏍️ QuickParts</a>
                    <div class="ml-10">
                        <a href="/products" class="text-gray-700 hover:text-gray-900">Products</a>
                    </div>
                </div>
                
                <div class="flex items-center space-x-4">
                    @auth
                        @if(auth()->user()->isAdmin())
                            <div class="relative">
                                <button onclick="toggleDropdown()" class="text-gray-700 hover:text-gray-900">Manage ▼</button>
                                <div id="dropdownMenu" class="hidden absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg z-10">
                                    <a href="/admin/dashboard" class="block px-4 py-2 text-gray-700 hover:bg-gray-100">Dashboard</a>
                                    <a href="/admin/products" class="block px-4 py-2 text-gray-700 hover:bg-gray-100">Products</a>
                                    <a href="/admin/orders" class="block px-4 py-2 text-gray-700 hover:bg-gray-100">Orders</a>
                                    <a href="/admin/customers" class="block px-4 py-2 text-gray-700 hover:bg-gray-100">Customers</a>
                                    <a href="/admin/sales-report" class="block px-4 py-2 text-gray-700 hover:bg-gray-100">Sales Report</a>
                                </div>
                            </div>
                        @else
                            <a href="/cart" class="text-gray-700 hover:text-gray-900">🛒 Cart</a>
                            <a href="/orders" class="text-gray-700 hover:text-gray-900">My Orders</a>
                        @endif
                        
                        <!-- ACCOUNT SETTINGS LINK - ADD THIS -->
                        <a href="/account/settings" class="text-gray-700 hover:text-gray-900">
                            ⚙️ Account Settings
                        </a>
                        
                        <form method="POST" action="/logout" class="inline">
                            @csrf
                            <button type="submit" class="text-gray-700 hover:text-gray-900">Logout</button>
                        </form>
                    @else
                        <a href="/login" class="text-gray-700 hover:text-gray-900">Login</a>
                        <a href="/register" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">Register</a>
                    @endauth
                </div>
            </div>
        </div>
    </nav>

    <main class="max-w-7xl mx-auto px-4 py-8">
        @if(session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                {{ session('error') }}
            </div>
        @endif

        @yield('content')
    </main>

    <script>
        function toggleDropdown() {
            const menu = document.getElementById('dropdownMenu');
            menu.classList.toggle('hidden');
        }

        document.addEventListener('click', function(event) {
            const dropdown = document.querySelector('.relative');
            const menu = document.getElementById('dropdownMenu');
            
            if (dropdown && !dropdown.contains(event.target)) {
                if (menu && !menu.classList.contains('hidden')) {
                    menu.classList.add('hidden');
                }
            }
        });
    </script>
</body>
</html>