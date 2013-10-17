<?php 

include_once(drupal_get_path('module', 'scoopit')."/ScoopIt.php");
include_once(drupal_get_path('module', 'scoopit')."/include/include_commons.php");

$auth_key = variable_get('scoopit_key', "fail");
$auth_secret = variable_get('scoopit_secret', "fail");
//print_r($node);
$scoopit_topic_url = variable_get("topic_for_node_".$node->nid, "");

if ($scoopit_topic_url != "") {
	$scoop = new ScoopIt(new SessionTokenStore(), ".", $auth_key, $auth_secret);
	
	if ($cache = cache_get($scoopit_topic_url)) {
		$topic_id = $cache->data;
	} else {
		$needle = "scoop.it/t/";
		$result = substr($scoopit_topic_url, strpos($scoopit_topic_url, $needle) + 11,strlen($scoopit_topic_url));
		$topicFound =  $scoop->resolveTopicFromItsShortName($result);
		$topic_id = $topicFound->id;
		cache_set($scoopit_topic_url, $topicFound->id, 'cache');
	}
	
	// nb post to display per page
	$nbPostsPerPage = 25;
	$page = isset($_REQUEST["page"]) ? (int)$_REQUEST["page"] : 1;
	$curated=$nbPostsPerPage;
	$curable=0;
	$topic = $scoop->topic($topic_id, $curated, $curable, $page - 1);


?>
<?php if ($teaser) { ?>
	<h1><a href="?q=node/<?php echo $node->nid;?>"><?php echo $node->title;?></a></h1>
<?php 
} else {
?>

<link href="<?php echo drupal_get_path('module', 'scoopit'); ?>/css/style.css" rel="stylesheet" type="text/css" media="all" />
<div id="scit_viewbox">	
	<table cellpadding="0" cellspacing="0" width="100%">
		<tr valign="top">
			<td width="50%" style="padding-right: 10px">
				<?php 
					$absoluteCounter = ($page-1)*$nbPostsPerPage;
					
					if(isset($topic->pinnedPost) && $page == 1) {
						$post = $topic->pinnedPost;
						include drupal_get_path('module', 'scoopit').'/include/include_a_post.php';
					}
					
					for ($i = 0; $i < count($topic->curatedPosts); $i++) {
						$post = $topic->curatedPosts[$i];
						if(isset($topic->pinnedPost) && $page == 1 && $i == 0) {
							$condition = ($i-1) % 2 == 0;
						} else {
							$condition = ($i-1) % 2 != 0;
						}
						if ($condition && (!isset($topic->pinnedPost) || $post->id != $topic->pinnedPost->id)) {
							$absolutePosition = $absoluteCounter + $i;
							include drupal_get_path('module', 'scoopit').'/include/include_a_post.php';
						}
					}
				?>
             </td>
             <td width="50%">
             	<?php 
					for ($i = 0; $i < count($topic->curatedPosts); $i++) {
						$post = $topic->curatedPosts[$i];
						if(isset($topic->pinnedPost) && $page == 1 && $i == 0) {
							$condition = ($i-1) % 2 != 0;
						} else {
							$condition = ($i-1) % 2 == 0;
						}
						if ($condition && (!isset($topic->pinnedPost) || $post->id != $topic->pinnedPost->id)) {
							$absolutePosition = $absoluteCounter + $i;
							include drupal_get_path('module', 'scoopit').'/include/include_a_post.php';
						}
					}
				?>
             </td>
        </tr>
    </table>
    <a class="poweredby" href="http://www.scoop.it" alt="scoop.it"></a>
    <div style="clear:both"></div>
    <?php 
	    $totalPostCount = $topic->curatedPostCount;
	    $pageCount = ceil($totalPostCount/$nbPostsPerPage);
	    if($pageCount==0) $pageCount=1;
	    if($page > $pageCount) $page = $pageCount;
		include_once drupal_get_path('module', 'scoopit').'/include/include_paginator.php';
	?>
</div>

<?php 
	} 
}?>