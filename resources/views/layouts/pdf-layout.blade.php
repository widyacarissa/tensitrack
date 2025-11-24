<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

    <title>@yield('title')</title>
    <style>
        @page {
            margin: 100px 50px 80px 50px; /* Top, Right, Bottom, Left margins */
        }

        body {
            font-family: 'Helvetica Neue', 'Helvetica', Arial, sans-serif;
            font-size: 12px;
            line-height: 1.6;
            color: #333;
        }

        /* Header */
        header {
            position: fixed;
            top: -60px;
            left: 0px;
            right: 0px;
            height: 50px;
            text-align: center;
            line-height: 35px;
            border-bottom: 1px solid #eee;
            padding-bottom: 10px;
        }

        header .logo {
            height: 40px;
            margin-right: 10px;
        }

        header h1 {
            margin: 0;
            font-size: 18px;
            color: #001B48; /* Dark Blue */
            line-height: 40px;
        }

        /* Footer */
        footer {
            position: fixed;
            bottom: -60px;
            left: 0px;
            right: 0px;
            height: 50px;
            text-align: center;
            line-height: 35px;
            border-top: 1px solid #eee;
            padding-top: 10px;
            font-size: 10px;
            color: #777;
        }

        /* Content */
        .content {
            margin-top: 40px;
            margin-bottom: 40px;
        }

        h2 {
            font-size: 16px;
            color: #001B48;
            border-bottom: 1px solid #eee;
            padding-bottom: 5px;
            margin-top: 20px;
            margin-bottom: 15px;
            text-align: center;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        table thead th {
            background-color: #001B48; /* Dark Blue */
            color: #FFFFFF;
            padding: 10px 12px;
            text-align: center;
            font-size: 11px;
            border: 1px solid #FFFFFF;
        }

        table tbody td {
            border: 1px solid #e0e0e0;
            padding: 8px 12px;
            vertical-align: top;
            font-size: 11px;
        }

        table tbody tr:nth-child(even) {
            background-color: #f8f8f8; /* Light gray for alternating rows */
        }

        .text-center {
            text-align: center;
        }

        .text-right {
            text-align: right;
        }
    </style>
</head>

<body>
    <header>
        <h1>@yield('title')</h1>
    </header>

    <footer>
        Dicetak pada {{ \Carbon\Carbon::now()->format('d M Y H:i') }} | Halaman <span class="page-number"></span>
    </footer>

    <main class="content">
        @yield('content')
    </main>

    <script type="text/php">
        if (isset($pdf)) {
            $text = "{PAGE_NUM} / {PAGE_COUNT}";
            $size = 10;
            $font = $fontMetrics->getFont("Helvetica");
            $width = $fontMetrics->get_text_width($text, $font, $size) / 2;
            $x = ($pdf->get_width() - $width) / 2;
            $y = $pdf->get_height() - 35;
            $pdf->page_text(520, $y, $text, $font, $size, array(0,0,0));
        }
    </script>
</body>

</html>