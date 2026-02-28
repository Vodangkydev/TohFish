@extends('layouts.app')

@section('title', 'Giới thiệu - TOH fish')

@section('content')
<section class="about-section py-5">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <h1 class="page-title text-center mb-5">Giới thiệu về TOH fish</h1>
            </div>
        </div>
        <div class="row align-items-center mb-5">
            <div class="col-lg-6">
                <img src="{{ url('images/home/img_3426_grande.jpg') }}" alt="Về TOH fish" class="img-fluid rounded">
            </div>
            <div class="col-lg-6">
                <h2>Công ty CP TOH fish</h2>
                <p><strong>Nhà cung cấp sỉ và lẻ cá sạch chất lượng cao</strong></p>
                <p>Số ĐKKD: 3603371810 do Sở KHĐT T. Đồng Nai cấp ngày 19/04/2016</p>
                <p>Người đại diện: Nguyễn Văn A</p>
                <p>TOH fish chuyên cung cấp các sản phẩm cá sạch, đặc biệt là cá lóc bông từ chăn nuôi, đảm bảo chất lượng và an toàn vệ sinh thực phẩm.</p>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-4 mb-4">
                <div class="info-box text-center p-4 bg-light rounded">
                    <i class="fas fa-fish fa-3x text-primary mb-3"></i>
                    <h4>Sản Phẩm Chất Lượng</h4>
                    <p>Cam kết cung cấp cá tươi sạch, đảm bảo chất lượng tốt nhất</p>
                </div>
            </div>
            <div class="col-lg-4 mb-4">
                <div class="info-box text-center p-4 bg-light rounded">
                    <i class="fas fa-shipping-fast fa-3x text-primary mb-3"></i>
                    <h4>Giao Hàng Nhanh</h4>
                    <p>Giao hàng tận nơi, đảm bảo sản phẩm tươi ngon đến tay khách hàng</p>
                </div>
            </div>
            <div class="col-lg-4 mb-4">
                <div class="info-box text-center p-4 bg-light rounded">
                    <i class="fas fa-certificate fa-3x text-primary mb-3"></i>
                    <h4>Chứng Nhận An Toàn</h4>
                    <p>Đạt các tiêu chuẩn về an toàn vệ sinh thực phẩm</p>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

