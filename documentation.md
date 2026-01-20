# Documentation System Inventaris KAI

Dokumen ini berisi diagram teknis untuk Sistem Manajemen Inventaris, termasuk Use Case Diagram, Data Flow Diagram (DFD), dan Entity Relationship Diagram (ERD).

---

## 1. Use Case Diagram
Use Case Diagram ini menggambarkan interaksi antara aktor (Admin & Pegawai) dengan fitur-fitur utama dalam sistem.

```mermaid
useCaseDiagram
    actor "Admin" as Admin
    actor "Pegawai" as Staff

    package "Sistem Inventaris" {
        usecase "Login & Kelola Profil" as UC1
        usecase "Monitoring Dashboard" as UC2
        usecase "Kelola Inventaris (Master Data)" as UC3
        usecase "Kelola Unit Inventaris (Serial Number)" as UC4
        usecase "Scan QR Code" as UC5
        usecase "Export Data (Excel/PDF)" as UC6
        usecase "Audit Trail (Activity Logs)" as UC7
    }

    Admin --> UC1
    Admin --> UC2
    Admin --> UC3
    Admin --> UC4
    Admin --> UC5
    Admin --> UC6
    Admin --> UC7

    Staff --> UC1
    Staff --> UC2
    Staff --> UC5
```

**Deskripsi Aktors:**
- **Admin**: Memiliki akses penuh ke seluruh fitur sistem, termasuk pengelolaan master data barang, unit, dan melihat log aktivitas seluruh pengguna.
- **Pegawai**: Memiliki akses terbatas, umumnya untuk melihat dashboard, mengelola profil sendiri, dan melakukan pemindaian (Scan) QR Code unit.

**Deskripsi:**
- **Admin/User**: Melakukan manajemen data barang, unit, dan melihat log aktivitas.
- **Scan QR Code**: Digunakan untuk identifikasi unit secara cepat di lapangan.
- **Activity Logs**: Mencatat setiap perubahan data untuk keperluan audit.

---

## 2. Data Flow Diagram (DFD) - Level 0
DFD Level 0 (Context Diagram) menunjukkan aliran data global antara entitas luar dan sistem.

```mermaid
graph LR
    User((User / Admin))
    System[[Sistem Inventaris KAI]]

    User -- "Input Data Inventaris, Login, Scan QR" --> System
    System -- "Laporan Inventaris, Log Aktivitas, Info Unit" --> User
```

### DFD Level 1
Menjelaskan proses internal sistem secara lebih rinci.

```mermaid
graph TD
    User((User / Admin))
    
    subgraph "Proses Sistem"
        P1[1.0 Otentikasi]
        P2[2.0 Manajemen Inventaris]
        P3[3.0 Manajemen Unit & QR]
        P4[4.0 Pelaporan & Log]
    end

    db1[(User DB)]
    db2[(Inventory DB)]
    db3[(Activity Log DB)]

    User --> P1
    P1 <--> db1

    User --> P2
    P2 <--> db2

    User --> P3
    P3 <--> db2

    User --> P4
    P4 <--> db3
    db2 --> P4
```

---

## 3. Entity Relationship Diagram (ERD)
ERD menggambarkan struktur database dan hubungan antar tabel.

```mermaid
erDiagram
    USERS ||--o{ ACTIVITY_LOGS : performs
    INVENTORY_ITEMS ||--o{ INVENTORY_UNITS : has
    INVENTORY_ITEMS ||--o{ ACTIVITY_LOGS : logged
    INVENTORY_UNITS ||--o{ ACTIVITY_LOGS : logged

    USERS {
        bigint id PK
        string name
        string email
        string role "admin / pegawai"
        string password
        datetime email_verified_at
    }

    INVENTORY_ITEMS {
        bigint id PK
        string name
        text note
        integer total_stock
        integer available_stock
        integer damaged_stock
        datetime deleted_at
    }

    INVENTORY_UNITS {
        string id PK
        bigint inventory_item_id FK
        string serial_number
        string photo
        string condition_status
        string current_holder
        text note
        string qr_code
        datetime deleted_at
    }

    ACTIVITY_LOGS {
        bigint id PK
        string action
        string model_type
        bigint model_id
        text description
        json old_values
        json new_values
        string user_name
        string ip_address
    }
```

---

## Cara Export ke PDF

Untuk mengubah dokumen ini menjadi PDF yang profesional, Anda dapat menggunakan salah satu cara berikut:

### Metode 1: Menggunakan VS Code (Direkomendasikan)
1. Pasang ekstensi **"Markdown PDF"** (oleh yyzhang).
2. Buka file `documentation.md`.
3. Tekan `Ctrl+Shift+P`, cari dan pilih **"Markdown PDF: Export (pdf)"**.

### Metode 2: Menggunakan Browser
1. Buka file `documentation.md` di browser (atau gunakan pratinjau Markdown).
2. Gunakan fitur **Print** (`Ctrl + P`).
3. Pilih **"Save as PDF"** sebagai tujuan printer.

### Metode 3: Menggunakan Obsidian atau Tool Markdown Lainnya
- Buka di aplikasi seperti Obsidian atau Typora, lalu pilih menu **Export to PDF**.
