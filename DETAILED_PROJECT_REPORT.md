# FOOD ORDERING MANAGEMENT SYSTEM - DETAILED PROJECT REPORT

---

## 1. PROJECT DESCRIPTION

### 1.1 Overview

For our assignment, we have created a website called **FoodHub**, which is a web-based food ordering management system built entirely on the Laravel PHP framework. The web application is built as an end-to-end solution platform for both customers and administrators of a food business. The web application is designed to enable online food ordering, purchase of food products and merchandise, customer management, and internal administration, which is all centrally accessible within the web application.

The project was developed based on the Model-View-Controller (MVC) architectural pattern, which is combined with Laravel's tools including Eloquent ORM, form request validation, routes, policies, and session-based authentication.

The web application that we have built will support the following functions:

| Feature                         | Explanation                                                                                                                                                                                                                                                                                                                                 |
| ------------------------------- | ------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- |
| **Food Menu Browsing**          | Customers can browse all available food items with detailed information including images, descriptions, pricing, and availability status. Food items are organized by categories for easy navigation. Users can search and filter foods by name or category. Latest food items are featured on the homepage for quick discovery.            |
| **Shopping Cart System**        | Customers can add food items to cart with quantity selection (1-99 items per food). Cart supports add, remove, and update quantity operations. Cart items are stored in session and persist within the user's session. Users can view complete cart details including item prices, quantities, and subtotals before proceeding to checkout. |
| **Order Placement & Tracking**  | Users can proceed from cart to checkout and place orders. Orders are recorded with timestamps, food items, quantities, pricing details, and status tracking. Customers can view complete order history and individual order details. Orders support status workflow: pending, confirmed, delivered, and cancelled states.                   |
| **Promotion & Discount System** | Administrators can create and manage promotional campaigns with custom discount percentages applied to specific food items. Promotions feature image uploads, discount rates, and time-based validity periods (start and end dates). System automatically filters and displays only active promotions to customers based on current date.   |
| **Admin Control Panel**         | Administrators have exclusive access to comprehensive management features including user account management, food and category CRUD operations, order management with status updates, promotion campaign management, and dashboard with system overview.                                                                                    |
| **Contact Form**                | Both authenticated users and guests can submit contact messages through a dedicated contact form. Messages include name, email, phone, and subject. Provides a direct communication channel between customers and the FoodHub business for inquiries and feedback.                                                                          |

### 1.4 Technology Stack

| Component                    | Technology                          |
| ---------------------------- | ----------------------------------- |
| **Backend Framework**        | Laravel 8.x                         |
| **Database**                 | MySQL/MariaDB                       |
| **Frontend Template Engine** | Blade Templates                     |
| **CSS Framework**            | Bootstrap 5                         |
| **Authentication**           | Laravel Built-in Auth + Sanctum     |
| **Session Driver**           | File-based (Database/Redis options) |
| **Web Server**               | Apache/Nginx                        |
| **PHP Version**              | 7.4 or higher                       |
| **Version Control**          | Git                                 |
| **API Documentation**        | RESTful conventions                 |

### 1.5 System Architecture

```
┌─────────────────────────────────────────────┐
│         CUSTOMER INTERFACE                  │
│  (Browse Foods, Cart, Orders, Checkout)    │
└────────────────┬────────────────────────────┘
                 │
┌────────────────┴────────────────────────────┐
│         WEB ROUTES (routes/web.php)         │
│  (Public, Auth, Admin routes organized)    │
└────────────────┬────────────────────────────┘
                 │
┌────────────────┴────────────────────────────┐
│    CONTROLLERS (app/Http/Controllers)       │
│  (FoodController, OrderController, etc)    │
└────────────────┬────────────────────────────┘
                 │
┌────────────────┴────────────────────────────┐
│      MODELS (app/Models)                    │
│  (User, Food, Order, Category, etc)        │
└────────────────┬────────────────────────────┘
                 │
┌────────────────┴────────────────────────────┐
│         MYSQL DATABASE                      │
│  (6 tables with relationships)              │
└─────────────────────────────────────────────┘
```

---

## 2. DATABASE DESIGN AND MIGRATIONS

### 2.1 Overview

The database architecture consists of 6 interconnected tables designed following relational database principles with proper normalization, foreign key constraints, and data integrity measures. The migration system in Laravel ensures version control of database schema changes.

### 2.2 Migration File Details

**File Location**: `database/migrations/2026_04_07_114316_create_all_tables.php`

**Creation Date**: April 7, 2026

**Executed Method**: `php artisan migrate`

**Rollback Method**: `php artisan migrate:rollback`

**Key Characteristics**:

-   Single migration file containing all table definitions
-   Proper down() method for reversing changes
-   Uses Laravel's Schema facade for database-agnostic syntax
-   Includes foreign key constraints with cascade actions

---

## 2.3 Detailed Table Specifications

### 2.3.1 Users Table

**Table Name**: `users`

**Purpose**: Stores all user account information including customer and administrator accounts

**Structure**:

```
Column Name          | Data Type              | Constraints          | Description
─────────────────────┼────────────────────────┼──────────────────────┼──────────────────────
id                   | BIGINT(20)             | PRIMARY KEY, AUTO    | User unique identifier
name                 | VARCHAR(255)           | NOT NULL             | User full name
email                | VARCHAR(255)           | NOT NULL, UNIQUE     | User email address
email_verified_at    | TIMESTAMP              | NULLABLE             | Email verification time
password             | VARCHAR(255)           | NOT NULL             | Hashed password (bcrypt)
remember_token       | VARCHAR(100)           | NULLABLE             | Remember me token
role                 | ENUM('user','admin')   | DEFAULT 'user'       | User role assignment
created_at           | TIMESTAMP              | DEFAULT CURRENT      | Record creation time
updated_at           | TIMESTAMP              | DEFAULT CURRENT      | Last update time
```

**Relationships**:

-   One-to-Many with Orders table (user_id)
-   No direct foreign keys stored (other tables reference this)

**Indexes**:

-   Primary Index: id
-   Unique Index: email
-   Remember Token Index for authentication

**Sample Data Purpose**:

-   Tracks customer and administrator accounts
-   Stores hashed passwords for security
-   Maintains role information for access control

---

### 2.3.2 Categories Table

**Table Name**: `categories`

**Purpose**: Stores food classification/categorization system for organized menu management

**Structure**:

```
Column Name   | Data Type      | Constraints          | Description
──────────────┼────────────────┼──────────────────────┼──────────────────────
id            | BIGINT(20)     | PRIMARY KEY, AUTO    | Category unique identifier
name          | VARCHAR(255)   | NOT NULL             | Category name (Appetizers, etc)
created_at    | TIMESTAMP      | DEFAULT CURRENT      | Record creation time
updated_at    | TIMESTAMP      | DEFAULT CURRENT      | Last update time
```

**Relationships**:

-   One-to-Many with Foods table (category_id)

**Example Categories**:

-   Appetizers
-   Main Courses
-   Desserts
-   Beverages
-   Side Dishes

**Database Constraints**:

-   No deletion constraints (foods will be orphaned if category deleted)
-   Recommendation: Set onDelete('set null') for referential integrity

---

### 2.3.3 Promotions Table

**Table Name**: `promotions`

**Purpose**: Manages promotional campaigns with discount information and validity periods

**Structure**:

```
Column Name           | Data Type          | Constraints          | Description
──────────────────────┼────────────────────┼──────────────────────┼──────────────────────
id                    | BIGINT(20)         | PRIMARY KEY, AUTO    | Promotion unique ID
name                  | VARCHAR(255)       | NOT NULL             | Promotion campaign name
description           | TEXT               | NULLABLE             | Detailed description
image                 | VARCHAR(255)       | NULLABLE             | Promotional image path
discount_percentage   | DECIMAL(5,2)       | NOT NULL             | Discount (0-100%)
start_date            | DATE               | NULLABLE             | Promotion start date
end_date              | DATE               | NULLABLE             | Promotion end date
created_at            | TIMESTAMP          | DEFAULT CURRENT      | Record creation time
updated_at            | TIMESTAMP          | DEFAULT CURRENT      | Last update time
```

**Relationships**:

-   One-to-Many with Foods table (promotion_id)

**Validation Rules**:

-   discount_percentage: 0 to 100
-   end_date must be >= start_date
-   Both dates optional for ongoing promotions

**Sample Promotions**:

-   "Summer Sale 2026" - 20% discount
-   "Grand Opening" - 30% discount
-   "Weekend Special" - 15% discount

---

### 2.3.4 Foods Table

**Table Name**: `foods`

**Purpose**: Central menu table storing all food items with pricing, availability, and relationships

**Structure**:

```
Column Name       | Data Type          | Constraints                  | Description
──────────────────┼────────────────────┼──────────────────────────────┼──────────────────────
id                | BIGINT(20)         | PRIMARY KEY, AUTO            | Food unique identifier
name              | VARCHAR(255)       | NOT NULL                     | Food item name
description       | TEXT               | NOT NULL                     | Detailed description
price             | DECIMAL(8,2)       | NOT NULL                     | Current price
availability      | VARCHAR(255)       | NOT NULL (available/unavail) | Availability status
image             | VARCHAR(255)       | NULLABLE                     | Food image path
category_id       | BIGINT(20)         | FK→categories.id, CASCADE    | Category reference
promotion_id      | BIGINT(20)         | FK→promotions.id, SET NULL   | Promotion reference
created_at        | TIMESTAMP          | DEFAULT CURRENT              | Record creation time
updated_at        | TIMESTAMP          | DEFAULT CURRENT              | Last update time
```

**Relationships**:

-   Many-to-One with Categories table
-   Many-to-One with Promotions table (optional)
-   One-to-Many with OrderItems table

**Key Constraints**:

-   category_id: onDelete('cascade') - deleting category removes foods
-   promotion_id: onDelete('set null') - promotion removal disassociates foods
-   Price must be >= 0

**Status Values**:

-   `available`: Food can be ordered
-   `unavailable`: Food is currently out of stock

---

### 2.3.5 Orders Table

**Table Name**: `orders`

**Purpose**: Main order transaction table storing order metadata and status

**Structure**:

```
Column Name    | Data Type          | Constraints                  | Description
───────────────┼────────────────────┼──────────────────────────────┼──────────────────────
id             | BIGINT(20)         | PRIMARY KEY, AUTO            | Order unique identifier
user_id        | BIGINT(20)         | FK→users.id, CASCADE         | Customer reference
food_id        | BIGINT(20)         | FK→foods.id, CASCADE         | Food reference
quantity       | INT                | NOT NULL                     | Order quantity
total_price    | DECIMAL(8,2)       | NOT NULL                     | Total order amount
status         | VARCHAR(255)       | DEFAULT 'pending'            | Order status
created_at     | TIMESTAMP          | DEFAULT CURRENT              | Order creation time
updated_at     | TIMESTAMP          | DEFAULT CURRENT              | Last update time
```

**Relationships**:

-   Many-to-One with Users table
-   Many-to-One with Foods table
-   One-to-Many with OrderItems table

**Status Workflow**:

-   `pending`: Order placed, awaiting confirmation
-   `confirmed`: Order confirmed by admin
-   `delivered`: Order completed and delivered
-   `cancelled`: Order cancelled by user/admin

**Important Notes**:

-   Current implementation seems redundant with OrderItems table
-   Consider consolidating or clarifying usage

---

### 2.3.6 OrderItems Table

**Table Name**: `order_items`

**Purpose**: Line items table for individual items within orders, storing historical pricing

**Structure**:

```
Column Name   | Data Type          | Constraints                  | Description
──────────────┼────────────────────┼──────────────────────────────┼──────────────────────
id            | BIGINT(20)         | PRIMARY KEY, AUTO            | Order item unique ID
order_id      | BIGINT(20)         | FK→orders.id, CASCADE        | Order reference
food_id       | BIGINT(20)         | FK→foods.id, CASCADE         | Food reference
quantity      | INT                | NOT NULL                     | Item quantity
price         | DECIMAL(8,2)       | NOT NULL                     | Price at purchase time
created_at    | TIMESTAMP          | DEFAULT CURRENT              | Record creation time
updated_at    | TIMESTAMP          | DEFAULT CURRENT              | Last update time
```

**Relationships**:

-   Many-to-One with Orders table
-   Many-to-One with Foods table

**Key Feature - Historical Pricing**:

-   Stores price at time of purchase
-   Allows accurate order history even if food price changes
-   Prevents retrospective price changes affecting past orders

**Example Flow**:

1. Food price is $10.00
2. Order placed, price recorded as $10.00 in OrderItems
3. Food price updated to $12.00
4. Historical order still shows $10.00 (original price)

---

### 2.4 Database Relationships Diagram

```
users (1) ─────────────── (N) orders
  │
  └──> role (enum: user, admin)

categories (1) ─────────────── (N) foods

promotions (1) ─────────────── (N) foods
                                 │
                                 └──> (N) order_items

orders (1) ─────────────── (N) order_items
              │
              └──> user_id

order_items (N) ──> foods
```

---

### 2.5 Migration Constraints and Integrity

**Cascade Delete Relationships**:

-   If user deleted → all orders and order_items deleted
-   If food deleted → all order_items referencing that food deleted
-   If order deleted → all associated order_items deleted

**Set Null Relationships**:

-   If promotion deleted → foods' promotion_id becomes NULL

**Data Integrity Measures**:

-   Foreign key constraints enforced at database level
-   Email uniqueness prevents duplicate accounts
-   Enum validation for role and availability fields
-   Decimal precision for accurate financial calculations (8,2)

---

## 3. MODELS AND RELATIONSHIPS

### 3.1 Overview

FoodHub utilizes Laravel's Eloquent ORM to create a comprehensive model layer that represents each entity in the system. The six core models—User, Category, Food, Order, OrderItem, and Promotion—work together to manage all business logic through well-defined relationships and methods. Each model is designed following MVC principles and leverages Eloquent's powerful features for efficient database interactions.

### 3.2 User Model

The User model, located at [app/Models/User.php](app/Models/User.php), extends Laravel's `Authenticatable` class to handle user authentication and authorization. It maintains user account information including name, email, password, and role. The model uses several traits including `HasApiTokens` for API token support, `HasFactory` for testing, and `Notifiable` for sending notifications.

```php
class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = ['name', 'email', 'password', 'role'];
    protected $hidden = ['password', 'remember_token'];
    protected $casts = ['email_verified_at' => 'datetime'];

    public function orders() {
        return $this->hasMany(Order::class);
    }
}
```

The User model defines one primary relationship: **User → Orders (One-to-Many)**. Through the `orders()` method, a single user can have multiple orders associated with their account. This relationship is essential for tracking customer order history and enables functionality like displaying all orders for a specific user. The relationship uses the user's ID as the foreign key in the orders table, allowing queries such as retrieving all orders for an authenticated user via `auth()->user()->orders()`.

Users are differentiated by the `role` attribute which can be either 'user' (regular customer) or 'admin' (administrator with full system access). The model implements Laravel's authentication contract, allowing the application to use `auth()->user()` throughout the codebase to access the currently authenticated user. Passwords are automatically hashed using bcrypt, ensuring they are never stored in plain text.

---

### 3.3 Food Model

The Food model, found at [app/Models/Food.php](app/Models/Food.php), represents individual menu items in the FoodHub system. Each food item contains comprehensive information including name, description, price, availability status, and an associated image. The model is central to the food ordering system, managing the relationship between categories, promotions, and orders.

```php
class Food extends Model
{
    protected $table = 'foods';
    protected $fillable = ['name', 'description', 'price', 'availability', 'image', 'category_id', 'promotion_id'];
    use HasFactory;

    public function category() {
        return $this->belongsTo(Category::class);
    }

    public function promotion() {
        return $this->belongsTo(Promotion::class);
    }

    public function orderItems() {
        return $this->hasMany(OrderItem::class);
    }
}
```

The Food model maintains three key relationships. First, **Food → Category (Many-to-One)** establishes that each food item belongs to exactly one category. This relationship enables efficient organization and filtering of menu items. When retrieving foods with their categories, developers use eager loading via `Food::with('category')` to prevent N+1 query problems, accessing category information through `$food->category->name`.

Second, **Food → Promotion (Many-to-One, Optional)** allows foods to be associated with promotional campaigns. This relationship is nullable, meaning not all foods must have a promotion. When a promotion is active, the discount percentage can be accessed via `$food->promotion->discount_percentage`, enabling the system to display discounted prices to customers automatically.

Third, **Food → OrderItems (One-to-Many)** indicates that a single food item can appear in multiple orders through separate order items. This relationship provides purchase history for each food and is used for generating analytics about which items are most popular. The relationship is maintained through the food_id foreign key in the order_items table.

The availability field stores either 'available' or 'unavailable', controlling whether customers can order a particular item. The system validates this status before adding items to the cart, preventing orders for out-of-stock foods.

---

### 3.4 Category Model

The Category model at [app/Models/Category.php](app/Models/Category.php) represents food classifications within the ordering system. Categories provide a logical organization structure for the menu, allowing customers to browse foods by type such as Appetizers, Main Courses, Desserts, Beverages, and Side Dishes.

```php
class Category extends Model
{
    use HasFactory;
    protected $table = 'categories';
    protected $fillable = ['name'];

    public function foods() {
        return $this->hasMany(Food::class);
    }
}
```

The Category model defines a single relationship: **Category → Foods (One-to-Many)**. Through the `foods()` method, each category can contain multiple food items. This relationship enables features like category-based filtering in the admin panel and menu navigation on the customer interface. Developers can access all foods in a category using `$category->foods` or count them with `$category->foods()->count()`. The relationship relies on the category_id foreign key stored in the foods table, linking each food to its parent category.

---

### 3.5 Order Model

The Order model, located at [app/Models/Order.php](app/Models/Order.php), represents a customer order transaction. It stores high-level order information including the customer, associated food item, quantity, total price, and order status. Each order serves as a container for one or more items that a customer has purchased.

```php
class Order extends Model
{
    use HasFactory;
    protected $table = 'orders';
    protected $fillable = ['user_id', 'food_id', 'quantity', 'total_price', 'status'];

    public function user() {
        return $this->belongsTo(User::class);
    }

    public function orderItems() {
        return $this->hasMany(OrderItem::class);
    }

    public function food() {
        return $this->belongsTo(Food::class);
    }
}
```

The Order model establishes three relationships. First, **Order → User (Many-to-One)** connects each order to the customer who placed it. This relationship enables queries like retrieving all orders for a specific user via `Order::where('user_id', $userId)->get()` and accessing user details from an order using `$order->user->name`. The user_id foreign key ensures that every order is associated with exactly one user.

Second, **Order → OrderItems (One-to-Many)** allows an order to contain multiple line items. Each order item represents a specific food with its quantity and the price paid at time of purchase. This relationship is accessed through `$order->orderItems` and enables detailed order display showing all foods within an order along with their historical pricing.

Third, **Order → Food (Many-to-One)** provides a direct reference to the primary food item in the order. While this relationship may seem redundant when using order items, it offers convenience for simple order representations.

Orders progress through a lifecycle defined by the status field. An order begins in the 'pending' state immediately after creation. Administrators can then confirm it ('confirmed'), after which it moves to 'delivered' when fulfilled. Alternatively, orders can be 'cancelled' at any point from pending or confirmed states, allowing customers or administrators to abort transactions as needed.

---

### 3.6 OrderItem Model

The OrderItem model at [app/Models/OrderItem.php](app/Models/OrderItem.php) represents individual line items within an order. It serves as a junction model between orders and foods, with a critical feature: storing historical pricing information. This ensures that order records remain accurate and auditable even if food prices change after the order is placed.

```php
class OrderItem extends Model
{
    use HasFactory;
    protected $table = 'order_items';
    protected $fillable = ['order_id', 'food_id', 'quantity', 'price'];

    public function order() {
        return $this->belongsTo(Order::class);
    }

    public function food() {
        return $this->belongsTo(Food::class);
    }
}
```

The OrderItem model defines two relationships. First, **OrderItem → Order (Many-to-One)** establishes that each line item belongs to exactly one order. Through `$orderItem->order->status`, developers can access the status of the parent order. This relationship uses the order_id foreign key to maintain the association.

Second, **OrderItem → Food (Many-to-One)** links each order item to the food product that was ordered. Developers access food details via `$orderItem->food->name` or `$orderItem->food->description`. Importantly, while the current food price is stored in the foods table and changes over time, the OrderItem maintains its own price field representing the exact price paid when the order was placed. This historical pricing feature is crucial for accuracy in financial records and customer communication.

For example, if a food item was $10.00 when ordered but the price is later updated to $12.00, the order item still displays $10.00, preserving the original transaction value. This prevents retrospective price changes from affecting historical order records and enables accurate analytics and accounting.

---

### 3.7 Promotion Model

The Promotion model at [app/Models/Promotion.php](app/Models/Promotion.php) manages promotional campaigns that offer discounts on specific food items. Promotions are time-bound, featuring start and end dates that determine when discounts are active.

```php
class Promotion extends Model
{
    protected $table = 'promotions';
    protected $fillable = ['name', 'description', 'image', 'discount_percentage', 'start_date', 'end_date'];
    use HasFactory;

    public function foods() {
        return $this->hasMany(Food::class);
    }
}
```

The Promotion model defines a single relationship: **Promotion → Foods (One-to-Many)**. Through this relationship, a single promotion campaign can be applied to multiple food items. Developers access the foods included in a promotion via `$promotion->foods`, enabling features like displaying all discounted items when a promotion is active.

The promotion_id field in the foods table establishes this association. Promotions store a discount_percentage field that specifies the discount amount as a decimal (0-100), allowing flexible discount configurations. The start_date and end_date fields, both optional, define the validity period for the promotion. The system automatically filters active promotions by comparing these dates against the current date, displaying only relevant discounts to customers based on the current time. The promotional image field enables visual branding of campaigns throughout the application interface.

---

## 4. CRUD OPERATIONS

### 4.1 Food CRUD Operations

**Controller**: `app/Http/Controllers/Admin/FoodController.php`

**Purpose**: Complete management of food menu items with full CRUD functionality and authorization

#### 4.1.1 Create Food (POST /admin/foods)

**Route Definition**:

```php
Route::resource('foods', AdminFoodController::class);
// Generates: POST /admin/foods → store()
```

**Method Implementation**:

```php
public function create() {
    $this->authorize('create', Food::class);
    $categories = Category::all();
    $promotions = Promotion::all();
    return view('admin.foods.create', compact('categories', 'promotions'));
}

public function store(Request $request) {
    $this->authorize('create', Food::class);

    $data = $request->validate([
        'name' => 'required|string|max:255',
        'description' => 'nullable|string',
        'price' => 'required|numeric|min:0',
        'availability' => 'required|in:available,unavailable',
        'category_id' => 'required|exists:categories,id',
        'promotion_id' => 'nullable|exists:promotions,id',
        'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
    ]);

    $food = new Food($data);
    if ($request->hasFile('image')) {
        $file = $request->file('image');
        $filename = time() . '_' . $file->getClientOriginalName();
        $file->move(public_path('images'), $filename);
        $food->image = $filename;
    }
    $food->save();

    return redirect()->route('admin.foods.index')
        ->with('success', 'Food created successfully.');
}
```

**Features**:

-   Authorization check via `FoodPolicy::create()`
-   Image upload to `public/images/` directory
-   Validation of all input fields
-   Mass assignment protection
-   Success message flashing

**Input Validation**:

-   name: Required, max 255 characters
-   price: Required, numeric, non-negative
-   category_id: Must exist in categories table
-   image: Optional, max 2MB, specific formats only

**Authorization**:

-   Only admin users can create foods
-   Checked via: `$this->authorize('create', Food::class)`

---

#### 4.1.2 Read Food List (GET /admin/foods)

**Method Implementation**:

```php
public function index(Request $request) {
    $this->authorize('viewAny', Food::class);

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

    if ($request->filled('category_id')) {
        $query->where('category_id', $request->category_id);
    }

    $foods = $query->get();
    $categories = Category::all();

    return view('admin.foods.index', compact('foods', 'categories'));
}
```

**Features**:

-   Eager loading relationships (`with('category', 'promotion')`)
-   Search by food name or category
-   Filter by category_id
-   Authorization check
-   Pagination ready

**Query Optimization**:

-   Uses `with()` to prevent N+1 queries
-   Search uses `whereHas()` for related searching
-   Returns only requested data

---

#### 4.1.3 Read Single Food (GET /admin/foods/{id})

**Method Implementation**:

```php
public function show($id) {
    $food = Food::findOrFail($id);
    $this->authorize('view', $food);
    return view('admin.foods.show', compact('food'));
}
```

**Features**:

-   404 if food not found
-   Authorization check
-   Detailed food information display

---

#### 4.1.4 Update Food (PUT/PATCH /admin/foods/{id})

**Method Implementation**:

```php
public function edit($id) {
    $food = Food::findOrFail($id);
    $this->authorize('update', $food);

    $categories = Category::all();
    $promotions = Promotion::all();
    return view('admin.foods.edit', compact('food', 'categories', 'promotions'));
}

public function update(Request $request, $id) {
    $food = Food::findOrFail($id);
    $this->authorize('update', $food);

    $data = $request->validate([
        'name' => 'required|string|max:255',
        'description' => 'nullable|string',
        'price' => 'required|numeric|min:0',
        'availability' => 'required|in:available,unavailable',
        'category_id' => 'required|exists:categories,id',
        'promotion_id' => 'nullable|exists:promotions,id',
        'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
    ]);

    $food->fill($data);
    if ($request->hasFile('image')) {
        $file = $request->file('image');
        $filename = time() . '_' . $file->getClientOriginalName();
        $file->move(public_path('images'), $filename);
        $food->image = $filename;
    }
    $food->save();

    return redirect()->route('admin.foods.index')
        ->with('success', 'Food updated successfully.');
}
```

**Features**:

-   Same validation as create
-   Handles image replacement
-   Preserves existing image if new not provided
-   Authorization check

---

#### 4.1.5 Delete Food (DELETE /admin/foods/{id})

**Method Implementation**:

```php
public function destroy($id) {
    $food = Food::findOrFail($id);
    $this->authorize('delete', $food);

    // Delete image file if exists
    if ($food->image && file_exists(public_path('images/' . $food->image))) {
        unlink(public_path('images/' . $food->image));
    }

    $food->delete();

    return redirect()->route('admin.foods.index')
        ->with('success', 'Food deleted successfully.');
}
```

**Features**:

-   Image file cleanup
-   Cascade deletion of order items (via DB constraint)
-   Authorization check
-   Soft delete optional (not currently implemented)

---

### 4.2 Order CRUD Operations

**Controller**: `app/Http/Controllers/OrderController.php`

**Purpose**: Customer order management and order history

#### 4.2.1 Create Order (POST /orders - via cart)

**Method Implementation**:

```php
public function store(Request $request) {
    $request->validate([
        'food_id' => 'required|exists:foods,id',
        'quantity' => 'required|integer|min:1',
    ]);

    // Create order
    $order = new Order();
    $order->user_id = auth()->id();
    $order->save();

    // Create order item
    $orderItem = new OrderItem();
    $orderItem->order_id = $order->id;
    $orderItem->food_id = $request->food_id;
    $orderItem->quantity = $request->quantity;
    $orderItem->price = Food::find($request->food_id)->price;
    $orderItem->save();

    return redirect()->route('orders.show', $order->id)
        ->with('success', 'Order placed successfully!');
}
```

**Features**:

-   Uses authenticated user ID
-   Captures price at time of order
-   Creates separate order and order items
-   Input validation for food existence

---

#### 4.2.2 Read Order List (GET /orders)

**Method Implementation**:

```php
public function index() {
    $orders = Order::with('orderItems.food.category')
        ->where('user_id', auth()->id())
        ->get();
    return view('orders.index', compact('orders'));
}
```

**Features**:

-   Shows only user's own orders
-   Eager loads related data
-   Prevents viewing other users' orders
-   Includes food and category information

---

#### 4.2.3 Read Single Order (GET /orders/{id})

**Method Implementation**:

```php
public function show($id) {
    $order = Order::with('orderItems.food.category')
        ->findOrFail($id);
    return view('orders.show', compact('order'));
}
```

**Features**:

-   Shows complete order with items
-   Displays historical pricing
-   Includes all order metadata

---

### 4.3 Promotion CRUD Operations

**Controller**: `app/Http/Controllers/Admin/PromotionController.php`

**Purpose**: Management of promotional campaigns

#### 4.3.1 Create Promotion

**Validation Rules**:

```php
$data = $request->validate([
    'name' => 'required|string|max:255',
    'description' => 'nullable|string',
    'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
    'discount_percentage' => 'required|numeric|min:0|max:100',
    'start_date' => 'nullable|date',
    'end_date' => 'nullable|date|after_or_equal:start_date',
]);
```

**Features**:

-   Discount percentage validation (0-100)
-   Date range validation
-   Optional start/end dates
-   Image upload to `public/images/promo/`

#### 4.3.2 Read Promotions

**Features**:

-   List all promotions with pagination
-   Authorization checks
-   Display active promotions on homepage

#### 4.3.3 Update Promotion

**Features**:

-   Same validation as create
-   Update discount and dates
-   Replace or keep existing image

#### 4.3.4 Delete Promotion

**Features**:

-   Remove promotion campaign
-   Disassociate from foods (SET NULL)
-   Clean up image files

---

### 4.4 Category CRUD (Implicit)

**Typically managed via seeder or admin commands**:

```php
// Database/Seeders/CategorySeeder.php
Category::create(['name' => 'Appetizers']);
Category::create(['name' => 'Main Courses']);
Category::create(['name' => 'Desserts']);
```

---

## 5. INPUT VALIDATION

### 5.1 Validation Architecture

Laravel's validation system provides both client-side guidance and server-side enforcement. Validation rules are defined in controllers using the `validate()` method or `Validator` facade.

### 5.2 User Registration Validation

**File**: `app/Http/Controllers/Auth/RegisterController.php`

**Validation Rules**:

```php
protected function validator(array $data) {
    return Validator::make($data, [
        'name' => ['required', 'string', 'max:255'],
        'email' => [
            'required',
            'string',
            'email',
            'max:255',
            'unique:users'
        ],
        'password' => [
            'required',
            'string',
            'min:8',
            'confirmed'
        ],
    ]);
}
```

**Validation Details**:

| Field    | Rules                     | Purpose                                  |
| -------- | ------------------------- | ---------------------------------------- |
| name     | required, string, max:255 | User display name validation             |
| email    | required, email, unique   | Prevent duplicate accounts, valid format |
| password | min:8, confirmed          | Password strength, confirm match         |

**Password Confirmation**:

-   Form field: `password`
-   Confirmation field: `password_confirmation`
-   Must match exactly

**Error Handling**:

-   Validation errors redirected back to form
-   Old input repopulated in form fields
-   Error messages displayed to user

---

### 5.3 Food CRUD Validation

**File**: `app/Http/Controllers/Admin/FoodController.php`

**Create/Update Validation**:

```php
$data = $request->validate([
    'name' => 'required|string|max:255',
    'description' => 'nullable|string',
    'price' => 'required|numeric|min:0',
    'availability' => 'required|in:available,unavailable',
    'category_id' => 'required|exists:categories,id',
    'promotion_id' => 'nullable|exists:promotions,id',
    'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
]);
```

**Validation Details**:

| Field        | Rules                    | Purpose              | Details                           |
| ------------ | ------------------------ | -------------------- | --------------------------------- |
| name         | required, max:255        | Menu item name       | Must be provided                  |
| description  | nullable, string         | Item description     | Optional lengthy text             |
| price        | required, numeric, min:0 | Item price           | Must be valid number >= 0         |
| availability | required, in             | Item status          | Only 'available' or 'unavailable' |
| category_id  | required, exists         | Food category        | Must reference existing category  |
| promotion_id | nullable, exists         | Associated promotion | Optional, must exist if provided  |
| image        | nullable, image          | Food image           | Max 2MB, specific formats         |

**Image Validation Details**:

-   `image`: Must be valid image file
-   `mimes:jpeg,png,jpg,gif`: Allowed formats
-   `max:2048`: Maximum 2MB file size

**File Upload Handling**:

```php
if ($request->hasFile('image')) {
    $file = $request->file('image');
    $filename = time() . '_' . $file->getClientOriginalName();
    $file->move(public_path('images'), $filename);
    $food->image = $filename;
}
```

---

### 5.4 Order Creation Validation

**File**: `app/Http/Controllers/OrderController.php`

**Validation Rules**:

```php
$request->validate([
    'food_id' => 'required|exists:foods,id',
    'quantity' => 'required|integer|min:1',
]);
```

**Validation Details**:

| Field    | Rules                    | Purpose                      |
| -------- | ------------------------ | ---------------------------- |
| food_id  | required, exists         | Must reference existing food |
| quantity | required, integer, min:1 | Must be positive integer     |

**Error Handling**:

-   Validates food exists in database
-   Prevents ordering non-existent items
-   Ensures positive quantities

---

### 5.5 Promotion Validation

**File**: `app/Http/Controllers/Admin/PromotionController.php`

**Validation Rules**:

```php
$data = $request->validate([
    'name' => 'required|string|max:255',
    'description' => 'nullable|string',
    'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
    'discount_percentage' => 'required|numeric|min:0|max:100',
    'start_date' => 'nullable|date',
    'end_date' => 'nullable|date|after_or_equal:start_date',
]);
```

**Validation Details**:

| Field               | Rules                     | Purpose                   |
| ------------------- | ------------------------- | ------------------------- |
| name                | required, max:255         | Campaign name             |
| discount_percentage | numeric, 0-100            | Valid discount range      |
| start_date          | nullable, date            | Campaign start (optional) |
| end_date            | after_or_equal:start_date | End >= start validation   |
| image               | image, max:2048           | Promotional image upload  |

**Date Range Validation**:

-   Both dates optional for ongoing promotions
-   If both provided: `end_date >= start_date`
-   Prevents illogical date ranges

---

### 5.6 Login Validation

**Built-in via `AuthenticatesUsers` trait**:

```php
// Validates
'email' => 'required|email',
'password' => 'required',
```

**Features**:

-   Throttle after failed attempts (5 attempts in 60 minutes)
-   Case-insensitive email matching
-   Secure password comparison (hash_verify)

---

### 5.7 Custom Validation Messages

**Default Laravel Messages Used**:

```
'required' => 'The {attribute} field is required.'
'email' => 'The {attribute} must be a valid email address.'
'unique' => 'The {attribute} has already been taken.'
'numeric' => 'The {attribute} must be a number.'
'min' => 'The {attribute} must be at least {min}.'
```

**Customization Example** (if needed):

```php
$messages = [
    'price.required' => 'You must set a price for this food.',
    'price.numeric' => 'The price must be a valid number.',
];

$request->validate($rules, $messages);
```

---

### 5.8 Validation Summary Table

| Entity            | Controller                | Validation Method        | Key Rules                         |
| ----------------- | ------------------------- | ------------------------ | --------------------------------- |
| User Registration | Auth/RegisterController   | validator()              | email unique, password min:8      |
| Food              | Admin/FoodController      | store/update             | category_id exists, image max:2MB |
| Order             | OrderController           | store                    | food_id exists                    |
| Promotion         | Admin/PromotionController | store/update             | discount 0-100, date range        |
| Login             | Auth/LoginController      | AuthenticatesUsers trait | email format, throttling          |

---

## 6. RELATIONAL QUERIES AND SEARCH FILTERS

### 6.1 Query Optimization - Eager Loading

**Problem**: N+1 Query Problem

-   Fetching 100 foods would cause 101 queries (1 foods + 100 category queries)

**Solution**: Eager Loading with `with()`

```php
// Bad: 101 queries
$foods = Food::all();
foreach($foods as $food) {
    echo $food->category->name; // Triggers query
}

// Good: 2 queries
$foods = Food::with('category', 'promotion')->get();
foreach($foods as $food) {
    echo $food->category->name; // No query
}
```

**File**: `app/Http/Controllers/Admin/FoodController.php`

```php
public function index(Request $request) {
    $query = Food::with('category', 'promotion');
    // ... search/filter logic
    $foods = $query->get();
}
```

**Usage Locations**:

-   Food listing with categories
-   Order display with items and foods
-   Promotion display with associated foods

---

### 6.2 Search Implementation

**File**: `app/Http/Controllers/Admin/FoodController.php`

**Complete Search Implementation**:

```php
public function index(Request $request) {
    $this->authorize('viewAny', Food::class);

    $query = Food::with('category', 'promotion');

    // Search by food name OR category name
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
    $categories = Category::all();

    return view('admin.foods.index', compact('foods', 'categories'));
}
```

**Search Features**:

#### 6.2.1 Text Search

```php
->where('name', 'like', "%{$term}%")
```

-   Case-insensitive LIKE search
-   Searches food name field
-   Example: searching "chicken" finds "Grilled Chicken", "Fried Chicken"

#### 6.2.2 Relationship Search (whereHas)

```php
->orWhereHas('category', function ($query) use ($term) {
    $query->where('name', 'like', "%{$term}%");
})
```

-   Searches in related category table
-   Example: searching "Appetizer" finds all foods in that category
-   Uses `whereHas()` for EXISTS query

**Query Generated**:

```sql
SELECT * FROM foods
WHERE name LIKE '%term%'
OR id IN (
    SELECT food_id FROM categories
    WHERE name LIKE '%term%'
)
WITH category, promotion
```

---

### 6.3 Category Filter

**Implementation**:

```php
if ($request->filled('category_id')) {
    $query->where('category_id', $request->category_id);
}
```

**Features**:

-   Filters foods by category_id
-   Works with or without search term
-   Dropdown/select UI implementation

**Example**:

```php
// Filter to Appetizers (category_id = 1)
GET /admin/foods?category_id=1
```

---

### 6.4 Date Range Filtering - Active Promotions

**File**: `app/Http/Controllers/FoodController.php`

**Implementation**:

```php
$promotions = Promotion::where('discount_percentage', '>', 0)
    ->where('start_date', '<=', now())
    ->where('end_date', '>=', now())
    ->has('foods')
    ->with('foods')
    ->get();
```

**Query Breakdown**:

| Condition                 | Purpose                           |
| ------------------------- | --------------------------------- |
| `discount_percentage > 0` | Exclude 0% promos                 |
| `start_date <= now()`     | Promotion has started             |
| `end_date >= now()`       | Promotion hasn't ended            |
| `has('foods')`            | Only promos with associated foods |
| `with('foods')`           | Eager load foods                  |

**Date Comparison**:

-   Uses `now()` Laravel helper
-   Compares DATE fields (ignores time)
-   Supports inclusive boundaries (<=, >=)

**Example Flow**:

-   Promo "Summer Sale": start=2026-04-01, end=2026-05-31
-   Today: 2026-04-25
-   Current date falls within range → returned

---

### 6.5 Latest Records Query

**File**: `app/Http/Controllers/FoodController.php`

**Implementation**:

```php
$foods = Food::latest()->take(5)->get();
```

**Features**:

-   `latest()`: Sorts by created_at DESC
-   `take(5)`: Limits to 5 records
-   Used for homepage "New Items" section

**Equivalent Query**:

```sql
SELECT * FROM foods
ORDER BY created_at DESC
LIMIT 5
```

---

### 6.6 Order Filtering by User

**File**: `app/Http/Controllers/OrderController.php`

**Implementation**:

```php
$orders = Order::with('orderItems.food.category')
    ->where('user_id', auth()->id())
    ->get();
```

**Features**:

-   Shows only authenticated user's orders
-   Prevents viewing other users' orders
-   Eager loads all related data

**Security**:

-   Uses `auth()->id()` for current user
-   Prevents authorization bypass
-   Database-level filtering

---

### 6.7 Complex Query Example - Orders with Food Details

**Implementation**:

```php
$order = Order::with([
    'user',
    'orderItems' => function ($query) {
        $query->with('food', 'food.category');
    }
])->findOrFail($id);
```

**Relationships Loaded**:

1. Order → User
2. Order → OrderItems
3. OrderItems → Food
4. Food → Category

**Single Query** (with all data):

```sql
SELECT * FROM orders WHERE id = ?;
SELECT * FROM users WHERE id = ?;
SELECT * FROM order_items WHERE order_id = ?;
SELECT * FROM foods WHERE id IN (...);
SELECT * FROM categories WHERE id IN (...);
```

---

### 6.8 Query Scopes (Best Practice Implementation)

**Add to Food Model**:

```php
class Food extends Model {
    public function scopeAvailable($query) {
        return $query->where('availability', 'available');
    }

    public function scopeWithPromotion($query) {
        return $query->whereNotNull('promotion_id');
    }

    public function scopeInCategory($query, $categoryId) {
        return $query->where('category_id', $categoryId);
    }
}

// Usage:
Food::available()->withPromotion()->get();
Food::inCategory(1)->available()->get();
```

---

### 6.9 Search and Filter Summary

| Query Type      | Method                           | Usage               |
| --------------- | -------------------------------- | ------------------- |
| Eager Loading   | `with('category')`               | Prevent N+1 queries |
| Text Search     | `where('name', 'like', ...)`     | Food name search    |
| Relation Search | `whereHas('category')`           | Category search     |
| Category Filter | `where('category_id', ...)`      | Filter by category  |
| Date Range      | `where('start_date', ...)`       | Active promotions   |
| Latest          | `latest()->take(5)`              | Homepage items      |
| User Filter     | `where('user_id', auth()->id())` | User's orders       |

---

## 7. AUTHENTICATION LOGIC

### 7.1 Authentication Overview

Authentication is the process of verifying user identity through credentials (email/password). Laravel provides built-in authentication system with customizable guards and providers.

### 7.2 Login Process

**File**: `app/Http/Controllers/Auth/LoginController.php`

**Implementation**:

```php
class LoginController extends Controller
{
    use AuthenticatesUsers;

    protected $redirectTo = RouteServiceProvider::HOME;

    public function __construct() {
        $this->middleware('guest')->except('logout');
    }

    public function logout(Request $request) {
        $this->guard()->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('login');
    }

    protected function authenticated(Request $request, $user) {
        return redirect('/homepage');
    }
}
```

**Login Flow**:

1. **User Access Login Form**

    - URL: `/login`
    - Middleware: `guest` (redirects if already logged in)
    - Displays login form with email and password fields

2. **Form Submission**

    - POST to `/login`
    - Laravel's AuthenticatesUsers trait handles validation
    - Validates: email exists, password correct

3. **Credential Validation**

    ```php
    // Built-in validation
    'email' => 'required|email',
    'password' => 'required',
    ```

4. **Throttle Protection**

    - Default: 5 failed attempts per 60 minutes
    - After threshold: locked out temporarily
    - Prevents brute-force attacks

5. **Password Verification**

    ```php
    // Uses bcrypt hash verification
    Hash::check($inputPassword, $user->password)
    ```

6. **Session Creation**

    - User authenticated
    - Session created with user ID
    - Cookie sent to browser with session ID

7. **Redirect**
    - Custom `authenticated()` method
    - Redirects to `/homepage` instead of default `/home`

**Login Form Fields**:

```html
<!-- Email input -->
<input type="email" name="email" required />

<!-- Password input -->
<input type="password" name="password" required />

<!-- Remember Me checkbox -->
<input type="checkbox" name="remember" />

<!-- CSRF token (auto-injected) -->
@csrf
```

**Security Measures**:

-   Password stored as bcrypt hash (one-way encryption)
-   CSRF token verification
-   Session validation on each request
-   Throttle limiting

---

### 7.3 Registration Process

**File**: `app/Http/Controllers/Auth/RegisterController.php`

**Implementation**:

```php
class RegisterController extends Controller
{
    use RegistersUsers;

    protected $redirectTo = RouteServiceProvider::HOME;

    public function __construct() {
        $this->middleware('guest');
    }

    protected function validator(array $data) {
        return Validator::make($data, [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);
    }

    protected function create(array $data) {
        return User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'role' => 'user',  // Default role
        ]);
    }
}
```

**Registration Flow**:

1. **User Access Registration Form**

    - URL: `/register`
    - Middleware: `guest`
    - Form displays: name, email, password, password_confirmation

2. **Form Validation**

    - name: Required, max 255 characters
    - email: Required, valid format, unique in database
    - password: Required, min 8 characters, must match confirmation

3. **Unique Email Check**

    ```sql
    SELECT * FROM users WHERE email = ?
    ```

    - Prevents duplicate accounts
    - Case-insensitive comparison

4. **Password Confirmation**

    - Form field: `password`
    - Confirm field: `password_confirmation`
    - Must match exactly

5. **User Creation**

    ```php
    User::create([
        'name' => $data['name'],
        'email' => $data['email'],
        'password' => Hash::make($data['password']),
        'role' => 'user',
    ]);
    ```

    - Password hashed using bcrypt
    - Default role: 'user'
    - Timestamps auto-set

6. **Auto-Login**
    - User automatically logged in after creation
    - Session created
    - Redirected to `/homepage`

**Password Hashing**:

```php
Hash::make($password)
// Output: $2y$10$N9qo8uLOickgx2ZMRZoMyeIjZAgcg7b3XeKeUxWdeS86E36CHhzPm
// One-way hash: cannot be reversed
```

**Email Verification** (Optional):

```php
// Can implement email verification by:
// 1. Add email_verified_at column check
// 2. Send verification email
// 3. Verify via email link
```

---

### 7.4 Logout Process

**Implementation**:

```php
public function logout(Request $request) {
    // 1. Remove authentication from guard
    $this->guard()->logout();

    // 2. Invalidate entire session
    $request->session()->invalidate();

    // 3. Generate new CSRF token
    $request->session()->regenerateToken();

    // 4. Redirect to login
    return redirect()->route('login');
}
```

**Logout Steps**:

1. **Guard Logout**

    - Removes user from authentication
    - Clears `auth()->user()` reference

2. **Session Invalidation**

    - Deletes session file from `storage/framework/sessions/`
    - Clears all session data
    - Invalidates session cookie

3. **CSRF Token Regeneration**

    - Old token destroyed
    - New token generated
    - Prevents CSRF attacks

4. **Redirect**
    - User redirected to login page
    - Cannot access protected routes

**Security Measures**:

-   Complete session cleanup
-   No residual authentication
-   New session for next login

---

### 7.5 Password Reset Process

**Files**:

-   `app/Http/Controllers/Auth/ForgotPasswordController.php`
-   `app/Http/Controllers/Auth/ResetPasswordController.php`
-   `app/Notifications/ResetPasswordNotification.php`

**Flow**:

1. **Forgot Password Request**

    - User enters email
    - System checks if email exists
    - Generates secure reset token
    - Sends email with reset link

2. **Email Link**

    ```
    https://app.com/password/reset/{TOKEN}?email=user@example.com
    ```

    - Token valid for typically 60 minutes
    - Email can only be accessed by registered user

3. **Reset Form**

    - User clicks link
    - Form displays: password and password_confirmation
    - Form includes hidden token

4. **Password Update**
    ```php
    $user->password = Hash::make($newPassword);
    $user->save();
    ```
    - New password hashed
    - Old password invalidated
    - User can login with new password

**Security**:

-   Token-based (not email-only)
-   Time-limited (60 minutes)
-   One-time use
-   Sent via encrypted email

---

### 7.6 Authentication Configuration

**File**: `config/auth.php`

**Key Settings**:

```php
return [
    'defaults' => [
        'guard' => 'web',
        'passwords' => 'users',
    ],

    'guards' => [
        'web' => [
            'driver' => 'session',
            'provider' => 'users',
        ],
        // API guard could be added here
    ],

    'providers' => [
        'users' => [
            'driver' => 'eloquent',
            'model' => App\Models\User::class,
        ],
    ],

    'passwords' => [
        'users' => [
            'provider' => 'users',
            'table' => 'password_resets',
            'expire' => 60,  // Token expiration (minutes)
        ],
    ],
];
```

**Guards**:

-   `web`: Session-based authentication (users)
-   Can add `api`: Token-based authentication (via Sanctum)

**Providers**:

-   Database driver: Query builder based
-   Eloquent driver: Model-based (currently used)

---

### 7.7 Authentication Middleware

**Built-in Middleware**:

#### 7.7.1 `auth` Middleware

```php
// Requires authentication
Route::get('/dashboard', [Controller::class, 'index'])->middleware('auth');
```

-   Redirects to login if not authenticated
-   Sets `auth()->user()` to current user

#### 7.7.2 `guest` Middleware

```php
// Only for unauthenticated users
Route::get('/login', [LoginController::class, 'show'])->middleware('guest');
```

-   Redirects to home if already authenticated
-   Prevents already-logged-in users from accessing login

#### 7.7.3 `auth.basic` Middleware

-   HTTP Basic Authentication
-   Not used in this project

---

### 7.8 Authentication Checks in Code

**Checking if User Authenticated**:

```php
if (auth()->check()) {
    echo "User is logged in";
}

if (auth()->guest()) {
    echo "User is not logged in";
}

if (auth()->user()) {
    echo "User: " . auth()->user()->name;
}
```

**Protecting Routes**:

```php
// Require authentication
Route::get('/homepage', ...)->middleware('auth');

// Multiple guards
Route::get('/api', ...)->middleware('auth:api');
```

---

### 7.9 Remember Me Implementation

**Storage**:

-   `remember_token` stored in users table
-   Token sent in browser cookie

**Default Duration**:

-   525,600 minutes (1 year)
-   Persistent across browser sessions

**Usage**:

```html
<input type="checkbox" name="remember" id="remember" />
<label for="remember">Remember Me</label>
```

**How It Works**:

1. User checks "Remember Me"
2. Long-lived cookie created with token
3. Token stored in user record
4. On next visit: cookie validated
5. User auto-authenticated

---

## 8. AUTHORIZATION LOGIC

### 8.1 Authorization Overview

Authorization determines what authenticated users can do. Different from authentication (who they are), authorization controls their permissions.

### 8.2 Role-Based Access Control (RBAC)

**User Roles**:

#### 8.2.1 User Role (Default)

-   Can browse food menu
-   Can add items to cart
-   Can place orders
-   Can view own orders
-   Cannot access admin panel
-   Cannot manage foods/promotions

#### 8.2.2 Admin Role

-   Full system access
-   Can manage foods and categories
-   Can manage promotions
-   Can manage users
-   Can view all orders
-   Can manage order status

**Role Storage**:

```sql
ALTER TABLE users ADD COLUMN role ENUM('user', 'admin') DEFAULT 'user';
```

**Role Assignment**:

```php
// Assign role on registration
User::create([
    'role' => 'user',  // Default
]);

// Change role
$user->update(['role' => 'admin']);
```

---

### 8.3 Authorization Gates

**File**: `app/Providers/AuthServiceProvider.php`

**Implementation**:

```php
public function boot() {
    $this->registerPolicies();

    Gate::define('admin-access', function ($user) {
        return $user->role === 'admin';
    });
}
```

**Usage in Routes**:

```php
Route::middleware('admin')->group(function () {
    // All routes protected
    Route::resource('foods', AdminFoodController::class);
});
```

**Gate Authorization Check**:

```php
// In controller
if (!Gate::allows('admin-access')) {
    abort(403, 'Unauthorized');
}

// In blade template
@can('admin-access')
    <!-- Show admin link -->
@endcan
```

---

### 8.4 Authorization Policies

**Purpose**: Object-based authorization (permissions on specific resources)

**Food Policy File**: `app/Policies/FoodPolicy.php`

**Implementation**:

```php
class FoodPolicy {
    use HandlesAuthorization;

    public function viewAny(User $user) {
        return $user->role === 'admin';
    }

    public function view(User $user, Food $food) {
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
}
```

**Policy Methods**:

| Method    | Purpose          | Usage     |
| --------- | ---------------- | --------- |
| viewAny() | List all foods   | index()   |
| view()    | View single food | show()    |
| create()  | Create new food  | store()   |
| update()  | Update food      | update()  |
| delete()  | Delete food      | destroy() |

**Policy Registration**:

```php
// AuthServiceProvider.php
protected $policies = [
    'App\Models\Food' => 'App\Policies\FoodPolicy',
    'App\Models\Promotion' => 'App\Policies\PromotionPolicy',
    'App\Models\Order' => 'App\Policies\OrderPolicy',
];
```

**Policy Usage in Controllers**:

```php
public function store(Request $request) {
    // Check if user can create foods
    $this->authorize('create', Food::class);
    // ... create food
}

public function update(Request $request, $id) {
    $food = Food::find($id);
    // Check if user can update this specific food
    $this->authorize('update', $food);
    // ... update food
}
```

**Error Response**:

-   Throws `AuthorizationException`
-   Returns 403 Forbidden HTTP response
-   Shows "This action is unauthorized" message

---

### 8.5 Promotion Policy

**File**: `app/Policies/PromotionPolicy.php`

**Similar to FoodPolicy**:

-   `viewAny()`: Admin only
-   `create()`: Admin only
-   `update()`: Admin only
-   `delete()`: Admin only

**Usage**:

```php
$this->authorize('viewAny', Promotion::class);
$this->authorize('create', Promotion::class);
```

---

### 8.6 Order Policy

**File**: `app/Policies/OrderPolicy.php`

**Access Rules**:

```php
public function viewAny(User $user) {
    // Admins see all orders, users see only their own
    // Implemented via model query filtering instead of policy
}

public function view(User $user, Order $order) {
    // User can view own order, admin views any
    return $user->id === $order->user_id || $user->role === 'admin';
}
```

**Implementation Note**:

-   Current implementation filters by user_id in query
-   Alternative: Use policy for object-level checks

---

### 8.7 Middleware-Based Authorization

**Admin Middleware File**: `app/Http/Middleware/CheckAdminRole.php`

**Implementation**:

```php
class CheckAdminRole {
    public function handle(Request $request, Closure $next) {
        if (auth()->check() && auth()->user()->role === 'admin') {
            return $next($request);
        }

        abort(403, 'Unauthorized access');
    }
}
```

**Registration**:

```php
// app/Http/Kernel.php
protected $routeMiddleware = [
    // ...
    'admin' => \App\Http\Middleware\CheckAdminRole::class,
];
```

**Usage in Routes**:

```php
Route::middleware('admin')->group(function () {
    // All routes in this group require admin role
    Route::get('/admin/dashboard', ...);
    Route::resource('foods', AdminFoodController::class);
    Route::resource('promotions', AdminPromotionController::class);
});
```

**Authorization Flow**:

1. Request comes in
2. `auth()` middleware ensures user logged in
3. `admin` middleware checks role === 'admin'
4. If fails: 403 Forbidden error
5. If passes: proceed to controller

---

### 8.8 Protected Routes

**File**: `routes/web.php`

**Route Protection**:

```php
// Requires authentication
Route::middleware('auth')->group(function () {
    // These routes need logged-in user

    // Requires authentication AND admin role
    Route::middleware('admin')->prefix('admin')->group(function () {
        Route::get('/dashboard', [AdminDashboardController::class, 'index']);
        Route::resource('users', AdminUserController::class);
        Route::resource('foods', AdminFoodController::class);
        Route::resource('promotions', AdminPromotionController::class);
        Route::resource('orders', AdminOrderController::class);
    });
});
```

**Route Security**:

-   Unauthenticated users → redirected to `/login`
-   Authenticated non-admin users → 403 Forbidden
-   Admin users → full access

---

### 8.9 Template-Level Authorization

**Blade Directives**:

```blade
<!-- Show only to authenticated users -->
@auth
    <p>Welcome, {{ auth()->user()->name }}</p>
@endauth

<!-- Show only to unauthenticated users -->
@guest
    <a href="/login">Login</a>
@endguest

<!-- Show only to users with permission -->
@can('admin-access')
    <a href="/admin/dashboard">Admin</a>
@endcan

<!-- Show only if user can perform action -->
@can('create', App\Models\Food::class)
    <button>Add Food</button>
@endcan
```

---

### 8.10 Authorization Summary

| Component  | Level          | Method                                    |
| ---------- | -------------- | ----------------------------------------- |
| Routes     | Route Group    | `middleware('auth', 'admin')`             |
| Policies   | Resource-Level | `$this->authorize('create', Food::class)` |
| Gates      | Global         | `Gate::allows('admin-access')`            |
| Middleware | Route-Level    | Custom CheckAdminRole                     |
| Templates  | View-Level     | `@can`, `@auth` directives                |

---

## 9. COOKIES AND SESSION IMPLEMENTATION

### 9.1 Session System Overview

Sessions maintain user state across HTTP requests. A session typically contains:

-   User ID
-   CSRF token
-   Flash messages
-   User preferences
-   Cart data

### 9.2 Session Configuration

**File**: `config/session.php`

**Key Settings**:

```php
return [
    'driver' => env('SESSION_DRIVER', 'file'),
    'lifetime' => env('SESSION_LIFETIME', 120),
    'expire_on_close' => false,
    'encrypt' => false,
    'path' => '/',
    'domain' => env('SESSION_DOMAIN', null),
    'secure' => env('SESSION_SECURE_COOKIES', false),
    'http_only' => true,
    'same_site' => 'lax',
];
```

**Configuration Details**:

| Setting   | Value      | Purpose                         |
| --------- | ---------- | ------------------------------- |
| driver    | file       | Session storage type            |
| lifetime  | 120        | Session timeout (minutes)       |
| path      | /          | Session cookie path             |
| http_only | true       | JavaScript cannot access cookie |
| same_site | lax        | CSRF protection level           |
| secure    | false/true | HTTPS-only in production        |

---

### 9.3 Session Drivers

**File-Based (Default)**:

```php
'driver' => 'file'
```

-   Sessions stored in `storage/framework/sessions/`
-   Filenames: Random session IDs
-   Suitable for single-server deployments
-   Automatic cleanup (garbage collection)

**Database**:

```php
'driver' => 'database'
```

-   Requires: `php artisan session:table` migration
-   Sessions stored in `sessions` table
-   Shared across multiple servers
-   More secure (not filesystem exposed)

**Redis**:

```php
'driver' => 'redis'
```

-   High-performance caching
-   Distributed across servers
-   Automatic cleanup (TTL)
-   Requires Redis installation

**Array** (Testing):

```php
'driver' => 'array'
```

-   Sessions in memory only
-   No persistence
-   Testing/debugging only

---

### 9.4 Session Lifetime and Expiration

**Default Lifetime**: 120 minutes

**Configuration**:

```php
// .env
SESSION_LIFETIME=120  // 2 hours
```

**Expiration Scenarios**:

1. **Idle Timeout**

    - No activity for 120 minutes
    - Session automatically deleted
    - User must log in again

2. **Browser Close** (Optional)

    ```php
    'expire_on_close' => false,  // Session persists
    'expire_on_close' => true,   // Session ends with browser
    ```

3. **Manual Logout**

    ```php
    auth()->logout();
    session()->invalidate();
    ```

4. **Manual Expiration**
    ```php
    session()->forget('key');
    session()->flush();
    ```

---

### 9.5 Cookie Encryption

**Middleware**: `app/Http/Middleware/EncryptCookies.php`

**Automatic Encryption**:

```php
class EncryptCookies extends Middleware {
    protected $except = [
        // Cookies NOT to encrypt (optional)
    ];
}
```

**How It Works**:

1. **Outgoing Cookies**

    ```php
    Cookie::make('session_id', 'abc123...', 120)
    // Encrypted before sending to browser
    ```

2. **Incoming Cookies**

    ```php
    // Browser sends encrypted cookie
    // Laravel decrypts automatically
    // $request->cookie('session_id') returns plain value
    ```

3. **Encryption Algorithm**
    - AES-256-CBC
    - Key from `APP_KEY` (.env)
    - Unique IV for each encryption

**Security Features**:

-   Tamper-proof (hash verification)
-   Unique IV (Initialization Vector)
-   Strong encryption (AES-256)

**Exception Example**:

```php
protected $except = [
    'remember_me',  // Don't encrypt this cookie
];
```

---

### 9.6 CSRF Protection

**Middleware**: `app/Http/Middleware/VerifyCsrfToken.php`

**Protection Mechanism**:

1. **Token Generation**

    - Unique per session
    - Regenerated on login
    - 40-character random string

2. **Token Transmission**

    - In forms: `@csrf` directive injects hidden input
    - In headers: `X-CSRF-TOKEN` header
    - In cookies: `XSRF-TOKEN` cookie

3. **Token Verification**
    - On form submission (POST, PUT, DELETE, PATCH)
    - Compares request token with session token
    - Fails if tokens don't match

**Implementation**:

```html
<!-- In Blade form -->
<form method="POST" action="/orders">
    @csrf
    <!-- Adds hidden input with token -->
    <input type="text" name="field" />
    <button>Submit</button>
</form>
```

**JavaScript Usage**:

```javascript
// Get CSRF token from meta tag
let token = document
    .querySelector('meta[name="csrf-token"]')
    .getAttribute("content");

// Use in fetch request
fetch("/orders", {
    method: "POST",
    headers: {
        "X-CSRF-TOKEN": token,
        "Content-Type": "application/json",
    },
    body: JSON.stringify(data),
});
```

**Token Regeneration**:

```php
// On login
$request->session()->regenerateToken();

// On logout
$request->session()->regenerateToken();
```

**Exceptions** (if needed):

```php
protected $except = [
    'api/webhook',  // Don't verify for this route
];
```

---

### 9.7 Remember Me Functionality

**Storage**:

```sql
ALTER TABLE users ADD COLUMN remember_token VARCHAR(100);
```

**Token Generation**:

```php
// On successful login, if "remember me" checked
Auth::login($user, $remember = true);
// Generates and stores random token
```

**Cookie Details**:

-   Name: `remember_web_59ba36addc2b2f9401580f014c7f58ea4e30989d`
-   Value: Token from users.remember_token
-   Expiration: 525,600 minutes (1 year)
-   HttpOnly: True (JavaScript cannot access)
-   Secure: True in production (HTTPS only)

**Flow**:

1. User checks "Remember Me"
2. Login successful
3. Token generated and stored in users table
4. Long-lived cookie sent to browser
5. On next visit, cookie validated
6. If valid, user auto-authenticated
7. Session created

**Form Implementation**:

```html
<input type="checkbox" name="remember" id="remember" />
<label for="remember">Remember Me</label>
```

**Security Considerations**:

-   Token is random, not password
-   Token stored in database (secure)
-   Cookie HttpOnly (safe from XSS)
-   Should regenerate token periodically

---

### 9.8 Session Management Methods

**Storing Data**:

```php
// Single value
$request->session()->put('key', 'value');
session()->put('cart_items', $items);
Session::put('message', 'Hello');

// Multiple values
$request->session()->put([
    'name' => 'John',
    'email' => 'john@example.com'
]);

// Push to array
session()->push('cart', $item);

// Flash data (available only next request)
session()->flash('success', 'Order placed!');
$request->session()->flash('message', 'Data saved');
```

**Retrieving Data**:

```php
// Get value
$value = session()->get('key');
session('key');  // Shorthand
$request->session()->get('key');

// Get with default
session()->get('key', 'default');

// Check existence
session()->has('key');
session()->exists('key');

// All data
session()->all();
```

**Deleting Data**:

```php
// Remove single key
session()->forget('key');
$request->session()->forget(['key1', 'key2']);

// Clear all
session()->flush();
$request->session()->invalidate();
```

**Increment/Decrement**:

```php
session()->increment('views');
session()->decrement('points', 10);
```

---

### 9.9 Flash Messages

**Flash Data** - automatically deleted after next request

**Implementation**:

```php
// In controller
return redirect()->back()->with('success', 'Order placed!');

// Alternative
session()->flash('success', 'Item added');
redirect('/cart');

// Multiple messages
return redirect()->back()->with([
    'success' => 'Updated!',
    'warning' => 'Check data'
]);
```

**In Blade Template**:

```blade
@if (session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
@endif

@if (session('error'))
    <div class="alert alert-danger">
        {{ session('error') }}
    </div>
@endif
```

**Old Input Preservation**:

```php
// Re-populate form after validation error
return redirect()->back()
    ->withInput()  // Remember old input
    ->withErrors($validator);
```

```blade
<!-- In form -->
<input type="text" name="name" value="{{ old('name') }}">
```

---

### 9.10 Session Security

**Best Practices**:

1. **Regenerate Token on Login**

    ```php
    session()->regenerateToken();
    ```

2. **Invalidate on Logout**

    ```php
    session()->invalidate();
    session()->regenerateToken();
    ```

3. **Use HTTPS in Production**

    ```php
    'secure' => env('SESSION_SECURE_COOKIES', true),
    ```

4. **Set HttpOnly Flag**

    ```php
    'http_only' => true,  // Prevent JavaScript access
    ```

5. **Same-Site Cookie**

    ```php
    'same_site' => 'strict',  // Prevent CSRF
    ```

6. **Reasonable Lifetime**

    ```php
    'lifetime' => 120,  // 2 hours, not too long
    ```

7. **Database Sessions** (for shared hosting)
    ```php
    'driver' => 'database',  // Better than file
    ```

---

### 9.11 Session Storage Details

**File-Based Location**:

```
storage/framework/sessions/
├── abc123def456...
├── ghi789jkl012...
└── ...
```

**File Content** (Serialized PHP):

```
serialized session data with user_id, csrf_token, cart data, etc.
```

**Database Storage**:

```
CREATE TABLE sessions (
    id VARCHAR(255) PRIMARY KEY,
    user_id BIGINT NULLABLE,
    ip_address VARCHAR(45),
    user_agent TEXT,
    payload LONGTEXT,
    last_activity INT
);
```

---

### 9.12 Cookie Security Headers

**Set-Cookie Header Example**:

```
Set-Cookie: XSRF-TOKEN=abc123...;
    Path=/;
    Domain=.example.com;
    Expires=Thu, 21-Dec-2026 16:00:00 GMT;
    Secure;
    HttpOnly;
    SameSite=Lax
```

**Attributes**:

| Attribute | Purpose                           |
| --------- | --------------------------------- |
| Path      | Cookie sent for all /paths        |
| Domain    | Cookie sent to all subdomains     |
| Expires   | Cookie expiration date/time       |
| Secure    | HTTPS-only transmission           |
| HttpOnly  | JavaScript cannot access          |
| SameSite  | CSRF protection (Strict/Lax/None) |

---

### 9.13 Session and Cookie Summary

| Component        | Location                   | Purpose                 |
| ---------------- | -------------------------- | ----------------------- |
| Session Driver   | config/session.php         | Storage mechanism       |
| Session Lifetime | .env SESSION_LIFETIME      | Auto-expire time        |
| Encryption       | EncryptCookies middleware  | Secure data             |
| CSRF Token       | VerifyCsrfToken middleware | Form protection         |
| Remember Token   | users table                | Persistent login        |
| Flash Messages   | Session storage            | Temporary notifications |
| All Methods      | $request->session()        | Session operations      |

---

## SUMMARY

This comprehensive Food Ordering Management System implements all major Laravel components:

-   **Database**: 6-table normalized schema with relationships
-   **Models**: Full Eloquent implementation with proper relationships
-   **CRUD**: Complete operations on foods, orders, and promotions
-   **Validation**: Server-side input validation on all forms
-   **Queries**: Optimized with eager loading and search capabilities
-   **Authentication**: Secure login/register/logout/password reset
-   **Authorization**: Role-based access control with policies and gates
-   **Sessions**: Secure session management with CSRF protection
-   **Cookies**: Encrypted cookies with remember me functionality

All code follows Laravel best practices and security standards.
