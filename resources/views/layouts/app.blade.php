<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>QuickParts - Motorcycle Parts Store</title>
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- Add Animate.css for animations -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">
    
    <style>
        /* Smooth page transitions */
        .fade-enter-active, .fade-leave-active {
            transition: opacity 0.3s ease;
        }

        .fade-enter-from, .fade-leave-to {
            opacity: 0;
        }

        /* Button pulse animation */
        @keyframes pulse {
            0% {
                transform: scale(1);
            }
            50% {
                transform: scale(1.05);
            }
            100% {
                transform: scale(1);
            }
        }

        .btn-pulse:hover {
            animation: pulse 0.5s ease-in-out;
        }

        /* Image zoom on hover */
        .image-zoom {
            overflow: hidden;
        }

        .image-zoom img {
            transition: transform 0.5s ease;
        }

        .image-zoom:hover img {
            transform: scale(1.1);
        }

        /* Card glow effect on hover */
        .card-glow:hover {
            box-shadow: 0 0 20px rgba(102, 126, 234, 0.3);
        }

        /* Loading spinner */
        .loading-spinner {
            width: 40px;
            height: 40px;
            border: 4px solid #f3f3f3;
            border-top: 4px solid #667eea;
            border-radius: 50%;
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
        
        /* Product card animations */
        .product-card {
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        }
        
        /* Floating animation for cards */
        @keyframes float {
            0% {
                transform: translateY(0px);
            }
            50% {
                transform: translateY(-10px);
            }
            100% {
                transform: translateY(0px);
            }
        }
        
        .float-animation {
            animation: float 3s ease-in-out infinite;
        }
        
        /* Skeleton loading animation */
        @keyframes shimmer {
            0% {
                background-position: -1000px 0;
            }
            100% {
                background-position: 1000px 0;
            }
        }
        
        .loading-skeleton {
            animation: shimmer 2s infinite linear;
            background: linear-gradient(to right, #f6f7f8 0%, #edeef1 20%, #f6f7f8 40%, #f6f7f8 100%);
            background-size: 1000px 100%;
        }
        
        /* Category hover effect */
        .category-link {
            transition: all 0.3s ease;
        }
        
        .category-link:hover {
            transform: translateX(5px);
            color: #3b82f6;
        }
        
        /* Bounce in effect */
        @keyframes bounceIn {
            0% {
                opacity: 0;
                transform: scale(0.3);
            }
            50% {
                opacity: 1;
                transform: scale(1.05);
            }
            70% {
                transform: scale(0.9);
            }
            100% {
                transform: scale(1);
            }
        }
        
        .bounce-in {
            animation: bounceIn 0.8s ease-out;
        }
        
        /* Keyframe Animations */
        @keyframes slideInLeft {
            from {
                opacity: 0;
                transform: translateX(-50px);
            }
            to {
                opacity: 1;
                transform: translateX(0);
            }
        }
        
        @keyframes slideInRight {
            from {
                opacity: 0;
                transform: translateX(50px);
            }
            to {
                opacity: 1;
                transform: translateX(0);
            }
        }
        
        @keyframes slideInDown {
            from {
                opacity: 0;
                transform: translateY(-30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        /* Line clamp */
        .line-clamp-2 {
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }
    </style>
</head>
<body class="bg-gray-100 overflow-x-hidden">
    <nav class="bg-white shadow-lg fixed w-full top-0 z-50">
        <div class="max-w-7xl mx-auto px-4">
            <div class="flex justify-between items-center h-16">
                <div class="flex items-center">
                    <a href="/" class="text-xl font-bold text-gray-800 transition-all duration-300 hover:text-blue-600">🏍️ QuickParts</a>
                    <div class="ml-10">
                        <a href="/products" class="text-gray-700 hover:text-gray-900 transition-all duration-300 hover:text-blue-600">Products</a>
                    </div>
                </div>
                
                <div class="flex items-center space-x-4">
                    @auth
                        @if(auth()->user()->isAdmin())
                            <div class="relative">
                                <button onclick="toggleDropdown()" class="text-gray-700 hover:text-gray-900 transition-all duration-300">Manage ▼</button>
                                <div id="dropdownMenu" class="hidden absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg z-10 transition-all duration-300">
                                    <a href="/admin/dashboard" class="block px-4 py-2 text-gray-700 hover:bg-gray-100">Dashboard</a>
                                    <a href="/admin/products" class="block px-4 py-2 text-gray-700 hover:bg-gray-100">Products</a>
                                    <a href="/admin/orders" class="block px-4 py-2 text-gray-700 hover:bg-gray-100">Orders</a>
                                    <a href="/admin/customers" class="block px-4 py-2 text-gray-700 hover:bg-gray-100">Customers</a>
                                    <a href="/admin/sales-report" class="block px-4 py-2 text-gray-700 hover:bg-gray-100">Sales Report</a>
                                </div>
                            </div>
                        @else
                            <a href="/cart" class="text-gray-700 hover:text-gray-900 transition-all duration-300 hover:text-blue-600 hover:scale-105 inline-block">🛒 Cart</a>
                            <a href="/orders" class="text-gray-700 hover:text-gray-900 transition-all duration-300 hover:text-blue-600">My Orders</a>
                        @endif
                        
                        <a href="/account/settings" class="text-gray-700 hover:text-gray-900 transition-all duration-300 hover:text-blue-600">
                            ⚙️ Account Settings
                        </a>
                        
                        <form method="POST" action="/logout" class="inline">
                            @csrf
                            <button type="submit" class="text-gray-700 hover:text-gray-900 transition-all duration-300 hover:text-red-600">Logout</button>
                        </form>
                    @else
                        <a href="/login" class="text-gray-700 hover:text-gray-900 transition-all duration-300 hover:text-blue-600">Login</a>
                        <a href="/register" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600 transition-all duration-300 hover:scale-105">Register</a>
                    @endauth
                </div>
            </div>
        </div>
    </nav>

    <!-- Add padding top to account for fixed navbar -->
    <main class="max-w-7xl mx-auto px-4 py-8 mt-16">
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

    <!-- Back to Top Button -->
    <button id="backToTop" class="fixed bottom-8 right-8 bg-blue-500 text-white p-3 rounded-full shadow-lg opacity-0 invisible transition-all duration-300 hover:bg-blue-600 hover:scale-110 z-50">
        ↑
    </button>

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
        
        // Back to top button functionality
        const backToTop = document.getElementById('backToTop');
        
        if (backToTop) {
            window.addEventListener('scroll', () => {
                if (window.scrollY > 300) {
                    backToTop.classList.remove('opacity-0', 'invisible');
                    backToTop.classList.add('opacity-100', 'visible');
                } else {
                    backToTop.classList.remove('opacity-100', 'visible');
                    backToTop.classList.add('opacity-0', 'invisible');
                }
            });
            
            backToTop.addEventListener('click', () => {
                window.scrollTo({ top: 0, behavior: 'smooth' });
            });
        }
    </script>
</body>
</html>