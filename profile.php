<?php
/*
This page shows a prorile of the user
*/
get_header();
?>

    <main id="site-content" role="main">

    <?php
        global $rsa;
        $id = $_GET['user'];

        //my_dg($rsa);
    ?>
        <div>
            <h4>Name: <?php echo get_the_author_meta('display_name', $id); ?></h4>
            <?php echo get_avatar($id, 80); ?>
            <p>Address: <?php echo $rsa->decode(get_the_author_meta('address', $id ) ) ?></p>
            <p>Phone: <?php echo $rsa->decode(get_the_author_meta('phone', $id))?></p>
            <p>Famely status: <?php echo $rsa->decode(get_the_author_meta('fstatus', $id))?></p>
            <p>Gender: <?php echo $rsa->decode(get_the_author_meta('gender', $id))?></p>

        </div>
    </main><!-- #site-content -->


<?php
get_footer();


