# YouTube Store Theme

## Image optimization (local)

Ảnh trong theme được resize và tạo bản WebP bằng script Node (package `sharp`).

- **Cài dependency:** `npm install`
- **Chạy tối ưu ảnh:** `npm run images:optimize`  
  - Resize ảnh rộng > 1920px xuống 1920px  
  - Tạo file `.webp` bên cạnh mỗi `.jpg`/`.png`  
- **Xem trước (không ghi file):** `npm run images:optimize:dry`

Sau khi chạy, commit cả thư mục `assets/images` (gồm các file `.webp` mới) lên server. Theme dùng helper `youtubestore_theme_img()` để tự phục vụ WebP khi có file tương ứng.

## Giảm tải đã áp dụng (PageSpeed)

- **CSS không chặn render:** Trang chủ tải `style.css` và `theme-optimized.css` dạng không chặn (media="print" + onload).
- **Google Fonts:** Thêm `display=swap` cho link font từ Google.
- **Lazy load ảnh:** Ảnh đính kèm và ảnh trong nội dung có `loading="lazy"` và `decoding="async"`.
- **JS trang chủ:** `front-page.js` được tải sau sự kiện `window.load` để giảm tải luồng chính.
- **Preload:** Preload CSS của plugin youtube-channel-stats (nếu có) để tải sớm hơn.

Ảnh trong **wp-content/uploads** (từ plugin/medias): nên dùng plugin tối ưu ảnh (WebP, resize) hoặc cấu hình server (gzip/brotli, cache) để giảm thêm tải.
