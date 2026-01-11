# PRODUCT REQUIREMENTS DOCUMENT (PRD)
**Project Name:** High Performance Youtube Store (WordPress)
**Version:** 1.0
**Date:** 2024
**Type:** Custom WordPress Development (No Page Builders)

---

## 1. TỔNG QUAN DỰ ÁN (PROJECT OVERVIEW)
Xây dựng website hiển thị danh sách kênh Youtube (Digital Products) với mục tiêu tối cao là hiệu năng (**Google PageSpeed > 90** trên cả Mobile/Desktop).
* **Mô hình dữ liệu:** Dữ liệu nhập tại Google Sheets -> Đồng bộ về Database WordPress -> Hiển thị ra Frontend.
* **Tính năng chính:** Xem danh sách, Lọc nâng cao (Filter), Xem chi tiết video, Liên hệ (Không có giỏ hàng/thanh toán online).

---

## 2. YÊU CẦU KỸ THUẬT (TECHNICAL STACK & CONSTRAINTS)

### 2.1. Tech Stack (Bắt buộc)
* **CMS:** WordPress (Latest Version).
* **Theme:** **Custom Theme** (Code tay từ đầu hoặc base trên `_s` / Underscores).
    * ⛔ **CẤM:** Sử dụng Page Builders (Elementor, WPBakery, Divi...).
    * ⛔ **CẤM:** Sử dụng Theme có sẵn trên Themeforest (thường rất nặng).
* **Plugins:** Hạn chế tối đa.
    * *Khuyên dùng:* Advanced Custom Fields (ACF) Pro để quản lý trường dữ liệu.
    * ⛔ **CẤM:** WooCommerce (Quá nặng cho nhu cầu này).
    * ⛔ **CẤM:** Revolution Slider, Plugin Filter có sẵn (như FacetWP - trừ khi dev tự optimize tốt).
* **Frontend Core:**
    * **CSS:** Viết tay hoặc dùng TailwindCSS (đã purge unused CSS).
    * **JS:** **Vanilla JavaScript** (JS thuần). Không phụ thuộc jQuery ở Frontend để tối ưu Render-blocking.

### 2.2. Hiệu năng (Performance KPIs)
* **Core Web Vitals:** LCP < 2.5s, CLS < 0.1, FID < 100ms.
* **PageSpeed Insights:** Điểm xanh lá (> 90) cho cả Mobile và Desktop.
* **Image Optimization:** Tự động convert ảnh sang định dạng **WebP**.

---

## 3. CẤU TRÚC DỮ LIỆU (DATABASE & MAPPING)

### 3.1. Custom Post Type
* **Slug:** `youtube_channel`
* **Supports:** Title, Thumbnail (Featured Image), Custom Fields.

---

## 4. CHI TIẾT TÍNH NĂNG (FUNCTIONAL SPECS)

### 4.1. Module Đồng bộ (Sync Engine - Backend)
* **Input:** Admin nhập Google Sheet ID và Tên Tab vào trang cài đặt.
* **Action:** Nút bấm "Sync Data Now" trong WP Admin.
* **Logic:**
    1.  Fetch dữ liệu từ Google Sheet API.
    2.  So sánh với Database hiện tại qua trường `ID`.
    3.  Xử lý hình ảnh: Nếu là link ảnh ngoài -> Tải về Media Library -> Convert WebP -> Gán làm Featured Image.
    4.  Xóa Cache (nếu có cài WP Rocket/LiteSpeed) sau khi sync xong.

### 4.2. Trang chủ & Danh sách (Archive Page)
* **Layout:**
    * **Desktop:** Grid 3 hoặc 4 cột.
    * **Mobile:** Grid 1 cột (Dạng Card dọc).
* **Card Component:**
    * Ảnh thumbnail (Lazy load).
    * Title (Tên kênh).
    * Các thông số: Sub, View, Kiếm tiền (Dùng icon minh họa cho gọn).
    * Giá tiền (Format: 1.000.000 đ).
    * Nút CTA: "Xem chi tiết".

### 4.3. Bộ lọc & Tìm kiếm (AJAX Filter)
* **Yêu cầu:** Code tay bằng AJAX (Fetch API), không reload lại trang.
* **Vị trí:** Sidebar bên trái (Desktop) / Off-canvas drawer (Mobile).
* **Các trường lọc:**
    * *Danh mục:* Checkbox (Vlog, Gaming, News...).
    * *Khoảng giá:* Radio (Dưới 1tr, 1-5tr, 5-10tr, Trên 10tr) hoặc Min-Max Input.
    * *Trạng thái:* Checkbox (Bật kiếm tiền, Đã bán...).
    * *Sắp xếp:* Mới nhất, Giá tăng/giảm.
* **UX:** Khi chọn filter -> Hiển thị Loading Skeleton -> Cập nhật danh sách sản phẩm.

### 4.4. Trang chi tiết (Single Page)
* **Video Player (Performance Critical):**
    * Sử dụng kỹ thuật **YouTube Facade**.
    * Ban đầu chỉ load thẻ `<img>` (Thumbnail chất lượng cao) và icon Play `div`.
    * Sự kiện `onclick`: Thay thế ảnh bằng `<iframe>` YouTube (autoplay=1).
* **Sticky CTA (Mobile):** Thanh bar cố định dưới cùng màn hình trên Mobile chứa 2 nút:
    * [Zalo Icon] Chat ngay (Deep link: `https://zalo.me/...`).
    * [Phone Icon] Gọi điện (`tel:...`).
* **Related Products:** Hiển thị 3-4 kênh cùng chủ đề ở cuối trang.

---

## 5. NON-FUNCTIONAL REQUIREMENTS

### 5.1. SEO Technical
* Cấu trúc Heading (H1, H2, H3) chuẩn logic.
* Schema Markup: Tự động tạo Schema `Product` cho mỗi kênh (để hiện giá/sao trên Google Search).
* URL Rewrite: `/kênh/ten-kenh-id` (Thân thiện SEO).

### 5.2. Security
* Disable XML-RPC.
* Ẩn WP Version.
* Escape dữ liệu đầu ra để chống XSS.

---

## 6. ACCEPTANCE CRITERIA (TIÊU CHÍ NGHIỆM THU)

1.  **Tính năng Sync:** Bấm sync, dữ liệu trên web khớp 100% với Sheet. Thêm dòng mới ở Sheet -> Web hiện dòng mới.
2.  **Bộ lọc:** Chọn "Giá dưới 1 triệu" -> Chỉ hiện các kênh giá < 1tr. Không bị lỗi layout khi lọc.
3.  **Tốc độ:** Chạy test trên https://pagespeed.web.dev/ phải đạt > 90 điểm Mobile.
4.  **Responsive:** Không bị vỡ khung trên iPhone SE, iPhone 14 Pro Max, iPad, và Laptop.
5.  **Clean Code:** Source code không chứa các thư viện thừa, comment code rõ ràng.