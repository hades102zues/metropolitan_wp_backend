<?php 

declare(strict_types = 1);



/**
 * 
 * Plugin Name: Blog Plugins
 * Description : This provides the endpoints for the blog endpoints
 * Version: 1.0
 * Author : Joshua Wiggins
 */


function getPost($params) {
  

    $hyphenatedPostTitle = $params["title"]; //single word and sometimes a hypenated word
    $cleanedPageTitle = strtolower(str_replace("-", " ",$hyphenatedPostTitle ));

    
    $args = array(
        'post_type' => 'custom_posts',
        'order' => 'DSC',
        'orderby' => 'date'
      //  'post_name' => $hyphenatedPostTitle
    );
   
    $query = new WP_Query($args);
    $posts = $query->posts;
 
   
    $matchedPost = array();
 
    foreach ($posts as $post){

            //dont use the post_name
            //It is only updated at the time the post is first published.
            //Hence it does not change along side the post title

            if(strcmp($cleanedPageTitle,  strtolower($post->post_title)) == 0){

                $matchedPost["title"] = $post->post_title;
                $matchedPost["date"] = date('F m, Y', strtotime($post->post_date) );
                $matchedPost["content"] = $post->post_content;

                
                $res = new WP_REST_Response (array(
                    'post' => $matchedPost
                ));

                $res->set_status(200);
                
                
                return  $res;
           
            }
    }


    $res = new WP_REST_Response (array(
        "server_message" => "Post not found."
    ));

    $res->set_status(404);
    
    
    return  $res;



 
  }
 
  add_action('rest_api_init', function(){
     register_rest_route('wapi','/wp-post/(?P<title>\w+(-\w+)*)',array(  /*blog/2 */
         'methods' => WP_REST_SERVER::READABLE,// wp equivalent of get
          'callback' => 'getPost' //Error location:The handler for the route is invalid
         
      ));
  });
  
 

 function getPosts($data) { //object of uri params
    $pageNumber = $data["id"]; //captures the parameter
   // $pageNumber = $_GET["page"]; //for use with uri  query strings

    $args = array(
        'post_type' => 'custom_posts',
        'post_per_page' => 10,
        'order' => 'DSC',
        'orderby' => 'date',
        'paged' => $pageNumber

    );

    $query = new WP_Query($args);
    $posts = $query->posts;
    $maxNumPost = $query->max_num_pages;


    $postList = array();
    $i = 0;

    foreach ($posts as $post){
        $postList[$i]['id'] = $post->ID;
        $postList[$i]['title'] = $post->post_title;
        $postList[$i]['author'] = $post->post_author;
        $postList[$i]['date'] = date('F m, Y', strtotime($post->post_date) );
        $postList[$i]['excerpt'] =$post->post_excerpt;
        $postList[$i]['image_url'] = get_the_post_thumbnail_url($post->ID, 'Large') ? get_the_post_thumbnail_url($post->ID, 'medium') : ""; //featured image

        $i++;
    }

    $res = new WP_REST_Response (array(
        'posts'=> $postList,
        'maxNumPages' => $maxNumPost
   
    ));

    $res->set_status(200);
      

 
    return  $res;


 }


 //get blog posts
 add_action('rest_api_init',function(){

    ///get-post-page/(?P<id>\d+) => /get-post-page/2 or /get-post-page/
    ///get-post-page => /get-post-page or /get-post-page?<x>=<b>

     register_rest_route('wapi','/wp-post-items-page/(?P<id>\d+)',array( // never give the first arguement an empty string
        'methods' => WP_REST_SERVER::READABLE,// wp equivalent of get
         'callback' => 'getPosts' //Error location:The handler for the route is invalid
        
     ));
 });