<html>
<head>
    <title>Paymob Checkout</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <style>
        * {
            box-sizing: border-box;
        }

        body {
            width: 100%;
            height: 100vh;
            margin: 0;
        }

        iframe {
            width: 100% !important;
            height: 100% !important;
        }
    </style>
</head>
<body>
<iframe src="https://accept.paymob.com/api/acceptance/iframes/{{ $iframeId }}?payment_token={{ $token }}"></iframe>
</body>
</html>

