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
              $message = OutgoingMessage::create($product->Title)
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
              $message = OutgoingMessage::create($product->Title)
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

      $products = $find_category->products()->orderByRaw("RAND()")->take(5)->get();

      $shopify = DB::table('shopify_products')->where('Handle','like','spotlight-gu10-3-5w')->first();
      //dd($shopify->Tags);
      $exploded_tags = explode(',',$shopify->Tags);
      dd($exploded_tags);
      foreach($exploded_tags as $tag){
        dd($tag);
      }
      foreach ($products as $product) {
        dd($product["Image Src"]);
      }
      dd($products);

    }
}
