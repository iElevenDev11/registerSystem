# Laravel Authentication Project Structure

```markdown
your-project-name/
│
├── 📁 app/
│ ├── 📁 Http/
│ │ ├── 📁 Controllers/
│ │ │ ├── Controller.php                                                (Already exists)
│ │ │ ├── RegisterController.php ⭐ Create with artisan
│ │ │ ├── LoginController.php ⭐ Create with artisan
│ │ │ └── LogoutController.php ⭐ Create with artisan
│ │ │
│ │ └── 📁 Middleware/
│ │ └── Authenticate.php ⭐ Create with artisan
│ │
│ ├── 📁 Models/
│ │ └── User.php                                                        (Already exists - configure)
│ │
│ └── 📁 Exceptions/
│ └── Handler.php ⭐ Create with artisan
│
├── 📁 database/
│ ├── 📁 migrations/
│ │ └── xxxx_xx_xx_xxxxxx_create_users_table.php ⭐ Create with artisan
│ │
│ └── database.sqlite                                                  (Auto-created after migration)
│
├── 📁 resources/
│ └── 📁 views/
| └── dashboard.blade.php ⭐ Create manually
│ ├── 📁 auth/ ⭐ Create manually
│ │ ├── register.blade.php ⭐ Create manually
│ │ └── login.blade.php ⭐ Create manually
│ │
│ └── 📁 layouts/
│ └── app.blade.php ⭐ Create manually
│
├── 📁 routes/
│ ├── web.php ⭐ Modify this
│ └── api.php (Not needed for auth)
│
├── 📁 config/
│ ├── auth.php                                                        (Already exists)
│ ├── database.php                                                    (Already exists)
│ └── app.php                                                         (Already exists)
│
├── 📁 public/
│ ├── index.php                                                       (Entry point)
│ └── 📁 css/
|    |__styles.css                                                    (Optional)
│
├── .env                                                              (Already exists - verify)
```

---

## 🎯 Artisan Commands to Generate Files

Run these commands in order:

### **1. Create Controllers**

```bash
php artisan make:controller RegisterController
php artisan make:controller LoginController
php artisan make:controller LogoutController
```

✅ Creates: `app/Http/Controllers/RegisterController.php`, etc.

### **2. Create Middleware**

```bash
php artisan make:middleware Authenticate
```

✅ Creates: `app/Http/Middleware/Authenticate.php`

### **3. Create Migration for Users Table Or Modify it**

```bash
php artisan make:migration create_users_table
```

✅ Creates: `database/migrations/xxxx_xx_xx_xxxxxx_create_users_table.php`

### **4. Create Exception Handler**

```bash
php artisan make:exception Handler
```

⚠️ Note: This creates it in `app/Exceptions/` but you might need to manually configure it

---

## 📁 Genrate these views

```bash
php artisan make:view auth.register
php artisan make:view auth.login
php artisan make:view layouts.app
php artisan make:view dashboard
```

---

## 📋 Complete Setup Checklist

```bash
# 1️⃣ Create Controllers
php artisan make:controller RegisterController
php artisan make:controller LoginController
php artisan make:controller LogoutController

# 2️⃣ Create Middleware
php artisan make:middleware Authenticate

# 3️⃣ Create Migration
php artisan make:migration create_users_table

# 4️⃣ Create Exception Handler
php artisan make:exception Handler

# 5️⃣ generate these views
- php artisan make:view auth.register
- php artisan make:view auth.login
- php artisan make:view layouts.app
- php artisan make:view dashboard


# 6️⃣ Run migrations
php artisan migrate

# 7️⃣ Visite in browser
resgisterSystem.test (if using herd)
**if not using herd you can run**
php artisan serve
```

---

## 📝 Files to Edit/Configure

| File                                              | Action                          |
| ------------------------------------------------- | ------------------------------- |
| `app/Models/User.php`                             | Configure Authenticatable trait |
| `app/Http/Controllers/RegisterController.php`     | Write registration logic        |
| `app/Http/Controllers/LoginController.php`        | Write login logic               |
| `app/Http/Controllers/LogoutController.php`       | Write logout logic              |
| `app/Http/Middleware/Authenticate.php`            | Configure auth middleware       |
| `routes/web.php`                                  | Add auth routes                 |
| `database/migrations/xxxx_create_users_table.php` | Add table columns               |
| `resources/views/layouts/app.blade.php`           | Create base template            |
| `resources/views/auth/register.blade.php`         | Create signup form              |
| `resources/views/auth/login.blade.php`            | Create login form               |
| `resources/views/dashboard.blade.php`             | Create dashboard page           |
| `.env`                                            | Verify DB_CONNECTION=sqlite     |
