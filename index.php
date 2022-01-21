<?php

     require __DIR__.'\vendor\autoload.php';
     use Nesk\Puphpeteer\Puppeteer;

     $url = 'https://en.antaranews.com/rss/news.xml';

     $file_name = basename($url);
     if(file_exists($file_name)){
          unlink($file_name);
     }

     if(file_exists($file_name)){
          echo "File Belum Terhapus";
     } else {
          if (file_put_contents($file_name, file_get_contents($url)))
          {
               echo "File downloaded successfully";
          }
          else
          {
               echo "File downloading failed.";
          }
     }

     $xmlObject = simplexml_load_file('news.xml');
     $encode = json_encode($xmlObject);
     $decode = json_decode($encode, true);
     
     $items = $decode['channel']['item'];
     $rowdatas = [];
     $arr= [];
     //$image = [];
     foreach($items as $item){
          $rowdatas[] = $item;
     }
     $n=0;
     foreach($rowdatas as $rowdata){
          $data = [];
          $data['title'] = $rowdata['title'];
          $data['link'] = $rowdata['link'];
          $data['pubDate'] = $rowdata['pubDate'];
          $data['description'] = $rowdata['description'];
          $temp = $rowdata['description'];
          $arr_kalimat = strpos($temp,">");
          $content = substr($temp, $arr_kalimat+1);
          $image = substr($temp,1,$arr_kalimat);
          $hasil = "<".$image;
          $data['image'] = $hasil;
          $data['content'] = $content;
          $temp_date = new DateTime($rowdata['pubDate']);
          $date = date_format($temp_date,"d/m/Y H:i");
          $myfile = fopen('file'.$n.'.html', 'w') ;
     $txt = "
<!DOCTYPE html>
<html lang="."\""."en"."\"".">
<head>
     <meta charset="."\""."UTF-8"."\"".">
     <meta http-equiv="."\""."X-UA-Compatible"."\""." content="."\""."IE=edge"."\"".">
     <link href='https://fonts.googleapis.com/css?family=Montserrat' rel='stylesheet'>
     <style>
     @media screen and (min-width: 1920px) {
          img {
               display: block;
               margin-left: auto;
               margin-right: auto;
          }
     }

     body {
          margin: 0px;
     }

     .content {
          background-image: url(image/Background.png);
          width: 1920px;
          height: 1080px;
     }
     
     .headline {
          position: absolute;
          margin-left: 53px;
          margin-top: 71px;
          font-size: 30px;
          font-weight: semibold;
          font-family:"."\"". "Montserrat"."\"".";
     }

     .title {
          color: white;
          position: absolute;
          white-space: nowrap;
          text-overflow: ellipsis;
          overflow: hidden;
          width: 1700px;
          margin-left: 55px;
          margin-top: 169px;
          font-size: 70px;
          font-family:"."\""."Trebuchet MS"."\"".";
          font-weight: bold;
     }
     
     .container-img img {
          position: absolute;
          margin-top: 350px;
          margin-left: 58px;
          width: 995px;
          height: 650px;
     }
     
     .description {
          position: absolute;
          font-family:"."\""."Trebuchet MS"."\"".";
          font-size: 40px;
          width: 689px;
          margin-top: 485px;
          margin-left: 1140px;
          color: white;
     }

     .time {
          position: absolute;
          white-space: nowrap;
          text-overflow: ellipsis;
          overflow: hidden;
          font-family:"."\""."Trebuchet MS"."\"".";
          font-size: 43px;
          width: 405px;
          margin-top: 955px;
          margin-left: 1140px;
          color: white;
     }

     .logo {
          position: absolute;
          font-family:"."\""."Trebuchet MS"."\"".";
          margin-top: 940px;
          margin-left: 1600px;
     }
     </style>
     <title>Document</title>
</head>
<body>
     <div class="."\""."content"."\"".">
          <h4 class="."\""."headline"."\"".">HEADLINE NEWS</h4>
          <h1 class="."\""."title"."\"".">".$data['title']."</h1>
          <div class="."\""."container-img"."\"".">"
          ."\n".$data['image']."\n".
          "</div>
          <p class="."\""."description"."\"".">".$data['content']."</p>
          <p class="."\""."time"."\"".">".$date."</p>
          <img src="."\""."image/Asset 5.png"."\""." alt="."\""."Logo"."\""." class="."\""."logo"."\"".">
     </div>
</body>
</html>";

     fwrite($myfile, $txt);
     fclose($myfile);

        $arr[] = $data;
        $n++;
   }

   for($a=0; $a<$n; $a++){
     $puppeteer = new Puppeteer();
     $browser = $puppeteer->launch();
 
     $page = $browser->newPage();
     $page->goto("file:///C:/xampp/htdocs/KP/Day%203/file".$a.".html");
     $page->setViewport(['width' => 1920, 'height' => 1080]);
 
     $page->screenshot(['path' => "example".$a.".png"]);
 
     $browser->close();
   }

     $jsonData = json_encode($arr, JSON_PRETTY_PRINT);
     print_r($jsonData);
