<?php
use App\Http\Controllers\BotManController;
use BotMan\BotMan\Messages\Incoming\Answer;
use BotMan\BotMan\Messages\Outgoing\Question;
use BotMan\BotMan\Messages\Outgoing\Actions\Button;
use BotMan\BotMan\Messages\Conversations\Conversation;
use BotMan\BotMan\BotMan;

// Conversations
use App\Conversations\ExampleConversation;
use App\Conversations\EngConversation;

$botman = resolve('botman');

$botman->hears('Start conversation', BotManController::class.'@startConversation');

// $botman->hears('Start conversation', function($bot){
//   $bot->startConversation(new ExampleConversation);
// });




$botman->fallback(function($bot) {
    $bot->reply('Sorry, I did not understand these commands. Type /help to see the list of commands');
});
