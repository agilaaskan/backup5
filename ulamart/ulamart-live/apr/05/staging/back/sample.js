if (window.innerWidth <= 1024) {
    var acc = document.getElementsByClassName("ss-adn");
        var i;
        for (i = 0; i < acc.length; i++) {
        acc[i].addEventListener("click", function() {
            this.classList.toggle("open");    
        });
 }
function myFunction() {
        var dots = document.getElementById("dots");
        var moreText = document.getElementById("more");
        var btnText = document.getElementById("myBtns");

        if (dots.style.display === "none") {
            dots.style.display = "inline";
            btnText.innerHTML = "Read more";
            moreText.style.display = "none";
        } else {
            dots.style.display = "none";
            btnText.innerHTML = "Read less";
            moreText.style.display = "inline";
        }
 }
function myFunction() {
        var dots = document.getElementById("dots-1");
        var moreText = document.getElementById("more-1");
        var btnText = document.getElementById("myBtns-1");

        if (dots.style.display === "none") {
            dots.style.display = "inline";
            btnText.innerHTML = "Read more";
            moreText.style.display = "none";
        } else {
            dots.style.display = "none";
            btnText.innerHTML = "Read less";
            moreText.style.display = "inline";
        }
}
// window.addEventListener('scroll', function (e) {
//         var box = document.querySelector('.mob-nav.wrap.scrol');
//         var x = window.scrollY;
//         if (x >= 700) {
//             box.classList.add("current");
//         } else {
//             box.classList.remove("current");
//         }
//     });
}
function openCity(evt, cityName) {
    var i, tabcontent, tablinks;
    tabcontent = document.getElementsByClassName("tabcontent");
    for (i = 0; i < tabcontent.length; i++) {
        tabcontent[i].style.display = "none";
    }
    tablinks = document.getElementsByClassName("tablinks");
    for (i = 0; i < tablinks.length; i++) {
        tablinks[i].className = tablinks[i].className.replace(" active", "");
    }
    document.getElementById(cityName).style.display = "block";
    evt.currentTarget.className += " active";
}
    // var sam = document.getElementById("defaultOpen");
var slideIndex = 1;
showSlides(slideIndex);

function plusSlides(n) {
    showSlides(slideIndex += n);
}

function currentSlide(n) {
    showSlides(slideIndex = n);
}
function showSlides(n) {
    var i;
    var slides = document.getElementsByClassName("mySlides");
    var dots = document.getElementsByClassName("dot");
    if (n > slides.length) { slideIndex = 1 }
    if (n < 1) { slideIndex = slides.length }
    for (i = 0; i < slides.length; i++) {
        slides[i].style.display = "none";
    }
    for (i = 0; i < dots.length; i++) {
        dots[i].className = dots[i].className.replace(" active", "");
    }
    slides[slideIndex - 1].style.display = "block";
    dots[slideIndex - 1].className += " active";
}
var acc = document.getElementsByClassName("lightbox");
        var i;

        for (i = 0; i < acc.length; i++) {
        acc[i].addEventListener("click", function() {
            this.classList.toggle("close");  
        });
}
function toggleItem(elem) {
    for (var i = 0; i < elem.length; i++) {
    elem[i].addEventListener("click", function(e) {
    var current = this;
    for (var i = 0; i < elem.length; i++) {
    if (current != elem[i]) {
    elem[i].classList.remove('active');
    } else if (current.classList.contains('active') === true) {
    current.classList.remove('active');
    } else {
    current.classList.add('active')
    };
    };
    e.preventDefault();
    });
    };
 }
    toggleItem(document.querySelectorAll('.ss-pro-vertical'));
// (function () {
    // var button = document.querySelector('a.pp-vd');
    // var box = document.querySelector('.lightbox');
    // button.addEventListener('click', function () {
    //     box.classList.toggle('close');
    // });
// });
if (window.innerWidth <= 1024) {
    const buttonRight = document.getElementById('slideRight');
    const buttonLeft = document.getElementById('slideLeft');

    buttonRight.onclick = function () {
        document.getElementById('ss-stick').scrollLeft += 70;
    };
    buttonLeft.onclick = function () {
        document.getElementById('ss-stick').scrollLeft -= 70;
    };
    var acc = document.getElementsByClassName("ss-adn");
        var i;

        for (i = 0; i < acc.length; i++) {
        acc[i].addEventListener("click", function() {
            this.classList.toggle("open");    
        });
    }
    function myFunction() {
        var dots = document.getElementById("dots");
        var moreText = document.getElementById("more");
        var btnText = document.getElementById("myBtns");

        if (dots.style.display === "none") {
            dots.style.display = "inline";
            btnText.innerHTML = "Read more";
            moreText.style.display = "none";
        } else {
            dots.style.display = "none";
            btnText.innerHTML = "Read less";
            moreText.style.display = "inline";
        }
    }
    function myFunction() {
        var dots = document.getElementById("dots-1");
        var moreText = document.getElementById("more-1");
        var btnText = document.getElementById("myBtns-1");

        if (dots.style.display === "none") {
            dots.style.display = "inline";
            btnText.innerHTML = "Read more";
            moreText.style.display = "none";
        } else {
            dots.style.display = "none";
            btnText.innerHTML = "Read less";
            moreText.style.display = "inline";
        }
    }

    window.addEventListener('scroll', function (e) {
        var box = document.querySelector('.mob-nav.wrap.scrol');
        var x = window.scrollY;
        if (x >= 1700) {
            box.classList.add("current");
        } else {
            box.classList.remove("current");
        }
    });
    
    

}
function openQuick(evt, quickName) {
    var i, tabproduct, tabmenu;
    tabproduct = document.getElementsByClassName("tabpro");
    for (i = 0; i < tabproduct.length; i++) {
        tabproduct[i].style.display = "none";
    }
    tabmenu = document.getElementsByClassName("tabmenus");
    for (i = 0; i < tabmenu.length; i++) {
        tabmenu[i].className = tabmenu[i].className.replace(" active", "");
    }
    document.getElementById(quickName).style.display = "block";
    evt.currentTarget.className += " active";
}
