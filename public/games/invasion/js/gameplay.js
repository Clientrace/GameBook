/**
 * gameplay.js
 *
 * TODO:
 *  all the things.
 * 
 * @author Richard Habeeb, Addison Shaw 
 **/
 
//SETUP HUD
var hud_stage = new Kinetic.Stage({
	container: 'GameHud',
	width: WINDOW_WIDTH_CELLS*PX_PER_CELL,
	height: parseInt(window.getComputedStyle(document.getElementById("GameHud")).getPropertyValue("height"))
});
var hud_top = new Hud(hud_stage);

 
 
 //SETUP MAP
var window_stage = new Kinetic.Stage({
	container: 'GameWindow',
	width: WINDOW_WIDTH_CELLS*PX_PER_CELL,
	height: WINDOW_HEIGHT_CELLS*PX_PER_CELL
});

var current_map = new Map(hud_top, WINDOW_HEIGHT_CELLS, WINDOW_WIDTH_CELLS);
current_map.SetupMapOnStage(window_stage);
current_map.SetupWalls();
current_map.SetupEntities();
current_map.SetupItems();
current_map.SetupCow();
current_map.SetupPlayer();
current_map.GenerateTerrain();




//INITIAL START GAME WINDOW
var paused = true;
var screen_cover = new Kinetic.Rect({
	x: 0,
	y: 0,
	width: WINDOW_WIDTH_CELLS*PX_PER_CELL,
	height: WINDOW_WIDTH_CELLS*PX_PER_CELL,
	fill: "black",
	opacity: 0.5
});
var top_layer = new Kinetic.Layer();
window_stage.add(top_layer);
top_layer.add(screen_cover);
top_layer.draw();

function startGame() {
	paused = false;
	document.getElementById("StartMenu").style.display = "none";
	screen_cover.hide();
	top_layer.draw();
	hud_top.layer.draw(); //this is done to get the fonts to work
}
function GameOver() {
	paused = true;
	screen_cover.show();
	top_layer.draw();
}


//HANDLE KEYBOARD EVENTS
var w_pressed = false;
var a_pressed = false;
var s_pressed = false;
var d_pressed = false;
setInterval(function() {
	if(!paused) {
		if(w_pressed) current_map.MoveEntity(current_map.player, NORTH);
		if(a_pressed) current_map.MoveEntity(current_map.player, WEST);
		if(s_pressed) current_map.MoveEntity(current_map.player, SOUTH);
		if(d_pressed) current_map.MoveEntity(current_map.player, EAST);
	}
}, 10); //poll keypress flags every ten ms.

keypress.register_combo({
    "keys"              : "w",
    "on_keydown"        : function() { w_pressed = true;},
	"on_keyup"			: function() { w_pressed = false;}
});

keypress.register_combo({
    "keys"              : "a",
    "on_keydown"        : function() { a_pressed = true;},
	"on_keyup"			: function() { a_pressed = false;}
});

keypress.register_combo({
    "keys"              : "s",
    "on_keydown"        : function() { s_pressed = true;},
	"on_keyup"			: function() { s_pressed = false;}
});

keypress.register_combo({
    "keys"              : "d",
    "on_keydown"        : function() { d_pressed = true;},
	"on_keyup"			: function() { d_pressed = false;}
});

keypress.register_combo({
    "keys"              : "j",
    "on_keydown"        : function() { current_map.EntityAttack(current_map.player, null);}
});

keypress.register_combo({
	"keys"				: "r",
	"on_keydown"		: function() { current_map.UseSingleUseItem(current_map.player);}
});



//GAME TIMERS AND "INTERRUPTS"
setInterval(function() {
	if(current_map.game_over_flag) {
		GameOver();
	}
	if(!paused) {
		current_map.HandleMonsterSpawning();
		current_map.HandleCowMovement();

	}
}, 1000);

setInterval(function() {
	if(!paused) {
		current_map.HandleItemSpawning();
	}
}, 500);

var monster_movement_handler = function() {
	if(!paused) {
		current_map.HandleAlienMovements();
	}
	setTimeout(monster_movement_handler, Math.random()*250);
}
monster_movement_handler();

