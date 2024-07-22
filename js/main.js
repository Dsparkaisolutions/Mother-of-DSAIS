(function ($) {
    "use strict";

    // Spinner
    var spinner = function () {
        setTimeout(function () {
            if ($('#spinner').length > 0) {
                $('#spinner').removeClass('show');
            }
        }, 1);
    };
    spinner();
    
    
    // Initiate the wowjs
    new WOW().init();
    
    
   // Back to top button
   $(document).ready(function() {
    $(window).scroll(function () {
        if ($(this).scrollTop() > 300) {
            $('.back-to-top').stop(true, true).fadeIn(7000); // Adjusted speed
        } else {
            $('.back-to-top').stop(true, true).fadeOut(2000); // Adjusted speed
        }
    });

    $('.back-to-top').click(function () {
        $('html, body').animate({scrollTop: 0}, 1000); // Adjusted speed
        return false;
    });
});



    // Team carousel
    $(".team-carousel").owlCarousel({
        autoplay: true,
        smartSpeed: 1000,
        center: false,
        dots: false,
        loop: true,
        margin: 50,
        nav : true,
        navText : [
            '<i class="bi bi-arrow-left"></i>',
            '<i class="bi bi-arrow-right"></i>'
        ],
        responsiveClass: true,
        responsive: {
            0:{
                items:1
            },
            768:{
                items:2
            },
            992:{
                items:3
            }
        }
    });


    // Testimonial carousel

    $(".testimonial-carousel").owlCarousel({
        autoplay: true,
        smartSpeed: 1500,
        center: true,
        dots: true,
        loop: true,
        margin: 0,
        nav : true,
        navText: false,
        responsiveClass: true,
        responsive: {
            0:{
                items:1
            },
            576:{
                items:1
            },
            768:{
                items:2
            },
            992:{
                items:3
            }
        }
    });


     // Fact Counter

     $(document).ready(function(){
        $('.counter-value').each(function(){
            $(this).prop('Counter',0).animate({
                Counter: $(this).text()
            },{
                duration: 2000,
                easing: 'easeInQuad',
                step: function (now){
                    $(this).text(Math.ceil(now));
                }
            });
        });
    });



})(jQuery);

// age validation

function validateAge() {
    var dobInput = document.getElementById('dob').value;
    var dob = new Date(dobInput);
    var today = new Date();
    var age = today.getFullYear() - dob.getFullYear();
    var m = today.getMonth() - dob.getMonth();
    
    if (m < 0 || (m === 0 && today.getDate() < dob.getDate())) {
        age--;
    }
    
    if (age < 18) {
        Swal.fire({
            icon: 'error',
            title: 'Oops...',
            text: 'Minimum age requirement is 18 years.'
        });
        return false;
    }
    
    return true;
}


//capcha validation
document.addEventListener('DOMContentLoaded', function() {
    const captchaSpan = document.getElementById('captcha');
    const refreshCaptchaButton = document.getElementById('refresh-captcha');
    const captchaInput = document.getElementById('captcha-input');
    const submitButton = document.getElementById('submit-btn');
    let captchaText = '';

    function generateCaptcha() {
        const characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
        let result = '';
        for (let i = 0; i < 6; i++) {
            result += characters.charAt(Math.floor(Math.random() * characters.length));
        }
        captchaText = result;
        captchaSpan.textContent = captchaText;
    }

    refreshCaptchaButton.addEventListener('click', generateCaptcha);
    generateCaptcha();

    submitButton.addEventListener('click', function(event) {
        if (captchaInput.value !== captchaText) {
            event.preventDefault();
            alert('Captcha verification failed. Please try again.');
            generateCaptcha();
        }
    });
});
