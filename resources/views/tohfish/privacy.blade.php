@extends('layouts.app')

@section('title', 'Bảo Mật Thông Tin - TOH fish')

@section('content')
<section class="privacy-section py-5">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <h1 class="page-title text-center mb-5">Chính Sách Bảo Mật Thông Tin</h1>
            </div>
        </div>
        
        <div class="row">
            <div class="col-lg-10 mx-auto">
                <div class="privacy-content">
                    <div class="privacy-item bg-light p-4 rounded shadow-sm mb-4">
                        <h3><i class="fas fa-user-shield"></i> Bảo Vệ Thông Tin Cá Nhân</h3>
                        <p>TOH fish cam kết bảo vệ thông tin cá nhân của khách hàng. Chúng tôi chỉ sử dụng thông tin để phục vụ đơn hàng và không chia sẻ với bên thứ ba.</p>
                    </div>
                    
                    <div class="privacy-item bg-light p-4 rounded shadow-sm mb-4">
                        <h3><i class="fas fa-database"></i> Thu Thập Thông Tin</h3>
                        <p>Chúng tôi chỉ thu thập thông tin cần thiết như: Họ tên, địa chỉ, số điện thoại, email để phục vụ giao hàng và liên hệ.</p>
                    </div>
                    
                    <div class="privacy-item bg-light p-4 rounded shadow-sm mb-4">
                        <h3><i class="fas fa-cookie"></i> Cookies</h3>
                        <p>Website sử dụng cookies để cải thiện trải nghiệm người dùng. Bạn có thể tắt cookies trong cài đặt trình duyệt.</p>
                    </div>
                    
                    <div class="privacy-item bg-light p-4 rounded shadow-sm mb-4">
                        <h3><i class="fas fa-envelope"></i> Email Marketing</h3>
                        <p>Chúng tôi có thể gửi email về sản phẩm và khuyến mãi. Bạn có thể hủy đăng ký bất cứ lúc nào.</p>
                    </div>
                    
                    <div class="contact-info mt-4 p-4 bg-primary text-white rounded">
                        <h4 class="mb-3">Liên Hệ Về Bảo Mật</h4>
                        <p><i class="fas fa-envelope"></i> Email: contact@tohfish.com</p>
                        <p><i class="fas fa-phone"></i> Hotline: (+84) 999 99 9999</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

