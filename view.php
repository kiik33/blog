<?php
session_start();
include_once 'db.php';
include_once './inc/meta.php';
?>
<?php
$id = $_GET['id'];
$query = "select * from `arts` where `id`='$id' limit 1";
$result = mysql_query($query);
$row = mysql_fetch_array($result);
$query = "update `arts` set `hits`=hits+1 where `id`='$id'";
$result = mysql_query($query);
if(isset($_POST['submit'])){
    $uname = $_POST['username'];
    $plcontent = $_POST['content'];
    if($_REQUEST['vericode'] == $_SESSION['authcode']){
        if($uname != '' && $plcontent != ''){
            $query = "insert into `reply` (`r_id`,`art_id`,`name`,`pl_content`,`pl_time`) values (NULL,'$id','$uname','$plcontent',now())";
            if(mysql_query($query)){
                echo "<script>alert('评论成功啦！');window.location.href='view.php?id=$id'</script>";
            }else{

                echo "<script>alert('再试试呗？');window.location.href='view.php?id=$id'</script>",mysql_error();
            }
        }else{
            echo "<script>alert('姓名和内容不可为空');window.location.href='view.php?id=$id'</script>",mysql_error();
        }
    }else{
        echo "<script>alert('据我估计，可能是验证码错了');window.location.href='view.php?id=$id'</script>";
    }
    die;
}
?>
<title><?php echo $row['title']?>-田超的博客|原创独立个人博客</title>
<?php
  include_once './inc/header.php';
?>
<div class="container">
    <div class="row">
        <div class="col-sm-9 blog-main">
            <div class="blog-header">
                <h1 class="blog-title"><?php echo $row['title']?></h1>
            </div>
            <div class="row">
                <div class="col-sm-12">
                    <div class="blog-content">
                        <p><?php echo $row['content']?></p>
                    </div>
                    <div class="original">
                        <p>© 本文版权归 田超 所有，任何形式转载需取得作者授权。</p>
                    </div>
                    <div class="time">
                        <small>
                            <img src="images/time.png" title="发布时间"><?php echo $row['time']?>
                            <img src="images/eye.png" title="阅读量" class="view"><a><?php echo $row['hits']?></a>
                        </small>
                    </div>
                    <div class="col-sm-3 blog-side-m">
                        <div class="recommend">
                            <h2>随机推荐</h2>
                            <ul>
                                <?php
                                $query = "select * from `arts` order by rand() limit 5";
                                $result = mysql_query($query);
                                while ($row = mysql_fetch_array($result)){
                                    ?>
                                    <li>
                                        <a href="view.php?id=<?php echo $row['id']?>" target="_blank"><?php echo $row['title']?></a>
                                        <span><?php echo iconv_substr($row['time'],0,10,'utf-8');?></span>
                                    </li>
                                    <?php
                                }
                                ?>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-12 TCPingLunBox">
                <form class="form-horizontal" action="" method="post" id="TCpinglun">
                    <div class="form-group">
                        <label class="col-sm-2 control-label">称呼：</label>
                        <div class="col-sm-3">
                            <input class="form-control username" type="text" name="username" placeholder="请输入您的称呼?"  />
                            <small class="name-prompt"></small>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label">内容：</label>
                        <div class="col-sm-9 send">
                            <textarea class="form-control content" name="content" rows="4" placeholder="说着玩呗"></textarea>
                            <small class="content-prompt"></small>
                            <a href="javascript:;" class='faces'></a>
                            <div class="face"></div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label">验证码：</label>
                        <div class="col-sm-2">
                            <input class="form-control veri" type="text" name="vericode">
                        </div>
                        <div class="col-sm-6">
                            <img id="vericode_img" border="0" src="veri_zh.php?r=<?php echo rand();?>" />
                            <a href="javascript:;" class="changecode" onclick="document.getElementById('vericode_img').src='veri_zh.php?r='+Math.random()">换一个？</a>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-sm-offset-2">
                            <div class="col-sm-2">
                                <button type="submit" name="submit" class="btn btn-primary submit">评论</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="col-sm-11 TCPingLunList">
                <div class="row">
                    <div class="col-sm-12">
                        <ul class="list-group list">
                            <?php
                            $query = "select * from `reply` where art_id=$id order by r_id DESC limit 10";
                            $result = mysql_query($query);
                            while ($row = mysql_fetch_array($result)){
                                ?>
                                <li class="list-group-item lgi">
                                    <h4><?php echo $row['name'] ?> <small>说：</small><small class="pull-right plTime">
                                            <?php echo $row['pl_time'] ?></small></h4>
                                    <p><?php echo iconv_substr($row['pl_content'],0,200,'utf-8');?></p>
                                    <div class="ly_reply"><em>作者回复：</em>
                                        <span class="re_con"><?php echo $row['repl_content'] ?></span>
                                        <span><?php echo $row['repl_time'] ?></span>
                                    </div>
                                </li>
                                <?php
                            }
                            ?>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-3 blog-side">
            <div class="blog-myself">
                <div class="pic">
                    <img src="images/logo2.jpg">
                </div>
                <div class="con">
                    <a href="about.php">田超</a>
                    <p>微博：<a href="http://weibo.com/aizaifengyexia">@田超7C</a></p>
                    <p>喜欢写文字的老青年。</p>
                </div>
            </div>
            <div class="recommend">
                <h2>随机推荐</h2>
                <ul>
                <?php
                $query = "select * from `arts` order by rand() limit 5";
                $result = mysql_query($query);
                while ($row = mysql_fetch_array($result)){
                    ?>
                    <li>
                        <a href="view.php?id=<?php echo $row['id']?>" target="_blank"><?php echo $row['title']?></a>
                        <span><?php echo iconv_substr($row['time'],0,10,'utf-8');?></span>
                    </li>
                <?php
                }
                ?>
                </ul>
            </div>
            <div class="donate">
                <a href="javascript:;">赞赏</a>
                <img src="images/blog_wxpay.png">
            </div>
        </div>
    </div>
	<p id="back-to-top"><a href="#top"><img src="images/backtop.png"></a></p>
</div>
<?php require_once'./inc/footer.php' ?>
<script type="text/javascript" src="./js/face.js"></script>
<script src="js/404.js"></script>