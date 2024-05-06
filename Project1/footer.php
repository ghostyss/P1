
<footer id="footer" class="bg-color-light border-0 pt-5 mt-0">
    <div class="container pb-5 mb-5">
        <div class="row justify-content-center">
            <div class="col-md-9 col-lg-6">
                <h2 class="font-weight-normal text-color-dark text-center text-8 mb-4"><strong class="font-weight-extra-bold">Contact</strong> Us</h2>
                <p class="text-4 opacity-8 text-center mb-4">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Phasellus blandit massa enim. Nullam id varius nunc. Vivamus bibendum</p>
                <form class="contact-form form-style-3" action="php/contact-form.php" method="POST">
                    <div class="contact-form-success alert alert-success d-none">
                        Message has been sent to us.
                    </div>

                    <div class="contact-form-error alert alert-danger d-none">
                        Error sending your message.
                        <span class="mail-error-message text-1 d-block"></span>
                    </div>

                    <input type="hidden" value="Contact Form" name="subject" id="subject">
                    <div class="form-row">
                        <div class="form-group col-md-6 pr-md-2">
                            <input type="text" value="" data-msg-required="Please enter your name." maxlength="100" class="form-control" placeholder="Your Name..." name="name" id="name" required>
                        </div>
                        <div class="form-group col-md-6 pl-md-2">
                            <input type="text" value="" data-msg-required="Please enter your phone." maxlength="100" class="form-control" placeholder="Your Phone..." name="phone" id="phone" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <input type="email" value="" data-msg-required="Please enter your email address." data-msg-email="Please enter a valid email address." maxlength="100" class="form-control" placeholder="Your Email Address..." name="email" id="email" required>
                    </div>
                    <div class="form-row">
                        <div class="form-group col">
                            <textarea maxlength="5000" data-msg-required="Please enter your message." rows="4" class="form-control" placeholder="Your Message..." name="message" id="message" required></textarea>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group col text-center">
                            <input type="submit" value="SUBMIT" class="btn btn-primary font-weight-semibold text-3 px-5 btn-py-2" data-loading-text="Loading...">
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="footer-copyright curved-border curved-border-top d-flex align-items-center">
        <div class="container py-2">
            <div class="row py-4">
                <div class="col text-center">
                    <p class="text-3">2020 © <strong class="font-weight-normal text-color-light opacity-7">Porto Template</strong> - Copyright. All Rights Reserved.</p>
                </div>
            </div>
        </div>
    </div>
</footer>
</div>

<!-- Vendor -->
<script src="vendor/jquery/jquery.min.js"></script>
<script src="vendor/jquery.appear/jquery.appear.min.js"></script>
<script src="vendor/jquery.easing/jquery.easing.min.js"></script>
<script src="vendor/jquery.cookie/jquery.cookie.min.js"></script>
<script src="vendor/popper/umd/popper.min.js"></script>
<script src="vendor/bootstrap/js/bootstrap.min.js"></script>
<script src="vendor/common/common.min.js"></script>
<script src="vendor/jquery.validation/jquery.validate.min.js"></script>
<script src="vendor/jquery.easy-pie-chart/jquery.easypiechart.min.js"></script>
<script src="vendor/jquery.gmap/jquery.gmap.min.js"></script>
<script src="vendor/jquery.lazyload/jquery.lazyload.min.js"></script>
<script src="vendor/isotope/jquery.isotope.min.js"></script>
<script src="vendor/owl.carousel/owl.carousel.min.js"></script>
<script src="vendor/magnific-popup/jquery.magnific-popup.min.js"></script>
<script src="vendor/vide/jquery.vide.min.js"></script>
<script src="vendor/vivus/vivus.min.js"></script>
<script src="js/theme.js"></script>
<script src="vendor/rs-plugin/js/jquery.themepunch.tools.min.js"></script>
<script src="vendor/rs-plugin/js/jquery.themepunch.revolution.min.js"></script>
<script src="js/views/view.contact.js"></script>
<script src="js/custom.js"></script>
<script src="js/theme.init.js"></script>

<!-- Google Analytics: Change UA-XXXXX-X to be your site's ID. Go to http://www.google.com/analytics/ for more information.
<script>
        (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
        (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
        m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
        })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

        ga('create', 'UA-12345678-1', 'auto');
        ga('send', 'pageview');
</script>
-->

</body>
</html>
