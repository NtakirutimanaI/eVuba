<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>eVubaConnect</title>

    <style>
        /* Smooth scrolling */
        html {
            scroll-behavior: smooth;
        }

        /* Offset for fixed header */
        section {
            scroll-margin-top: 110px; /* header height */
        }

        /* Scroll to Top Button */
        #scrollToTopBtn {
            position: fixed;
            bottom: 30px;
            right: 30px;
            width: 36px;
            height: 36px;
            border: none;
            border-radius: 50%;
            background-color: #f4f4f4;
            color: #000;
            font-size: 18px;
            cursor: pointer;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.2);
            display: none;
            z-index: 1000;
        }

        #scrollToTopBtn:hover {
            background-color: #e0e0e0;
        }
    </style>
</head>
<body>

    {{-- HEADER --}}
    @include('web.header')

    {{-- PAGE SECTIONS --}}
    <section id="home">
        @include('web.home')
    </section>

    <section id="about">
        @include('web.about-us')
    </section>

    <section id="services">
        @include('web.services')
    </section>

    <section id="contact">
        @include('web.contacts')
    </section>
        @include('web.chatbot')

    {{-- Scroll to Top Button --}}
    <button id="scrollToTopBtn" title="Go to top">↑</button>

    <script>
        const scrollToTopBtn = document.getElementById("scrollToTopBtn");

        window.addEventListener("scroll", () => {
            scrollToTopBtn.style.display =
                window.scrollY > 300 ? "block" : "none";
        });

        scrollToTopBtn.addEventListener("click", () => {
            window.scrollTo({
                top: 0,
                behavior: "smooth"
            });
        });
    </script>

    {{-- FOOTER --}}
    @include('web.footer')

</body>
</html>
