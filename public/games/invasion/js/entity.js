/**
 * entity.js
 * 
 * TODO:
 *  -Handle inventory pickups and drops
 *  -Handle attack requests
 * 
 * @class An entity structure. This can be a Mob, the Player, or Cow.
 * @author Richard Habeeb, Addison Shaw 
 **/
function Entity(layer, r, c, target) {
	var self 					= this;
	this.health 				= 100;
	this.max_health 			= 100;
	this.row 					= r;
	this.col 					= c;	
	this.x 						= (PX_PER_CELL*c)+PX_PER_CELL/2;
	this.y 						= (PX_PER_CELL*r)+PX_PER_CELL/2;
	this.heading 				= NORTH;
	this.target 				= target;
	this.attack_damage_bonus 	= 0;
	this.level 					= 0;
	this.type;					//Player, Cow, Mob
	this.move_time 				= 0.15; //time in tween animation in secs
	this.items 					= new Array();
	this.single_use_items 	= new Array();
	this.current_equip;			//Equiped item.
	this.kills 					= 0;
	this.is_taking_damage 		= false;
	this.time_of_last_hit 		= (new Date()).getTime();
	this.imageObj 				= new Image();
	this.imageObj.src 			= 'images/Brain Jelly.png';
	this.layer 					= layer; 
	this.anim					= null;
	this.loaded 				= false;
	this.sprite = new Kinetic.Image({
			x: this.x,
			y: this.y,
			width: PX_PER_CELL,
			height: PX_PER_CELL,
			offset: { x : PX_PER_CELL/2, y: PX_PER_CELL/2},
			image: this.imageObj
	});
	
	
	/**
	 * This will fire once the imageObj has been loaded. 
	 */
	this.imageObj.onload = function() {
		self.layer.add(self.sprite);
		self.layer.draw();
		self.loaded = true;
	};
	
	/**
	 * This will return false if this entity is animating, or it's equip is animating.
	 * @return {bool}
	 */
	this.IsStopped = function() {
		return 	(this.anim == null 		  || (typeof this.anim === "object" && this.anim.tween._time >= this.anim.tween.duration)) &&
				(this.current_equip == null || (typeof this.current_equip === "object" && !this.current_equip.is_animating));
	};

	
	/**
	 * Move and animate this entity in a given heading
	 * @param {string} heading
	 */
	this.Move = function(heading) {
		if(this.loaded && this.IsStopped()) {
		
			this.FaceHeading(heading);
			
			switch(heading) {
				case NORTH:
					this.row -= 1;
					this.y = Math.max(0, this.y-PX_PER_CELL);
					break;
				case EAST:
					this.col += 1;
					this.x = Math.min(WINDOW_WIDTH_PX, this.x+PX_PER_CELL);
					break;
				case SOUTH:
					this.row += 1;
					this.y = Math.min(WINDOW_HEIGHT_PX, this.y+PX_PER_CELL);
					break;
				case WEST:
					this.col -= 1;
					this.x = Math.max(0, this.x-PX_PER_CELL);
					break;
			}
			this.sprite.setZIndex(this.y);
			this.anim = new Kinetic.Tween({
				node: this.sprite,
				x: this.x,
				y: this.y,
				duration: this.move_time,
				easing: Kinetic.Easings.Linear
			});
			this.anim.play();
		}
	}; //END Move
	
	
	/**
	 * Change the heading field and flip this sprite, if necesary. 
	 * @param {string} heading
	 */
	this.FaceHeading = function(heading) {
		
		if(heading == WEST && heading != this.heading) {
			this.sprite.setScaleX(-Math.abs(this.sprite.getScaleX()));
			this.layer.draw();
		} else if(heading == EAST  && heading != this.heading) {
			this.sprite.setScaleX(Math.abs(this.sprite.getScaleX()));
			this.layer.draw();
		}
		this.heading = heading;
	};
	
	
	/**
	 * Attack. Calculate the cells affected and the damages and return them. Animate the equipped weapon.
	 * UNFINISHED!
	 * @return {Array({r: int, c: int, damage: int})}
	 */
	this.Attack = function() {
		if(this.loaded && typeof this.current_equip != "undefined" && !this.current_equip.is_animating && (this.current_equip.type == ITEM_TYPES.EQUIP || this.current_equip.type == ITEM_TYPES.SINGLE_USE_WEAPON)) {
			var cells_affected = new Array();
			if(this.current_equip.type == ITEM_TYPES.EQUIP) {
				if(this.current_equip.melee) {
					if(this.current_equip.single_direction) {
						var pos = {x:this.x, y:this.y};
						
						if(this.heading == NORTH) {
							pos.y -= this.current_equip.range*PX_PER_CELL;
							for(var i = this.row-1; i >= Math.max(this.row-this.current_equip.range, 0); i--) 
								cells_affected.push({r:i, c:this.col, damage:this.current_equip.base_damage+this.attack_damage_bonus});
						} else if(this.heading == EAST) {
							pos.x += this.current_equip.range*PX_PER_CELL;
							for(var i = this.col+1; i <= Math.min(this.col+this.current_equip.range, WINDOW_WIDTH_CELLS); i++) 
								cells_affected.push({r:this.row, c:i, damage:this.current_equip.base_damage+this.attack_damage_bonus});
						} else if(this.heading == SOUTH) {
							pos.y += this.current_equip.range*PX_PER_CELL;
							for(var i = this.row+1; i <= Math.min(this.row+this.current_equip.range, WINDOW_HEIGHT_CELLS); i++) 
								cells_affected.push({r:i, c:this.col, damage:this.current_equip.base_damage+this.attack_damage_bonus});
						} else if(this.heading == WEST) {
							pos.x -= this.current_equip.range*PX_PER_CELL;
							for(var i = this.col-1; i >= Math.max(this.col-this.current_equip.range, 0); i--) 
								cells_affected.push({r:this.row, c:i, damage:this.current_equip.base_damage+this.attack_damage_bonus});
						}
						
						
						this.current_equip.FaceHeading(this.heading);
						this.current_equip.Animate(pos.x, pos.y);
						
						
					} else {
						
						
					}
				} else {
				
				
				}
			}
			
			return cells_affected;
		}
		return null;
	}; //END Attack
	
	
	/**
	 * Take damage and animate. return the remaining health. Target the attacker. 
	 * @param {int} amount
	 * @param {Entity} from entity
	 * @return {int}
	 */
	this.TakeDamage = function(amount, from_entity) {
		if(this.loaded && !this.is_taking_damage) {
			this.health = Math.max(this.health-amount, 0);
			var init_rotation = this.sprite.getRotation();
			var init_opacity = this.sprite.getOpacity();
			this.is_taking_damage = true;
			this.anim = new Kinetic.Tween({
				node: this.sprite,
				opacity: 0.1,
				rotation: (Math.random() > 0.5) ? Math.random() : -Math.random(),
				duration: 0.1,
				easing: Kinetic.Easings.EaseInOut,
				onFinish: function() {
				
					self.anim = new Kinetic.Tween({
						node: self.sprite,
						opacity: init_opacity,
						rotation: init_rotation,
						duration: 0.1,
						easing: Kinetic.Easings.EaseInOut,
						onFinish: function() { self.is_taking_damage = false;}
					});
					self.anim.play();
					
					if(self.health <= 0) self.sprite.destroy();
				}
			});
			this.anim.play();
			
			if(this.type == MOB) {
				this.target = from_entity;
			
			}	
		}
		return this.health;
	}; //END TakeDamage
	
	
	/**
	 * Use the queued up single use item. If the item causes damage, it returns an array of cells affected.
	 * UNFINISHED
	 * @return {Array({r: int, c: int, damage: int})}
	 */
	this.UseSingleUseItem = function() {
		if (this.single_use_items.length > 0) {
			if(this.single_use_items[0].type == ITEM_TYPES.SINGLE_USE_BUFF) {
				if(this.single_use_items[0].buff_attribute.indexOf("health") !== -1) {
					
					this.health = Math.min(this.health+this.single_use_items[0].buff_amount, this.max_health)
					
				}
				
				this.single_use_items[0].Animate(this.x, this.y);
				

			}
			
			this.single_use_items.splice(0, 1);
			return null;
			
		}
	}

	
	/**
	 * Adds the given item to inventory, if it is an equip, equip it.
	 * @param {Item} item
	 */
	this.AddItem = function(item) 
	{
		if (item.type == ITEM_TYPES.EQUIP) {
			this.items.push(item);
			this.current_equip = item;
		} else {
			this.single_use_items.push(item);
		}
	}


	/**
	 * Removes the given item from 'inventory'
	 * @param {Item} item
	 */
	this.RemoveItem = function(item) 
	{
		if (array.indexOf(item) != -1) 
		{
			this.items.splice(array.indexOf(item), 1);
		}
		
	}
	
	
	/**
	 * Handle what items are dropped when this entity is dead.
	 * @return {Array({item})}
	 */
	this.HandleDrops = function()
	{
		return this.single_use_items;
	}
	
	

}