## Team Post Type

WordPress plugin to add Team portfolios as a Custom Post Type

### Description

This plugin includes a couple common features that are used with custom post types:

* Registers a Custom Post Type
* Registers a custom taxonomy
* Registers a few metaboxes (Title, Twitter, Facebook, LinkedIn)
* Adds the featured image to the admin column display
* Adds the post count to the admin dashboard
* Adds RivalMind Team widget

### Usage

To display the Team Profiles, add the following shortcode to your post or page content:
~~~
[RivalMind_team][/RivalMind_team]
~~~

If you're comfortable editing template files, add this line of code to your template file:
~~~PHP
<?php the_team(); ?>
~~~

Optionally, you can control the number of profiles to show, what category, or show specific team members.

~~~PHP
<?php the_team( $posts_per_page, $cat, $team_id ); ?>
~~~

Where $posts_per_page is the number of profiles to show, $cat is the category id, and $team_id is an id or array of ids to show. 

### Requirements

* WordPress 3.8 or higher

### Credits

Based on the terrific boilerplate by [Devin Price](http://www.wptheming.com/).  The "Dashboard Glancer" class and much re-used code from the Portfolio Post Type plugin by [Gary Jones](http://gamajo.com/).