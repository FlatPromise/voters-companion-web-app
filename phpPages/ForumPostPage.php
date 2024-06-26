<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Voters' Companion</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bulma@0.9.3/css/bulma.min.css">
    <link rel="stylesheet" href="../resources/css/voterscompanion.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
    <script src="../resources/js/forum.js"></script>
    <?php

    require_once 'footer-header/header.php';
    require_once '../phpScripts/globals.php';
    require '../phpScripts/forum_processor.php';

    //initialize variables
    $post_header = $_POST['post_header'];


    if(isset($_POST['post_user'])){
      $post_user = $_POST['post_user'];
    }
    else{
      $post_user = '';
    }
      
    $post_message = fetchPostInfo($DB_CREDENTIALS, 'message', $post_header);
    $post_id = fetchPostInfo($DB_CREDENTIALS,'post_id', $post_header);
    $check_likes = checkLikes($DB_CREDENTIALS, 'post_id');
    $post_likes = (string) fetchLikes($DB_CREDENTIALS, $post_id);


    //replies
    $reply_id = fetchReplyElement($DB_CREDENTIALS, $post_id, 'acc_id');
    $reply_messages = fetchReplyElement($DB_CREDENTIALS, $post_id, 'message');
    $reply_post_id = fetchReplyElement($DB_CREDENTIALS, $post_id, 'post_id');


  
    ?>
  </head>

  <style>
    .post, .reply{
      border-bottom-style: solid;
      border-color: rgb(235, 231, 231);
      margin-bottom: 10px;
      padding-bottom: 7px;

    }
    
    .specific-post{
      border-bottom-style: solid;
      border-color: rgb(235, 231, 231);
      margin-bottom: 10px;
      padding-bottom: 7px;
    }

    .post-icons{
      padding-right: 10px;
    }

    .transparent {
    cursor: pointer;
    border: 1px solid white;
    background-color: transparent;
    color: gray;

   }

   .button-to-link {
    background: none;
    border: none;
    padding: 0;

    /*input has OS specific font-family*/
    color: #069;
    cursor: pointer;
  }

    .blue {
      cursor: pointer;
      border: 1px solid white;
      border-radius: 0.7ch;
      background-color: #24a0ed;
      color: white;
    }
  </style>

  <!-- Font Awesome  -->
<script src="https://kit.fontawesome.com/29800fcb6c.js" crossorigin="anonymous"></script>


  <body>
    <div class="column is-10 is-offset-1">
      <div class="box content">
        
        <article class="specific-post">
          <?php

            echo "
            <form method='POST'>
            <input type='hidden' name='post_user' value= '" . $post_user . "'></input>


            <h4 class='post_header'>".$post_header."</h4>
            <p class = 'post-message'>".$post_message ."</p>

                  <div class='media'>
            <div class='media-left'>
              <p class='image is-32x32'>
                <img src='http://bulma.io/images/placeholders/128x128.png'>
              </p>
            </div>
            <div class='media-content'>
              <div class='content'>
                <p>
                  <button formaction='usrPage.php' class='button-to-link' type='submit'>".$post_user. "</button>
                  <span class='tag'>Question</span>
                </p>
              </div>
            </div>
            </form>
            <div class='media-right'>";
            $is_post_liked = checkIfPostLiked($check_likes, $post_id);
      if ($is_post_liked) {
        echo "<span>
              <button 
              type='button'
              data-post-id='$post_id' 
              id ='btn'
              class='blue post-icons active-like'
              name='like-button'
              >
              <i class='fa-solid fa-thumbs-up'>
              </i>
              <span 
              name='like-count'>
              $post_likes</span>
              </button>
              </span>";
      } else {
        echo "<span>
              <button 
              type='button'
              data-post-id='$post_id' 
              id ='btn'
              class='transparent post-icons'
              name='like-button'
              >
              <i class='fa-solid fa-thumbs-up'>
              </i>
              <span 
              name='like-count'>
              $post_likes</span>
              </button>
              </span>";
      }
            echo "
            </div>
          </div>
            ";

          ?>
         
        </article>

        <?php
        echo "<article class='reply column is-offset-1'>";

        $index = 0;
        foreach($reply_messages as $replies){
          $reply_users = fetchReplyTag($DB_CREDENTIALS, $reply_id[$index]);
          $post_likes_reply = (string) fetchLikes($DB_CREDENTIALS, $reply_post_id[$index]);
          $is_reply_liked = checkIfPostLiked($check_likes, $reply_post_id[$index]);


        echo " 
        <form method='POST'>
        <input type='hidden' name='post_user' value= '" . $reply_users . "'></input>

        <p class = 'reply'>".$replies."</p>
        <div class='media'>
          <div class='media-left'>
            <p class='image is-32x32'>
              <img src='http://bulma.io/images/placeholders/128x128.png'>
            </p>
          </div>
          <div class='media-content'>
            <div class='content'>
              <p>
              <button formaction='usrPage.php' class='button-to-link' type='submit'>". $reply_users ."</button> 
                <span class='tag'>Reply</span>
              </p>
            </div>
          </div>
          <div class='media-right'>";

          if ($is_reply_liked) {
            echo "<span>
                  <button 
                  type='button'
                  data-post-id='".$reply_post_id[$index]."' 
                  id ='btn'
                  class='blue post-icons active-like'
                  name='like-button'
                  >
                  <i class='fa-solid fa-thumbs-up'>
                  </i>
                  <span 
                  name='like-count'>
                  $post_likes_reply</span>
                  </button>
                  </span>";
          } else {
            echo "<span>
                  <button 
                  type='button'
                  data-post-id='".$reply_post_id[$index]."' 
                  id ='btn'
                  class='transparent post-icons'
                  name='like-button'
                  >
                  <i class='fa-solid fa-thumbs-up'>
                  </i>
                  <span 
                  name='like-count'>
                  $post_likes_reply</span>
                  </button>
                  </span>";
          }

          echo "
          </div>
        </div>
        </form>
      </article>";
      $index++;
      }
        

        ?>

        <?php

        if(strcmp($post_user, '') != 0){
        echo "
        <article class='reply column is-offset-1'>
        <form action='../phpScripts/upload.php'  method='POST'>
          <input type= 'hidden' name='is_reply' value='1'> 
          
          <input type='hidden' name='post_id' value='".$post_id."'>
         
          <div class='message-input-div media'>
            <textarea class='textarea is-medium is-hovered' name='message-input' placeholder='Express yourself...'></textarea>
          </div>
          <div class='media'>
            <div class='media-content'>
              (1000 Characters max)
            </div>
            <div class='media-right'>
            <button class='button' name='post' type='submit'><strong> Post </strong></button>
            </div>
          </div>
        </form>
        </article>
        ";
        }


        ?>
      </div>
    </div>
    </div>
  </body>
</html>