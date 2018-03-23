<?php

namespace App\Conversations;

use Illuminate\Foundation\Inspiring;
use BotMan\BotMan\Messages\Incoming\Answer;
use BotMan\BotMan\Messages\Outgoing\Question;
use BotMan\BotMan\Messages\Outgoing\Actions\Button;
use BotMan\BotMan\Messages\Conversations\Conversation;


class EngConversation extends Conversation
{
    /**
     * First question
     */
    public function greet()
    {
      $this->say('Hi how can i help you?');


    }

    public function test(){
      $this->ask('test!?', function(Answer $answer){
        $this->say('Done test: '.$answer->getText());
      });
    }

    /**
     * Start the conversation
     */
    public function run()
    {
        $this->greet();
    }


}
