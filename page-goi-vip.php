<?php
/**
 * Template Name: Ví Tiền
 */

get_header();

// Kiểm tra đăng nhập
if (!is_user_logged_in()) {
    wp_redirect(home_url('/wp-login.php'));
    exit;
}

// Lấy thông tin người dùng hiện tại
$current_user = wp_get_current_user();
$user_balance = get_user_meta($current_user->ID, '_user_balance', true);
$user_balance = floatval($user_balance);

// Lấy thông tin gói VIP hiện tại
$vip_data = get_user_meta($current_user->ID, 'vip_package', true);
$current_vip_status = array(
    'vip_3_months' => false,
    'vip_permanent' => false
);

if ($vip_data && $vip_data['is_active']) {
    if ($vip_data['package_type'] === 'vip_permanent') {
        $current_vip_status['vip_permanent'] = true;
    } else if ($vip_data['package_type'] === 'vip_3_months') {
        // Kiểm tra hết hạn cho gói 3 tháng
        $expiry_date = strtotime($vip_data['expiry_date']);
        $current_date = strtotime(current_time('mysql'));
        if ($current_date <= $expiry_date) {
            $current_vip_status['vip_3_months'] = true;
        }
    }
}

?>
<div class="page-goi-vip">
<div class="container">
    <div class="container py-5">
        <div class="row text-center mb-5">
            <div class="col">
                <h2 class="display-4 fw-bold">Chọn gói VIP</h2>
                <p class="lead">Số dư hiện tại: <span class="text-primary fw-bold"><?php echo number_format($user_balance); ?> kim tệ</span></p>
            </div>
        </div>
        <div class="row row-cols-1 row-cols-md-3 g-4">
            <!-- Pro Plan -->
            <div class="col">
                <div class="card h-100 pricing-card shadow position-relative">
                    <span class="badge gradient-custom text-white popular-badge px-4 py-2">Phổ biến</span>
                    <div class="card-body p-5">
                        <h5 class="card-title text-primary text-uppercase mb-4">VIP 2 THÁNG</h5>
                        <h1 class="display-5 mb-4">350,000 kim tệ<small class="text-muted fw-light">/2 THÁNG</small></h1>
                        <ul class="list-unstyled feature-list">
                            <li>Đọc truyện không giới hạn 2 tháng</li>
                            <li>Danh hiệu VIP tạm thời</li>
                        </ul>
                        <?php if ($current_vip_status['vip_3_months']): ?>
                            <button class="btn btn-secondary btn-lg w-100 mt-4" disabled>
                                <i class="bi bi-check-circle me-2"></i>Đã mua
                            </button>
                        <?php else: ?>
                            <button class="btn gradient-custom text-white btn-lg w-100 mt-4 buy-vip" data-package="vip_3_months">Mua ngay</button>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <!-- Enterprise Plan -->
            <div class="col">
                <div class="card h-100 pricing-card shadow-sm">
                    <div class="card-body p-5">
                        <h5 class="card-title text-muted text-uppercase mb-4">VIP VĨNH VIỄN</h5>
                        <h1 class="display-5 mb-4">800,000 kim tệ<small class="text-muted fw-light">/&#8734;</small></h1>
                        <ul class="list-unstyled feature-list">
                            <li>Bạn sẽ đứng đầu trong thế giới TuSacTruyen</li>
                            <li>Đọc truyện không giới hạn</li>
                            <li>Danh hiệu VIP siêu cấp vip pro</li>
                            <li>Khung hình đại diện thể hiện đẳng cấp tu tiên</li>
                        </ul>
                        <?php if ($current_vip_status['vip_permanent']): ?>
                            <button class="btn btn-secondary btn-lg w-100 mt-4" disabled>
                                <i class="bi bi-check-circle me-2"></i>Đã mua
                            </button>
                        <?php else: ?>
                            <button class="btn gradient-custom-vv btn-lg w-100 mt-4 buy-vip" data-package="vip_permanent">Mua ngay</button>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</div>

<script>
jQuery(document).ready(function($) {
    $('.buy-vip').on('click', function() {
        const packageType = $(this).data('package');
        const button = $(this);
        
        // Disable button và hiển thị loading
        button.prop('disabled', true).html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Đang xử lý...');
        
        $.ajax({
            url: '<?php echo admin_url('admin-ajax.php'); ?>',
            type: 'POST',
            data: {
                action: 'process_vip_purchase',
                package_type: packageType
            },
            success: function(response) {
                if (response.success) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Thành công!',
                        text: response.data.message,
                        confirmButtonText: 'OK',
                        confirmButtonColor: '#3085d6'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            // Cập nhật số dư hiển thị
                            $('.lead .text-primary').text(response.data.new_balance.toLocaleString() + ' kim tệ');
                            // Cập nhật trạng thái nút
                            button.replaceWith('<button class="btn btn-secondary btn-lg w-100 mt-4" disabled><i class="bi bi-check-circle me-2"></i>Đã mua</button>');
                        }
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Lỗi!',
                        text: response.data,
                        confirmButtonText: 'OK',
                        confirmButtonColor: '#d33'
                    });
                }
            },
            error: function() {
                Swal.fire({
                    icon: 'error',
                    title: 'Lỗi!',
                    text: 'Có lỗi xảy ra, vui lòng thử lại sau',
                    confirmButtonText: 'OK',
                    confirmButtonColor: '#d33'
                });
            },
            complete: function() {
                // Reset button về trạng thái ban đầu nếu có lỗi
                if (!button.prop('disabled')) {
                    button.text('Mua ngay');
                }
            }
        });
    });
});
</script>

<?php get_footer(); ?> 