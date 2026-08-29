<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Lampiran Gambar</title>
    <style>
        @page {
            margin: 0;
            padding: 0;
        }
        body {
            margin: 0;
            padding: 0;
            text-align: center;
            background-color: #ffffff;
        }
        .img-container {
            width: 100%;
            height: 100%;
            display: block;
        }
        .img-container img {
            max-width: 100%;
            max-height: 100%;
            margin: auto;
            display: block;
        }
    </style>
</head>
<body>
    <div class="img-container">
        <img src="{{ $imageBase64 }}" alt="Lampiran Gambar" />
    </div>
</body>
</html>
