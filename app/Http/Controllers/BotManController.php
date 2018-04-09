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
              $bot->reply('You are now browsing '.$find_category->title);
              $products = $find_category->products()->orderByRaw("RAND()")->take(5)->get();
              foreach ($products as $product) {
                # code...
                // Create attachment
                $attachment = new Image($product["Image Src"]);

                // Build message object
                $message = OutgoingMessage::create($product->Title." <a href='".$product["link"]."' target='_top'>Click Here</a>")
                            ->withAttachment($attachment);

                // Reply message object
                $bot->reply($message);
              }
            }else{
              $find_category = DB::table('categories')->where('name','like',$category.'%')->first();
              $bot->reply('You are now browsing '.$find_category->title);
              $find_category = Category::find($find_category->id);
              $products = $find_category->products()->orderByRaw("RAND()")->take(5)->get();
              foreach ($products as $product) {
                # code...
                // Create attachment
                $attachment = new Image($product["Image Src"]);

                // Build message object
                $message = OutgoingMessage::create($product->Title." <a href='".$product["link"]."' target='_top'>Click Here</a>")
                            ->withAttachment($attachment);

                // Reply message object
                $bot->reply($message);
              }
            }


        })->middleware($dialogflow);



        $botman->hears('/lang', function($bot) {
            $id = $bot->getUser()->getId();
            // $bot->say('Message', $id, WebDriver::class);
            $bot->reply('Hello! Userid: '.$id);

            $question = Question::create("What is your prefered langauge?")
              ->fallback('Unable to ask question')
              ->callbackId('ask_lang')
              ->addButtons([
                  Button::create('中文')->value('chi'),
                  Button::create('English')->value('eng'),
              ]);

              $bot->ask($question, function($answer,$bot) {
                  // $bot->hears('chi', function($bot){
                  //   $bot->startConversation(new ExampleConversation);
                  // });


                  if ($answer->isInteractiveMessageReply()) {
                      if ($answer->getValue() === 'chi') {
                        //$bot->say('chi');
                        // $bot->startConversation(new ExampleConversation);
                        $bot->say("chinese hasn't been developed");
                      } else {
                        $bot->say("type /help to start!");
                          //$bot->startConversation(new EngConversation);
                      }
                  }
              });

        });

        // start the conversation pretent the lang pref worked ...
        // ideas for lang = load a neutral one then ask for lang then load the different link to handle
        // btw need to do if how much does "product_name" cost? => show price ...
        // check how natural langauge work
        $botman->hears('/start eng',function($bot){
            $bot->startConversation(new EngConversation);
        });

        $botman->hears('/help',function($bot){
          $bot->reply('Here are what i can do for you:');
          $bot->reply('Browse Random Catalogue: /browse');
          $bot->reply('Show the list of Catalogues: /list');
          $bot->reply('Information about the company: /info');
          $bot->reply('Choose language preference: /lang');
        });

        $botman->hears('/browse\s?({category})?',function($bot, $category){
          if($category==''){
            $category_number = mt_rand(1,4);
            $find_category = Category::find($category_number);
            $bot->reply('You are now browsing '.$find_category->title);
            $products = $find_category->products()->orderByRaw("RAND()")->take(5)->get();
            foreach ($products as $product) {
              # code...
              // Create attachment
              $attachment = new Image($product["Image Src"]);

              // Build message object
              $message = OutgoingMessage::create($product->Title." <a href='".$product["link"]."' target='_top'>See More</a> <br> <br>")
                          ->withAttachment($attachment);

              // Reply message object
              $bot->reply($message);
            }
          }else{
            $find_category = DB::table('categories')->where('name','like',$category.'%')->first();
            $bot->reply('You are now browsing '.$find_category->title);
            $find_category = Category::find($find_category->id);
            $products = $find_category->products()->orderByRaw("RAND()")->take(5)->get();
            foreach ($products as $product) {
              # code...
              // Create attachment
              $attachment = new Image($product["Image Src"]);

              // Build message object
              $message = OutgoingMessage::create($product->Title." <a href='".$product["link"]."' target='_top'>Click Here</a>")
                          ->withAttachment($attachment);

              // Reply message object
              $bot->reply($message);
            }
          }

        });

        $botman->hears('/list',function($bot){
            $categorys = Category::all();
            foreach ($categorys as $category) {
              $bot->reply("$category->title");
            }
        });

        $botman->hears('/info',function($bot){
            $bot->reply('LONGS Optoelectronics Company Limited');
            $bot->reply('Check us out in https://is-project-management-trial.myshopify.com/pages/about-us');
            $bot->reply('Or you can find us in https://is-project-management-trial.myshopify.com/pages/contact-us');
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
