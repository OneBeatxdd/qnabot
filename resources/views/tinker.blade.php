@extends('layouts.app')
@section('head')
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <!-- Fonts -->
  <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro" rel="stylesheet" type="text/css">
  <!-- Styles -->
  <style>
      body {
          font-family: "Source Sans Pro", sans-serif;
          margin: 0;
          padding: 0;
          background: radial-gradient(#57bfc7, #45a6b3);
      }

      .container {
          display: flex;
          height: 100vh;
          align-items: center;
          justify-content: center;
      }

      .content {
          text-align: center;
      }
  </style>
@endsection
@section('content')
  <div class="container">
      <div class="content" id="app">
          <botman-tinker api-endpoint="/qnabot/public/botman"></botman-tinker>
      </div>
  </div>

 {{-- Web Widget --}}
<script>
var botmanWidget = {
    frameEndpoint: '/qnabot/public/test',
    chatServer: '/qnabot/public/botman',
    'introMessage': "Hello! I am LONGS Q&A Chatbot. Here is what I can do for you:<br> 🌈 Feeling lucky: browse<br> 💡 See our catalogue list: list<br> 🗂 Understander our company: info<br> 👩🏻‍💻 You can also ask me any questions (i.e. What is the price of MR16 Type A)?",
    bubbleAvatarUrl: '/qnabot/public/image/ligtttttt.png',
    title: 'LONGS BOT',
    mainColor: '#7796A8',
    bubbleBackground: '#7796A8'
};
</script>

<script src='https://cdn.jsdelivr.net/npm/botman-web-widget@0/build/js/widget.js'></script>
@endsection
