<?php
    require __DIR__.'\vendor\autoload.php';
    use Nesk\Puphpeteer\Puppeteer;

    $puppeteer = new Puppeteer();
    $browser = $puppeteer->launch();

    $page = $browser->newPage();
    $page->goto('http://127.0.0.1:5501/testfile0.html');
    $page->setViewport(['width' => 1920, 'height' => 1080]);

    $page->screenshot(['path' => 'example.png']);

    $browser->close();
?>
