
        // Initialize AOS
        AOS.init({
            duration: 1000,
            once: true,
            offset: 100
        });
        
        $(document).ready(function() {
            // Back to top button
            $(window).scroll(function() {
                if ($(this).scrollTop() > 300) {
                    $('.back-to-top').addClass('active');
                } else {
                    $('.back-to-top').removeClass('active');
                }
            });
            
            $('.back-to-top').click(function(e) {
                e.preventDefault();
                $('html, body').animate({scrollTop: 0}, 800);
                return false;
            });
            
            // Smooth scrolling for navigation links
            $('a[href*="#"]').on('click', function(e) {
                e.preventDefault();
                
                $('html, body').animate(
                    {
                        scrollTop: $($(this).attr('href')).offset().top - 70,
                    },
                    500,
                    'linear'
                );
            });
            
            // Navbar background change on scroll
            $(window).scroll(function() {
                if ($(window).scrollTop() > 50) {
                    $('.navbar').addClass('navbar-scrolled');
                } else {
                    $('.navbar').removeClass('navbar-scrolled');
                }
            });
            
            // Counter animation on page load
            $(window).on('load', function() {
                $('.counter-number').each(function() {
                    $(this).prop('Counter', 0).animate({
                        Counter: $(this).text()
                    }, {
                        duration: 2000,
                        easing: 'swing',
                        step: function (now) {
                            $(this).text(Math.ceil(now));
                        }
                    });
                });
            });
            
            // Form submission
            $('#contactForm').on('submit', function(e) {
                e.preventDefault();
                alert('Thank you for your message! We will get back to you soon.');
                this.reset();
            });
            
            // PDF Download functionality
            $('#pdfDownload').click(function() {
                // Create a clone of the body content
                const element = document.body.cloneNode(true);
                
                // Remove elements we don't want in the PDF
                $(element).find('.navbar, .footer, .back-to-top, .pdf-download-btn, #contactForm').remove();
                
                // Add page break classes to prevent blank pages
                //$(element).find('.counter-section, .cta-section').addClass('pagebreak-before');
                
                // Options for html2pdf
                const options = {
                    margin: 10,
                    filename: 'ProTech-Engineering-Brochure.pdf',
                    image: { type: 'jpeg', quality: 0.98 },
                    html2canvas: { 
                        scale: 2,
                        useCORS: true,
                        logging: false
                    },
                    jsPDF: { 
                        unit: 'mm', 
                        format: 'a4', 
                        orientation: 'portrait' 
                    },
                    pagebreak: { 
                        mode: ['avoid-all', 'css', 'legacy'] 
                    }
                };
                
                // Generate PDF
                html2pdf().set(options).from(element).save();
            });
        });

    
document.getElementById("contactForm").addEventListener("submit", function(e){
    e.preventDefault();

    let formData = new FormData(this);

    fetch("send-mail.php", {
        method: "POST",
        body: formData
    })
    .then(res => res.text())
    .then(data => {
        if (data === "success") {
            alert("Message sent successfully! Our team will contact you soon.");
            this.reset();
        }
        else if (data === "missing") {
            alert("Please fill all required fields.");
        }
        else if (data === "invalid_email") {
            alert("Invalid email address.");
        }
        else {
            alert("Unable to send email. Try later.");
        }
    });
});

