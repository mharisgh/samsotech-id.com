<?php
include './includes/connections.php';

$get_blogs_recent = get_alldata("tbl_posts ORDER BY date DESC LIMIT 5");
while ($res_blogs_recent = $get_blogs_recent->fetch_assoc()) {
    $link_title1 = str_replace(" ", "-", $res_blogs_recent['title']);
    $link_title1 = str_replace("&", "and", $link_title1);
    //echo $res_blogs_recent['title']
    ?>
    <!--    <div class="col-md-5">
            <img src="<?php echo substr($res_blogs_recent['image'], 3); ?>" alt="<?php echo $res_blogs_recent['title']; ?>" class="w-100">
        </div>-->
    <div class="col-md-12">
        <p class="mb-0"><a href="blog-details.php?blog=<?php echo $link_title1; ?>"><?php echo $res_blogs_recent['title']; ?></a></p>
    </div>
    <div class="col-md-12 mb-3" style="font-size: 11px;">
        <time datetime=""><?php echo date('M j<\s\up>S<\/\s\up> Y', strtotime($res_blogs_recent['date'])); ?></time>
    </div>
    <?php
}
?>

