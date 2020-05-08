<?php
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");
?>
<!doctype html>
<head>
    <meta charset=utf-8>
    <link rel="stylesheet" href="https://maimaidx.jp/maimai-mobile/css/common.css?ver=1.00" />
    <link rel="apple-touch-icon-precomposed" href="./apple-touch-icon.png">
    <link rel="icon" type="image/png" href="./apple-touch-icon.png">
    <meta name="robots" content="index, nofollow">
    <meta name="keywords" content="ongeki, kawaii">
    <meta property="og:url" content="//gaming.harold.kim/maimai/">
    <meta property="og:title" content="stypr's maimai status">
    <meta property="og:type" content="profile">
    <meta property="og:description" content="Check out my maimai status!">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>MAIMAI DX STATUS</title>
</head>
<body oncontextmenu="return false;">
    <div class="wrapper main_wrapper t_c">
        <hr style="border: 0;">
        <div class="container3">
<?php
    // Remove Unwanted Tags
    // Load File
    $result = file_get_contents("static/result.html");
    $doc = new DOMDocument();
    $doc->loadHTML('<?xml encoding="utf-8" ?>' . $result);
    $remove = [];
    // t_l m_15 f_12 l_h_12
    $classname = "t_l m_15"; // f_12 l_h_1";
    $finder = new DomXPath($doc);
    $nodelist = $finder->query("//*[contains(concat(' ', normalize-space(@class), ' '), ' $classname ')]");
    foreach($nodelist as $node){
        if($node instanceof DOMElement){
            $remove[] = $node;
        }
    }

    // Remove Form Tags
    $nodelist = $doc->getElementsByTagName("form");
    foreach($nodelist as $node){
        if($node instanceof DOMElement){
            $remove[] = $node;
        }
    }
    foreach($remove as $node){
        $node->parentNode->removeChild($node);
    }
    echo $doc->saveHTML();
?>
        </div>
    </div>
</body>
</html>

