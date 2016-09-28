/**
 * INVASION
 * FILE: item.js
 * AUTHORS: Richard Habeeb, Addison Shaw 
 * TODO:
 *  -all the things.
 **/
function Item(key, map_layer, animation_layer)
{
	var self 					= this;
	this.key 					= key;
	this.name 					= ITEM_DICT[key].name;
	this.type 					= ITEM_DICT[key].type;
	this.icon_image				= new Image();
	this.map_image 				= new Image();
	this.animation_image		= new Image();
	this.icon_image.src 		= ITEM_DICT[key].icon_image;
	this.map_image.src			= ITEM_DICT[key].map_image;
	this.animation_image.src	= ITEM_DICT[key].animation_image;
	this.icon_image_loaded 		= false;
	this.map_image_loaded 		= false;
	this.animation_image_loaded = false;
	this.map_layer 				= map_layer;
	this.animation_layer 		= animation_layer;
	this.row_map				= 0;
	this.col_map				= 0;
	this.x_map					= 0;
	this.y_map					= 0;
	this.x_anim					= 0;
	this.y_anim					= 0;
	this.is_animating			= false;
	this.is_visible_on_map		= false;
	this.animation_type			= ITEM_DICT[key].animation_type;
	this.animation_duration		= ITEM_DICT[key].animation_duration;
	this.heading				= NORTH;
	
	//EQUIP PROPERTIES
	if(this.type == ITEM_TYPES.EQUIP) {
		this.single_direction = ITEM_DICT[key].single_direction;
		this.melee = ITEM_DICT[key].melee;
		this.range = ITEM_DICT[key].range;
		this.base_damage = ITEM_DICT[key].base_damage;
	}
	
	//SINGLE_USE_BUFF PROPERTIES
	if(this.type == ITEM_TYPES.SINGLE_USE_BUFF) {
		this.buff_attribute = "health";
		this.buff_amount = 25;
	}
	
	
	this.icon_image.onload = function() {
		//self.layer.add(self.sprite);
		//self.layer.draw();
		self.icon_image_loaded = true;
	};
	
	this.map_image.onload = function() {
		if(!self.is_visible_on_map) 	{
			self.map_sprite.hide();
		} else 	{
			self.map_sprite.setPosition(this.x_map, this.y_map);
			self.map_sprite.show();
			self.MapHoverAnimation.start();	
		}
		self.map_layer.add(self.map_sprite);
		self.map_layer.draw();
		self.map_image_loaded = true;
	};
	
	this.animation_image.onload = function() {
		if(!this.is_animating) 	self.animation_sprite.hide();
		else 						self.animation_sprite.show();
		self.animation_layer.add(self.animation_sprite);
		self.animation_layer.draw();
		self.animation_image_loaded = true;
	};
	
	this.map_sprite = new Kinetic.Image({
			x: this.x,
			y: this.y,
			width: PX_PER_CELL,
			height: PX_PER_CELL,
			offset: { x : PX_PER_CELL/2, y: PX_PER_CELL/2},
			image: this.map_image
	});
	
	this.animation_sprite = new Kinetic.Image({
			x: this.x,
			y: this.y,
			width: PX_PER_CELL,
			height: PX_PER_CELL,
			offset: { x : PX_PER_CELL/2, y: PX_PER_CELL/2},
			image: this.animation_image
	});
	
	this.SetMapRCXY = function (r, c) {
		this.row_map = r;
		this.col_map = c;
		this.x_map = (PX_PER_CELL*c)+PX_PER_CELL/2;
		this.y_map = (PX_PER_CELL*r)+PX_PER_CELL/2;
		this.map_sprite.setPosition(this.x_map, this.y_map);
		if(this.map_image_loaded) {
			this.map_layer.draw();
		}
	};
	
	this.SetAnimXY = function (x, y) {
		this.x_anim = x;
		this.y_anim = y
		this.animation_sprite.setPosition(this.x_anim, this.y_anim);
		this.animation_layer.draw();
	};
	
	this.ShowImageOnMap = function(r, c) {
		this.is_visible_on_map = true;
		this.SetMapRCXY(r, c);
		this.map_sprite.show();
		if(this.map_image_loaded) this.MapHoverAnimation.start();
		return self;
	};
	
	this.HideImageOnMap = function() {
		this.is_visible_on_map = false;
		this.map_sprite.hide();
		this.MapHoverAnimation.stop();
	};
	
	this.FaceHeading = function(heading) {
		if(heading != this.heading) {
			if(heading == NORTH) {
				this.animation_sprite.setRotationDeg(270);
			} else if(heading == EAST) {
				this.animation_sprite.setRotationDeg(0);
			} else if(heading == SOUTH) {
				this.animation_sprite.setRotationDeg(90);
			} else if(heading == WEST) {
				this.animation_sprite.setRotationDeg(180);
			}
			this.animation_layer.draw();
			this.heading = heading;
		}
	}
	
	
	//This should be improved by a dictionary, but I don't care right now. (I know this is kinda a ghetto way to do this.)
	this.Animate = function(x, y) {
		if(this.animation_image_loaded) {
			if(this.animation_type == "BLINK") this.StartBlinkAnimation(x, y);
			
		}
	
	}
	
	
	//BLINK - fade in and out over a period of time
	this.blink_animation_period_ms = 200;
	this.StartBlinkAnimation = function(x, y) {
		if(!this.is_animating) {
			this.SetAnimXY(x, y);
			this.animation_sprite.show();
			this.BlinkAnimation.start();
			this.is_animating = true;
			setTimeout(this.StopBlinkAnimation, this.animation_duration);
		}
	};
	
	this.StopBlinkAnimation = function() {
		if(self.is_animating) {
			self.BlinkAnimation.stop();
			self.animation_sprite.setOpacity(1);
			self.animation_sprite.hide();
			self.animation_layer.draw();
			self.is_animating = false;
		}
	};
	this.BlinkAnimation = new Kinetic.Animation(function(frame) {
		self.animation_sprite.setOpacity(Math.abs(Math.sin(frame.time * 2 * Math.PI / self.blink_animation_period_ms)));
    }, this.animation_layer);
	
	
	
	
	
	//Map item hover
	this.map_hover_period = 3000;
	this.StopMapHoverAnimation = function() {
		if(self.is_animating) {
			self.MapHoverAnimation.stop();
			self.SetMapRCXY(self.row_map, self.col_map);
		}
	};
	this.MapHoverAnimation = new Kinetic.Animation(function(frame) {
		
		self.map_sprite.setY(self.y_map-3*Math.sin(frame.time * 2 * Math.PI / self.map_hover_period));
    }, this.map_layer);

	
	
};

