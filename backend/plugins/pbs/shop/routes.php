<?php

use Pbs\Shop\Models\Product;
use Pbs\Shop\Models\Order;
use Pbs\Shop\Models\OrderItem;
use Pbs\Shop\Models\Cart;
use Pbs\Shop\Models\CartItem;
use Pbs\Shop\Api\Resources\ProductResource;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Request;

Route::get("/api/shop/products", function () {
    $products = Product::with("productable")->get();
    return ProductResource::collection($products);
});

Route::post("/api/shop/checkout", function () {
    $data = Request::all();

    if (!isset($data['first_name'], $data['last_name'], $data['email'], $data['items'])) {
        return response()->json(['error' => 'Missing required fields'], 400);
    }

    $order = Order::create([
        'first_name' => $data['first_name'],
        'last_name' => $data['last_name'],
        'email' => $data['email'],
        'address' => $data['address'],
        'total' => $data['total'],
        'status' => 'pending'
    ]);

    foreach ($data['items'] as $item) {
        OrderItem::create([
            'order_id' => $order->id,
            'product_id' => $item['id'],
            'quantity' => $item['quantity'],
            'price' => $item['price']
        ]);
    }

    return response()->json([
        'success' => true,
        'order_id' => $order->id
    ]);
});

// Auth Routes (Login is Guest-accessible, User is protected)
Route::group(['prefix' => 'api/auth', 'middleware' => [\Fruitcake\Cors\HandleCors::class]], function () {
    Route::post('login', function () {
        $credentials = [
            'login'    => Request::input('email'),
            'password' => Request::input('password'),
        ];

        try {
            $user = \Winter\User\Classes\AuthManager::instance()->authenticate($credentials);
            $token = \Pbs\Shop\Classes\JWTAuth::generateToken($user);

            return response()->json([
                'token' => $token,
                'user'  => [
                    'id'    => $user->id,
                    'name'  => $user->name,
                    'email' => $user->email
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Invalid credentials'], 401);
        }
    });

    Route::middleware([\Pbs\Shop\Classes\AuthMiddleware::class])->get('user', function () {
        $user = request()->user;
        return response()->json([
            'id'    => $user->id,
            'name'  => $user->name,
            'email' => $user->email
        ]);
    });
});

// Cart Routes
Route::group(['prefix' => 'api/shop/cart', 'middleware' => [\Fruitcake\Cors\HandleCors::class]], function () {

    // Sync Cart: Overwrites current cart state
    Route::post('sync', function () {
        $sessionId = Request::input('session_id');
        $items = Request::input('items', []);

        if (!$sessionId) {
            return response()->json(['error' => 'No session ID provided'], 400);
        }

        // Try to identify user if JWT is present
        $user = null;
        $token = Request::bearerToken();
        if ($token) {
            $decoded = \Pbs\Shop\Classes\JWTAuth::decodeToken($token);
            if ($decoded) {
                $user = \Winter\User\Models\User::find($decoded->sub);
            }
        }

        // Find or create cart
        $cart = Cart::firstOrCreate(['session_id' => $sessionId]);
        if ($user) {
            $cart->user_id = $user->id;
            $cart->save();
        }

        // Wipe and rebuild items
        $cart->items()->delete();
        foreach ($items as $item) {
            CartItem::create([
                'cart_id' => $cart->id,
                'product_id' => $item['id'],
                'quantity' => $item['quantity']
            ]);
        }

        return response()->json(['success' => true]);
    });

    // Get Cart: Fetches persisted cart
    Route::get('get', function () {
        $sessionId = Request::query('session_id');

        if (!$sessionId) {
            return response()->json(['error' => 'No session ID provided'], 400);
        }

        $cart = Cart::where('session_id', $sessionId)
            ->with('items.product')
            ->first();

        if (!$cart) {
            return response()->json(['items' => []]);
        }

        $formattedItems = $cart->items->map(function ($item) {
            if (!$item->product) return null;
            return [
                'id' => $item->product->id,
                'title' => $item->product->title,
                'price' => (float)$item->product->price,
                'type' => str_replace('Pbs\\Shop\\Models\\', '', $item->product->productable_type),
                'quantity' => $item->quantity
            ];
        })->filter()->values();

        return response()->json(['items' => $formattedItems]);
    });
});

// User Orders API
Route::middleware([
    \Fruitcake\Cors\HandleCors::class,
    \Pbs\Shop\Classes\AuthMiddleware::class
])->group(function () {
    Route::get('/api/shop/orders/my', function () {
        $user = request()->user;

        $orders = \Pbs\Shop\Models\Order::where('email', $user->email)
            ->with('items.product')
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json([
            'orders' => $orders
        ]);
    });
});
