<?php
$slug = preg_replace('/[^a-z0-9\-_]/i', '', trim($_GET['slug'] ?? ''));
if ($slug) {
    header('Location: https://ovc.me/article.php?slug=' . rawurlencode($slug), true, 301);
} else {
    header('Location: https://bim.ovc.me/', true, 302);
}
exit;
