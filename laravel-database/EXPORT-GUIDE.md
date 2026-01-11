# Hướng dẫn Export SQL Files từ Laravel Database

## 📋 Các bảng cần export

Bạn cần export **3 bảng** sau từ database Laravel:

1. **`categories`** - Danh mục bài viết
2. **`posts`** - Bài viết  
3. **`seos`** - Dữ liệu SEO

## 🔧 Cách Export

### Phương pháp 1: Sử dụng phpMyAdmin

1. Đăng nhập vào phpMyAdmin
2. Chọn database Laravel của bạn
3. Với mỗi bảng (`categories`, `posts`, `seos`):
   - Click vào tên bảng
   - Chọn tab **"Export"**
   - Chọn method: **"Custom"**
   - Format: **"SQL"**
   - Chọn **"INSERT"** statements
   - Click **"Go"** để download
4. Lưu các file với tên:
   - `categories.sql`
   - `posts.sql`
   - `seos.sql`
5. Copy các file vào thư mục: `wp-content/themes/youtubestore/laravel-database/`

### Phương pháp 2: Sử dụng MySQL Command Line

```bash
# Export categories
mysqldump -u [username] -p [database_name] categories > categories.sql

# Export posts
mysqldump -u [username] -p [database_name] posts > posts.sql

# Export seos
mysqldump -u [username] -p [database_name] seos > seos.sql
```

**Lưu ý:** Thay `[username]` và `[database_name]` bằng thông tin thực tế của bạn.

### Phương pháp 3: Export chỉ dữ liệu (không có structure)

Nếu bạn chỉ muốn export dữ liệu INSERT:

```bash
mysqldump -u [username] -p [database_name] categories --no-create-info --skip-triggers > categories.sql
mysqldump -u [username] -p [database_name] posts --no-create-info --skip-triggers > posts.sql
mysqldump -u [username] -p [database_name] seos --no-create-info --skip-triggers > seos.sql
```

## 📁 Cấu trúc file SQL

File SQL cần có format như sau:

```sql
INSERT INTO `categories` (`id`, `language`, `category_id`, `master_category_id`, `name`, `slug`, `order`, `content`, `created_at`, `updated_at`) VALUES
(1, 'vi', NULL, 1, 'Tên danh mục', 'ten-danh-muc', NULL, NULL, '2021-01-01 00:00:00', '2021-01-01 00:00:00');
```

## ✅ Kiểm tra sau khi export

1. Mở file SQL bằng text editor
2. Đảm bảo có các INSERT statements
3. Kiểm tra encoding (nên là UTF-8)
4. Đảm bảo file không bị corrupt

## 🚀 Sau khi export xong

1. Copy 3 file SQL vào: `wp-content/themes/youtubestore/laravel-database/`
2. Vào WordPress Admin → Tools → Import Laravel Data
3. Click "Bắt đầu Import từ SQL Files"
4. Đợi quá trình import hoàn tất

## ⚠️ Lưu ý quan trọng

- **Chỉ import posts có `language = 'vi'` và `status = 'published'`**
- **Chỉ import categories có `language = 'vi'`**
- File `posts.sql` có thể rất lớn, quá trình import có thể mất vài phút
- Nếu gặp lỗi timeout, cần tăng `max_execution_time` trong PHP
