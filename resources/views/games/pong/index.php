<!DOCTYPE html> 
<html>
<head>
  <title>Pong!</title> 
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/> 
  <link href="/games/pong/css/pong.css" media="screen, print" rel="stylesheet" type="text/css" /> 
</head> 
 
<body> 

  <div id="sidebar">

    <h2>This is Pong!</h2>

    <ul class='parts'>
      <li><a href='/' class='selected'>Full Game</a></li>
      <li><a href='/part1'><i>game runner</i> - Part 1</a></li>
      <li><a href='/part2'><i>bouncing ball</i> - Part 2</a></li>
      <li><a href='/part3'><i>game loop</i> - Part 3</a></li>
      <li><a href='/part4'><i>collision detection</i> - Part 4</a></li>
      <li><a href='/part5'><i>computer AI</i> - Part 5</a></li>
    </ul>

    <div class='description'>
      <p>
        This is a javascript version of Pong.
      </p>
      <p>
        Press <b>1</b> for a single player game.<br>
        Press <b>2</b> for a double player game.<br>
        Press <b>0</b> to watch the computer play itself.
      </p>
      <p>
        Player 1 moves using the <b>Q</b> and <b>A</b> keys.<br>
        Player 2 moves using the <b>P</b> and <b>L</b> keys.
      </p>
      <p>
        <b>Esc</b> can be used to abandon a game.
      </p>
    </div>

    <div class='settings'>
      <label for='sound'>sound: </label>
      <input type='checkbox' id='sound'>
    </div>

    <div class='settings'>
      <label for='stats'>stats: </label>
      <input type='checkbox' id='stats' checked>
    </div>

    <div class='settings'>
      <label for='footprints'>footprints: </label>
      <input type='checkbox' id='footprints'>
    </div>

    <div class='settings'>
      <label for='predictions'>predictions: </label>
      <input type='checkbox' id='predictions'>
    </div>

  </div>

  <canvas id="game">
    <div id="unsupported">
      Sorry, this example cannot be run because your browser does not support the &lt;canvas&gt; element
    </div>
  </canvas>

  <script src="/games/pong/js/game.js" type="text/javascript"></script> 
  <script src="/games/pong/js/pong.js" type="text/javascript"></script>
  <script type="text/javascript">
  Game.ready(function() {

    var size        = document.getElementById('size');
    var sound       = document.getElementById('sound');
    var stats       = document.getElementById('stats');
    var footprints  = document.getElementById('footprints');
    var predictions = document.getElementById('predictions');

    var pong = Game.start('game', Pong, {
      sound:       sound.checked,
      stats:       stats.checked,
      footprints:  footprints.checked,
      predictions: predictions.checked
    });

    Game.addEvent(sound,       'change', function() { pong.enableSound(sound.checked);           });
    Game.addEvent(stats,       'change', function() { pong.showStats(stats.checked);             });
    Game.addEvent(footprints,  'change', function() { pong.showFootprints(footprints.checked);   });
    Game.addEvent(predictions, 'change', function() { pong.showPredictions(predictions.checked); });

  });
  </script>

</body> 
</html>
