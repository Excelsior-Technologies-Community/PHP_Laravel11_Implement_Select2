 # PHP_Laravel11_Implement_Select2

This documentation explains how to build a complete product CRUD system in Laravel 11 where:

- Products support multiple tag selection.
- Select2 is used for a modern multi-select dropdown.
- Tags are stored in a JSON `tag_ids` column.
- Includes full CRUD for Products and Tags.
- Includes admin panel using Laravel Breeze.

This README is based entirely on the provided implementation.

---

# Step 1: Install Laravel 11

Create a new Laravel project:

```
composer create-project laravel/laravel example-app
```

---

# Step 2: Configure MySQL Database

Open `.env` and set:

```
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=blog
DB_USERNAME=root
DB_PASSWORD=root
```

---

# Step 3: Create Products Migration

Run:

```
php artisan make:migration create_products_table --create=products
```

Add columns:

```php
Schema::create('products', function (Blueprint $table) {
    $table->id();
    $table->string('name');
    $table->text('details');
    $table->decimal('price', 8, 2);
    $table->string('size');
    $table->string('color');
    $table->string('category');
    $table->string('image')->nullable();
    $table->timestamps();
});
```

Run migration:

```
php artisan migrate
```

---

# Step 4: Add Resource Route

In `routes/web.php`:

```php
use App\Http\Controllers\ProductController;

Route::resource('products', ProductController::class);
```

---

# Step 5: Create Product Model & Controller

```
php artisan make:controller ProductController --resource --model=Product
```

## Product Model

```php
class Product extends Model
{
    protected $fillable = [
        'name',
        'details',
        'image',
        'size',
        'color',
        'category',
        'price',
        'tag_ids',
    ];

    protected $casts = [
        'tag_ids' => 'array',
    ];
}
```

---

# Step 6: ProductController Logic (CRUD + Select2 Multi-Tag)

## Show products

```php
public function index()
{
    $products = Product::all();
    return view('products.index', compact('products'));
}
```

---

## Create form

```php
public function create()
{
    $tags = Tag::all();
    return view('products.create', compact('tags'));
}
```

---

## Store product

```php
public function store(Request $request)
{
    $request->validate([
        'name'      => 'required',
        'details'   => 'required',
        'size'      => 'required',
        'color'     => 'required',
        'category'  => 'required',
        'price'     => 'required|numeric',
        'image'     => 'nullable|image|max:2048',
        'tag_ids'   => 'nullable|array',
    ]);

    $imagePath = null;

    if ($request->hasFile('image')) {
        $imageName = time().'_'.uniqid().'.'.$request->image->getClientOriginalExtension();
        $request->image->move(public_path('images'), $imageName);
        $imagePath = 'images/'.$imageName;
    }

    Product::create([
        'name'      => $request->name,
        'details'   => $request->details,
        'image'     => $imagePath,
        'size'      => $request->size,
        'color'     => $request->color,
        'category'  => $request->category,
        'price'     => $request->price,
        'tag_ids'   => $request->tag_ids,
    ]);

    return redirect()->route('products.index')->with('success', 'Product created successfully.');
}
```

---

## Edit form

```php
public function edit(Product $product)
{
    $tags = Tag::all();
    return view('products.edit', compact('product','tags'));
}
```

---

## Update product

```php
public function update(Request $request, Product $product)
{
    $request->validate([
        'name'      => 'required',
        'details'   => 'required',
        'size'      => 'required',
        'color'     => 'required',
        'category'  => 'required',
        'price'     => 'required|numeric',
        'image'     => 'nullable|image|max:2048',
        'tag_ids'   => 'nullable|array',
    ]);

    $imagePath = $product->image;

    if ($request->hasFile('image')) {

        if ($product->image && file_exists(public_path($product->image))) {
            unlink(public_path($product->image));
        }

        $imageName = time().'_'.uniqid().'.'.$request->image->getClientOriginalExtension();
        $request->image->move(public_path('images'), $imageName);

        $imagePath = 'images/'.$imageName;
    }

    $product->update([
        'name'      => $request->name,
        'details'   => $request->details,
        'image'     => $imagePath,
        'size'      => $request->size,
        'color'     => $request->color,
        'category'  => $request->category,
        'price'     => $request->price,
        'tag_ids'   => $request->tag_ids,
    ]);

    return redirect()->route('products.index')->with('success', 'Product updated successfully.');
}
```

---

## Delete product

```php
public function destroy(Product $product)
{
    if ($product->image && file_exists(public_path($product->image))) {
        unlink(public_path($product->image));
    }

    $product->delete();

    return redirect()->route('products.index')->with('success', 'Product deleted successfully.');
}
```

---

# Step 7: Blade Files (index, create, edit)

### index.blade.php includes:

- Showing product image  
- Showing tags by reading JSON tag_ids  
- Edit/Delete actions  

Tag display example:

```php
$tagIds = is_array($product->tag_ids) ? $product->tag_ids : json_decode($product->tag_ids, true);
$tags = \App\Models\Tag::whereIn('id', $tagIds ?? [])->pluck('tag_name');
```

---

# Step 8: Select2 in Create Form

```blade
<select name="tag_ids[]" class="form-control select2-tags" multiple>
    @foreach($tags as $tag)
        <option value="{{ $tag->id }}">{{ $tag->tag_name }}</option>
    @endforeach
</select>
```

Add Select2 JS:

```javascript
$('.select2-tags').select2({
    placeholder: "Select product tags",
    allowClear: true,
    closeOnSelect: true,
    width: "100%"
});
```

---

# Step 9: Select2 in Edit Form (Pre-selected)

```php
$selectedTags = $product->tag_ids ?? [];
```

```blade
<select name="tag_ids[]" id="tagSelect" class="form-select" multiple>
    @foreach($tags as $tag)
        <option value="{{ $tag->id }}" 
            {{ in_array($tag->id, $selectedTags) ? 'selected' : '' }}>
            {{ $tag->tag_name }}
        </option>
    @endforeach
</select>
```

Select2 initialization:

```javascript
$('#tagSelect').select2({
    placeholder: "Select Tags",
    allowClear: true,
    closeOnSelect: true,
    width: "100%"
});
```

---

# Step 10: Create Tags Module

## Migration

```
php artisan make:migration create_tags_table --create=tags
```

```php
Schema::create('tags', function (Blueprint $table) {
    $table->id();
    $table->string('tag_name');
    $table->timestamps();
});
```

---

## Tag Model

```php
class Tag extends Model
{
    protected $fillable = ['tag_name'];
}
```

---

## TagController CRUD

Includes:

- index  
- create  
- edit  
- update  
- destroy  

Pagination example:

```php
$tags = Tag::latest()->paginate(10);
```

---

## Tag Blade Files

Includes:

- index.blade.php  
- create.blade.php  
- edit.blade.php  

All forms match product CRUD style.

---

# Step 11: Add Tag Routes

```
Route::resource('tags', TagController::class);
```

---

# Step 12: Add tag_ids to Products Table

```
php artisan make:migration add_tag_ids_to_products_table --table=products
```

```php
Schema::table('products', function (Blueprint $table) {
    $table->json('tag_ids')->nullable();
});
```

Run:

```
php artisan migrate
```

---

# Step 13: Admin Panel (Laravel Breeze)

Install authentication:

```
composer require laravel/breeze --dev
php artisan breeze:install blade
npm install
npm run dev
php artisan migrate
```

Protect product routes:

```php
Route::middleware(['auth'])->group(function () {
    Route::resource('products', ProductController::class);
});
```

Set login redirect:

```
public const HOME = '/products';
```

---

# Step 14: Run Project

```
php artisan serve
```

Open:

```
http://localhost:8000/products
```

<img width="676" height="191" alt="image" src="https://github.com/user-attachments/assets/cf9d9e08-d38a-4847-87b6-943d86b1fcf5" />
<img width="676" height="199" alt="image" src="https://github.com/user-attachments/assets/debd64e8-fd1b-41e1-bcc4-6bf0f281df3c" />

---
