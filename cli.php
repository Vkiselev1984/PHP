<?php
require __DIR__ . '/vendor/autoload.php';
spl_autoload_register('ClassLoader');

function ClassLoader($className)
    {
    $fileName = str_replace('\\', '/', $className);
    $fileName = str_replace('_', '/', $fileName);
    $fileName = str_replace('Phpexample/Myblog', 'src/', $fileName) . ".php";
    if (file_exists($fileName))
    {
        include $fileName;
    }
    }
use Phpexample\Myblog\User;
use Phpexample\Myblog\Comment;
use Phpexample\Myblog\Post;

$faker = Faker\Factory::create('ru_RU');

$user = new User(1, $faker->firstName('male'), $faker->lastname('male'));
$post = new Post(1, 2, $faker->word(), $faker->sentence($nbWords = 26, $variableNbWords = true));
$note = new Comment(1, 2, 3, $faker->word());

$finding = array();

if (empty($argv[1])) {
    $inputData = $argv;
}
else {
    $inputData = $argv[1];
    }

switch ($inputData) {
    case 'user':
        print $user;
        break;
    case 'post':
        print $post;
        break;
    case 'note':
        print $note;
        break;
    default:
        print "Это пустой массив";
}
