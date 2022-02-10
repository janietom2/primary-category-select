<?php 

/**
 * Plugin Name: Primary Category Selector
 * Description: Select a primary category for post or page. Shortcode available to show post by categoty id: [show_primary_cat_posts cat_id="category_id"]
 * Version: 1.0.0
 * Requires at least: 5.9
 * Requires PHP: 7.2 
 * Author: Jose Nieto
 * License: GPL v2
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 */

class primary_category{

    public function __construct(){
        add_action('add_meta_boxes', [$this, 'create_meta_box_category']);
        add_action('save_post', [$this, 'save_primary_post']);
        add_shortcode('show_primary_cat_posts', [$this, 'show_posts_by_cat_id']);
    }

    /**
     * Creating shortcode to test it can query by the meta value "primary_category"
     * Shotcode: [show_primary_cat_posts cat_id="<category_id>"] can be used in any post/page/html space
     * This prints:
     * - Title
     * - Content (Short version)
     * - Read more link
     * - HR Separator
     */
    public function show_posts_by_cat_id($atts) {

        $attribute = shortcode_atts( 
            ['cat_id' => '0'], 
            $atts 
        );

        // Parameters to query by meta value of the post in this case "primary_category"
        $args = 
            ['meta_query' => 
                [
                    [
                        'key' => 'primary_category',
                        'value' => $attribute['cat_id']
                    ]
                ],
                'posts_per_page' => -1  // Show all
            ];
        
        // Load posts
        $posts = get_posts($args);

        // Check if category exists
        if(get_cat_name($attribute['cat_id']) == ''){
            ob_start();
            ?>
                <h3>
                    Selected category does not exist.
                </h3>

            <?php 
            return ob_get_clean();
        }

        // Check if there are posts on that primary category
        if(count($posts) > 0) {

            ob_start();
            ?>

            <!-- Show which primary category we are sorting for -->
            <h3>
                Showing post of primary category: 
                <strong><?php echo get_cat_name($attribute['cat_id']); ?></strong>
            </h3>

            <!-- Show article/posts/pages -->
            <?php foreach($posts as $post): ?>

                <article id="<?php echo $post->ID; ?>">
                <h1> <?php echo $post->post_title; ?>  </h1>
                <hr>
                <p> <?php echo wp_trim_words( $post->post_content, 50, NULL ); ?> </p>
                <a href="<?php echo get_permalink($post->ID); ?>">Read more...</a>
                <article>
            
            <?php endforeach; 

            return ob_get_clean();

        } else {

            ob_start();
            ?>

                <h3>
                    No post or pages for primary category: 
                    <strong><?php echo get_cat_name($attribute['cat_id']); ?></strong>
                </h3>

            <?php
            return ob_get_clean();

        }
    }

    /**
     * Check if the category selected as primary is actually selected in the post.
     */
    private function is_category_selected($selected_category, $post_id) {
        $all_categories = get_the_category($post_id);
    
        foreach($all_categories as $category){
            if($selected_category == $category->term_id){
                return true;
            }
        }
        return false;
    }

    public function save_primary_post($post_id) {
        // Using a bit of memory space creating a variable, but readability is important.
        $select = $_POST['primary_category'];

        if(isset($select) && is_numeric($select)) {

            // Secure the input by sanitizing it.
            $primary_category = sanitize_text_field($select);

            /**
             * This condition will reset to default (No primary category) if the category is unselected *from the category from the post.
             *  */ 
            if($this->is_category_selected($primary_category, $post_id)){
                update_post_meta($post_id, 'primary_category', $primary_category);
                return true;
            } else{
                update_post_meta($post_id, 'primary_category', 0);
                return false;
            }
        }
    }

    public function create_meta_box_category() {
        add_meta_box('wpc_edit', 'Set Primary Category', [$this, 'meta_box_html'], ['post']);
    }

    // HTML Content for the box
    public function meta_box_html() {

        // Fetch post meta
        $selected = get_post_meta(get_the_ID(), 'primary_category', true);

        // Select only categories that are selected
        $all_categories = get_the_category(get_the_ID());

        ob_start();
        ?>
            <p>
                <em>Select the primary category for this post.</em>
            </p>
            <label id="primary_category_selector_label" for="primary_category_selector_dropdown">
                <select name="primary_category" id="primary_category_selector_dropdown">
                    <!-- default value -->
                    <option value="0" <?php selected($selected, 0, true); ?>>No primary category</option>
                    <!-- default value -->

                    <!-- Print selected categories -->
                    <?php foreach($all_categories as $category): ?>
                        <option value="<?php echo $category->term_id; ?>" <?php selected($selected, $category->term_id, true) ?>> <?php echo $category->name; ?></option>
                    <?php endforeach; ?>
                    <!-- Print selected categories -->

                </select>
            </label>

            <p>
                <em><a href="/wp-admin/edit-tags.php?taxonomy=category" target="_blank">Need more categories? Click here.</a></em>
            </p>

        <?php

        $content = ob_get_clean();
        echo $content;

    }
}
 
new primary_category();

?>