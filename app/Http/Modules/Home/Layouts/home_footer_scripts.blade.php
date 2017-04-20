{{--<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/2.1.4/jquery.min.js"></script>--}}
{{--<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>--}}
{{--<script src="/assets/home/js/jquery-ui.1.11.3.min.js"></script>--}}
{{--<script src="/assets/home/js/jquery.min.js"></script>--}}
{{--<script src="/assets/global/plugins/jquery-validation/js/jquery.validate.min.js"></script>--}}
{{--<script type="text/javascript" src="/assets/home/js/main.js"></script>--}}
{{--<script src="/assets/plugins/toastr/toastr.min.js"></script>--}}
{{--<script src="/assets/js/custom.js"></script>--}}
{{--<script>--}}
{{--$(document).ready(function () {--}}
{{--$('#login-trigger').click(function () {--}}
{{--$(this).next('#login-content').slideToggle();--}}
{{--$(this).toggleClass('active');--}}

{{--if ($(this).hasClass('active')) $(this).find('span').html('&#x25B2;')--}}
{{--else $(this).find('span').html('&#x25BC;')--}}
{{--});--}}

{{--$('#notification-trigger').click(function (event) {--}}
{{--event.stopPropagation();--}}
{{--$(this).next('#notification-content').slideToggle();--}}
{{--$('#country-trigger').removeClass("active");--}}
{{--$('#account-trigger').removeClass("active");--}}
{{--$(this).toggleClass('active');--}}

{{--$('#account-trigger').next('#account-content').slideUp();--}}
{{--$('#country-trigger').next('#country-content').slideUp();--}}
{{--});--}}

{{--$('#account-trigger').click(function (event) {--}}
{{--event.stopPropagation();--}}
{{--$(this).next('#account-content').slideToggle();--}}
{{--$('#notification-trigger').removeClass("active");--}}
{{--$('#country-trigger').removeClass("active");--}}
{{--$(this).toggleClass('active');--}}
{{--$('#country-trigger').next('#country-content').slideUp();--}}
{{--$('#notification-trigger').next('#notification-content').slideUp();--}}
{{--});--}}

{{--$('#country-trigger').click(function (event) {--}}
{{--event.stopPropagation();--}}
{{--$(this).next('#country-content').slideToggle();--}}
{{--$(this).toggleClass('active');--}}
{{--$('#notification-trigger').removeClass("active");--}}
{{--$('#account-trigger').removeClass("active");--}}
{{--$('#account-trigger').next('#account-content').slideUp();--}}
{{--$('#notification-trigger').next('#notification-content').slideUp();--}}
{{--});--}}

{{--$(document.body).click(function () {--}}

{{--$('#notification-trigger').removeClass("active");--}}
{{--$('#account-trigger').removeClass("active");--}}
{{--$('#country-trigger').removeClass("active");--}}

{{--$('#account-trigger').next('#account-content').slideUp();--}}
{{--$('#notification-trigger').next('#notification-content').slideUp();--}}
{{--$('#country-trigger').next('#country-content').slideUp();--}}

{{--});--}}

{{--});--}}
{{--</script>--}}
{{--<script type="text/javascript">--}}
{{--$(document).ready(function () {--}}
{{--$(".dropdown").hover(--}}
{{--function () {--}}
{{--$('.dropdown-menu', this).not('.in .dropdown-menu').stop(true, true).slideDown("fast");--}}
{{--$(this).toggleClass('open');--}}
{{--},--}}
{{--function () {--}}
{{--$('.dropdown-menu', this).not('.in .dropdown-menu').stop(true, true).slideUp("fast");--}}
{{--$(this).toggleClass('open');--}}
{{--}--}}
{{--);--}}
{{--});--}}

{{--function DropDown(el) {--}}
{{--this.dd = el;--}}
{{--this.placeholder = this.dd.children('span');--}}
{{--this.opts = this.dd.find('ul.dropdown > li');--}}
{{--this.val = '';--}}
{{--this.index = -1;--}}
{{--this.initEvents();--}}
{{--}--}}
{{--DropDown.prototype = {--}}
{{--initEvents : function() {--}}
{{--var obj = this;--}}
{{--obj.dd.on('click', function(event){--}}
{{--$(this).toggleClass('active');--}}
{{--return false;--}}
{{--});--}}
{{--obj.opts.on('click',function(e){--}}
{{--var thisid = $(this).data("id");--}}

{{--if(thisid == "df"){--}}
{{--e.stopPropagation();--}}
{{--} else {--}}
{{--var opt = $(this);--}}
{{--obj.val = opt.text();--}}
{{--obj.index = opt.index();--}}
{{--obj.placeholder.text(obj.val);--}}
{{--}--}}
{{--});--}}
{{--},--}}
{{--getValue : function() {--}}
{{--return this.val;--}}
{{--},--}}
{{--getIndex : function() {--}}
{{--return this.index;--}}
{{--}--}}
{{--}--}}

{{--$(function() {--}}

{{--var dd = new DropDown( $('#dd') );--}}

{{--$(document).click(function() {--}}
{{--// all dropdowns--}}
{{--$('.wrapper-dropdown-3').removeClass('active');--}}
{{--});--}}

{{--});--}}

{{--$(function() {--}}

{{--var de = new DropDown( $('#de') );--}}

{{--$(document).click(function() {--}}
{{--// all dropdowns--}}
{{--$('.wrapper-dropdown-3').removeClass('active');--}}
{{--});--}}

{{--});--}}

{{--$(function() {--}}

{{--var df = new DropDown( $('#df') );--}}

{{--$(document).click(function() {--}}
{{--// all dropdowns--}}
{{--$('.wrapper-dropdown-3').removeClass('active');--}}
{{--});--}}

{{--});--}}
{{--</script>--}}


<!-- NEW RAJESH DESIGN START -->
<!-- JS -->

<!-- jquery js -->
<script src="/assets/home/js/vendor/jquery-1.11.2.min.js"></script>

<!-- bootstrap js -->
<script src="/assets/home/js/bootstrap.min.js"></script>

<script src="/assets/global/plugins/jquery-validation/js/jquery.validate.min.js"></script>

<!-- owl.carousel.min js -->
<script src="/assets/home/js/owl.carousel.min.js"></script>

<!-- meanmenu js -->
<script src="/assets/home/js/jquery.meanmenu.js"></script>

<!-- jquery.countdown js -->
<script src="/assets/home/js/jquery.countdown.min.js"></script>

<!-- parallax js -->
<script src="/assets/home/js/parallax.js"></script>

<!-- jquery.collapse js -->
<script src="/assets/home/js/jquery.collapse.js"></script>

<!-- jquery.easing js -->
<script src="/assets/home/js/jquery.easing.1.3.min.js"></script>

<!-- jquery.scrollUp js -->
<script src="/assets/home/js/jquery.scrollUp.min.js"></script>

<!-- knob circle js -->
<script src="/assets/home/js/jquery.knob.js"></script>

<!-- jquery.appear js -->
<script src="/assets/home/js/jquery.appear.js"></script>

<!-- jquery.fancybox.pack js -->
<script src="/assets/home/js/fancybox/jquery.fancybox.pack.js"></script>

<!-- jquery.counterup js -->
<script src="/assets/home/js/jquery.counterup.min.js"></script>
<script src="/assets/home/js/waypoints.min.js"></script>

<!-- slider js -->
<script src="/assets/home/js/nivo-slider.js"></script>

<!-- wow js -->
<script src="/assets/home/js/wow.js"></script>
<script>
    new WOW().init();
</script>
<script>
    //    var vid = document.getElementById("bg");
    //    vid.loop = true;
    //    vid.muted = true
</script>

<!-- plugins js -->
<script src="/assets/home/js/plugins.js"></script>

<!-- main js -->
<script src="/assets/home/js/main.js"></script>

<!-- NEW RAJESH DESIGN END -->

<script src="/assets/js/custom.js"></script>
