<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ShortLinkX</title>
    <style>
        .container {
            max-width: 600px;
            margin: 0 auto;
            text-align: center;
            padding-top: 50px;
        }

        input[type="url"] {
            width: 80%;
            padding: 10px;
            margin-bottom: 10px;
        }

        button {
            padding: 10px 20px;
            background-color: #4CAF50;
            color: white;
            border: none;
            cursor: pointer;
        }

        button:hover {
            background-color: #45a049;
        }

        #shortUrl {
            margin-top: 20px;
            font-size: 18px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>ShortLinkX</h1>
        <form method="post">
            <input type="url" name="longUrl" placeholder="Enter your URL" required>
            <button type="submit" name="submit">Shorten</button>
        </form>

        <?php
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit'])) {
            $dbFile = 'urls.json';

            $data = file_get_contents($dbFile);
            $urls = json_decode($data, true);

            $shortUrl = generateShortUrl();

            $urls[] = array('shortUrl' => $shortUrl, 'longUrl' => $_POST['longUrl']);
            file_put_contents($dbFile, json_encode($urls));

            $protocol = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on') ? 'https://' : 'http://';
            $domain = $protocol . $_SERVER['HTTP_HOST'];
            
            echo '<div id="shortUrl">Shortened URL: <a href="' . $domain . $_SERVER['REQUEST_URI'] . '?short=' . $shortUrl . '" target="_blank">' . $domain . $_SERVER['REQUEST_URI'] . '?short=' . $shortUrl . '</a></div>';
        }
        function generateShortUrl() {
            $characters = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
            $shortUrl = '';
            $length = strlen($characters);
            for ($i = 0; $i < 6; $i++) {
                $shortUrl .= $characters[rand(0, $length - 1)];
            }
            return $shortUrl;
        }
        if (isset($_GET['short'])) {
            $dbFile = 'urls.json';
            $data = file_get_contents($dbFile);
            $urls = json_decode($data, true);

            foreach ($urls as $url) {
                if ($url['shortUrl'] === $_GET['short']) {
                    header('Location: ' . $url['longUrl']);
                    exit();
                }
            }
        }
        ?>
    </div>
</body>
</html>
