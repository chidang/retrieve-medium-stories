<?php
$user = "algorand";

if (isset($_GET['user']))  {
  $user = preg_replace("/[^A-Za-z0-9_]/", '', $_GET['user']);
}

$url = "https://medium.com/@$user/latest?format=json";
//  Initiate curl
$ch = curl_init();
// Will return the response, if false it print the response
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
// Set the url
curl_setopt($ch, CURLOPT_URL, $url);
// Execute
$result=curl_exec($ch);
// Closing
curl_close($ch);

$result = str_replace('])}while(1);</x>', '', $result);
$data = json_decode($result, true);

$medium_user_id = $data['payload']['user']['userId'];

$content = '';

if($data['success'] == 1){
  $posts = $data['payload']['references']['Post'];
  foreach ($posts as $post) {
    $url = "https://medium.com/$user/" . $post['uniqueSlug'];
    $content .= "<div class='medium-item'><a href='$url' target='_blank'>" . $post['title']. '</a>';
    $content .= "<p>{$post['content']['subtitle']}</p></div><hr>";
  }

  $medium_user_id = $data['payload']['user']['userId'];
  $followers_count = $data['payload']['references']['SocialStats'][$medium_user_id]['usersFollowedByCount'];
}

echo "<h1>Medium: $followers_count followers</h1>";
echo $content;