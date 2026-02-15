# Refactor Tenancy to Path-Based URLs

- [x] Planning & Configuration <!-- id: 0 -->
    - [x] Analyze current tenancy setup (`config/tenancy.php`, `routes/tenant.php`) <!-- id: 1 -->
    - [x] Identify necessary route changes for `localhost:8000/nama_lembaga` <!-- id: 2 -->
    - [x] Create `TenantSeeder` for demo data <!-- id: 3 -->
- [x] **Enhance Tenant Database Configuration**
    - [x] Update `config/tenancy.php` to use dynamic database prefix from `.env` or `DB_USERNAME`.
    - [x] Fix `TenantSeeder` to handle shared hosting restrictions (manual DB creation instructions).
    - [/] Make `central_domains` dynamic based on `APP_URL` and `.env` to support future hosting changes. <!-- id: 5 -->
- [x] Implementation <!-- id: 4 -->
    - [x] Update `routes/tenant.php` to use `/{tenant}` prefix with `InitializeTenancyByPath` and fix redirection logic <!-- id: 5 -->
    - [x] Update `routes/web.php` to remove domain grouping and order routes correctly <!-- id: 6 -->
    - [x] Modify `login.blade.php` redirection logic <!-- id: 7 -->
    - [x] Fix `AuthenticatedSessionController` redirection logic for path-based tenancy <!-- id: 16 -->
    - [x] Fix `SchoolSettingController` to sync and fallback to Tenant model data <!-- id: 17 -->
    - [x] Create and run `TenantSeeder` <!-- id: 8 -->
    - [x] Fix Tenant Asset Route (`UrlGenerationException`) <!-- id: 4 -->
- [ ] **Live Image Preview** <!-- id: 4 -->
    - [x] Refactor JS for generic preview <!-- id: 5 -->
    - [x] Apply to all file inputs <!-- id: 6 -->
- [x] **Dynamic Footer Links & Social** <!-- id: 7 -->
    - [x] Add "Footer & Social" tab to Admin Settings <!-- id: 8 -->
    - [x] Implement inputs for Social Media URLs <!-- id: 9 -->
    - [x] Implement inputs for Product, Company, Legal links (fixed slots) <!-- id: 10 -->
    - [x] Update frontend footer to use dynamic settings <!-- id: 11 -->
- [x] **Enhance Footer Settings** (Rich Text & Frontend Update)
- [x] **Dynamic Logo & Favicon** (Backend & Frontend)
- [x] **School Registration Flow** (Fields & Tenant Creation)
- [x] **Enhance Login Page** (Modern Split Layout)
    - [x] Create Split Layout (Image Left, Form Right) <!-- id: 64 -->
    - [x] Add Logic Cover Image Setting in Backend <!-- id: 65 -->
    - [x] Implement Dynamic Background Image <!-- id: 66 -->
    - [x] Add "Daftar Sekarang" Link <!-- id: 67 -->
- [x] **Refactor Register Page** (Match Login Style) <!-- id: 68 -->
    - [x] Create Split Layout (Consistent Branding) <!-- id: 69 -->
    - [x] Update Form Styling (Tailwind) <!-- id: 70 -->
    - [x] Ensure Dynamic Settings Usage (Logo, Favicon, Legal Links) <!-- id: 71 -->
- [x] **Forgot Password & OTP** <!-- id: 72 -->
    - [x] Implement UI from User Template (`forgot-password.blade.php`) <!-- id: 73 -->
    - [x] Dynamic SMTP Configuration (Load from DB) <!-- id: 74 -->
    - [x] **New:** Create WhatsApp Settings Page (Super Admin) <!-- id: 77 -->
    - [x] Implement WhatsApp OTP Logic (Multi-provider Service) <!-- id: 75 -->
    - [x] Update Password Reset Controller (Added `PasswordResetOtpController`) <!-- id: 76 -->
- [x] **Dynamic Page Management** <!-- id: 12 -->
    - [x] Create `pages` table migration <!-- id: 13 -->
    - [x] Create `Page` model <!-- id: 14 -->
    - [x] Create `PageController` (Backend CRUD) <!-- id: 15 -->
    - [x] Create Admin Views (index, create, edit) <!-- id: 16 -->
    - [x] Create `FrontPageController` & Route for `/p/{slug}` <!-- id: 17 -->
    - [x] Create Frontend View `pages/show.blade.php` <!-- id: 18 -->
- [x] Fix Tenant Profile Update <!-- id: 5 -->
    - [x] Debug controller not hit <!-- id: 6 -->
    - [x] Fix validation blocker <!-- id: 7 -->
    - [x] Fix DB connection/session mix-up <!-- id: 8 -->
- [x] Refactor Tenant Profile (Dedicated Controller & View) <!-- id: 9 -->
    - [x] Create `App\Http\Controllers\Tenant\ProfileController` <!-- id: 10 -->
    - [x] Create dedicated view `resources/views/tenant/profile/edit.blade.php` <!-- id: 11 -->
    - [x] Update `routes/tenant.php` <!-- id: 12 -->
    - [x] Fix Tenant Asset Route (`App\Http\Controllers\Tenant\AssetController`) <!-- id: 14 -->
- [x] Fix Login Redirect Loop/Logic <!-- id: 13 -->
    - [x] Create `RedirectIfAuthenticated` middleware <!-- id: 15 -->
    - [x] Update `AuthenticatedSessionController` for role-based redirect <!-- id: 16 -->
- [x] Verification <!-- id: 9 -->
    - [x] Run `php artisan migrate:fresh --seed` <!-- id: 10 -->
    - [x] Verify Central Login (`/login`) <!-- id: 11 -->
    - [x] Verify Tenant Redirect (`/login` -> `/smpn1/login`) <!-- id: 12 -->
    - [x] Verify Tenant Dashboard Access <!-- id: 13 -->

# Feature: Student Management (Data Akademik) <!-- id: 17 -->
- [/] Tenant Student CRUD <!-- id: 18 -->
    - [x] Create/Update `TenantStudentController` (Added destroy method) <!-- id: 19 -->
    - [x] Setup `Student` model with Tenancy scope (Verified) <!-- id: 20 -->
    - [x] Create/Update views (Fixed asset usage) <!-- id: 21 -->
    - [ ] Implement Import/Export (Excel/CSV) logic <!-- id: 22 -->
    - [x] Implement PDF Print (Individual) <!-- id: 26 -->
    - [x] Implement Letterhead (Kop Surat) Settings <!-- id: 27 -->
    - [ ] Implement Bulk Delete & Bulk Print <!-- id: 28 -->
- [ ] Student Document Archive <!-- id: 23 -->
    - [ ] Document Upload & Category Management <!-- id: 24 -->
    - [ ] Secure File Serving (using `AssetController` logic) <!-- id: 25 -->
- [ ] Feature: Document Type Management (Super Admin) <!-- id: 29 -->
    - [ ] Create `DocumentType` model and migration (Central DB) <!-- id: 30 -->
    - [ ] Create `DocumentTypeController` and Views for Super Admin <!-- id: 31 -->
    - [ ] Integrate with Tenant `DocumentController` <!-- id: 32 -->

# Feature: Dynamic Frontend (Landing Page) <!-- id: 33 -->
- [x] Backend Implementation <!-- id: 34 -->
    - [x] Update `SettingController` for new file uploads (Hero, Architecture, Partners) <!-- id: 35 -->
- [x] **Refactor Settings UI** <!-- id: 36 -->
    - [x] Update `SettingController` with separated methods <!-- id: 45 -->
    - [x] Create `settings/general.blade.php` <!-- id: 46 -->
    - [x] Create `settings/landing.blade.php` <!-- id: 47 -->
    - [x] Create `settings/footer.blade.php` <!-- id: 48 -->
    - [x] Create `settings/smtp.blade.php` <!-- id: 49 -->
    - [x] Update Routes and Sidebar <!-- id: 50 -->
- [x] Frontend Implementation <!-- id: 37 -->
    - [x] Refactor `hero.blade.php` to use `AppSetting` <!-- id: 38 -->
    - [x] Refactor `features.blade.php` to use `AppSetting` <!-- id: 39 -->
    - [x] Refactor `architecture.blade.php` to use `AppSetting` <!-- id: 40 -->
    - [x] Refactor `security.blade.php` to use `AppSetting` <!-- id: 41 -->
    - [x] Refactor `trusted-by.blade.php` to use `AppSetting` <!-- id: 42 -->
    - [x] Refactor `cta.blade.php` to use `AppSetting` <!-- id: 43 -->

# Refactor: SEO Friendly URLs (Split Landing Page) <!-- id: 54 -->
- [x] Create separate routes and controller methods (Features, Architecture, Security) <!-- id: 55 -->
- [x] Create separate view files for each page <!-- id: 56 -->
- [x] Update Navbar and Hero links <!-- id: 57 -->

# Refactor: Landing Page UI Polish <!-- id: 58 -->
- [x] Add Global Background Pattern (Gradient/Grid) <!-- id: 59 -->
- [x] Modernize Hero Section (Glassmorphism, Floating Badges) <!-- id: 60 -->
- [x] Modernize Features Section (Card Hover Effects, Icons) <!-- id: 61 -->
- [x] Modernize CTA & Architecture Section <!-- id: 62 -->
- [x] Fix Mobile Navigation (Hamburger Menu) <!-- id: 63 -->

# Documentation <!-- id: 51 -->
- [x] Create README.md for GitHub <!-- id: 52 -->
- [x] Cleanup junk files from repository (adminlte3 docs/pages) <!-- id: 53 -->
