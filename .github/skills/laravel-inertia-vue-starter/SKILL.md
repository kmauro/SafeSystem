---
name: laravel-inertia-vue-starter
description: 'Use when: starting a new Laravel project from scratch. Creates a fully configured Laravel + Inertia + Vue3 + TailwindCSS + shadcn-vue project with dashboard, sidebar, PageTitle component, Login and Sign-up authentication integrated.'
trigger: Create a new Laravel project with Inertia, Vue3, TailwindCSS
---

# Laravel + Inertia + Vue3 + TailwindCSS + shadcn-vue Starter

This skill automates the creation of a production-ready Laravel project with a complete frontend stack and authentication system.

## Workflow

### Phase 1: Laravel & Inertia Setup
1. Create Laravel project: `laravel new project-name`
2. Install Inertia Laravel: `composer require inertiajs/inertia-laravel`
3. Publish Inertia configuration: `php artisan inertia:install`
4. Install Inertia dependencies: `npm install @inertiajs/vue3 vue`

### Phase 2: Frontend Stack
1. Install TailwindCSS: `npm install -D tailwindcss postcss autoprefixer`
2. Initialize Tailwind config: `npx tailwindcss init -p`
3. Install shadcn-vue: `npm install -D shadcn-vue`
4. Initialize shadcn-vue: `npx shadcn-vue@latest init`
5. Install UI components from shadcn-vue:
   - `npx shadcn-vue@latest add input`
   - `npx shadcn-vue@latest add button`
   - `npx shadcn-vue@latest add card`
   - `npx shadcn-vue@latest add dropdown-menu`
   - `npx shadcn-vue@latest add collapsible`
   - `npx shadcn-vue@latest add separator`

### Phase 3: Folder Structure
Create organized directory structure:
```
resources/js/
├── Pages/
│   ├── Auth/
│   │   ├── Login.vue
│   │   └── Signup.vue
│   ├── Dashboard.vue
│   └── Profile.vue
├── Components/
│   ├── ui/
│   │   ├── Sidebar/
│   │   └── [shadcn components]
│   ├── PageTitle.vue
│   └── ThemeToggle.vue
├── Layouts/
│   ├── AppLayout.vue
│   └── AuthLayout.vue
├── Composables/
│   └── [custom hooks]
└── app.js

app/Domains/Users/
├── User.php (Model)
├── UserController.php
├── UserService.php
├── UserRepository.php
├── UserRequest.php
```

### Phase 4: Database & Models
1. Update user migration to include: `name`, `surname`, `username`, `email`, `phone`, `password`, `role_id`
2. Create roles table with admin (id=1) and usuario (id=2)
3. Generate User model with factory

### Phase 5: Authentication
1. Create `UserRequest` form request with validation
2. Create `UserService` for registration/login logic
3. Create `UserRepository` for database operations
4. Create `UserController` with signup, login, logout methods
5. Create auth routes in `routes/web.php`

### Phase 6: Frontend Components
1. Create `PageTitle.vue` with breadcrumbs, menu toggle, theme toggle
2. Create `Sidebar.vue` with menu navigation, user profile dropdown
3. Create `ThemeToggle.vue` for dark/light mode
4. Create `AppLayout.vue` combining Sidebar + PageTitle
5. Create `Login.vue` page with form
6. Create `Signup.vue` page with form
7. Create `Dashboard.vue` page with sample content

### Phase 7: Inertia Middleware
1. Update `HandleInertiaRequests.php` to share user data globally:
   ```php
   'auth' => [
       'user' => $request->user()?->only('id', 'name', 'surname', 'username', 'phone', 'email'),
   ]
   ```
2. Add flash data sharing for success/error messages

### Phase 8: Configuration & Styling
1. Configure Tailwind to include shadcn-vue paths
2. Set up CSS variables for theming
3. Configure Vite for hot module reloading
4. Add default 404/500 error pages

### Phase 9: Testing & Verification
1. Run database migrations: `php artisan migrate`
2. Seed roles: `php artisan db:seed`
3. Start dev server: `composer run dev`
4. Test signup flow: Complete form and create user
5. Test login flow: Log in with created account
6. Verify sidebar loads user data correctly
7. Test theme toggle functionality

## Quality Checklist

✓ User attributes complete (name, surname, username, phone, email, password, role_id)
✓ Default role_id=2 assigned to new users
✓ Authentication middleware properly configured
✓ Inertia props shared globally with user data
✓ Sidebar renders user profile with initials
✓ PageTitle component responsive on mobile
✓ Dark/light theme toggle working
✓ Forms validated with shadcn-vue Input components
✓ Flash messages display on success/error
✓ All routes protected with auth middleware

## Next Steps After Creation

- Add database query builder helpers
- Create additional pages/routes
- Set up permissions system per role
- Add image upload functionality
- Configure email notifications
- Set up Redis for caching

## Example Prompt to Activate This Skill

*"Create a new Laravel project with Inertia, Vue3, TailwindCSS integration and complete authentication system including sidebar and dashboard."*
