@extends('layouts.app')

@section('title', 'Cách đặt hàng - TOH fish')

@section('content')
<section class="order-guide-section py-5">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <h1 class="page-title text-center mb-5">Hướng dẫn đặt hàng</h1>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-8 mx-auto">
                <div class="guide-steps">
                    <div class="step-item mb-4 p-4 bg-light rounded">
                        <div class="step-number">1</div>
                        <div class="step-content">
                            <h4>Chọn sản phẩm</h4>
                            <p>Duyệt qua các danh mục sản phẩm và chọn sản phẩm bạn muốn mua. Bạn có thể xem chi tiết từng sản phẩm để biết thêm thông tin.</p>
                        </div>
                    </div>
                    <div class="step-item mb-4 p-4 bg-light rounded">
                        <div class="step-number">2</div>
                        <div class="step-content">
                            <h4>Thêm vào giỏ hàng</h4>
                            <p>Nhấn nút "Thêm vào giỏ" để thêm sản phẩm vào giỏ hàng của bạn. Bạn có thể tiếp tục mua sắm hoặc thanh toán ngay.</p>
                        </div>
                    </div>
                    <div class="step-item mb-4 p-4 bg-light rounded">
                        <div class="step-number">3</div>
                        <div class="step-content">
                            <h4>Kiểm tra giỏ hàng</h4>
                            <p>Xem lại các sản phẩm đã chọn, số lượng và tổng tiền. Bạn có thể thay đổi số lượng hoặc xóa sản phẩm nếu cần.</p>
                        </div>
                    </div>
                    <div class="step-item mb-4 p-4 bg-light rounded">
                        <div class="step-number">4</div>
                        <div class="step-content">
                            <h4>Điền thông tin đặt hàng</h4>
                            <p>Nhập thông tin liên hệ, địa chỉ giao hàng và chọn phương thức thanh toán phù hợp.</p>
                        </div>
                    </div>
                    <div class="step-item mb-4 p-4 bg-light rounded">
                        <div class="step-number">5</div>
                        <div class="step-content">
                            <h4>Xác nhận đặt hàng</h4>
                            <p>Kiểm tra lại thông tin và xác nhận đặt hàng. Chúng tôi sẽ liên hệ với bạn trong thời gian sớm nhất để xác nhận đơn hàng.</p>
                        </div>
                    </div>
                </div>
                <div class="alert alert-info mt-4">
                    <h5><i class="fas fa-gift"></i> Khuyến mãi đặc biệt</h5>
                    <p class="mb-0">Giảm 3% khi đặt hàng qua WEB với mã <strong>TOH3</strong> (áp dụng cho đơn hàng từ 600k trở lên)</p>
                </div>
                <div class="alert alert-warning mt-3">
                    <h5><i class="fas fa-info-circle"></i> Lưu ý</h5>
                    <ul class="mb-0">
                        <li>Vui lòng kiểm tra kỹ thông tin trước khi xác nhận đặt hàng</li>
                        <li>Đơn hàng sẽ được xử lý trong giờ hành chính</li>
                        <li>Thời gian giao hàng: 1-3 ngày tùy khu vực</li>
                        <li>Liên hệ hotline (+84) 999 99 9999 nếu cần hỗ trợ</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</section>

<style>
.step-item {
    position: relative;
    display: flex;
    align-items: flex-start;
}

.step-number {
    width: 50px;
    height: 50px;
    background: var(--primary-color);
    color: white;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.5rem;
    font-weight: bold;
    flex-shrink: 0;
    margin-right: 20px;
}

.step-content h4 {
    color: var(--dark-color);
    margin-bottom: 10px;
}
</style>
@endsection

