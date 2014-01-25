<!doctype html>
<html>
    <head>
        <title><?php wp_title('|', true, 'right'); ?> <?php bloginfo('name'); ?></title>
        <link rel="shortcut icon" href="<?php bloginfo('template_url'); ?>/images/favico.ico" type="image/x-icon" />
        <?php wp_head(); ?>
    </head>

    <body <?php
// body_class();
    ?>>
        <div id="wrapper">

            <div id="header">
                <a id="logo" href="<?php bloginfo('home'); ?>">TIDE</a>
                <ul id="widget-header">
                    <?php dynamic_sidebar('header'); ?>
                </ul>
            </div>
            <?php
            wp_nav_menu(array(
                'theme_location' => 'main_nav',
                'container' => false,
                'menu_class' => 'menu-class',
                'menu_id' => 'header_menu'
            ));
            ?>
            <div id="content">
                <?php
                if (have_posts()) {
                    the_post();
                    if (!is_front_page()) {
                        the_title('<h1>', '</h1>');
                    }
                    the_content();
                } else {
                    echo '<h1>Not Found :(</h1>';
                }
                ?>
            </div>
            <br style="clear: both" />
        </div>

<?php wp_footer(); ?> 

        <script type="text/javascript">

            var _gaq = _gaq || [];
            _gaq.push(['_setAccount', 'UA-3547190-3']);
            _gaq.push(['_trackPageview']);

            (function() {
                var ga = document.createElement('script');
                ga.type = 'text/javascript';
                ga.async = true;
                ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
                var s = document.getElementsByTagName('script')[0];
                s.parentNode.insertBefore(ga, s);
            })();

        </script> 

    </body>
</html>
