<?php
    header('Last-Modified: 12 Aug 2012 00:00:00 GMT');
    header('Expires: 22 Mar 2100 00:00:00 GMT');
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html>
    <head>
        <title>SEN v4 (as on Feb 10, 2007)</title>
        <link rel="stylesheet" type="text/css" href="miniforums.css" />
        <link rel="stylesheet" type="text/css" href="staticsen.css" />
        <script src="script.js" type="text/javascript"></script>
    </head>
    <body>
        <div class="Main">
            <div class="Titlebar">
                <div class="Login">
                    This is a static copy of the SEN v4 forums.<br />
                    The database used is from Febuary 10th, 2007.<br />
                    <a href='index.php'>Forums</a> :: <a href='/members.php'>Members</a>
                </div>
                <h1>Staredit Network</h1>
            </div>
<?php
    $db = parse_ini_file("../inc/database.ini");
    $dsn = "mysql:host=" . $db['host'] . ";dbname=" . $db['database'] . ";charset=" . $db['charset'];
    $opt = [
        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES   => false,
    ];
    $pdo = new PDO($dsn, $db['username'], $db['password'], $opt);

    $p = isset($_GET['p']) ? (int)$_GET['p'] : 0;
    //Static SEN
    echo '<div class="Section">';
    if (isset($_GET['p'])) {
        if (!ctype_digit($_GET['p'])) {
            $_GET['p'] = 0;
        }
        $lim_low = $_GET['p'] * 50;
    }
    else {
        $lim_low = 0;
    }
    $lim_high = $lim_low + 50;

    if (isset($_GET['c'])) {
        if (!ctype_alnum($_GET['c'])) { }
        else {
            $where = "WHERE name LIKE '%{$_GET['c']}%'";
        }
    }
    else {
        $where = "";
    }
    $a = $pdo->query("SELECT id, name, joined, posts, last_activity FROM ibf_members {$where} LIMIT {$lim_low}, {$lim_high} ");
    $flipper = 0;
    while ($row = $a->fetch()) {
        echo '<div class="Forum'.(($flipper ^= 1)+1).'">
        <div class="LastPost'.($flipper+1).'">'.date("Y-m-d H:i:s",$row['joined']).'<br />'
        .date("Y-m-d H:i:s",$row['last_activity']).'<br />'.'</div>'
        ."<a class='ForumTitle' href='?sf=".$row['id']."'>". $row['name'] . "</a><p class='ForumDescription'>"
        .$row['posts']."</p></div>";
    }
    echo "* URL hacking: add ?p=[page number - 1]&c=[name contains]<br />
    Example: http://v4.staredit.net/members.php?p=4&c=e is fifth page of members whose name contain \"e\"";
    echo '</div>';
?>
            <div class="Footer">
            </div>
        </div>
    </body>
</html>
<?
    ob_end_flush();
?>
