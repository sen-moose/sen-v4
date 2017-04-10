<?php header('Last-Modified: 12 Aug 2012 00:00:00 GMT');
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
  $p = isset($_GET['p']) ? (int)$_GET['p'] : 0;
//Static SEN
$connect = mysql_connect("localhost","%DB_USER%","%DB_PASSWORD%");
mysql_select_db("starlite_v4");
if (isset($_GET['idx']) || count($_GET) == 0) {
  echo '<div class="Section">';
  $a = mysql_query("select name, description, id, last_title, last_poster_name from ibf_forums where parent_id != -1");
  $flipper = 0;
  while ($row = mysql_fetch_assoc($a)) {
    echo '<div class="Forum'.(($flipper ^= 1)+1).'">
          <div class="LastPost'.($flipper+1).'">'.$row['last_title'].'<br />'
          .$row['last_poster_name'].'<br />'.'</div>'
          ."<a class='ForumTitle' href='?sf=".$row['id']."'>". $row['name'] . "</a><p class='ForumDescription'>"
          .$row['description']."</p></div>";
  }
  echo '</div>';
} else if (isset($_GET['sf'])) {
  $a = mysql_query("select name from ibf_forums where id = ".(int)$_GET['sf']);
  $forumtitle = mysql_fetch_assoc($a);
  $forumtitle = $forumtitle['name'];
  echo '<a style="margin:2px;font: bold 12px Tahoma" href="?idx">Staredit Network</a> -> <a style="font: bold 12px Tahoma" href="?sf=<'.$_GET['sf'].'">'.$forumtitle.'</a>
  <div class="Section"><div class="SectionHeader"><span class="lph">Last Post</span>Topic Title</div>';
  mysql_free_result($a);
  @$a = mysql_query("select tid, title, description, starter_name, last_poster_name, last_post from ibf_topics WHERE forum_id = ".(int)$_GET['sf']." ORDER BY pinned DESC, last_post DESC LIMIT " . (($_GET['p']*25) ? $_GET['p']*25 . ", " : '') . "25");
  $flipper = 0;
  while ($row = mysql_fetch_assoc($a)) {
    echo '<div class="Forum'.(($flipper ^= 1)+1).'" style="line-height:12px;padding: 8px;">'
         ."<div class='LastPost".($flipper+3)."'>".date("Y-m-d H:i:s",$row['last_post'])."<br />"
         .$row['last_poster_name']."</div><a class='TopicTitle' href='?st=".$row['tid']."'>".$row['title']."</a><br />
         <p style='font: normal 8px Tahoma;'>".$row['starter_name']."</p></div>";
  }
  mysql_free_result($a);
  echo "</div>";
  echo "<a href='?sf=". $_GET['sf'] ."&p=" . ($p+1) . "'>Next Page (" . ($p+1) . ")</a>";
} else if (isset($_GET['st'])) {

  $a = mysql_query("select t.title, f.name, t.forum_id from ibf_topics as t left join ibf_forums as f on t.forum_id = f.id where tid = ".(int)$_GET['st']);
  echo mysql_error();
  $t = mysql_fetch_assoc($a);
  $topictitle = $t['title'];
  $forumname = $t['name'];
  $forumid = $t['forum_id'];
  mysql_free_result($a);
  $a = mysql_query("select author_name, post_date, post from ibf_posts where topic_id = ".(int)$_GET['st']." ORDER BY post_date LIMIT " . (($p) ? $p*25 . ", " : '') . "25");
  ?><a style="margin:2px;font: bold 12px Tahoma" href="?idx">Staredit Network</a> -> <a href="?st=<?=$forumid?>"><?=$forumname?></a> -> <a style="font: bold 12px Tahoma" href='?st=<?=$_GET['st']?>'><?=$topictitle?></a>
  <div class="Section">
  <?php
  $flipper = 0;
  while ($row = mysql_fetch_assoc($a)) {
  	$row['post'] = str_replace("<#EMO_DIR#>", "smilies", $row['post']);
    echo "<div class='Forum".(($flipper ^= 1)+1)."'>".
      '<div class="SectionHeader" style="margin: -10px;"><span class="lph" style="width: auto">Report, edit, etc...</span>'.
      "Posted by ".$row['author_name']." on ".date("Y-m-d \\a\\t H:i:s",$row['post_date'])."</div>".
      '<div style="padding-top: 10px;min-height: 25px;">'.$row['post'] ."</div></div>";
  }
  echo "</div>";
  mysql_free_result($a);
  echo "<a href='?st=". $_GET['st'] ."&p=" . ($p+1) . "'>Next Page (" . ($p+1) . ")</a>";
  mysql_close($connect);
}
?>
<div class="Footer"></div>
</div></body></html><?ob_end_flush();?>
