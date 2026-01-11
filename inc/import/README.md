# Import Functionality Documentation

## Tổng quan

Module này cung cấp các chức năng import dữ liệu từ Laravel database và import kênh YouTube từ file CSV/Excel.

## 1. Import Data từ Laravel Database

### Cách sử dụng:

1. Vào **Tools > Import Laravel Data** trong WordPress admin
2. Nhập thông tin kết nối database Laravel:
   - Database Host
   - Database Name
   - Database User
   - Database Password
   - Table Prefix (nếu có)
3. Chọn các mục muốn import:
   - ✅ Import Categories
   - ✅ Import Posts
   - ✅ Import SEO Data
   - ✅ Create Menu
4. Click **Start Import**

### Dữ liệu được import:

- **Categories**: Tạo categories WordPress từ bảng `categories` của Laravel
- **Posts**: Tạo posts WordPress từ bảng `posts` của Laravel
- **SEO**: Import metadata SEO (title, description, canonical, robots) - hỗ trợ Yoast SEO và Rank Math
- **Menu**: Tạo menu WordPress với các menu items chính

### Lưu ý:

- Đảm bảo database Laravel có thể truy cập được từ server WordPress
- Posts sẽ được tạo với slug giống Laravel
- Featured images sẽ được download từ URL trong database Laravel
- SEO data sẽ được lưu vào post meta tương ứng với plugin SEO bạn đang sử dụng

## 2. Import Kênh YouTube từ CSV/Excel

### Cách sử dụng:

1. Vào **YouTube Channels > Import Channels** trong WordPress admin
2. Upload file CSV hoặc Excel với format:
   - **Lượng subscribers**: Số lượng subscribers (ví dụ: 18.000, 2.800.000)
   - **Link Kênh**: URL kênh YouTube (ví dụ: https://www.youtube.com/@channel)
   - **Chủ Đề**: Chủ đề/category của kênh (ví dụ: Âm nhạc, Game, Ô tô)
   - **Giá bán (VND)**: Giá bán kênh (ví dụ: 10.000.000)
   - **Tình trạng kênh**: Trạng thái kiếm tiền (Đã bật kiếm tiền / Chưa bật kiếm tiền)
   - **Mua hàng**: Trạng thái (optional)

3. Chọn options:
   - ✅ Update existing channels (matched by URL)
   - ✅ Skip duplicates if not updating

4. Click **Import Channels**

### Format CSV mẫu:

```csv
Lượng subscribers,Link Kênh,Chủ Đề,Giá bán (VND),Tình trạng kênh,Mua hàng
18.000,https://www.youtube.com/@ydyd1234/featured,Âm nhạc,10.000.000,Chưa bật kiếm tiền,
2.800.000,https://www.youtube.com/channel/UC-E1JQSSrC9G7P6MtCMtbpQ,Ô tô,222000000,Chưa bật kiếm tiền,
490.000,https://www.youtube.com/@tiemcuanang,Idol Kpop,180000000,Đã bật kiếm tiền,
```

### Tính năng:

- Tự động tạo title từ URL và chủ đề
- Tự động normalize subscriber count (loại bỏ dấu chấm, phẩy)
- Tự động normalize price (loại bỏ ký tự đặc biệt)
- Tự động detect monetization status
- Tự động tạo category nếu chưa có
- Hỗ trợ cả CSV và Excel (XLSX, XLS)

## 3. Table of Contents (TOC)

### Tính năng:

- Tự động tạo mục lục từ các heading (H2, H3, H4) trong bài viết
- Hiển thị dạng collapsible (có thể thu gọn/mở rộng)
- Smooth scroll khi click vào mục lục
- Tự động thêm ID cho các heading để anchor link hoạt động

### Cách sử dụng:

1. Khi viết bài, sử dụng các heading H2, H3, H4 để tạo cấu trúc
2. TOC sẽ tự động xuất hiện sau đoạn văn đầu tiên
3. Trong meta box "Table of Contents" khi edit post, bạn có thể:
   - Show Table of Contents (mặc định)
   - Hide Table of Contents

### Style:

TOC được style với:
- Background trắng, border và shadow
- Responsive design
- Hover effects
- Icon và numbering

## Cấu trúc Files

```
inc/
├── import/
│   ├── class-laravel-import.php          # Class import từ Laravel DB
│   ├── import-admin-page.php             # Admin page cho Laravel import
│   ├── class-youtube-channel-import.php  # Class import kênh YouTube
│   ├── channel-import-admin-page.php     # Admin page cho YouTube import
│   └── README.md                          # File này
└── table-of-contents.php                 # Chức năng TOC
```

## Requirements

- WordPress 5.0+
- PHP 7.4+
- MySQL/MariaDB
- ACF (Advanced Custom Fields) - cho YouTube channels
- Optional: Yoast SEO hoặc Rank Math - cho SEO import

## Troubleshooting

### Lỗi kết nối database:

- Kiểm tra thông tin database connection
- Đảm bảo database Laravel có thể truy cập từ server WordPress
- Kiểm tra firewall/security settings

### Lỗi import file:

- Đảm bảo file có format đúng
- Kiểm tra encoding (nên dùng UTF-8)
- Với Excel, có thể cần cài PhpSpreadsheet library

### TOC không hiển thị:

- Kiểm tra bài viết có heading (H2, H3, H4) không
- Kiểm tra meta box "Table of Contents" đã được set chưa
- Clear cache nếu đang dùng caching plugin
