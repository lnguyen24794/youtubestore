# YouTube Store Theme

## Image optimization (local)

Ảnh trong theme được resize và tạo bản WebP bằng script Node (package `sharp`).

- **Cài dependency:** `npm install`
- **Chạy tối ưu ảnh:** `npm run images:optimize`  
  - Resize ảnh rộng > 1920px xuống 1920px  
  - Tạo file `.webp` bên cạnh mỗi `.jpg`/`.png`  
- **Xem trước (không ghi file):** `npm run images:optimize:dry`

Sau khi chạy, commit cả thư mục `assets/images` (gồm các file `.webp` mới) lên server. Theme dùng helper `youtubestore_theme_img()` để tự phục vụ WebP khi có file tương ứng.
