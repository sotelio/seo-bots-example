<?php
include 'vendor/autoload.php';
include 'env.php';

# $path is the url from the user request. It will be used to get the data.
$path = '/';
if (!empty($_SERVER['REQUEST_URI']) && $_SERVER['REQUEST_URI'] != '/') {

    # In my database, the seo urls don't have the first slash.
    $path = $_SERVER['REQUEST_URI'];
    if (substr($path, 0, 1) == '/') {
        $path = substr($path, 1);
    }
}

# Adding other parameters to specify the data I need. 
# For example: articles don't need an extra SEO data, because they already have title, description, etc.
# Here I'm adding the type of content and the article id, so the API code knows where to find it (SEO table or Articles table).
# etc.
if (strpos( $path, 'blog/detail') !== false) {
    $url = explode('/', $path);
    $getUrl = "seo?type=article&id=" . $url[2];
}
else {
    # For statics pages, like Home, About Us, Contact Us, etc.
    $getUrl = "seo?path=$path";
}

# Connecting to the API
$client = new GuzzleHttp\Client(['base_uri' => getenv('API_URL')]);

$response = $client->request('GET', $getUrl);

$seo = [
    'title' => 'Default Title',
    'description' => 'Default Description',
    'image' => '',
    'image_size' => [500, 300], # default image size,
    'url' => ''
];

if ($response->getStatusCode() == 200) {
    $seo = json_decode($response->getBody(), true);
}

$seo['url'] = getenv('BASE_URL') . $path;

?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title><?= $seo['title'] ?? 'Default Title';?></title>

    <meta name="description" content="<?= $seo['description'] ?? '';?>">

    <meta property="og:description" content="<?= $seo['description'] ?? '';?>">
    <meta property="og:title" content="<?= $seo['title'] ?? '';?>">
    <meta property="og:url" content="<?= $seo['url'] ?? '';?>">
    <meta property="og:type" content="website">
    <meta property="og:image" content="<?= $seo['image'] ?? '';?>">
    <meta property="og:image:width" content="<?= $seo['image_size'][0] ?? 500;?>">
    <meta property="og:image:height" content="<?= $seo['image_size'][1] ?? 300;?>">

</head>
<body>
    
</body>
</html>