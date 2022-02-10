# Primary Category Selector

![Primary Category Logo ](https://i.imgur.com/EwOS0gz.jpeg)

Primary category selector is a WordPress plugin that let select a category for a posts as a Primary. This is helpful to query for posts using the primary category, since a post or page can have multiple categories.

## Installation
Just upload the zip file to your WordPress instalation and activate the plugin.

Zip file can be found in release page of this repository: [Releases](https://github.com/janietom2/primary-category-select/releases/tag/Release)

## Features
Metabox on Post page to assign the primary category.

- Dropdown will only contain categories that have been selected.
- Shortcode to filter posts by the meta value `primary_category`.
- Shortcode detects if any posts are available and/or category exists to avoid any errors on the front-end.
- If category is unselected the primary category resets to default value (none) to avoid any errors.

## Usage
Go to your post and look for the metabox **Set Primary Category** and select the primary category you need.

For the shortcode:

1) This is the shortcode suntax `[show_primary_cat_posts cat_id="category_id"]`
2) Put this code in your post/page/widget and will print:
* Title
* Content (Cut to 50 words)
* Read more (permalink to post)
* Separator (`<hr>` tag)

## Docker File
A docker compose file is being added that contains a wordpress and MySQL installation. It contains WordPress 5.9 and MySQL 5.7 (Also support for Apple M1). It is recommended to use docker to have the same environment.

## Notes
Please report any issues here on this GitHub repository.