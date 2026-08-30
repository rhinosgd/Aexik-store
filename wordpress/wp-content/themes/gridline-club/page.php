<?php get_header(); ?>
<main id="main" class="site-main gl-container gl-content-shell">
	<?php while ( have_posts() ) : the_post(); ?>
		<article <?php post_class( 'gl-page' ); ?>><h1><?php the_title(); ?></h1><div class="gl-prose"><?php the_content(); ?></div></article>
	<?php endwhile; ?>
</main>
<?php get_footer(); ?>

