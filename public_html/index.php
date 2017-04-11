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
                    <a href='index.php'>Forums</a> :: <a href='./members.php'>Members</a>
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
    if (isset($_GET['idx']) || count($_GET) == 0) {
        echo '<div class="Section">';
        $a = $pdo->query("select name, description, id, last_title, last_poster_name from ibf_forums where parent_id != -1");
        $flipper = 0;
        while ($row = $a->fetch()) {
            echo '<div class="Forum'.(($flipper ^= 1)+1).'">
            <div class="LastPost'.($flipper+1).'">'.$row['last_title'].'<br />'
            .$row['last_poster_name'].'<br />'.'</div>'
            ."<a class='ForumTitle' href='?sf=".$row['id']."'>". $row['name'] . "</a><p class='ForumDescription'>"
            .$row['description']."</p></div>";
        }
        echo '</div>';
    } else if (isset($_GET['sf'])) {
        $forum_id = (int)$_GET['sf'];
        $a = $pdo->prepare("select name from ibf_forums where id = ?");
        $a->execute([$forum_id]);
        $forumtitle = $a->fetch();
        $forumtitle = $forumtitle['name'];
        echo '<a style="margin:2px;font: bold 12px Tahoma" href="?idx">Staredit Network</a> -> <a style="font: bold 12px Tahoma" href="?sf='.$forum_id.'">'.$forumtitle.'</a>
        <div class="Section"><div class="SectionHeader"><span class="lph">Last Post</span>Topic Title</div>';
        $a = $pdo->prepare("select tid, title, description, starter_name, last_poster_name, last_post from ibf_topics WHERE forum_id = ".$forum_id." ORDER BY pinned DESC, last_post DESC LIMIT ?, 25");
        $post_quantity = ($p*25) ? $p*25 : '';
        $a->execute([$post_quantity]);
        $flipper = 0;
        while ($row = $a->fetch()) {
        echo '<div class="Forum'.(($flipper ^= 1)+1).'" style="line-height:12px;padding: 8px;">'
        ."<div class='LastPost".($flipper+3)."'>".date("Y-m-d H:i:s",$row['last_post'])."<br />"
        .$row['last_poster_name']."</div><a class='TopicTitle' href='?st=".$row['tid']."'>".$row['title']."</a><br />
        <p style='font: normal 8px Tahoma;'>".$row['starter_name']."</p></div>";
        }
        echo "</div>";
        echo "<a href='?sf=" . $forum_id . "&p=" . ($p+1) . "'>Next Page (" . ($p+1) . ")</a>";
    } else if (isset($_GET['st'])) {
        $topic_id = (int)$_GET['st'];
        $a = $pdo->prepare("select t.title, f.name, t.forum_id from ibf_topics as t left join ibf_forums as f on t.forum_id = f.id where tid = ?");
        $a->execute([$topic_id]);
        $t = $a->fetch();
        $topictitle = $t['title'];
        $forumname = $t['name'];
        $forumid = $t['forum_id'];
        $a = $pdo->prepare("select author_name, post_date, post from ibf_posts where topic_id = ? ORDER BY post_date LIMIT ?, 25");
        $post_quantity = ($p*25) ? $p*25 : '';
        $a->execute([$topic_id, $post_quantity]);
        ?><a style="margin:2px;font: bold 12px Tahoma" href="?idx">Staredit Network</a> -> <a href="?sf=<?=$forumid?>"><?=$forumname?></a> -> <a style="font: bold 12px Tahoma" href='?st=<?=$_GET['st']?>'><?=$topictitle?></a>
        <div class="Section">
        <?php
        $flipper = 0;
        while ($row = $a->fetch()) {
            $row['post'] = str_replace("<#EMO_DIR#>", "smilies", $row['post']);
            echo "<div class='Forum".(($flipper ^= 1)+1)."'>".
            '<div class="SectionHeader" style="margin: -10px;"><span class="lph" style="width: auto">Report, edit, etc...</span>'.
            "Posted by ".$row['author_name']." on ".date("Y-m-d \\a\\t H:i:s",$row['post_date'])."</div>".
            '<div style="padding-top: 10px;min-height: 25px;">'.$row['post'] ."</div></div>";
        }
        echo "</div>";
        echo "<a href='?st=". $_GET['st'] ."&p=" . ($p+1) . "'>Next Page (" . ($p+1) . ")</a>";
        $pdo = null;
    }
?>
            <div class="Footer">
            </div>
        </div>
    </body>
</html>
<?
    ob_end_flush();
?>
