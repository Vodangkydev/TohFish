@extends('layouts.app')

@section('title', 'Liên hệ - TOH fish')

@section('content')
<section class="contact-section py-5">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <h1 class="page-title text-center mb-5">Liên hệ với chúng tôi</h1>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-6 mb-4">
                <div class="contact-info h-100 p-4 bg-light rounded">
                    <h3 class="mb-4">Thông tin liên hệ</h3>
                    <div class="contact-item mb-3">
                        <i class="fas fa-map-marker-alt text-primary me-3"></i>
                        <div>
                            <strong>Địa chỉ:</strong>
                            <p class="mb-0">Số 32, đường 104, ấp 2, xã Phú Ngọc, Huyện Định Quán, Đồng Nai</p>
                        </div>
                    </div>
                    <div class="contact-item mb-3">
                        <i class="fas fa-phone text-primary me-3"></i>
                        <div>
                            <strong>Hotline:</strong>
                            <p class="mb-0">(+84) 999 99 9999</p>
                            <p class="mb-0">Viber/Zalo/Skype/whatsapp</p>
                        </div>
                    </div>
                    <div class="contact-item mb-3">
                        <i class="fas fa-envelope text-primary me-3"></i>
                        <div>
                            <strong>Email:</strong>
                            <p class="mb-0">contact@tohfish.com</p>
                        </div>
                    </div>
                    <div class="contact-item mb-3">
                        <i class="fas fa-globe text-primary me-3"></i>
                        <div>
                            <strong>Importers:</strong>
                            <p class="mb-0">Phone/viber/zalo/whatsapp: (+65) 90050704 / (+84) 909 454 881</p>
                        </div>
                    </div>
                    <div class="mt-4">
                        <a href="https://www.facebook.com/tohfish" class="btn btn-primary me-2" target="_blank">
                            <i class="fab fa-facebook"></i> Facebook
                        </a>
                        <a href="tel:(+84) 999 99 9999" class="btn btn-success">
                            <i class="fas fa-phone"></i> Gọi ngay
                        </a>
                    </div>
                </div>
            </div>
            <div class="col-lg-6 mb-4">
                <div class="contact-form p-4 bg-light rounded">
                    <h3 class="mb-4">Gửi tin nhắn</h3>
                    <form>
                        <div class="mb-3">
                            <label for="name" class="form-label">Họ và tên *</label>
                            <input type="text" class="form-control" id="name" required>
                        </div>
                        <div class="mb-3">
                            <label for="email" class="form-label">Email *</label>
                            <input type="email" class="form-control" id="email" required>
                        </div>
                        <div class="mb-3">
                            <label for="phone" class="form-label">Số điện thoại *</label>
                            <input type="tel" class="form-control" id="phone" required>
                        </div>
                        <div class="mb-3">
                            <label for="message" class="form-label">Nội dung *</label>
                            <textarea class="form-control" id="message" rows="5" required></textarea>
                        </div>
                        <button type="submit" class="btn btn-primary">Gửi tin nhắn</button>
                    </form>
                </div>
            </div>
        </div>
        <div class="row mt-5">
            <div class="col-12">
                <div class="map-container">
                    <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3914.3970130670964!2d107.28014681481145!3d11.158212292063135!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3174f57b3b78bfc9%3A0xcff092350d8920db!2zQ8O0bmcgdHkgQ-G7lSBwaOG6p24gVE9IIGZpc2g!5e0!3m2!1sen!2s!4v1496551203208" 
                            width="100%" 
                            height="450" 
                            style="border:0;" 
                            allowfullscreen="" 
                            loading="lazy">
                    </iframe>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

