<?php
// Usage: php tools/snapshot.php <outDir> <url>   — renders one GET page in-process (one process per page) and stores normalized HTML.
require __DIR__.'/../vendor/autoload.php';
$app = require __DIR__.'/../bootstrap/app.php';
$out = $argv[1]; $u = $argv[2]; @mkdir($out, 0777, true);
if ($u === '--urls') { // list URLs
    $app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();
    $db = Illuminate\Support\Facades\DB::class;
    $blog = $db::table('blogs')->where('status','published')->value('slug');
    $event = $db::table('events')->value('slug');
    echo implode("\n", ['/','/about','/blogs','/blog-post?slug='.$blog,'/contact','/events','/event?slug='.$event,'/gallery','/downloads','/fees','/mbbs','/programs','/scholarships','/support','/privacy','/terms','/admissions','/be-our-partner','/login','/register','/forgot_password','/sitemap','/nonexistent-page']), "\n"; exit;
}
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
parse_str((string) parse_url($u, PHP_URL_QUERY), $q); $_GET = $q;
$_SERVER = array_merge($_SERVER, ['REQUEST_METHOD'=>'GET','HTTP_HOST'=>'localhost','SERVER_NAME'=>'localhost','REQUEST_URI'=>$u,'REMOTE_ADDR'=>'127.0.0.1','HTTPS'=>'off','SCRIPT_NAME'=>'/index.php','HTTP_USER_AGENT'=>'snap']);
$res = $kernel->handle(Illuminate\Http\Request::create('http://localhost'.$u, 'GET'));
$body = $res->getContent();
$body = preg_replace('/name="(lg_)?csrf_token" value="[^"]*"/','name="csrf_token" value="X"',$body);
$body = preg_replace('/(csrf[_-]token[^>]*content=")[^"]*"/i','$1X"',$body);
$name = trim(str_replace(['/','?','=','&'],'_',$u),'_') ?: 'home';
file_put_contents("$out/$name.html", $res->getStatusCode()."\n".$body);
printf("%-40s %d %d bytes\n", $u, $res->getStatusCode(), strlen($body));
