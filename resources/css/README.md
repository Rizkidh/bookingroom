# CSS Architecture - Professional Blue Theme

## Deskripsi
Aplikasi ini menggunakan arsitektur CSS modular dengan 4 file terpisah untuk mengelola styling secara efisien dan maintainable.

## File Struktur

### 1. **theme.css** (Base Layer - Global Styles)
File ini berisi:
- **CSS Custom Properties (Variables)**: Mendefinisikan palet warna biru profesional dan spacing
  - `--primary-blue: #1e3a8a` (Dark Navy Blue untuk button utama)
  - `--secondary-blue: #3b82f6` (Bright Blue untuk hover/secondary actions)
  - `--success-green: #16a34a` (Professional green untuk success alerts)
  - `--danger-red: #dc2626` (Professional red untuk delete/error)
  - `--text-dark: #1f2937` (Text color)
  - `--text-light: #6b7280` (Secondary text color)
  
- **Global Components**:
  - Navbar styling dengan gradient background
  - Form input base styles dengan focus states
  - Button variants (primary, secondary, danger, success)
  - Card styling dengan hover animations
  - Table base styles
  - Badge dan status badge styles
  - Alert styling
  - Link dan text utilities
  - Border dan spacing utilities

**Dependencies**: None (base layer)

---

### 2. **dashboard.css** (Dashboard-Specific Styles)
File ini berisi styling untuk halaman dashboard:

**Main Containers**:
- `.dashboard-container`: Wrapper utama dengan gradient background
- `.dashboard-header`: Header section dengan centered title
- `.stats-grid`: Grid 4 kolom untuk stat cards (responsive)

**Components**:
- `.stat-card`: Kartu statistik individual dengan warna kode
  - `.stat-value`: Menampilkan angka besar (color-coded: total=blue, available=green, damaged=red, units=purple)
  - `.stat-label`: Label untuk stat card
  
- `.inventory-section`: Section untuk daftar inventaris
- `.section-header`: Header untuk inventory list dengan action button
- `.table-wrapper`: Wrapper untuk table dengan responsive overflow
- `.empty-state`: Styling untuk empty state messages

**Responsive**:
- Tablet (768px): Grid menjadi 2 kolom
- Mobile (480px): Grid menjadi 1 kolom

**Dependencies**: theme.css (menggunakan CSS variables)

---

### 3. **forms.css** (Form-Specific Styles)
File ini berisi styling untuk semua form pages (create/edit inventories dan inventory units):

**Main Containers**:
- `.form-container`: Wrapper dengan padding dan max-width
- `.form-card`: Card wrapper dengan shadow dan animation fade-in

**Form Components**:
- `.form-title`: Title styling dengan bottom border
- `.form-group`: Wrapper untuk setiap form field
- `.form-label`: Label styling dengan required indicator (red asterisk)
- `.form-input`: Input/select styling dengan focus states
  - Blue border + shadow on focus
  - Error variant: `.input-error` dengan red border

**Special Inputs**:
- `.stock-grid`: Grid untuk 3 input stok (total, available, damaged)
- `.stock-input-total`: Blue themed input
- `.stock-input-available`: Green themed input
- `.stock-input-damaged`: Red themed input

**Form Actions**:
- `.form-actions`: Flex container untuk submit/cancel buttons
- `.btn-submit`: Green gradient button
- `.btn-cancel`: Gray gradient button
- Hover effects dengan translateY(-2px)

**Error Handling**:
- `.error-alert`: Red gradient alert untuk validation errors
- `.form-helper`: Small text untuk helper text atau error messages

**Responsive**:
- Tablet: Form actions flex-direction column-reverse
- Mobile: Full-width buttons, reduced padding/font sizes

**Dependencies**: theme.css (menggunakan CSS variables)

---

### 4. **inventory.css** (Inventory-Specific Styles)
File ini berisi styling untuk halaman inventaris (list, detail, units):

**Management Pages** (index.blade.php):
- `.management-container`: Wrapper utama
- `.management-card`: Card wrapper dengan shadow
- `.management-header`: Header dengan title dan add button
- `.management-table`: Table styling untuk inventory list
- `.item-name-link`: Clickable nama barang dengan hover effect

**Detail Pages** (show.blade.php):
- `.unit-detail-container`: Wrapper untuk detail page
- `.unit-detail-card`: Card untuk detail inventory
- `.unit-detail-header`: Header dengan inventory name
- `.unit-section-header`: Header untuk unit list section
- `.unit-count-badge`: Badge untuk menampilkan jumlah unit

**Tables**:
- `.unit-table`: Table styling untuk unit list
- `.unit-table-container`: Responsive wrapper dengan overflow handling

**Status Badges** (5 variants):
- `.status-available`: Green background (#16a34a)
- `.status-in_use`: Blue background (#3b82f6)
- `.status-maintenance`: Yellow background (#eab308)
- `.status-damaged`: Red background (#dc2626)
- `.status-lost`: Gray background (#6b7280)

**Unit Information**:
- `.unit-holder`: Styling untuk pemegang unit yang aktif (blue)
- `.unit-holder-gudang`: Styling untuk unit di gudang (gray)
- `.serial-number`: Styling untuk nomor serial
- `.empty-state`: Styling untuk empty unit state

**Action Buttons**:
- `.btn-add`: Primary button untuk "Tambah" actions
- `.btn-edit`: Edit button styling
- `.btn-delete`: Delete button dengan red color
- `.btn-action`: Action button untuk detail/edit

**Responsive**:
- Grid layout menyesuaikan untuk tablet dan mobile
- Table menjadi scrollable pada layar kecil

**Dependencies**: theme.css (menggunakan CSS variables)

---

## Import Order di app.js

Urutan import di [resources/js/app.js](resources/js/app.js) sangat penting:

```javascript
import '../css/theme.css';        // Layer 1: Global base styles
import '../css/dashboard.css';    // Layer 2: Dashboard specific
import '../css/forms.css';        // Layer 3: Form specific
import '../css/inventory.css';    // Layer 4: Inventory specific
```

**Penjelasan**:
1. **theme.css** diload pertama (base layer) - mendefinisikan variables dan global styles
2. **dashboard.css** mengikuti - bisa menggunakan variables dari theme.css
3. **forms.css** mengikuti - overrides dan style spesifik untuk forms
4. **inventory.css** terakhir - overrides jika ada konflik dengan files sebelumnya

Urutan ini memastikan cascading CSS bekerja dengan benar dan mencegah style conflicts.

---

## CSS Variables (Available di theme.css)

```css
/* Colors */
--primary-blue: #1e3a8a;
--secondary-blue: #3b82f6;
--success-green: #16a34a;
--danger-red: #dc2626;
--warning-yellow: #eab308;
--gray-dark: #1f2937;
--text-light: #6b7280;
--bg-light: #f8fafc;
--border-color: #e2e8f0;

/* Spacing & Sizing */
--spacing-xs: 0.25rem;
--spacing-sm: 0.5rem;
--spacing-md: 1rem;
--spacing-lg: 1.5rem;
--spacing-xl: 2rem;

/* Border & Shadow */
--border-radius-sm: 4px;
--border-radius-md: 8px;
--border-radius-lg: 12px;
--shadow-sm: 0 1px 2px rgba(0, 0, 0, 0.05);
--shadow-md: 0 4px 6px rgba(0, 0, 0, 0.1);
--shadow-lg: 0 10px 15px rgba(0, 0, 0, 0.1);
```

---

## Maintenance & Customization

### Mengubah Warna Tema
Jika ingin mengubah warna biru menjadi warna lain, cukup ubah variables di `theme.css` bagian `:root`:

```css
:root {
    --primary-blue: #NEW_COLOR;
    --secondary-blue: #NEW_COLOR;
}
```

Semua file yang menggunakan `var(--primary-blue)` akan otomatis menggunakan warna baru.

### Menambah Component Baru
1. Tentukan apakah component ini global (theme.css) atau page-specific (dashboard/forms/inventory.css)
2. Tulis class dan style di file yang sesuai
3. Gunakan CSS variables untuk warna dan spacing
4. Pastikan nama class descriptive dan mengikuti naming convention yang ada

### Naming Convention
- Gunakan kebab-case untuk class names: `.form-group`, `.btn-submit`
- Prefixing untuk parent component: `.stat-card`, `.stat-value`
- Utility-style untuk simple styling: `.text-center`, `.mt-2`

---

## Browser Support

Arsitektur CSS ini menggunakan:
- CSS Grid & Flexbox (IE 11+ / Modern browsers)
- CSS Custom Properties / Variables (IE 11 tidak support, tapi aplikasi target modern browsers)
- Gradient backgrounds
- CSS animations & transitions

Untuk IE 11 support, perlu fallback untuk CSS variables.

---

## Testing Checklist

Saat membuat perubahan CSS:
- [ ] Run `npm run build` untuk memastikan tidak ada error
- [ ] Test di Chrome, Firefox, Safari
- [ ] Test responsive pada breakpoints: 768px, 480px
- [ ] Check CSS variables tidak ada typo
- [ ] Pastikan warna sesuai dengan tema blue professional

---

## File Sizes (Production)

Setelah build dengan Vite:
- **theme.css**: ~2KB (base styles)
- **dashboard.css**: ~3KB (dashboard specific)
- **forms.css**: ~2.5KB (form specific)
- **inventory.css**: ~3KB (inventory specific)
- **Total**: ~18KB (sebelum gzip), ~3.71KB (setelah gzip)

CSS files digabungkan menjadi 1 file di production dengan bundling otomatis oleh Vite.
