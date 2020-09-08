<?php 

declare(strict_types = 1);



/**
 * 
 * Plugin Name: Blog Plugins
 * Description : This provides the endpoints for the blog endpoints
 * Version: 1.0
 * Author : Joshua Wiggins
 */


 function cleaned($postTitle) {
        //cleaning process to get a clean hypenated title cleaned of any special characters
        $cleanStep1 = str_replace(".","",$postTitle);
        $cleanStep2 = str_replace("!","",$cleanStep1);
        $cleanStep3 = str_replace("?","",$cleanStep2);
        $cleanStep4 = str_replace("(","",$cleanStep3);
        $cleanStep5 = str_replace(")","",$cleanStep4);
        $cleanStep6 = str_replace("@","",$cleanStep5);
        $cleanStep7 = str_replace("&","",$cleanStep6);
        $cleanStep8 = str_replace("#","",$cleanStep7);
        $cleanStep9 = str_replace("$","",$cleanStep8);
        $cleanStep10 = str_replace("\"","",$cleanStep9);
        $cleanStep11 = str_replace("'","",$cleanStep10);
        $cleanStep12 = str_replace(":","",$cleanStep11);
        $cleanStep13 = str_replace(";","",$cleanStep12);
        $cleanStep14 = str_replace(">","",$cleanStep13);
        $cleanStep15 = str_replace("<","",$cleanStep14);
        $cleanStep16 = str_replace("[","",$cleanStep15);
        $cleanStep17 = str_replace("]","",$cleanStep16);
        $cleanStep18 = str_replace("%","",$cleanStep17);
        $cleanStep19 = str_replace("\\","",$cleanStep18);
        $cleanStep20 = str_replace("|","",$cleanStep19);
        $cleanStep21 = str_replace("-","",$cleanStep20);
        $cleanStep22 = str_replace("+","",$cleanStep21);
        $cleanStep23 = str_replace("_","",$cleanStep22);
        $cleanStep24 = str_replace("=","",$cleanStep23);
        $cleanStep25 = str_replace("*","",$cleanStep24);
        $cleanStep26 = str_replace("^","",$cleanStep25);

        $cleanStep27 = trim(preg_replace('/\s\s+/', ' ', str_replace("\n", " ", $cleanStep26))); //leaves only a single space between words
        $cleanStep28 = str_replace(" ","-",$cleanStep27);
        $cleanStep29 = strtolower($cleanStep28);

        return $cleanStep29;
 }

function getPost($params) {
  

    $hyphenatedPostTitle = $params["title"]; //single word and sometimes a hypenated word

    
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

            
            $urlReadyTitle = cleaned($post->post_title);
            $proposedMatch = $urlReadyTitle;

            $revealPost = get_post_meta($post->ID, "show", true); //flag which determines if a post should appear on the website.

           
                if(strcmp($hyphenatedPostTitle,  strtolower($proposedMatch)) == 0){

                    if(strcmp($revealPost,"1")==0) {


                        $featuredImage = get_the_post_thumbnail_url($post->ID, 'medium') ? get_the_post_thumbnail_url($post->ID, 'medium') : "";
                        if(strlen($featuredImage) > 1){
                            $split1 = explode('/wp-content',$featuredImage);
                            $featuredImage = $split1[1];
                        }
                       
                        $matchedPost["title"] = $post->post_title;
                     
                        $matchedPost["date"] = date('F m, Y', strtotime($post->post_date) );
                        $matchedPost['featured_image_url'] = get_the_post_thumbnail_url($post->ID, 'medium') ? get_the_post_thumbnail_url($post->ID, 'medium') : ""; 
                       // $matchedPost['featured_image_url'] = $featuredImage; 
                        $matchedPost["content"] = $post->post_content;
                        $matchedPost['url_cleaned_title'] = $urlReadyTitle;
                        $matchedPost["excerpt"] = $post->post_excerpt;
                        //$matchedPost["postToBeRevealed"] = true;
    
                    
                       // $matchedPost["postExist"] = true;
                        
                        $res = new WP_REST_Response (array(
                            'post' => $matchedPost,
                            'goodObject' => true
                        ));
        
                        $res->set_status(200); 
                        
                        
                        return  $res;

                    }
                    else {
                        $matchedPost["title"] = "";
                        $matchedPost["date"] = "";
                        $matchedPost['featured_image_url'] = ""; 
                        $matchedPost["content"] = "";
                        $matchedPost["excerpt"] = "";
                        //$matchedPost["postToBeRevealed"] = false;

                        $res = new WP_REST_Response (array(
                            'post' => $matchedPost,
                            'goodObject' => false
                        ));
        
                        $res->set_status(410);
                        
                        
                        return  $res;
                    }
                  
                }
               
            

            
            
    }

    
    $matchedPost["title"] = "";
    $matchedPost["date"] = "";
    $matchedPost['featured_image_url'] = ""; 
    $matchedPost["content"] = "";
    $matchedPost["excerpt"] = "";
   // $matchedPost["postToBeRevealed"] = false;

    $res = new WP_REST_Response (array(
        'post' => $matchedPost,
        'goodObject' => false
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
        'posts_per_page' => 10, //controls the amount of posts per page
        'order' => 'DSC',
        'orderby' => 'date',
        'paged' => $pageNumber

    );

    $query = new WP_Query($args);
    $posts = $query->posts;
    $maxNumPost = $query->max_num_pages;


    $postList = array(); //an empty array always defaults to []
    $i = 0;

    foreach ($posts as $post){

        
        $urlReadyTitle = cleaned($post->post_title);
        $featuredImage = get_the_post_thumbnail_url($post->ID, 'medium') ? get_the_post_thumbnail_url($post->ID, 'medium') : "";
        if(strlen($featuredImage) > 1){
            $split1 = explode('/wp-content',$featuredImage);
            $featuredImage = $split1[1]; //  E.g     /uploads/20/09/ima.png
        }
       
         $revealPost = get_post_meta($post->ID, "show", true); //flag which determines if a post should appear on the website.

        if(strcmp($revealPost,"1")==0) {
            $postObj = array(
                'id' => $post->ID,
                'title' => $post->post_title,
                'author' => $post->post_author,
                'date' => date('F m, Y', strtotime($post->post_date) ),
                'excerpt' => $post->post_excerpt,
                'featured_image_url' => get_the_post_thumbnail_url($post->ID, 'medium') ? get_the_post_thumbnail_url($post->ID, 'medium') : "",
                'url_cleaned_title' => $urlReadyTitle
            );
        //     $postList[$i]['id'] = $post->ID;
        //     $postList[$i]['title'] = $post->post_title;
        //     $postList[$i]['author'] = $post->post_author;
        //     $postList[$i]['date'] = date('F m, Y', strtotime($post->post_date) );
        //     $postList[$i]['excerpt'] =$post->post_excerpt;
        //    // $postList[$i]['featured_image_url'] = $featuredImage; //featured image
        //    $postList[$i]['featured_image_url'] = get_the_post_thumbnail_url($post->ID, 'medium') ? get_the_post_thumbnail_url($post->ID, 'medium') : "";
        //     $postList[$i]['url_cleaned_title'] = $urlReadyTitle;
        //$postList[$i] = $postObj;
        array_push($postList, $postObj); //push is the only way of insuring that we are building an indexed array
        }
      
       
        

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