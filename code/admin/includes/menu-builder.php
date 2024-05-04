<?php
$pageName = basename($_SERVER["PHP_SELF"]);
$posName = strpos($pageName, '.php');
$pageName = (substr($pageName, 0, $posName));
$pg = $pageName;
$c = 'current';
$cu = 'active';
$op = 'open';
if(isset($_SESSION["UType"]) && $_SESSION["UType"] == 2){
?>
<ul>
	<li class="<?php echo $pg == 'index' ? $c : ''; ?>"> <a href="index.php"> <i class="fa fa-tachometer fa-fw"></i> <span class="menu-text">Dashboard</span> <span class="selected"></span> </a> </li>
	<li class="has-sub <?php echo ( $pg == 'manage_library' || $pg == 'manage_subject' || $pg == 'manage_author' || $pg == 'manage_publisher' ) ? $cu : ''; ?>"> <a href="javascript:;" class=""> <i class="fa  fa-folder-open"></i> <span class="menu-text">Library</span> <span class="arrow <?php echo ( $pg == 'manage_library' || $pg == 'manage_subject' || $pg == 'manage_author' || $pg == 'manage_publisher' ) ? $op : ''; ?>"></span> </a>
		<ul class="sub">
			<li class="<?php echo ($pg == 'manage_subject') ? $c : ''; ?>"><a href="manage_subject.php" title="Subject Management"><span class="sub-menu-text">Subject</span></a></li>
			<li class="<?php echo ($pg == 'manage_author') ? $c : ''; ?>"><a href="manage_author.php" title="Author Management"><span class="sub-menu-text">Author</span></a></li>
			<li class="<?php echo ($pg == 'manage_publisher') ? $c : ''; ?>"><a href="manage_publisher.php" title="Publisher Management"><span class="sub-menu-text">Publisher</span></a></li>
			<li class="<?php echo ($pg == 'manage_library') ? $c : ''; ?>"><a href="manage_library.php" title="Library Management"><span class="sub-menu-text">Library</span></a></li>
		</ul>
	</li>
	<li class="has-sub <?php echo ( $pg == 'manage_banner' || $pg == 'manage_faqs' || $pg == 'manage_social_network' || $pg == 'manage_content' || $pg == 'manage_widgets') ? $cu : ''; ?>"> <a href="javascript:;" class=""> <i class="fa  fa-folder-open"></i> <span class="menu-text">CMS</span> <span class="arrow <?php echo ( $pg == 'manage_banner' || $pg == 'manage_faqs' || $pg == 'manage_social_network' || $pg == 'manage_content' || $pg == 'manage_widgets') ? $op : ''; ?>"></span> </a>
		<ul class="sub">
			<li class="<?php echo ($pg == 'manage_banner') ? $c : ''; ?>"><a href="manage_banner.php" title="Banner"><span class="sub-menu-text">Banners</span></a></li>
			<li class="<?php echo ($pg == 'manage_faqs') ? $c : ''; ?>"><a href="manage_faqs.php" title="FAQs"><span class="sub-menu-text">FAQs</span></a></li>
			<li class="<?php echo ($pg == 'manage_social_network') ? $c : ''; ?>"><a href="manage_social_network.php" title="Social Networks"><span class="sub-menu-text">Social Networks</span></a></li>
			<li class="<?php echo ($pg == 'manage_content') ? $c : ''; ?>"><a href="manage_content.php" title="Content"><span class="sub-menu-text">Contents</span></a></li>
			<li class="<?php echo ($pg == 'manage_widgets') ? $c : ''; ?>"><a href="manage_widgets.php" title="Widgets"><span class="sub-menu-text">Widgets</span></a></li>
		</ul>
	</li>
</ul>
<?php } ?>