<?php

require_once("load_modules.php");

$cmd = ui::browser_fields()->cmd()->toCmd();
$cmd->execute();

$page = ui::browser_fields()->page()->toPage();
$page_wrapper = new MotokaPageWrapper($page);
print $page_wrapper."";

//6. save any necessary states
app::sitemap()->serializeToFile(app::settings()->host_file_for_php_sitemap());
app::sitemap()->build(app::settings()->host_file_for_xml_sitemap());




