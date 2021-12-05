<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Laravel</title>

    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap" rel="stylesheet">

    <!-- Styles -->
    <style>
        /*! normalize.css v8.0.1 | MIT License | github.com/necolas/normalize.css */
        html {
            line-height: 1.15;
            -webkit-text-size-adjust: 100%
        }

        body {
            margin: 0
        }

        a {
            background-color: transparent
        }

        [hidden] {
            display: none
        }

        html {
            font-family: system-ui, -apple-system, BlinkMacSystemFont, Segoe UI, Roboto, Helvetica Neue, Arial, Noto Sans, sans-serif, Apple Color Emoji, Segoe UI Emoji, Segoe UI Symbol, Noto Color Emoji;
            line-height: 1.5
        }

        *, :after, :before {
            box-sizing: border-box;
            border: 0 solid #e2e8f0
        }

        a {
            color: inherit;
            text-decoration: inherit
        }

        svg, video {
            display: block;
            vertical-align: middle
        }
    </style>

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: 'Nunito', sans-serif;
        }
        img {
            display: inline-block;
            vertical-align: bottom;
        }

        @media print {
            .no-print {
                display: none;
            }
        }

        .flex {
            display: flex;
        }

        .justify-between {
            justify-content: space-between
        }

        #print-area {
            width: 264px;
        }

        #print-area .tag-item {
            display: flex;
            justify-content: center;
            flex-wrap: wrap;
            align-items: center;
            padding: 5px 0;
            width: 132px;
            height: 81px;
            line-height: 1;
            font-size: 14px;
            /*border: 1px solid;*/
        }
    </style>
</head>
<body class="antialiased">


<div id="print-area" class="flex justify-between binhtq-print">
    <div class="tag-item">
        <p>binhtq test 123</p>
        @php
            echo '<img src="data:image/png;base64,' . DNS1D::getBarcodePNG(route('api.qr-code.show', ['code' => '333']), 'C128B', 1, 30) . '" alt="barcode"   />';
        @endphp
        <p>1.000.000 VNĐ</p>
    </div>
    <div class="tag-item">
        <p>binhtq test 456</p>
        @php
            echo '<img src="data:image/png;base64,' . DNS1D::getBarcodePNG(route('api.qr-code.show', ['code' => '333']), 'C128B', 1, 30) . '" alt="barcode"   />';
        @endphp
        <p>2.000.000 VNĐ</p>
    </div>
</div>

<button type="button" class="btn no-print" id="print-btn" onclick="window.print()">Print</button>


</body>
</html>
