# TOHFISH – Website bán sản phẩm cá và đặc sản TOH

TOHFISH là website thương mại điện tử giúp người dùng đặt mua các sản phẩm cá, hải sản và đặc sản TOH một cách nhanh chóng, tiện lợi.  
Dự án được xây dựng với Laravel 9, tập trung vào trải nghiệm mua hàng đơn giản, rõ ràng và tích hợp thanh toán online.

---

## Giới thiệu

Người dùng có thể:
- Xem danh sách sản phẩm theo từng nhóm (Sơ chế, Chế biến, Chế biến sẵn, Bún cá TOH, Rau gia vị, Khuyến mãi…)
- Tìm kiếm, lọc theo loại sản phẩm và khoảng giá
- Thêm sản phẩm vào giỏ hàng, chỉnh sửa số lượng, mua ngay
- Lưu sản phẩm yêu thích
- Đặt hàng, theo dõi lịch sử mua hàng, hủy hoặc mua lại đơn

Quản trị viên có thể:
- Quản lý sản phẩm, bài viết blog, slider, danh sách đơn hàng
- Cập nhật trạng thái đơn, xác nhận thanh toán
- Quản lý tin tuyển dụng và CV ứng viên

---

## Tính năng chính

### Front-end
- Giao diện bán hàng, trang chi tiết sản phẩm, giỏ hàng, thanh toán, blog, tuyển dụng.
- Layout responsive, tối ưu trải nghiệm đặt hàng trên desktop và mobile.
- Sử dụng AJAX để thêm/xóa/cập nhật giỏ hàng và danh sách yêu thích mà không cần tải lại trang.

### Back-end
- Kiến trúc MVC với Laravel, tách lớp Service để xử lý nghiệp vụ (TohfishService, CartService, PostService…).
- Luồng đặt hàng đầy đủ: giỏ hàng ➝ thanh toán ➝ tạo đơn ➝ lịch sử mua hàng ➝ hủy / mua lại đơn.
- Tích hợp thanh toán ngân hàng/VietQR và MoMo:
  - Tạo `PaymentTransaction`, lưu trạng thái thanh toán.
  - Webhook/IPN và job queue để kiểm tra kết quả thanh toán.
- Khu vực quản trị:
  - CRUD sản phẩm, bài viết, slider.
  - Quản lý đơn hàng (chi tiết, cập nhật trạng thái, xác nhận thanh toán).
  - Quản lý tin tuyển dụng, vị trí và CV ứng viên.

---

## Công nghệ sử dụng

- **Back-end**: PHP 8+, Laravel 9, Laravel Sanctum, Eloquent ORM  
- **Front-end**: Blade Template, JavaScript, AJAX, Vite, Axios  
- **Cơ sở dữ liệu**: MySQL  
- **Khác**: Queue/Job (đối soát thanh toán), logging (Monolog/Log), Storage & ImageService cho xử lý ảnh

---

## Cài đặt & chạy dự án

### 1. Yêu cầu môi trường

- PHP 8.0.2 trở lên  
- Composer  
- MySQL  
- Node.js & npm (hoặc yarn)  

### 2. Clone & cài đặt

```bash
git clone https://github.com/<your-username>/Tohfish.git
cd Tohfish

# Cài đặt PHP dependencies
composer install

# Cài đặt front-end dependencies
npm install
```

### 3. Cấu hình môi trường

```bash
cp .env.example .env
php artisan key:generate
```

- Cập nhật thông tin database, thông tin thanh toán (nếu dùng) trong file `.env`.
- Tạo database và chạy migration + seed (nếu có):

```bash
php artisan migrate --seed
```

### 4. Chạy dự án

Chạy server Laravel:

```bash
php artisan serve
```

Chạy dev server front-end:

```bash
npm run dev
```

Truy cập ứng dụng tại: `http://localhost:8000`

---

## Đóng góp

- Issues, pull request hoặc ý tưởng cải thiện giao diện, trải nghiệm mua hàng và tích hợp thanh toán luôn được hoan nghênh.

---

## Tác giả

- TOHFISH – Dự án cá nhân, phát triển nhằm luyện tập Laravel, xử lý thanh toán online và xây dựng hệ thống bán hàng hoàn chỉnh.

<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

<p align="center">
<a href="https://github.com/laravel/framework/actions"><img src="https://github.com/laravel/framework/workflows/tests/badge.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

## About Laravel

Laravel is a web application framework with expressive, elegant syntax. We believe development must be an enjoyable and creative experience to be truly fulfilling. Laravel takes the pain out of development by easing common tasks used in many web projects, such as:

- [Simple, fast routing engine](https://laravel.com/docs/routing).
- [Powerful dependency injection container](https://laravel.com/docs/container).
- Multiple back-ends for [session](https://laravel.com/docs/session) and [cache](https://laravel.com/docs/cache) storage.
- Expressive, intuitive [database ORM](https://laravel.com/docs/eloquent).
- Database agnostic [schema migrations](https://laravel.com/docs/migrations).
- [Robust background job processing](https://laravel.com/docs/queues).
- [Real-time event broadcasting](https://laravel.com/docs/broadcasting).

Laravel is accessible, powerful, and provides tools required for large, robust applications.

## Learning Laravel

Laravel has the most extensive and thorough [documentation](https://laravel.com/docs) and video tutorial library of all modern web application frameworks, making it a breeze to get started with the framework.

You may also try the [Laravel Bootcamp](https://bootcamp.laravel.com), where you will be guided through building a modern Laravel application from scratch.

If you don't feel like reading, [Laracasts](https://laracasts.com) can help. Laracasts contains over 2000 video tutorials on a range of topics including Laravel, modern PHP, unit testing, and JavaScript. Boost your skills by digging into our comprehensive video library.

## Laravel Sponsors

We would like to extend our thanks to the following sponsors for funding Laravel development. If you are interested in becoming a sponsor, please visit the Laravel [Patreon page](https://patreon.com/taylorotwell).

### Premium Partners

- **[Vehikl](https://vehikl.com/)**
- **[Tighten Co.](https://tighten.co)**
- **[Kirschbaum Development Group](https://kirschbaumdevelopment.com)**
- **[64 Robots](https://64robots.com)**
- **[Cubet Techno Labs](https://cubettech.com)**
- **[Cyber-Duck](https://cyber-duck.co.uk)**
- **[Many](https://www.many.co.uk)**
- **[Webdock, Fast VPS Hosting](https://www.webdock.io/en)**
- **[DevSquad](https://devsquad.com)**
- **[Curotec](https://www.curotec.com/services/technologies/laravel/)**
- **[OP.GG](https://op.gg)**
- **[WebReinvent](https://webreinvent.com/?utm_source=laravel&utm_medium=github&utm_campaign=patreon-sponsors)**
- **[Lendio](https://lendio.com)**

## Contributing

Thank you for considering contributing to the Laravel framework! The contribution guide can be found in the [Laravel documentation](https://laravel.com/docs/contributions).

## Code of Conduct

In order to ensure that the Laravel community is welcoming to all, please review and abide by the [Code of Conduct](https://laravel.com/docs/contributions#code-of-conduct).

## Security Vulnerabilities

If you discover a security vulnerability within Laravel, please send an e-mail to Taylor Otwell via [taylor@laravel.com](mailto:taylor@laravel.com). All security vulnerabilities will be promptly addressed.

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
