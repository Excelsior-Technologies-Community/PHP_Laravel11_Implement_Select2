<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1"
    >

    <title>Customer Area</title>


    {{-- Bootstrap --}}

    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
        rel="stylesheet"
    >


    {{-- Font Awesome --}}

    <link
        href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css"
        rel="stylesheet"
    >


    @stack('styles')


    <style>

        body {
            background: #f5f6f7;
        }

        .product-img {
            height: 220px;
            width: 100%;
            object-fit: cover;
        }

    </style>

</head>


<body>


    {{-- CUSTOMER HEADER --}}

    <nav class="navbar navbar-light bg-white shadow-sm mb-4">

        <div class="container">

            <h4 class="m-0">

                Customer Products

            </h4>

        </div>

    </nav>


    {{-- CONTENT --}}

    <div class="container">

        @yield('content')

    </div>


    {{-- jQuery --}}

    <script
        src="https://code.jquery.com/jquery-3.6.0.min.js">
    </script>


    {{-- Bootstrap JS --}}

    <script
        src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js">
    </script>


    {{-- Select2 --}}

    <script
        src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js">
    </script>


    @stack('scripts')

</body>

</html>