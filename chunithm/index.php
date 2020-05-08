<?php
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");
?>
<!doctype html>
<head>
    <meta charset=utf-8>
    <link rel="stylesheet" href="https://chunithm-net.com/mobile/css/common.css?ver=1.40" />
    <link rel="stylesheet" href="https://chunithm-net.com/mobile/css/contents.css?ver=1.40" />
    <link rel="apple-touch-icon-precomposed" href="./apple-touch-icon.png">
    <link rel="icon" type="image/png" href="./apple-touch-icon.png">
    <meta name="robots" content="index, nofollow">
    <meta name="keywords" content="chunithm, cool">
    <meta property="og:url" content="//gaming.harold.kim/chunithm/">
    <meta property="og:title" content="stypr's chunithm status">
    <meta property="og:type" content="profile">
    <meta property="og:description" content="Check out my chunithm status!">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>CHUNITHM STATUS</title>
</head>
<body oncontextmenu="return false;">
    <h1 id="header"></h1>
        <div class="frame01 w460">
            <div class="frame01_inside w450">
                <div class="mt_10">
<?php
    // Remove Unwanted Tags
    // Load File
    $result = file_get_contents("static/result.html");
    $doc = new DOMDocument();
    $doc->loadHTML('<?xml encoding="utf-8" ?>' . $result);
    $remove = [];

    // t_l m_15 f_12 l_h_12
    $classname = "more"; // f_12 l_h_1";
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

