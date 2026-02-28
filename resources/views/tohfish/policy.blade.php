@extends('layouts.app')

@section('title', 'Chính Sách - TOH fish')

@section('content')
<section class="policy-section py-5">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <h1 class="page-title text-center mb-5">Chính Sách Quy Định</h1>
            </div>
        </div>
        
        <div class="row">
            <div class="col-lg-10 mx-auto">
                <div class="policy-content">
                    <div class="policy-item bg-light p-4 rounded shadow-sm mb-4">
                        <h3><i class="fas fa-shipping-fast"></i> Chính Sách Vận Chuyển</h3>
                        <p>Chúng tôi giao hàng tận nơi trong phạm vi thành phố. Thời gian giao hàng từ 1-3 ngày làm việc tùy khu vực.</p>
                    </div>
                    
                    <div class="policy-item bg-light p-4 rounded shadow-sm mb-4">
                        <h3><i class="fas fa-undo"></i> Chính Sách Đổi Trả</h3>
                        <p>Khách hàng có quyền đổi trả sản phẩm trong vòng 24 giờ kể từ khi nhận hàng nếu sản phẩm không đúng như mô tả hoặc bị hỏng.</p>
                    </div>
                    
                    <div class="policy-item bg-light p-4 rounded shadow-sm mb-4">
                        <h3><i class="fas fa-shield-alt"></i> Chính Sách Bảo Hành</h3>
                        <p>Tất cả sản phẩm đều được đảm bảo chất lượng và an toàn thực phẩm. Sản phẩm có dấu hiệu không đảm bảo sẽ được đổi mới hoặc hoàn tiền.</p>
                    </div>
                    
                    <div class="policy-item bg-light p-4 rounded shadow-sm mb-4">
                        <h3><i class="fas fa-lock"></i> Chính Sách Thanh Toán</h3>
                        <p>Chúng tôi chấp nhận thanh toán khi nhận hàng (COD), chuyển khoản ngân hàng và các ví điện tử phổ biến.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

