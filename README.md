# TestimonialsPost Type

WordPress plugin to add testimonials as a Custom Post Type

# Description

This plugin includes a couple common features that are used with custom post types:

* Registers a Custom Post Type
* Registers a custom taxonomy
* Registers a few metaboxes (Client Name, Job Title, email, website name, website URL)
* Adds the featured image to use if the client does not have a Gravatar
* Adds the post count to the admin dashboard
* Adds fwdd Testimonials widget

# Usage

To display the testimonials, add the following shortcode to your post or page content:
~~~
[testimonials][/testimonials]
~~~

If you're comfortable editing template files, add this line of code to your template file:
~~~PHP
<?php the_testimonials(); ?>
~~~

Optionally, you can control the number of testimonials to show, the order, or show specific testimonials.

~~~PHP
<?php the_testimonials( $posts_per_page, $order_by, $testimonial_id ); ?>
~~~

Where $posts_per_page is the number of testimonials to show, $order_by is the field to order by, and $testimonial_id is an id or array of ids to show. 


## Requirements

* WordPress 3.8 or higher
