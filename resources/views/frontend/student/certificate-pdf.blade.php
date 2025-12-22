<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <style>
        @page {
            margin: 0;
        }

        html, body {
            margin: 0;
            padding: 0;
            height: 100%;
        }

        body {
            width: 100%;
            height: 100%;
        }

        img {
            display: block;
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
    </style>
</head>
<body>
    <img src="data:image/png;base64,{{ $image }}" alt="Certificate">
</body>
</html>
