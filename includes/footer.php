<footer class="footer">
    <div class="footer-top py-5">
        <div class="container">
            <div class="row g-4">
                <div class="col-lg-3 col-md-6">
                    <div class="footer-widget">
                        <h4 class="widget-title">Về Sport Elite</h4>
                        <div class="footer-logo mb-3">
                            <span class="gradient-text">Sport</span><strong>Elite</strong>
                        </div>
                        <p class="mb-4">Chuyên cung cấp các sản phẩm thể thao chính hãng với chất lượng cao nhất. Đảm bảo uy tín và dịch vụ tốt nhất cho khách hàng.</p>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6">
                    <div class="footer-widget">
                        <h4 class="widget-title">Thông Tin Hữu Ích</h4>
                        <ul class="footer-links">
                            <li><a href="about.php"><i class="bi bi-chevron-right"></i> Về chúng tôi</a></li>
                            <li><a href="shipping.php"><i class="bi bi-chevron-right"></i> Chính sách vận chuyển</a></li>
                            <li><a href="returns.php"><i class="bi bi-chevron-right"></i> Chính sách đổi trả</a></li>
                            <li><a href="warranty.php"><i class="bi bi-chevron-right"></i> Chính sách bảo hành</a></li>
                            <li><a href="privacy.php"><i class="bi bi-chevron-right"></i> Chính sách bảo mật</a></li>
                            <li><a href="terms.php"><i class="bi bi-chevron-right"></i> Điều khoản sử dụng</a></li>
                        </ul>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6">
                    <div class="footer-widget">
                        <h4 class="widget-title">Danh Mục Sản Phẩm</h4>
                        <ul class="footer-links">
                            <li><a href="category.php?id=1"><i class="bi bi-chevron-right"></i> Thiết bị tập gym</a></li>
                            <li><a href="category.php?id=3"><i class="bi bi-chevron-right"></i> Thể thao đồng đội</a></li>
                            <li><a href="category.php?id=4"><i class="bi bi-chevron-right"></i> Phụ kiện thể thao</a></li>
                        </ul>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6">
                    <div class="footer-widget">
                        <h4 class="widget-title">Thông Tin Liên Hệ</h4>
                        <div class="contact-info">
                            <div class="contact-item mb-3">
                                <i class="bi bi-geo-alt-fill"></i>
                                <div class="contact-text">
                                    <h5>Địa chỉ:</h5>
                                    <p>123 Nguyễn Văn A, Phường X, Quận Y, TP.HCM</p>
                                </div>
                            </div>
                            <div class="contact-item mb-3">
                                <i class="bi bi-telephone-fill"></i>
                                <div class="contact-text">
                                    <h5>Hotline:</h5>
                                    <p>0123.456.789 - 0987.654.321</p>
                                </div>
                            </div>
                            <div class="contact-item mb-3">
                                <i class="bi bi-envelope-fill"></i>
                                <div class="contact-text">
                                    <h5>Email:</h5>
                                    <p>contact@sportelite.com</p>
                                </div>
                            </div>
                            <div class="contact-item">
                                <i class="bi bi-clock-fill"></i>
                                <div class="contact-text">
                                    <h5>Giờ làm việc:</h5>
                                    <p>08:00 - 21:00 (Thứ 2 - Chủ nhật)</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="footer-bottom py-3">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-md-12">
                    <p class="mb-md-0 text-center text-md-start">© 2023 Sport Elite. All rights reserved.</p>
                </div>
            </div>
        </div>
    </div>
</footer>

<button id="back-to-top" class="btn btn-primary back-to-top">
    <i class="bi bi-arrow-up"></i>
</button>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="/WebbandoTT/app/public/js/main.js"></script>

<script>
    window.addEventListener('load', function() {
        document.querySelector('.page-loader').style.display = 'none';
    });
    window.addEventListener('scroll', function() {
        if (window.scrollY > 50) {
            document.querySelector('.navbar').classList.add('scrolled');
        } else {
            document.querySelector('.navbar').classList.remove('scrolled');
        }
    });
</script>
</body>
</html>
