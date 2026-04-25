# Food Ordering Management System

## 1. Project Description

This is a **Laravel-based Food Ordering Management System** designed for restaurants/food businesses to manage their menu, orders, promotions, and users. The system includes both customer and administrator interfaces with role-based access control.

### Key Features:

-   User authentication and registration with role-based access (User/Admin)
-   Food menu management with categories and promotions
-   Shopping cart functionality with checkout process
-   Order management and tracking
-   Admin dashboard for managing foods, promotions, users, and orders
-   Session management and secure cookie handling
-   Input validation and authorization checks

### Technology Stack:

-   **Framework**: Laravel 8.x
-   **Database**: MySQL
-   **Frontend**: Blade Templates with Bootstrap
-   **Authentication**: Laravel Sanctum & Built-in Auth
-   **Session Driver**: File-based (configurable to database/Redis)

---

## 2. Database Design and Migrations

### Overview

The database consists of 6 main tables with proper relationships and foreign key constraints.

### Migration File Location

**File**: `database/migrations/2026_04_07_114316_create_all_tables.php`

### Database Schema Details

#### 2.1 Users Table

```
Table: users
- id (Primary Key)
- name (string)
- email (string, unique)
- email_verified_at (timestamp, nullable)
- password (string)
- rememberToken (string)
- role (enum: 'user', 'admin') - Default: 'user'
- timestamps (created_at, updated_at)
```

**Purpose**: Stores user account information with role-based access control

#### 2.2 Categories Table

```
Table: categories
- id (Primary Key)
- name (string)
- timestamps (created_at, updated_at)
```

**Purpose**: Stores food categories (e.g., Appetizers, Main Course, Desserts)

#### 2.3 Promotions Table

```
Table: promotions
- id (Primary Key)
- name (string)
- description (text, nullable)
- image (string, nullable)
- discount_percentage (decimal: 5,2)
- start_date (date, nullable)
- end_date (date, nullable)
- timestamps (created_at, updated_at)
```

**Purpose**: Manages promotional campaigns with discount percentages and date ranges

#### 2.4 Foods Table

```
Table: foods
- id (Primary Key)
- name (string)
- description (text)
- price (decimal: 8,2)
- availability (string)
- image (string, nullable)
- category_id (Foreign Key → categories.id)
- promotion_id (Foreign Key → promotions.id, nullable)
- timestamps (created_at, updated_at)
```

**Purpose**: Stores food/menu items with pricing, availability, and relationships to categories and promotions

#### 2.5 Orders Table

```
Table: orders
- id (Primary Key)
- user_id (Foreign Key → users.id)
- food_id (Foreign Key → foods.id)
- quantity (integer)
- total_price (decimal: 8,2)
- status (string) - Default: 'pending'
- timestamps (created_at, updated_at)
```

**Purpose**: Main orders table storing order metadata

#### 2.6 Order Items Table

```
Table: order_items
- id (Primary Key)
- order_id (Foreign Key → orders.id)
- food_id (Foreign Key → foods.id)
- quantity (integer)
- price (decimal: 8,2) - Records price at purchase time
- timestamps (created_at, updated_at)
```

**Purpose**: Line items for orders (stores individual items in an order with historical pricing)

### 2.7 Constraints and Relationships

-   All foreign keys use `onDelete('cascade')` for data integrity
-   Email field in users table is unique
-   Promotion relationship on foods table allows null values

---

## 3. Models and Relationships

### Overview

All models implement Eloquent ORM with proper relationship definitions.

### 3.1 User Model

**File**: `app/Models/User.php`

```php
class User extends Authenticatable {
    // Relationships
    - hasMany(Order::class)
    - hasApiTokens (Sanctum)
}
```

**Key Methods**:

-   `getOrders()`: Returns all orders belonging to the user
-   Implements `MustVerifyEmail` interface (optional)

**Role-based Access**:

-   `role = 'user'`: Regular customer
-   `role = 'admin'`: Administrator with full system access

### 3.2 Food Model

**File**: `app/Models/Food.php`

```php
class Food extends Model {
    // Relationships
    - belongsTo(Category::class)
    - belongsTo(Promotion::class)
    - hasMany(OrderItem::class)
}
```

**Mass Assignable Fields**: name, description, price, availability, image, category_id, promotion_id

### 3.3 Category Model

**File**: `app/Models/Category.php`

```php
class Category extends Model {
    // Relationships
    - belongsToMany(Food::class) // via pivot table
}
```

### 3.4 Order Model

**File**: `app/Models/Order.php`

```php
class Order extends Model {
    // Relationships
    - belongsTo(User::class)
    - belongsTo(Food::class)
    - hasMany(OrderItem::class)
}
```

**Status Values**: 'pending', 'confirmed', 'delivered', 'cancelled'

### 3.5 OrderItem Model

**File**: `app/Models/OrderItem.php`

```php
class OrderItem extends Model {
    // Relationships
    - belongsTo(Order::class)
    - belongsTo(Food::class)
}
```

**Purpose**: Represents individual items within an order with historical pricing

### 3.6 Promotion Model

**File**: `app/Models/Promotion.php`

```php
class Promotion extends Model {
    // Relationships
    - hasMany(Food::class)
}
```

---

## 4. CRUD Operations

### 4.1 Food CRUD (Admin Only)

**Controller**: `app/Http/Controllers/Admin/FoodController.php`

#### Create Food

-   **Route**: `POST /admin/foods`
-   **Method**: `store(Request $request)`
-   **Features**:
    -   Validates input (name, price, category, image, etc.)
    -   Handles image upload to `public/images/`
    -   Authorization check: `$this->authorize('create', Food::class)`

#### Read Foods

-   **Route**: `GET /admin/foods` (List all)
-   **Method**: `index(Request $request)`
-   **Features**:
    -   Search by food name or category name
    -   Filter by category_id
    -   Eager load relationships with `with('category', 'promotion')`

#### Update Food

-   **Route**: `PUT/PATCH /admin/foods/{id}`
-   **Method**: `update(Request $request, $id)`
-   **Features**:
    -   Validate updated data
    -   Handle image replacement
    -   Authorization check before update

#### Delete Food

-   **Route**: `DELETE /admin/foods/{id}`
-   **Method**: `destroy($id)`
-   **Authorization**: Admin-only via policy

### 4.2 Order CRUD (User)

**Controller**: `app/Http/Controllers/OrderController.php`

#### Create Order

-   **Route**: `POST /orders` (via cart checkout)
-   **Method**: `store(Request $request)`
-   **Process**:
    1. Validate food_id and quantity
    2. Create Order record with user_id
    3. Create OrderItem with current food price

#### Read Orders

-   **Route**: `GET /orders` (User's orders)
-   **Method**: `index()`
-   **Features**:
    -   Shows only authenticated user's orders
    -   Eager loads order items with food and category details

#### Display Single Order

-   **Route**: `GET /orders/{id}`
-   **Method**: `show($id)`

### 4.3 Promotion CRUD

**Controller**: `app/Http/Controllers/Admin/PromotionController.php`

#### Create Promotion

-   **Validation Rules**:
    -   name: required|string|max:255
    -   description: nullable|string
    -   discount_percentage: required|numeric|min:0|max:100
    -   start_date: nullable|date
    -   end_date: nullable|date|after_or_equal:start_date
    -   image: nullable|image|mimes:jpeg,png,jpg,gif|max:2048

#### Update & Delete

-   Similar authorization checks with promotion policies
-   Image handling for promo images stored in `public/images/promo/`

---

## 5. Input Validation

### 5.1 Validation Rules by Resource

#### User Registration

**File**: `app/Http/Controllers/Auth/RegisterController.php`

```php
[
    'name' => ['required', 'string', 'max:255'],
    'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
    'password' => ['required', 'string', 'min:8', 'confirmed'],
]
```

#### Food Creation/Update

**File**: `app/Http/Controllers/Admin/FoodController.php`

```php
[
    'name' => 'required|string|max:255',
    'description' => 'nullable|string',
    'price' => 'required|numeric|min:0',
    'availability' => 'required|in:available,unavailable',
    'category_id' => 'required|exists:categories,id',
    'promotion_id' => 'nullable|exists:promotions,id',
    'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
]
```

#### Order Creation

**File**: `app/Http/Controllers/OrderController.php`

```php
[
    'food_id' => 'required|exists:foods,id',
    'quantity' => 'required|integer|min:1',
]
```

#### Promotion Validation

**File**: `app/Http/Controllers/Admin/PromotionController.php`

```php
[
    'name' => 'required|string|max:255',
    'description' => 'nullable|string',
    'discount_percentage' => 'required|numeric|min:0|max:100',
    'start_date' => 'nullable|date',
    'end_date' => 'nullable|date|after_or_equal:start_date',
    'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
]
```

### 5.2 Custom Validation Messages

-   Default Laravel validation messages are used
-   Server-side validation prevents invalid data entry
-   Client-side validation (optional) can be added in Blade views

---

## 6. Relational Queries and Search Filters

### 6.1 Eager Loading

**Example**: `Food::with('category', 'promotion')`

**Purpose**: Prevents N+1 query problems by loading relationships in single query

**Usage Locations**:

-   `FoodController@index()`: Lists all foods with categories
-   `OrderController@show()`: Shows order with items and food details

### 6.2 Search Implementation

**Controller**: `app/Http/Controllers/Admin/FoodController.php`

```php
$query = Food::with('category', 'promotion');

if ($request->filled('search')) {
    $term = $request->search;
    $query->where(function ($query) use ($term) {
        $query->where('name', 'like', "%{$term}%")
              ->orWhereHas('category', function ($query) use ($term) {
                  $query->where('name', 'like', "%{$term}%");
              });
    });
}

$foods = $query->get();
```

**Features**:

-   Search by food name
-   Search by category name using `whereHas()`
-   Case-insensitive LIKE search

### 6.3 Filter by Category

```php
if ($request->filled('category_id')) {
    $query->where('category_id', $request->category_id);
}
```

### 6.4 Date Range Filtering

**Controller**: `app/Http/Controllers/FoodController.php`

```php
$promotions = Promotion::where('discount_percentage', '>', 0)
    ->where('start_date', '<=', now())
    ->where('end_date', '>=', now())
    ->has('foods')  // Only promotions with foods
    ->with('foods')
    ->get();
```

**Purpose**: Shows only active promotions

### 6.5 Latest Records

```php
$foods = Food::latest()->take(5)->get();
```

**Purpose**: Gets 5 most recent foods for homepage

---

## 7. Authentication Logic

### 7.1 Login Process

**File**: `app/Http/Controllers/Auth/LoginController.php`

**Features**:

-   Uses `AuthenticatesUsers` trait from Laravel
-   Middleware `guest` prevents already-logged-in users from accessing login page
-   Custom `authenticated()` method redirects to `/homepage` after successful login
-   Implements "Remember Me" functionality via `rememberToken`

**Login Flow**:

1. User submits credentials via `/login` form
2. `AuthenticatesUsers` validates credentials
3. User session is created
4. Redirected to homepage

### 7.2 Registration Process

**File**: `app/Http/Controllers/Auth/RegisterController.php`

**Features**:

-   Uses `RegistersUsers` trait
-   Custom `validator()` method with rules
-   Password hashing via `Hash::make()`
-   Email uniqueness check
-   Password confirmation validation

**Registration Flow**:

1. User fills registration form
2. Validators check email uniqueness
3. Password is hashed before storage
4. User record created in database
5. Auto-login after registration

### 7.3 Logout Process

**File**: `app/Http/Controllers/Auth/LoginController.php` → `logout()` method

```php
public function logout(Request $request)
{
    $this->guard()->logout();
    $request->session()->invalidate();
    $request->session()->regenerateToken();
    return redirect()->route('login');
}
```

**Security Measures**:

-   Session invalidation
-   CSRF token regeneration
-   Redirect to login page

### 7.4 Password Reset

**Files**:

-   `app/Http/Controllers/Auth/ForgotPasswordController.php`
-   `app/Http/Controllers/Auth/ResetPasswordController.php`

**Features**:

-   Email-based password recovery
-   Secure token-based reset links
-   Password hashing on reset

### 7.5 Authentication Guard

**Config**: `config/auth.php`

-   Uses `web` guard for session-based authentication
-   Can extend to API authentication via Sanctum tokens

---

## 8. Authorization Logic

### 8.1 Role-Based Access Control (RBAC)

**User Roles**:

-   `user`: Regular customer (default)
-   `admin`: Full system access

**Storage**: `users.role` enum field

### 8.2 Authorization Gates

**File**: `app/Providers/AuthServiceProvider.php`

```php
Gate::define('admin-access', function ($user) {
    return $user->role === 'admin';
});
```

**Usage in Routes**: `middleware('admin')` → checks admin role

### 8.3 Authorization Policies

**Food Policy**: `app/Policies/FoodPolicy.php`

```php
public function viewAny(User $user) {
    return $user->role === 'admin';
}

public function create(User $user) {
    return $user->role === 'admin';
}

public function update(User $user, Food $food) {
    return $user->role === 'admin';
}

public function delete(User $user, Food $food) {
    return $user->role === 'admin';
}
```

**Usage in Controllers**:

```php
$this->authorize('create', Food::class);
$this->authorize('update', $food);
```

### 8.4 Similar Policies

-   `app/Policies/PromotionPolicy.php`: Admin-only promotion management
-   `app/Policies/OrderPolicy.php`: Admin can view all orders, users see only theirs

### 8.5 Middleware-Based Authorization

**File**: `app/Http/Middleware/CheckAdminRole.php`

```php
public function handle(Request $request, Closure $next)
{
    if (auth()->check() && auth()->user()->role === 'admin') {
        return $next($request);
    }
    abort(403, 'Unauthorized access');
}
```

**Applied to Routes**: All `/admin/*` routes

**Response**: Returns 403 Forbidden if user is not admin

### 8.6 Protected Routes

**File**: `routes/web.php`

```php
Route::middleware('admin')->prefix('admin')->name('admin.')->group(function () {
    // All admin routes protected
    Route::resource('users', AdminUserController::class);
    Route::resource('foods', AdminFoodController::class);
    Route::resource('promotions', AdminPromotionController::class);
    Route::resource('orders', AdminOrderController::class);
});
```

---

## 9. Cookies and Session Implementation

### 9.1 Session Configuration

**File**: `config/session.php`

```php
'driver' => env('SESSION_DRIVER', 'file'),  // Default: file
'lifetime' => env('SESSION_LIFETIME', 120), // 120 minutes
'expire_on_close' => false,
```

**Session Drivers Available**:

-   `file`: Store sessions in `storage/framework/sessions/`
-   `database`: Store in sessions table (requires migration)
-   `redis`: High-performance session storage
-   `memcached`: Distributed session cache

### 9.2 Cookie Encryption

**Middleware**: `app/Http/Middleware/EncryptCookies.php`

**Features**:

-   Automatically encrypts/decrypts cookies
-   HTTPS-only transmission (in production)
-   HttpOnly flag prevents JavaScript access

**Exception List** (unencrypted cookies):

-   Can be defined in middleware if needed

### 9.3 Remember Me Functionality

**Implementation**:

-   Browser stores `remember_token` in `users` table
-   Persists login across browser sessions (default: 525,600 minutes ≈ 1 year)
-   Automatically sent with request cookies

**Usage in Login Form**:

```html
<input type="checkbox" name="remember" id="remember" />
<label for="remember">Remember Me</label>
```

### 9.4 CSRF Token Protection

**Middleware**: `app/Http/Middleware/VerifyCsrfToken.php`

**Implementation**:

-   Each request includes unique CSRF token
-   Regenerated on login (`$request->session()->regenerateToken()`)
-   Verified on form submissions
-   Prevents Cross-Site Request Forgery attacks

**Usage in Forms**:

```blade
@csrf <!-- Adds hidden token field -->
```

### 9.5 Session Management

**Session Methods**:

-   `$request->session()->get('key')`: Retrieve value
-   `$request->session()->put('key', 'value')`: Store value
-   `$request->session()->forget('key')`: Delete value
-   `$request->session()->flush()`: Clear all data
-   `$request->session()->invalidate()`: Invalidate on logout

**Example - Logout**:

```php
$request->session()->invalidate();      // Clear session
$request->session()->regenerateToken(); // New CSRF token
```

### 9.6 Session Storage Locations

**File-Based** (Default):

-   Path: `storage/framework/sessions/`
-   Filename: Session ID
-   Readable by PHP process only

**Database** (Alternative):

-   Requires: `php artisan session:table` and migration
-   Allows: Multiple server deployments, persistent storage

### 9.7 Session Lifecycle

1. **Creation**: User logs in → session created → cookie sent
2. **Validation**: Each request validates session validity
3. **Refresh**: Activity resets expiration timer
4. **Expiration**: No activity for 120 minutes → session deleted
5. **Logout**: Session invalidated immediately

---

## Summary Table

| Component       | File Location                                                   | Description                                       |
| --------------- | --------------------------------------------------------------- | ------------------------------------------------- |
| Database Schema | `database/migrations/2026_04_07_114316_create_all_tables.php`   | 6 main tables with relationships                  |
| Models          | `app/Models/*.php`                                              | User, Food, Order, Category, Promotion, OrderItem |
| Food CRUD       | `app/Http/Controllers/Admin/FoodController.php`                 | Create, read, update, delete foods                |
| Orders          | `app/Http/Controllers/OrderController.php`                      | Customer order management                         |
| Authentication  | `app/Http/Controllers/Auth/*.php`                               | Login, register, password reset                   |
| Authorization   | `app/Policies/*.php` + `app/Http/Middleware/CheckAdminRole.php` | Role-based access control                         |
| Routes          | `routes/web.php`                                                | All application routes organized                  |
| Session Config  | `config/session.php`                                            | Session driver and lifetime settings              |
| CSRF Protection | `app/Http/Middleware/VerifyCsrfToken.php`                       | Cross-site request forgery prevention             |
