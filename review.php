<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
        rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH"
        crossorigin="anonymous" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.1/css/all.min.css" integrity="sha512-5Hs3dF2AEPkpNAR7UiOHba+lRSJNeM2ECkwxUIxC1Q/FLycGTbNapWXB4tP889k5T5Ju8fs4b1P5z/iB4nMfSQ==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="style.css" />
    <script src="//code.jquery.com/jquery-1.11.1.min.js"></script>
    <title>Login</title>
</head>


<body class="loginpage" style="background-color: orange;">
    <div>
        <nav class="navbar navbar-expand-lg navbar-light fixed-top p-0">
            <div class="container">
                <!-- <img
                    class="navbar-brand me-auto"
                    src="image/Slothji.png"
                    style="width: 50px; height: auto; border-radius: 50%;"
                    href="index.html" /> -->
                <button
                    class="navbar-toggler"
                    type="button"
                    data-bs-toggle="collapse"
                    data-bs-target="#navbarSupportedContent"
                    aria-controls="navbarSupportedContent"
                    aria-expanded="false"
                    aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <ul class="navbar-nav ms-auto">
                        <li class="nav-item">
                            <a class="nav-link" href="index.php">HOME</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="aboutus.php">ABOUT US</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link active" href="places.php">PLACES</a>
                        </li>

                        <li>
                            <button type="button" class="login-btn" data-bs-toggle="modal" data-bs-target="#staticBackdrop">
                                <a class="nav-link">ACCOUNT</a>
                            </button>
                        </li>



                    </ul>
                </div>
            </div>
        </nav>
    </div>


    <div class="modal fade" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content login-content">

                <div class="modal-body login-body">
                    <div>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="login-wrapper">
                        <div class="form-wrapper sign-in">
                            <form action="">
                                <h2>Login</h2>
                                <div class="input-group">
                                    <input type="text" required>
                                    <label for="">Username</label>
                                </div>
                                <div class="input-group">
                                    <input type="password" required>
                                    <label for="">Password</label>
                                </div>
                                <div class="remember">
                                    <label for=""><input type="checkbox"> Remember me</label>
                                </div>
                                <button type="submit" class="rgb">Login</button>
                                <div class="signup-link">
                                    <p>Don't have an account?<a href="#" class="signupbtn-link">Sign Up</a></p>
                                </div>
                            </form>
                        </div>

                        <div class="form-wrapper sign-up">
                            <form action="">
                                <h2>Sign Up</h2>
                                <div class="input-group">
                                    <input type="text" required>
                                    <label for="">Username</label>
                                </div>
                                <div class="input-group">
                                    <input type="email" required>
                                    <label for="">Email</label>
                                </div>
                                <div class="input-group">
                                    <input type="password" required>
                                    <label for="">Password</label>
                                </div>
                                <button type="submit" class="rgb">Sign Up</button>
                                <div class="signup-link">
                                    <p>Already have an account?<a href="#" class="signinbtn-link">Sign In</a></p>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <section class="locate-group">

    </section>
    <!-- footer -->
    <footer class="footer-body">
        <div class="footer-col col-lg-3 col-md-6 col-sm-12">
            <h4>Menu</h4>
            <ul>
                <li><a href="index.php">Home</a></li>
                <li><a href="places.php">Place</a></li>
                <li><a href="#">teams</a></li>
            </ul>
        </div>
        <div class="footer-col col-lg-3 col-md-6 col-sm-12">
            <h4>network</h4>
            <ul>
                <li><a href="#">teams</a></li>
                <li><a href="#">teams</a></li>
                <li><a href="#">teams</a></li>
            </ul>
        </div>
        <div class="footer-col col-lg-3 col-md-6 col-sm-12">
            <h4>company</h4>
            <ul>
                <li><a href="#">about us</a></li>
                <li><a href="#">teams</a></li>
                <li><a href="#">contact us</a></li>
            </ul>
        </div>
        <div class="footer-col col-lg-3 col-md-6 col-sm-12">
            <h4>follow us</h4>
            <div class="links">
                <a href="#"><i class="fa-brands fa-facebook"></i></a>
                <a href="#"><i class="fa-solid fa-x"></i></a>
                <a href="#"><i class="fa-brands fa-instagram"></i></a>
                <a href="#"><i class="fa-brands fa-youtube"></i></a>
            </div>
        </div>
    </footer>



    </section>
    <script
        src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
        crossorigin="anonymous"></script>
    <script
        src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"
        integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r"
        crossorigin="anonymous"></script>
    <script
        src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js"
        integrity="sha384-0pUGZvbkm6XF6gxjEnlmuGrJXVbNuzT9qBBavbLwCsOGabYfZo0T0to5eqruptLy"
        crossorigin="anonymous"></script>
    <script type="text/javascript" src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
    <script type="text/javascript"
        src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.0/umd/popper.min.js"></script>
    <script type="text/javascript"
        src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.0/js/bootstrap.min.js"></script>

    <!-- login & signup script -->
    <script>
        const signinbtnLink = document.querySelector('.signinbtn-link');
        const signupbtnLink = document.querySelector('.signupbtn-link');
        const loginwrapper = document.querySelector('.login-wrapper');

        signupbtnLink.addEventListener('click', () => {
            loginwrapper.classList.toggle('active');
        });

        signinbtnLink.addEventListener('click', () => {
            loginwrapper.classList.toggle('active');
        });
    </script>


    <!-- rating-script -->
    <script>
        const btn = document.querySelector("button");
        const post = document.querySelector(".post");
        const widget = document.querySelector(".star-widget");
        const editbtn = document.querySelector(".edit");
        btn.onclick = () => {
            widget.style.display = "none";
            post.style.display = "block";
            return false;
            editbtn.onclick = () => {
                widget.style.display = "block";
                post.style.display = "none";

            }
            return false;

        }
    </script>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const navE1 = document.querySelector('.navbar');
            window.addEventListener('scroll', () => {
                if (navE1 && window.scrollY > 56) {
                    navE1.classList.add('navbar-scrolled');
                } else if (navE1) {
                    navE1.classList.remove('navbar-scrolled');
                }
            });
        });
    </script>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const navbarToggler = document.querySelector('.navbar-toggler');
            const navbarCollapse = document.querySelector('.navbar-collapse');

            navbarToggler.addEventListener('click', () => {
                // Toggle 'show' class for navbar-collapse
                navbarCollapse.classList.toggle('show');

                // Apply or remove backdrop styles
                if (navbarCollapse.classList.contains('show')) {
                    navbarCollapse.style.backgroundColor = 'rgba(27, 40, 12, 0.7)';
                    navbarCollapse.style.backdropFilter = 'blur(15px)';
                } else {
                    navbarCollapse.style.backgroundColor = '';
                    navbarCollapse.style.backdropFilter = '';
                }
            });

            // Optionally: Close menu when clicking outside (optional)
            document.addEventListener('click', (event) => {
                if (
                    !navbarToggler.contains(event.target) && // Clicked outside toggler
                    !navbarCollapse.contains(event.target) // Clicked outside menu
                ) {
                    navbarCollapse.classList.remove('show');
                    navbarCollapse.style.backgroundColor = '';
                    navbarCollapse.style.backdropFilter = '';
                }
            });
        });
    </script>
    <script>
        $(document).ready(function() {
            var itemsMainDiv = ('.MultiCarousel');
            var itemsDiv = ('.MultiCarousel-inner');
            var itemWidth = "";

            $('.leftLst, .rightLst').click(function() {
                var condition = $(this).hasClass("leftLst");
                if (condition)
                    click(0, this);
                else
                    click(1, this)
            });

            ResCarouselSize();


            $(window).resize(function() {
                ResCarouselSize();
            });

            //this function define the size of the items
            function ResCarouselSize() {
                var incno = 0;
                var dataItems = ("data-items");
                var itemClass = ('.item');
                var id = 0;
                var btnParentSb = '';
                var itemsSplit = '';
                var sampwidth = $(itemsMainDiv).width();
                var bodyWidth = $('body').width();
                $(itemsDiv).each(function() {
                    id = id + 1;
                    var itemNumbers = $(this).find(itemClass).length;
                    btnParentSb = $(this).parent().attr(dataItems);
                    itemsSplit = btnParentSb.split(',');
                    $(this).parent().attr("id", "MultiCarousel" + id);


                    if (bodyWidth >= 1200) {
                        incno = itemsSplit[3];
                        itemWidth = sampwidth / incno;
                    } else if (bodyWidth >= 992) {
                        incno = itemsSplit[2];
                        itemWidth = sampwidth / incno;
                    } else if (bodyWidth >= 768) {
                        incno = itemsSplit[1];
                        itemWidth = sampwidth / incno;
                    } else {
                        incno = itemsSplit[0];
                        itemWidth = sampwidth / incno;
                    }
                    $(this).css({
                        'transform': 'translateX(0px)',
                        'width': itemWidth * itemNumbers
                    });
                    $(this).find(itemClass).each(function() {
                        $(this).outerWidth(itemWidth);
                    });

                    $(".leftLst").addClass("over");
                    $(".rightLst").removeClass("over");

                });
            }


            //this function used to move the items
            function ResCarousel(e, el, s) {
                var leftBtn = ('.leftLst');
                var rightBtn = ('.rightLst');
                var translateXval = '';
                var divStyle = $(el + ' ' + itemsDiv).css('transform');
                var values = divStyle.match(/-?[\d\.]+/g);
                var xds = Math.abs(values[4]);
                if (e == 0) {
                    translateXval = parseInt(xds) - parseInt(itemWidth * s);
                    $(el + ' ' + rightBtn).removeClass("over");

                    if (translateXval <= itemWidth / 2) {
                        translateXval = 0;
                        $(el + ' ' + leftBtn).addClass("over");
                    }
                } else if (e == 1) {
                    var itemsCondition = $(el).find(itemsDiv).width() - $(el).width();
                    translateXval = parseInt(xds) + parseInt(itemWidth * s);
                    $(el + ' ' + leftBtn).removeClass("over");

                    if (translateXval >= itemsCondition - itemWidth / 2) {
                        translateXval = itemsCondition;
                        $(el + ' ' + rightBtn).addClass("over");
                    }
                }
                $(el + ' ' + itemsDiv).css('transform', 'translateX(' + -translateXval + 'px)');
            }

            //It is used to get some elements from btn
            function click(ell, ee) {
                var Parent = "#" + $(ee).parent().attr("id");
                var slide = $(Parent).attr("data-slide");
                ResCarousel(ell, Parent, slide);
            }

        });
    </script>
</body>

</html>