 Laravel 11 – Select2 Multi‑Select Tags with Product CRUD 
 
![Laravel](https://img.shields.io/badge/Laravel-11-orange)
![PHP](https://img.shields.io/badge/PHP-8.2-blue)
![Bootstrap](https://img.shields.io/badge/Bootstrap-5-purple)
![MySQL](https://img.shields.io/badge/Database-MySQL-yellow)

This documentation explains how to create a **Tags Module** and integrate it with **Product CRUD** using **Select2 Multiple Selection** in Laravel 11.

---

 Overview
This project includes:

- Full **Tags CRUD Module**
- Product CRUD with:
  - Single Image Upload  
  - Select2 Multi‑Tag Selection  
  - Display selected tags in product list  
- Admin Panel layout  
- Laravel Breeze authentication

---

 Folder Structure
```
project/
│── app/
│   ├── Models/Product.php
│   ├── Models/Tag.php
│   └── Http/Controllers/
│       ├── ProductController.php
│       └── TagController.php
│
│── resources/views/
│       ├── tags/
│       │    ├── index.blade.php
│       │    ├── create.blade.php
│       │    └── edit.blade.php
│       ├── products/
│       │    ├── index.blade.php
│       │    ├── create.blade.php
│       │    └── edit.blade.php
│       └── layouts/
│            ├── admin.blade.php
│            └── app.blade.php
│
│── database/migrations/
│── public/images/
│── routes/web.php
│── README.md
```

---

 Step 1 — Install Laravel 11
```
composer create-project laravel/laravel example-app
```

---

 Step 2 — Configure Database  
Edit `.env`
```
DB_DATABASE=your_db
DB_USERNAME=root
DB_PASSWORD=root
```

---

 Step 3 — Create Products Table Migration
```
php artisan make:migration create_products_table --create=products
```
Columns included:  
- name  
- details  
- price  
- size  
- color  
- category  
- image  
- **tag_ids (JSON)** – added later  

Run migration:
```
php artisan migrate
```

---

 Step 4 — Add Resource Route
```php
Route::resource('products', ProductController::class);
```

---

 Step 5 — Product Model
```php
protected $fillable = [
    'name','details','image','size','color','category','price','tag_ids'
];

protected $casts = [
    'tag_ids' => 'array'
];
```

---

 Step 6 — ProductController (Select2 Tags + CRUD)

 Create Product
- Fetch tags to show in Select2
- Validate
- Upload single image
- Save selected tags JSON array

 Update Product
- Delete old image if replaced
- Re-save new tag selections

 Delete Product
- Remove image from public folder
- Delete record

---

 Step 7 — Product Create Page (Select2 Multi‑Select)

```
<select name="tag_ids[]" class="form-control select2-tags" multiple>
    @foreach($tags as $tag)
        <option value="{{ $tag->id }}">{{ $tag->tag_name }}</option>
    @endforeach
</select>
```

 Initialize Select2
```js
$('.select2-tags').select2({
    placeholder: "Select product tags",
    allowClear: true,
    closeOnSelect: true,
    width: "100%"
});
```

---

 Step 8 — Create Tags Table
```
php artisan make:migration create_tags_table --create=tags
```

 Migration Fields
- id  
- tag_name  

---

 Step 9 — Tag Model
```php
protected $fillable = ['tag_name'];
```

---

 Step 10 — TagController (Full CRUD)
Includes:
✔ index  
✔ create  
✔ edit  
✔ update  
✔ delete  

---

 Step 11 — Tags Blade Pages
- index.blade.php  
- create.blade.php  
- edit.blade.php  

Includes tag list, form, and CRUD UI.

---

 Step 12 — Display Tags in Product List
```php
$tags = Tag::whereIn('id', $product->tag_ids ?? [])->pluck('tag_name');
```

```
@foreach($tags as $tag)
    <span class="badge bg-info text-dark">{{ $tag }}</span>
@endforeach
```

---

 Step 13 — Update Admin Layout  
Added:

- jQuery CDN
- Bootstrap 5
- Select2 CSS + JS hooks (via @stack)

---

 Step 14 — Add Admin Authentication (Laravel Breeze)

```
composer require laravel/breeze --dev
php artisan breeze:install blade
npm install && npm run dev
php artisan migrate
```

Protect product routes:
```php
Route::middleware(['auth'])->group(function(){
    Route::resource('products', ProductController::class);
});
```

Set login redirect:
```
public const HOME = '/products';
```

---

 Run Project
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
