<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;

// Botman stuff
use BotMan\BotMan\BotMan;
use Illuminate\Http\Request;
use BotMan\BotMan\Messages\Incoming\Answer;
use BotMan\BotMan\Messages\Outgoing\Question;
use BotMan\BotMan\Messages\Outgoing\Actions\Button;
use BotMan\BotMan\Messages\Conversations\Conversation;
use BotMan\BotMan\Messages\Attachments\Image;
use BotMan\BotMan\Messages\Outgoing\OutgoingMessage;
use BotMan\BotMan\Middleware\ApiAi;

// Database
use App\Product;
use App\Category;

// conversations
use App\Conversations\ExampleConversation;
use App\Conversations\EngConversation;

// shopify


class BotManController extends Controller
{
    /**
     * Place your BotMan logic here.
     */
    public function handle()
    {
        $botman = app('botman');



        // my code
        //$botman->say('Hello there!');
        // $id = $botman->getUser()->getId();
        //$botman->say('Message', $id, WebDriver::class);

        $dialogflow = ApiAi::create(env('DIALOGFLOW_API'))->listenForAction();

        // Apply global "received" middleware
        $botman->middleware->received($dialogflow);

        // Apply matching middleware per hears command
        $botman->hears('catalogue.search', function (BotMan $bot) {
            // The incoming message matched the "my_api_action" on Dialogflow
            // Retrieve Dialogflow information:
            $extras = $bot->getMessage()->getExtras();
            $apiReply = $extras['apiReply'];
            $apiAction = $extras['apiAction'];
            $apiIntent = $extras['apiIntent'];

            $category = $extras['apiParameters']['catalogue'];

            if($category==''){
              $category_number = mt_rand(1,4);
              $find_category = Category::find($category_number);
              $message = OutgoingMessage::create("You are now browsing <a href='".$find_category->link."' target='_top'>".$find_category->title."</a><br><br>");
              $bot->reply($message);
              $products = $find_category->products()->orderByRaw("RAND()")->take(5)->get();
              foreach ($products as $product) {
                # code...
                // Create attachment
                $attachment = new Image($product["Image Src"]);

                // Build message object
                $message = OutgoingMessage::create($product->Title." <a href='".$product["link"]."' target='_top'>See More</a><br><br>$".$product["Variant Price"])
                            ->withAttachment($attachment);

                // Reply message object
                $bot->reply($message);
              }
            }else{
              $find_category = DB::table('categories')->where('name','like',$category.'%')->first();
              $message = OutgoingMessage::create("You are now browsing <a href='".$find_category->link."' target='_top'>".$find_category->title."</a><br><br>");
              $bot->reply($message);
              $find_category = Category::find($find_category->id);
              $products = $find_category->products()->orderByRaw("RAND()")->take(5)->get();
              foreach ($products as $product) {
                # code...
                // Create attachment
                $attachment = new Image($product["Image Src"]);

                // Build message object
                $message = OutgoingMessage::create($product->Title." <a href='".$product["link"]."' target='_top'>See More</a><br><br>$".$product["Variant Price"])
                            ->withAttachment($attachment);

                // Reply message object
                $bot->reply($message);
              }
            }


        })->middleware($dialogflow);


        // start the conversation pretent the lang pref worked ...
        // ideas for lang = load a neutral one then ask for lang then load the different link to handle
        // btw need to do if how much does "product_name" cost? => show price ...
        // check how natural langauge work
        $botman->hears('/start eng',function($bot){
            $bot->startConversation(new EngConversation);
        });

        $botman->hears('help',function($bot){
          $message = OutgoingMessage::create("Hello! I am LONGS Q&A Chatbot. Here is what I can do for you:<br> 🌈 Feeling lucky: browse<br> 💡 See our catalogue list: list<br> 🗂 Understander our company: info<br> 👩🏻‍💻 You can also ask me any questions (i.e. What is the price of MR16 Type A)?");
          $bot->reply($message);
        });


        $botman->hears('list',function($bot){
            $bot->reply("Here is the list of categories:");
            $categorys = Category::all();
            foreach ($categorys as $category) {
              $message = OutgoingMessage::create("<a target='_top' href='".$category->link."'>".$category->title."</a>");
              $bot->reply($message);
            }
        });

        $botman->hears('info',function($bot){
            $bot->reply('LONGS Optoelectronics Company Limited');
            $message = OutgoingMessage::create("Check us out in <a target='_top' href='https://is-project-management-trial.myshopify.com/pages/about-us'>About Us</a>");
            $bot->reply($message);
            $message = OutgoingMessage::create("Or you can find us in <a target='_top' href='https://is-project-management-trial.myshopify.com/pages/contact-us'>Contact Us</a>");
            $bot->reply($message);
        });

        $botman->listen();
    }



    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function tinker()
    {
        return view('tinker');
    }

    /**
     * Loaded through routes/botman.php
     * @param  BotMan $bot
     */
    public function startConversation(BotMan $bot)
    {
        $bot->startConversation(new ExampleConversation());
    }


    public function debug()
    {
      # code...
      $category_number = mt_rand(1,4);
      $botman = 'commercial';
      $categories = DB::table('categories')->where('name','like',$botman.'%')->first();
      //dd($categories->name);
      $find_category = Category::find(4);
      //dd($find_category);

      //$products = $find_category->products()->orderByRaw("RAND()")->take(5)->get();

      // $shopify = DB::table('shopify_products')->get();
      // // $shopify = DB::table('shopify_products')->where('Handle','like','10-paper-empire-lamp-shade')->first();
      // //
      // // $shopify = get_object_vars($shopify);
      // // dd($shopify["Image Src"]);
      // foreach ($shopify as $one) {
      //   # code...
      //   $one     = get_object_vars($one);
      //   dd(Product::where("Title",$one["Title"])->first()->id);
      // }
      // dd($shopify);
      $shopify = DB::table('shopify_products')->where('Handle','like','spotlight-gu10-3-5w')->first();
      //dd($shopify->Tags);
      $shopify = DB::table('shopify_products')->where('Handle','like','light-bulb-8w')->first();
      $shopify = DB::table('shopify_products')->first();
      $exploded_tags = explode(',',$shopify->Tags);
      //dd($exploded_tags);
      foreach($exploded_tags as $tag){
        dd(ltrim($tag));
      }
      foreach ($products as $product) {
        dd($product["Image Src"]);
      }
      dd($products);

    }



}
