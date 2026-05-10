<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use App\Models\Order;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class AdminController extends Controller
{
    public function dashboard()
    {
        $totalOrders = Order::count();
        $totalProducts = Product::count();
        $totalUsers = User::where('role', 'user')->count();
        $pendingOrders = Order::where('status', 'pending')->count();
        $processingOrders = Order::where('status', 'processing')->count();
        $completedOrders = Order::where('status', 'completed')->count();
        $cancelledOrders = Order::where('status', 'cancelled')->count();
        
        $recentOrders = Order::with('user')->orderBy('created_at', 'desc')->limit(10)->get();
        
        $ordersByStatus = [
            'pending' => $pendingOrders,
            'processing' => $processingOrders,
            'completed' => $completedOrders,
            'cancelled' => $cancelledOrders,
        ];
        
        $recentCustomers = User::where('role', 'user')
                               ->orderBy('created_at', 'desc')
                               ->limit(5)
                               ->get();
        
        return view('admin.dashboard', compact(
            'totalOrders', 'totalProducts', 'totalUsers', 
            'pendingOrders', 'processingOrders', 'completedOrders', 
            'cancelledOrders', 'recentOrders', 'ordersByStatus', 
            'recentCustomers'
        ));
    }

    public function products()
    {
        $products = Product::with('category')->orderBy('created_at', 'desc')->paginate(10);
        return view('admin.products.index', compact('products'));
    }

    public function createProduct()
    {
        $categories = Category::all();
        return view('admin.products.create', compact('categories'));
    }

    public function storeProduct(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'category_id' => 'required|exists:categories,id',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $slug = Str::slug($request->name);
        $count = Product::where('slug', $slug)->count();
        if ($count > 0) {
            $slug = $slug . '-' . time();
        }

        $imagePath = null;
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imageName = time() . '_' . Str::slug($request->name) . '.' . $image->getClientOriginalExtension();
            $imagePath = $image->storeAs('products', $imageName, 'public');
        }

        Product::create([
            'name' => $request->name,
            'slug' => $slug,
            'description' => $request->description,
            'price' => $request->price,
            'stock' => $request->stock,
            'category_id' => $request->category_id,
            'image' => $imagePath,
            'is_active' => $request->has('is_active'),
        ]);

        return redirect()->route('admin.products')->with('success', 'Product created successfully!');
    }

    public function editProduct($id)
    {
        $product = Product::findOrFail($id);
        $categories = Category::all();
        return view('admin.products.edit', compact('product', 'categories'));
    }

    public function updateProduct(Request $request, $id)
    {
        $product = Product::findOrFail($id);
        
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'category_id' => 'required|exists:categories,id',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        if ($product->name !== $request->name) {
            $slug = Str::slug($request->name);
            $count = Product::where('slug', $slug)->where('id', '!=', $product->id)->count();
            if ($count > 0) {
                $slug = $slug . '-' . time();
            }
            $product->slug = $slug;
        }

        if ($request->hasFile('image')) {
            if ($product->image && Storage::disk('public')->exists($product->image)) {
                Storage::disk('public')->delete($product->image);
            }
            
            $image = $request->file('image');
            $imageName = time() . '_' . Str::slug($request->name) . '.' . $image->getClientOriginalExtension();
            $imagePath = $image->storeAs('products', $imageName, 'public');
            $product->image = $imagePath;
        }

        $product->name = $request->name;
        $product->description = $request->description;
        $product->price = $request->price;
        $product->stock = $request->stock;
        $product->category_id = $request->category_id;
        $product->is_active = $request->has('is_active');
        $product->save();

        return redirect()->route('admin.products')->with('success', 'Product updated successfully!');
    }

    public function deleteProduct($id)
    {
        $product = Product::findOrFail($id);
        
        if ($product->image && Storage::disk('public')->exists($product->image)) {
            Storage::disk('public')->delete($product->image);
        }
        
        $product->delete();
        
        return redirect()->route('admin.products')->with('success', 'Product deleted successfully!');
    }

    public function orders()
    {
        $orders = Order::with('user')->orderBy('created_at', 'desc')->paginate(10);
        return view('admin.orders.index', compact('orders'));
    }

    public function orderDetails($orderId)
    {
        $order = Order::with(['user', 'items.product'])->findOrFail($orderId);
        return view('admin.orders.details', compact('order'));
    }

    public function updateOrderStatus(Request $request, $id)
    {
        $order = Order::findOrFail($id);
        $request->validate([
            'status' => 'required|in:pending,processing,completed,cancelled'
        ]);

        $order->status = $request->status;
        $order->save();

        return redirect()->back()->with('success', 'Order status updated successfully');
    }

    public function allCustomers()
    {
        $customers = User::where('role', 'user')->withCount('orders')->orderBy('created_at', 'desc')->paginate(15);
        return view('admin.customers.index', compact('customers'));
    }

    public function customerOrders($userId)
    {
        $customer = User::findOrFail($userId);
        $orders = Order::where('user_id', $userId)->with('items.product')->orderBy('created_at', 'desc')->paginate(10);
        return view('admin.customers.orders', compact('customer', 'orders'));
    }

    public function salesReport()
    {
        $totalSales = Order::where('status', 'completed')->sum('total_amount');
        $totalOrders = Order::where('status', 'completed')->count();
        
        $ordersByMonth = Order::selectRaw('MONTH(created_at) as month, SUM(total_amount) as total')
                             ->where('status', 'completed')
                             ->whereYear('created_at', date('Y'))
                             ->groupBy('month')
                             ->orderBy('month', 'asc')
                             ->get();
        
        return view('admin.reports.sales', compact('totalSales', 'totalOrders', 'ordersByMonth'));
    }
}