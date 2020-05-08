<?php

    // Set your SEGA ID and password.
    define("__USERNAME__", "Your_SEGA_ID");
    define("__PASSWORD__", "Your_SEGA_PW");

    // Script starts from here
    set_time_limit(0);
    error_reporting(0);
    ini_set("display_errors", "off");
    header("Cache-control: no-cache, must-revalidate");

    function write_log($content){
        $fp = @fopen("~debug.log", "a+");
        fwrite($fp, "[" . date("Y-m-d H:i:s") . "] " . $content . "\n");
        fclose($fp);
        return true;
    }

    function show_error($message){
        include("./template/error.php");
    }

    function retrieve_data_ongeki($username, $password){
        $tmp = tempnam(sys_get_temp_dir(), "ongeki");

        // get auth cookie, get auth tokens first
        $f = curl_init("https://ongeki-net.com/ongeki-mobile/");
        curl_setopt($f, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($f, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($f, CURLOPT_COOKIEJAR, $tmp);
        curl_setopt($f, CURLOPT_COOKIEFILE, $tmp);
        $r = curl_exec($f);
        $token = explode('name="token" value="', $r);
        $token = explode('"', $token[1])[0];
        //var_dump($token);

        // login with valid tokens
        $data = array(
            'segaId' => $username,
            'password' => $password,
            'token' => $token,
        );
        $f = curl_init("https://ongeki-net.com/ongeki-mobile/submit/");
        curl_setopt($f, CURLOPT_POST, true);
        curl_setopt($f, CURLOPT_POSTFIELDS, http_build_query($data));
        curl_setopt($f, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($f, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($f, CURLOPT_COOKIEJAR, $tmp);
        curl_setopt($f, CURLOPT_COOKIEFILE, $tmp);
        $r = curl_exec($f);
        $info = curl_getinfo($f);
        if($info['url'] === "https://ongeki-net.com/ongeki-mobile/aimeList/"){
            $f = curl_init("https://ongeki-net.com/ongeki-mobile/aimeList/submit/?idx=0");
            curl_setopt($f, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($f, CURLOPT_FOLLOWLOCATION, true);
            curl_setopt($f, CURLOPT_COOKIEJAR, $tmp);
            curl_setopt($f, CURLOPT_COOKIEFILE, $tmp);
            $r = curl_exec($f);
            $info = curl_getinfo($f);
            //var_dump($info);
            if($info['url'] === "https://ongeki-net.com/ongeki-mobile/home/"){
                goto parse1;
            }
        }
        @unlink($tmp);
        return false;

        parse1:
        // playlog
        $f = curl_init("https://ongeki-net.com/ongeki-mobile/record/playlog/");
        curl_setopt($f, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($f, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($f, CURLOPT_COOKIEJAR, $tmp);
        curl_setopt($f, CURLOPT_COOKIEFILE, $tmp);
        $r = curl_exec($f);
        $playlog = explode('<div class="m_15">', $r)[1];
        $playlog = explode('<footer class="f_0 footer">', $playlog)[0];
        $playlog = '<div class="m_15">' . $playlog;

        // home
        $f = curl_init("https://ongeki-net.com/ongeki-mobile/home/");
        curl_setopt($f, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($f, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($f, CURLOPT_COOKIEJAR, $tmp);
        curl_setopt($f, CURLOPT_COOKIEFILE, $tmp);
        $r = curl_exec($f);
        $home = explode('<div class="m_15">', $r)[1];
        $home = '<div class="m_15">' . $home;

        @unlink($tmp);
        return $home . $playlog;
    }

    function retrieve_data_maimai($username, $password){
        $tmp = tempnam(sys_get_temp_dir(), "maimai");

        // get auth cookie, get auth tokens first
        $f = curl_init("https://maimaidx.jp/maimai-mobile/");
        curl_setopt($f, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($f, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($f, CURLOPT_COOKIEJAR, $tmp);
        curl_setopt($f, CURLOPT_COOKIEFILE, $tmp);
        $r = curl_exec($f);
        $token = explode('name="token" value="', $r);
        $token = explode('"', $token[1])[0];

        // login with valid tokens
        $data = array(
            'segaId' => $username,
            'password' => $password,
            'token' => $token,
            'save_cookie' => 'on',
        );
        $f = curl_init("https://maimaidx.jp/maimai-mobile/submit/");
        curl_setopt($f, CURLOPT_POST, true);
        curl_setopt($f, CURLOPT_POSTFIELDS, http_build_query($data));
        curl_setopt($f, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($f, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($f, CURLOPT_COOKIEJAR, $tmp);
        curl_setopt($f, CURLOPT_COOKIEFILE, $tmp);
        $r = curl_exec($f);
        $info = curl_getinfo($f);
        if($info['url'] === "https://maimaidx.jp/maimai-mobile/aimeList/"){
            $f = curl_init("https://maimaidx.jp/maimai-mobile/aimeList/submit/?idx=0");
            curl_setopt($f, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($f, CURLOPT_FOLLOWLOCATION, true);
            curl_setopt($f, CURLOPT_COOKIEJAR, $tmp);
            curl_setopt($f, CURLOPT_COOKIEFILE, $tmp);
            $r = curl_exec($f);
            $info = curl_getinfo($f);
            //var_dump($info);
            if($info['url'] === "https://maimaidx.jp/maimai-mobile/home/"){
                goto parse2;
            }
        }
        @unlink($tmp);
        return false;

        parse2:
        // playlog
        $f = curl_init("https://maimaidx.jp/maimai-mobile/record/");
        curl_setopt($f, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($f, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($f, CURLOPT_COOKIEJAR, $tmp);
        curl_setopt($f, CURLOPT_COOKIEFILE, $tmp);
        $r = curl_exec($f);
        $playlog = explode('class="title m_10"/>', $r)[1];
        $playlog = explode('<footer class="f_0">', $playlog)[0];
        // $playlog = $playlog;

        // home - Rating Target Music
        $f = curl_init("https://maimaidx.jp/maimai-mobile/home/ratingTargetMusic/");
        curl_setopt($f, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($f, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($f, CURLOPT_COOKIEJAR, $tmp);
        curl_setopt($f, CURLOPT_COOKIEFILE, $tmp);
        $r = curl_exec($f);
        $home = explode('<div class="see_through_block m_15 m_t_0 p_10 t_l f_0">', $r)[1];
        $home = explode('<div class="screw_block m_15 f_15">', $home)[0];
        $home = '<div class="see_through_block m_15 m_t_0 p_10 t_l f_0">' . $home;
        @unlink($tmp);
        return $home . $playlog;
    }

    function retrieve_data_chunithm($username, $password){
        $tmp = tempnam(sys_get_temp_dir(), "chunithm");

        // get auth cookie, get auth tokens first
        $f = curl_init("https://chunithm-net.com/mobile/");
        curl_setopt($f, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($f, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($f, CURLOPT_COOKIEJAR, $tmp);
        curl_setopt($f, CURLOPT_COOKIEFILE, $tmp);
        $r = curl_exec($f);
        $token = explode('name="token" value="', $r);
        $token = explode('"', $token[1])[0];
        // login with valid tokens
        $data = array(
            'segaId' => $username,
            'password' => $password,
            'save_cookie' => 'save_cookie',
            'token' => $token,
        );
        $f = curl_init("https://chunithm-net.com/mobile/submit/");
        curl_setopt($f, CURLOPT_POST, true);
        curl_setopt($f, CURLOPT_POSTFIELDS, http_build_query($data));
        curl_setopt($f, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($f, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($f, CURLOPT_COOKIEJAR, $tmp);
        curl_setopt($f, CURLOPT_COOKIEFILE, $tmp);
        $r = curl_exec($f);
        $info = curl_getinfo($f);
        if($info['url'] === "https://chunithm-net.com/mobile/aimeList/"){
            $token = explode('name="token" value="', $r);
            $token = explode('"', $token[1])[0];
            $f = curl_init("https://chunithm-net.com/mobile/aimeList/submit/");
            $data = array(
                "idx" => "0",
                "token" => $token,
            );
            curl_setopt($f, CURLOPT_POST, true);
            curl_setopt($f, CURLOPT_POSTFIELDS, http_build_query($data));
            curl_setopt($f, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($f, CURLOPT_FOLLOWLOCATION, true);
            curl_setopt($f, CURLOPT_COOKIEJAR, $tmp);
            curl_setopt($f, CURLOPT_COOKIEFILE, $tmp);
            $r = curl_exec($f);
            $info = curl_getinfo($f);
            if($info['url'] === "https://chunithm-net.com/mobile/home/"){
                goto parse3;
            }
        }
        @unlink($tmp);
        return false;

        parse3:
        // playlog
        $f = curl_init("https://chunithm-net.com/mobile/record/playlog");
        curl_setopt($f, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($f, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($f, CURLOPT_COOKIEJAR, $tmp);
        curl_setopt($f, CURLOPT_COOKIEFILE, $tmp);
        $r = curl_exec($f);
        $playlog = explode('<h2 id="page_title">', $r)[1];
        $playlog = explode('<div class="sleep_penguin"></div>', $playlog)[0];
        $playlog = '<h2 id="page_title">' . $playlog;

        // home
        $f = curl_init("https://chunithm-net.com/mobile/home/");
        curl_setopt($f, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($f, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($f, CURLOPT_COOKIEJAR, $tmp);
        curl_setopt($f, CURLOPT_COOKIEFILE, $tmp);
        $r = curl_exec($f);
        $home = explode('<div class="w420 box_player clearfix">', $r)[1];
        $home = explode('<div class="box01 w420">', $home)[0];
        $home = '<div class="w420 box_player clearfix">' . $home;
        @unlink($tmp);
        return $home . $playlog;
    }

    $username = __USERNAME__;
    $password = __PASSWORD__;
    $current_path = realpath(dirname(__FILE__));

    // Read maimai, ongeki, chunithm
    $res = retrieve_data_maimai($username, $password);
    if($res !== false){
        echo "Maimai Success.\n";
        file_put_contents($current_path . "/maimai/static/result.html", $res);
    }
    $res = retrieve_data_ongeki($username, $password);
    if($res !== false){
        echo "Ongeki Success.\n";
        file_put_contents($current_path . "/ongeki/static/result.html", $res);
    }
    $res = retrieve_data_chunithm($username, $password);
    if($res !== false){
        echo "Chunithm Success.\n";
        file_put_contents($current_path . "/chunithm/static/result.html", $res);
    }

?>
