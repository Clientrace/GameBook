/**
 * map.js
 *
 * TODO:
 *  -Adjust monster movement flooding to optimize speed.
 *  -Improve terrain generation
 *  -Add background terrain generation
 *  -Fix fence glitches
 *  -change the ["r"] stuff to .r 
 * 
 * @class The map controller
 * @author Richard Habeeb, Addison Shaw 
 **/
function Map(hud, size_r, size_c) {
	this.hud 						= hud;
	this.time_map_created 			= (new Date()).getTime();
	this.size_r 					= size_r;
	this.size_c 					= size_c;
	this.entities; 					//a 2d array of all entities
	this.walls; 					//a 2d array of logical walls and blocks
	this.items; 					//a 2d array of items
	this.player;					//reference to the player
	this.cow;						//reference to the cow
	this.mob_count 					= 0;
	this.mob_cap					= 40;
	this.largest_mob_group			= 10; //# of aliens that can spawn at once in a group.
	this.horde_states				= ["BUILD", "PEAK", "RESTORE"];
	this.horde_state				= 0; //BUILD
	this.horde_state_time			= (new Date()).getTime();
	this.horde_state_max_time		= 1*60*1000; //ms
	this.edge_spawn_size_cells		= 3; //the game can spawn mobs within edge_spawn_size_cells spaces of the edge
	this.max_barriers				= 20;
	this.min_barriers				= 3;
	this.cowpen_min_dim				= 4;
	this.cowpen_max_dim				= 8;
	this.item_count 				= 0;
	this.total_item_cap				= 10;
	this.items_count				= {};
	this.spawnable_items 			= ["LASER_VISION", "REPAIR_KIT", "BOMB"];
	this.spawnable_items_probs		= {"LASER_VISION" : 0.1, "REPAIR_KIT" : 0.5, "BOMB" : 0.2 };
	this.spawnable_items_limits		= {"LASER_VISION" : 1, "REPAIR_KIT" : 20, "BOMB" : 6 };
	this.dropable_items				= ["REPAIR_KIT", "BOMB"];
	this.dropable_items_probs		= {"REPAIR_KIT": 0.05, "BOMB": 0.02};
	this.dropables_per_mob			= 3;
	this.flood_unassigned_depth		= Number.MAX_VALUE;
	this.game_over_flag				= false; //This flag is polled by gameplay.
	this.background_layer 			= new Kinetic.Layer();
	this.items_layer 				= new Kinetic.Layer();
	this.monster_layer 				= new Kinetic.Layer();
	this.player_layer 				= new Kinetic.Layer();
	this.walls_layer 				= new Kinetic.Layer();
	this.anim_layer 				= new Kinetic.Layer();
	
	
	/**
	 * Configure the map onto the KineticJS stage.
	 * @param {Kinetic.Stage} stage
	 */
	this.SetupMapOnStage = function(stage) {
		if(typeof stage === "object") {
			stage.add(this.background_layer);
			stage.add(this.items_layer);
			stage.add(this.monster_layer);
			stage.add(this.player_layer);
			stage.add(this.walls_layer);
			stage.add(this.anim_layer);
			this.background_layer.draw();
			this.items_layer.draw();
			this.monster_layer.draw();
			this.player_layer.draw();
			this.walls_layer.draw();
			this.anim_layer.draw();
		} else alert("Error setting up " + (typeof stage));
	};
	
	
	/**
	 * Reinitalize the walls property.
	 */
	this.SetupWalls = function() {
		this.walls = new Array();
		for(var r = 0; r < this.size_r; r++) {
			this.walls[r] = new Array();
			for(var c = 0; c < this.size_c; c++) {
				this.walls[r][c] = { //Put walls on the outer edges.
					NORTH: 	(r == 0),
					EAST:	(c == this.size_c-1),
					SOUTH:	(r == this.size_r-1),
					WEST:	(c == 0),
					BLOCKED: false,
					LOCKED: false,
					IMAGE:	 new MapCell(this.walls_layer, r, c)
				};
			}
		}
	};
	
	
	/**
	 * Reinitalize the entities property.
	 */
	this.SetupEntities = function() {
		this.entities = new Array();
		this.mob_count = 0;
		for(var r = 0; r < this.size_r; r++) {
			this.entities[r] = new Array();
			for(var c = 0; c < this.size_c; c++) {
				this.entities[r][c] = null;
			}
		}
	
	};
	
	
	/**
	 * Reinitalize the items_count,item_count,items properties.
	 */
	this.SetupItems = function() {
		for(var item in ITEM_DICT) {
			this.items_count[item] = 0;
		}
	
		this.items = new Array();
		this.item_count = 0;
		for(var r = 0; r < this.size_r; r++) {
			this.items[r] = new Array();
			for(var c = 0; c < this.size_c; c++) {
				this.items[r][c] = null;
			}
		}
	};
	
	
	/**
	 * Add the cow entity to the map.
	 */
	this.SetupCow = function() {
		var spawn_cell = this.GetRandomSpawnCell();
		this.cow = new Entity(this.player_layer, Math.floor(this.size_r/2), Math.floor(this.size_c/2), null);
		this.cow.type = COW;
		this.cow.health = 1000;
		this.cow.max_health = 1000;
		this.cow.move_time = 2;
		this.cow.imageObj.src = COW_IMAGE;
	};
	
	
	/**
	 * Add the player entity to the map.
	 */
	this.SetupPlayer = function() {
		var spawn_cell = this.GetRandomSpawnCell();
		this.player = new Entity(this.player_layer, spawn_cell["r"], spawn_cell["c"], null);
		this.player.type = PLAYER;
		this.player.health = 100;
		this.player.max_health = 100;
		this.player.imageObj.src = PLAYER_IMAGE;
		this.player.AddItem(new Item("TAZER", this.items_layer, this.anim_layer));
	};
	
	
	/**
	 * Add all the barriers, and the fence to the map.
	 */
	this.GenerateTerrain = function() {
		
		this.GenerateAnimalPen(this.cow.row, this.cow.col);
		
		var num_barriers = this.min_barriers + Math.floor(Math.random()*(this.max_barriers-this.min_barriers));
		
		for(var i = 0; i < num_barriers; i++) {
			var cell = this.GetRandomOpenCell();
			this.AddBarrier(cell["r"], cell["c"]);
		}
	};
	
	
	/**
	 * Initate an entity attack, 
	 * @param {Entity} entity
	 */
	this.EntityAttack = function(entity) {
		this.HandleAttackedCells(entity, entity.Attack());
	};
	
	
	/**
	 * Initate and handle an entity healing.
	 * @param {Entity} entity
	 */
	this.UseSingleUseItem = function(entity) {
			
		this.HandleAttackedCells(entity, entity.UseSingleUseItem());
		
		if(entity.type == PLAYER || entity.type == COW) this.hud.UpdateStats(entity);
		
	};
	
	/**
	 * Deal out damage to attacked cells, 
	 * @param {Entity} attacker
	 * @param {Array({r,c,damage})} entity
	 */
	this.HandleAttackedCells = function(attacker, cells_affected) {
		if(cells_affected != null) {
			for(var i = 0; i < cells_affected.length; i++) {
				var attacked_ent;
				if((attacked_ent = this.entities[cells_affected[i].r][cells_affected[i].c]) != null)  {
					if(attacked_ent.TakeDamage(cells_affected[i].damage, attacker) <= 0) {
						//entity attacked just died.
						attacker.kills++;
						this.entities[cells_affected[i].r][cells_affected[i].c] = null;
						this.HandleDrops(cells_affected[i].r, cells_affected[i].c, attacked_ent.HandleDrops());
						if(attacked_ent.type == MOB) {
							this.mob_count--;
						} else if(attacked_ent.type == PLAYER || attacked_ent.type == COW) {
							this.game_over_flag = true;
						}
						
					}
					
					if(attacked_ent.type == PLAYER || attacked_ent.type == COW) this.hud.UpdateStats(attacked_ent);
				}
			}
		}
		
		if(attacker.type == PLAYER || attacker.type == COW) this.hud.UpdateStats(attacker);
	};
	
	
	/**
	 * Handle when and where a monster gets spawned. This method is called on an interval from gameplay.js.
	 * Factors that affect the monster spawning: 
	 *  # of aliens on map,
	 *  player health,
	 *  player health lost since last spawn handling,
	 *  time since last player damage,
	 *  the "power" of the player (possibly determined by item strength),
	 *  distance of player from the cow
	 */
	this.HandleMonsterSpawning = function() {
		var current_time = (new Date()).getTime();
		var max_mobs = this.mob_cap;
		var number_of_mobs_to_spawn = 0;
		
		if(current_time - this.horde_state_time > this.horde_state_max_time) {
			this.horde_state = (this.horde_state+1) % this.horde_states.length;
			this.horde_state_time = current_time;
			console.log("AI State:" + this.horde_states[this.horde_state]);
		}
		
		if(this.horde_states[this.horde_state] == "BUILD") {
			max_mobs = Math.floor(this.mob_cap / 2);
			//the number of mobs that are spawned is based in part on the time since the player was last damaged. (always at least one).
			number_of_mobs_to_spawn = Math.ceil(Math.random()*Math.min(((new Date()).getTime() - this.player.time_of_last_hit)/(15.0*1000), 1.0)*Math.min(max_mobs - this.mob_count, this.largest_mob_group));
		}
			
		if(this.horde_states[this.horde_state] == "PEAK") {
			max_mobs = this.mob_cap;
			number_of_mobs_to_spawn = Math.min(max_mobs-this.mob_count, this.largest_mob_group);
			
			if(number_of_mobs_to_spawn == 0) this.horde_state = (this.horde_state+1) % this.horde_states.length;
		}
		
		if(this.horde_states[this.horde_state] == "RESTORE") {
			max_mobs = Math.floor(this.mob_cap / 4);
			number_of_mobs_to_spawn = 1;
		}
		
		
		
		
		
		
		//the probability of spawning an alien horde is proportional to the # of aliens available to spawn as well as the time since the player was last damaged.
		if(this.mob_count < max_mobs && Math.random() > (this.mob_count/max_mobs )) { 
			
			this.SpawnMobGroup(number_of_mobs_to_spawn, this.GetRandomSpawnCell());
			

		}
		
		
		
		
	}; //END HandleMonsterSpawning
	
	
	/**
	 * Handle when and where an item gets spawned. This method is called on an interval from gameplay.js.
	 * 	Similarly to monster spawning we should only have a certain number of items on the map at any time.
	 *		We'll want to have bias probablities for spawning each item
	 *		
	 *		meaning bombs will spawn the most, as a single use item
	 *		swords will be slightly better as reusible items followed by the machine gun which should have ammo.
	 *		
	 *		The bias probablities will only come into play if an item can be spawned
	 *		
	 *		An item *can* be spawned 
	 *			-if there is an open *valid* space, no groups so this shouldn't be tough
	 *			
	 *			-if there is not more than a certain number of that item (Bombs could probably have 2 on the map? This does not include items that have been placed.)
	 *				So if you drop a bomb as an attack, new bombs can still spawn. We can have a flag to know whether or not the item is dropped.
	 *				If you kill a monster, it has a probablity to drop from it's inventory. This should not affect normal item spawning probablity.	
	 */
	this.HandleItemSpawning = function() {

		if(this.item_count < this.total_item_cap && Math.random() > (this.item_count/this.total_item_cap )) { 
		
			var spawn_cell = this.GreedySearchForValidSpawnCell(1);
			
			//valid space
			if(spawn_cell != null) { 
				//Get random item
				var randItem = new Item(this.spawnable_items[Math.floor(Math.random()* (this.spawnable_items.length))], this.items_layer, this.anim_layer);
				
				
				// Get item bias probablity -- spawn item
				if (this.items_count[randItem.key] <= this.spawnable_items_limits[randItem.key])
				{
					if (this.spawnable_items_probs[randItem.key] >= Math.random()) {
						randItem.ShowImageOnMap(spawn_cell["r"], spawn_cell["c"]);
						this.items_count[randItem.key]++;
						this.item_count++;
						this.items[spawn_cell["r"]][spawn_cell["c"]] = randItem;
					}
						
				}
			}
		}	
	}; //END HandleItemSpawning

	
	/**
	 * Handles the Aliens' movement. This method is called on a periodic inverval.
	 */
	this.HandleAlienMovements = function() {
		for(var r = 0; r < this.size_r; r++) {
			for(var c = 0; c < this.size_c; c++) {
				var mob = this.entities[r][c];
				if(mob != null && mob.target != null && Math.random() > 0.5 && mob.IsStopped()) {
					var next_best_heading = this.GetNextBestHeading(mob.row, mob.col, mob.target.row, mob.target.col, false)
					var next_best_cell = this.GetCellInHeading(r, c, next_best_heading);
					
					this.MoveEntity(mob, next_best_heading);
					
					if(next_best_cell["r"] == mob.target.row && next_best_cell["c"] == mob.target.col) {
						this.EntityAttack(mob);
					}
				}
			}
		}
	};
	
	
	/**
	 * Handles the cow movement. This method is called on a periodic inverval.
	 */
	this.HandleCowMovement = function() {
		if(this.cow.IsStopped() && Math.random() > 0.5) {
			var headings = [NORTH,EAST,SOUTH,WEST];
			this.MoveEntity(this.cow, headings[Math.floor(Math.random()*headings.length)]);
		}
	};
	
	
	/**
	 * Spawn a mob group on the map at a cell.
	 * @param {int} r
	 * @param {int} c
	 */
	this.SpawnMobGroup = function(number_to_spawn, cell) {
		var headings = new Array(NORTH,EAST,SOUTH,WEST);
		var number_spawned_already = 0;
		var queue = [cell];
		
		while(number_spawned_already < number_to_spawn && queue.length > 0 && queue[0] != null) {
			var frontier_cell = queue[0];
			queue.splice(0,1);
			
			if(this.IsValidSpawnCell(frontier_cell["r"], frontier_cell["c"]))
			{
				this.SpawnMob(frontier_cell["r"], frontier_cell["c"]);
				number_spawned_already++;
			}
			
			for(var i = 0; i < headings.length; i++) {
				var cell = this.GetCellInHeading(frontier_cell["r"], frontier_cell["c"], headings[i]);
	
				if(!this.walls[frontier_cell["r"]][frontier_cell["c"]][headings[i]] && this.IsValidSpawnCell(cell["r"], cell["c"]))
				{	
					queue.push(cell);
				}		
			}
		} //end while
	}
	
	
	/**
	 * Spawn a mob on the map. (This assumes that the passed in cell is valid)
	 * @param {int} r
	 * @param {int} c
	 */
	this.SpawnMob = function(r, c) {
		
		var mob = new Entity(this.monster_layer, r, c, this.cow);
		mob.type = MOB;
		mob.health = 50 + Math.floor(Math.random()*this.player.kills*10);
		mob.max_health = mob.health;
		mob.move_time = 1;
		
		for(var j = 0; j < this.dropables_per_mob; j++) {//iterate though the possible drops and possibly pick one. 
			for(var i = 0; i < this.dropable_items.length; i++) {  
				if(Math.random() < this.dropable_items_probs[this.dropable_items[i]]) {
					mob.AddItem(new Item(this.dropable_items[i], this.items_layer, this.anim_layer));
					break;
				}
			}
		}
		mob.AddItem(new Item("CROWBAR", this.items_layer, this.anim_layer));
		
		mob.imageObj.src = ALIEN_IMAGES[Math.floor(Math.random()*ALIEN_IMAGES.length)];
		
		this.entities[mob.row][mob.col] = mob;
		this.mob_count++;
	};
	
	
	/**
	 * Move an entity in a given heading. This will check for walls and check for item pickups
	 * @param {Entity} entity
	 * @param {string} heading
	 */
	this.MoveEntity = function(entity, heading) {
		var adjacent = this.GetCellInHeading(entity.row,entity.col,heading);
		
		if(adjacent["r"] != entity.row || adjacent["c"] != entity.col) {
		
			if(entity.loaded && entity.IsStopped()) {
			
				if(	this.walls[entity.row][entity.col][heading] === false &&
					this.walls[adjacent["r"]][adjacent["c"]][BLOCKED] === false &&
					this.entities[adjacent["r"]][adjacent["c"]] === null
				)
				{
					this.entities[entity.row][entity.col] = null;
					entity.Move(heading);
					this.entities[entity.row][entity.col] = entity;
					
					//check for items in this square of player
					if(entity.type == PLAYER && this.items[entity.row][entity.col] != null) {
						entity.AddItem(this.items[entity.row][entity.col]);
						this.items[entity.row][entity.col].HideImageOnMap();
						this.items_count[this.items[entity.row][entity.col].key]--;
						this.item_count--;
						this.items[entity.row][entity.col] = null;	
					}
					
				} else {
					entity.FaceHeading(heading);
				}
			}
		}
	}; //END MoveEntity
	
	
	/**
	 * Search for a valid spawn cell group and return the center cell. Uses a greedy algorithm (needs more testing).
	 * @param {int} size (max of 5)
	 * @return {"r": int, "c": int} OR null if no cells exist.
	 */
	this.GreedySearchForValidSpawnCell = function(size) {
		
		var tested_cells = new Array();
		for(var r = 0; r < this.size_r; r++) {
			tested_cells[r] = new Array();
			for(var c = 0; c < this.size_c; c++) {
				tested_cells[r][c] = false;
			}
		}
	
		var temp_cell = this.GetRandomSpawnCell();
		pivot_cell_r = temp_cell["r"];
		pivot_cell_c = temp_cell["c"];
		
		var spawn_pivot_cell_valid = (size == 1); // we are good if only one thing needs spawning.
		
		while(!spawn_pivot_cell_valid) {
			
			tested_cells[pivot_cell_r, pivot_cell_c] = true;
			
			if(this.GetNumberOfSpawnableAdjacentCells(pivot_cell_r, pivot_cell_c) >= size-1) {//Check if the cell group is valid
				
				spawn_pivot_cell_valid = true;
			
			} else { //The next location we will check is the MOST OPEN ADJACENT CELL that we haven't checked yet.
				
				var next_r = pivot_cell_r;
				var next_c = pivot_cell_c;
				var next_best_num_open_cells = 0;
				var temp_next;
				var headings = new Array(NORTH,EAST,SOUTH,WEST);
				
				
				for(var i = 0; i < headings.length; i++) {
				
					var cell = this.GetCellInHeading(pivot_cell_r, pivot_cell_c, headings[i]);
					
					if((cell["r"] != pivot_cell_r || cell["c"] != pivot_cell_c) &&
						!tested_cells[cell["r"]][cell["c"]] && 
						this.IsValidSpawnCell(cell["r"], cell["c"]) && 
						(temp_next = this.GetNumberOfSpawnableAdjacentCells(cell["r"], cell["c"])) > next_best_num_open_cells)
					{
						next_best_num_open_cells = temp_next;
						next_r = cell["r"];
						next_c = cell["c"];
					}
				}
				
				
				if(next_best_num_open_cells == 0) { //If all the adjacent cells are not good for spawning or have been checked (we're trapped)...
					//then we move into a brute force iteration through cells looking for a good start point. 
					//This loses some of the true random of this method, but it is rare that we get trapped. (this needs more testing)
					
					console.log("Testing: GreedySearchForValidSpawnCell got trapped, looking for a new search-entry point.");
					var spawn_cells_dont_exist = true;
					
					for(var r = 0; r < this.size_r; r++) {
						
						
						for(var c = 0; c < this.size_c; c++) {
							
							if(!tested_cells[r][c] && IsValidSpawnCell(r,c)) {
								
								spawn_cells_dont_exist = false;
								next_r = r;
								next_c = c; //found a new starting point for next iteration.
								break;
								
							} else {
								tested_cells[r][c] = true;
							}
						}
						
						if(!spawn_cells_dont_exist) break;
					}
					
					if(spawn_cells_dont_exist) return null;
					
					
				}
				
				pivot_cell_r = next_r;
				pivot_cell_c = next_c;
				
			}
		}
		
		return {"r": pivot_cell_r, "c": pivot_cell_c};
		
	}; //END GreedySearchForValidSpawnCell
	
	
	/**
	 * This algorithm implements a pathfinding algorithm to determine the next best cell given a start cell and end cell.
	 * The algorithm will always return a heading, and it may recurse a single level 
	 * @param {int} row_start The row of the starting cell
	 * @param {int} col_start The col of the starting cell
	 * @param {int} row_end The row of the end cell of the flood. 
	 * @param {int} col_end The row of the end cell of the flood.
	 * @param {bool} ignore_entities This is used for the recursive case where entities are blocking the path.
	 * @return {string} next_best_heading NORTH, SOUTH, EAST, WEST
	 */
	this.GetNextBestHeading = function(row_start, col_start, row_end, col_end, ignore_entities) {
	    var flooded_map = this.FloodMap(row_start, col_start, row_end, col_end, ignore_entities);
		
		var next_best_heading = NORTH;

		if(flooded_map != null) {
			var headings = new Array(NORTH,EAST,SOUTH,WEST);
			
			var next_best_cell = {"r": row_start, "c": col_start};
			
			for(var i = 0; i < headings.length; i++) {
				var cell = this.GetCellInHeading(row_start, col_start, headings[i]);
				
				if(	
					!this.walls[row_start][col_start][headings[i]] && 
					!this.walls[cell["r"]][cell["c"]][BLOCKED] && 
					(this.entities[cell["r"]][cell["c"]] == null || ignore_entities || (cell["r"] == row_end && cell["c"] == col_end)) &&
					flooded_map[next_best_cell["r"]][next_best_cell["c"]] != this.flood_unassigned_depth &&
					flooded_map[next_best_cell["r"]][next_best_cell["c"]] > flooded_map[cell["r"]][cell["c"]]
				  ) 
				{
					next_best_cell = {"r": cell["r"], "c": cell["c"]};
					next_best_heading = headings[i];
				}
					
			}
			
		} else if(!ignore_entities) {
			
			next_best_heading = this.GetNextBestHeading(row_start, col_start, row_end, col_end, true);
			
		} else {
			//if no direct path is found just go directly towards the goal. Wall checking maybe necessary here.
			
			if(row_start > row_end) next_best_heading = NORTH;
			if(row_start < row_end) next_best_heading = SOUTH;
			if(col_start > col_end) next_best_heading = WEST;
			if(col_start < col_end) next_best_heading = EAST;
			
		}
		return next_best_heading;
	}; //END GetNextBestHeading
	
	
	/**
	 * This algorithm does a breadth-first search using a flood-fill technique.
	 * The algorithm will quit once the end cell is flooded.
	 * @param {int} row_start The row of the "starting cell" of the flood
	 * @param {int} col_start The col of the "starting cell" of the flood 
	 * @param {int} row_end The row of the end cell of the flood. 
	 * @param {int} col_end The row of the end cell of the flood.
	 * @return {int, int} flooded_map each element in this array will contain the "depth" or the number of steps to get to the middle.
	 */
	this.FloodMap = function(row_end, col_end, row_start, col_start, ignore_entities) {
		var flood_max_depth = this.size_r*this.size_c;	
		
		var flooded_map = new Array();
		for(var r = 0; r < this.size_r; r++) {
			flooded_map[r] = new Array();
			for(var c = 0; c < this.size_c; c++) {
				flooded_map[r][c] = this.flood_unassigned_depth;
			}
		}

		flooded_map[row_start][col_start] = 0;
		var flood_change = true;
		
		// Flood from the goal of the maze towards the current position
		for(var frontier_depth = 1; frontier_depth < flood_max_depth; frontier_depth++) 
		{
			if(!flood_change) return; //dead end check
			else flood_change = false;
			
			for(var r = 0; r < this.size_r; r++)
			{
				for(var c = 0; c < this.size_c; c++)
				{ 
					if	(	flooded_map[r][c] == this.flood_unassigned_depth && 
							!this.walls[r][c][BLOCKED] && 
							(
								this.entities[r][c] == null || 
								(row_end == r && col_end == c) ||
								ignore_entities
							) && 
							(
								(r+1 < this.size_r		&& (flooded_map[r+1][c] == frontier_depth-1) && !this.walls[r][c][SOUTH]) ||
								(r-1 >= 0			 	&& (flooded_map[r-1][c] == frontier_depth-1) && !this.walls[r][c][NORTH]) ||
								(c+1 < this.size_c 		&& (flooded_map[r][c+1] == frontier_depth-1) && !this.walls[r][c][EAST] ) ||
								(c-1 >= 0	 			&& (flooded_map[r][c-1] == frontier_depth-1) && !this.walls[r][c][WEST] ) 
							)
						)
					{
						flooded_map[r][c] = frontier_depth; //the next cell's depth is the current cell's depth + 1
						flood_change = true;
						if(row_end == r && col_end == c) return flooded_map; // made it to the goal so we've flooded enough
					}
				}
			}
		} // end of frontier depth 'for' loop
	    
	}; //END FloodMap
	
	
	/**
	 * This adds the animal pen to the map around the given point.
	 * @param {int} center_point_r The row of cow
	 * @param {int} center_point_c The col of cow
	 */
	this.GenerateAnimalPen = function(center_point_r, center_point_c) {
		var headings = [NORTH,EAST,SOUTH,WEST];
		
		//setup cow pen
		var fence_size_c = this.cowpen_min_dim + Math.floor(Math.random() * (this.cowpen_max_dim - this.cowpen_min_dim));
		var fence_size_r = this.cowpen_min_dim + Math.floor(Math.random() * (this.cowpen_max_dim - this.cowpen_min_dim));
		
		var fence_opening_side = headings[Math.floor(Math.random() * headings.length)];
		var fence_opening_size = 1;
		if(fence_opening_side == NORTH || fence_opening_side == SOUTH) 
			fence_opening_size = 1 + Math.floor(Math.random() * (fence_size_r - 1));
			
		if(fence_opening_side == EAST || fence_opening_side == WEST) 
			fence_opening_size = 1 + Math.floor(Math.random() * (fence_size_c - 1));
		
		var start_corner_r = center_point_r-Math.round(fence_size_r/2);
		var start_corner_c = center_point_c-Math.round(fence_size_c/2);
		
		
		for(var r = start_corner_r; r < start_corner_r+fence_size_r; r++) {
			if((fence_opening_side == WEST && r > start_corner_r+fence_opening_size-1) || fence_opening_side != WEST) 
				this.SetWall(r, start_corner_c, WEST);
				
			if((fence_opening_side == EAST && r > start_corner_r+fence_opening_size-1) || fence_opening_side != EAST) 
				this.SetWall(r, start_corner_c+fence_size_c-1, EAST);
		}
		for(var c = start_corner_c; c < start_corner_c+fence_size_c; c++) {
			if((fence_opening_side == NORTH && c > start_corner_c+fence_opening_size-1) || fence_opening_side != NORTH) 
				this.SetWall(start_corner_r, c, NORTH);
				
			if((fence_opening_side == SOUTH && c > start_corner_c+fence_opening_size-1) || fence_opening_side != SOUTH)  
				this.SetWall(start_corner_r+fence_size_r-1, c, SOUTH);
		}
	}; //END GenerateAnimalPen
	
	
	/**
	 * Get the cell in the specified direction
	 * @param {int} r starting row
	 * @param {int} c starting col
	 * @return {"r": int, "c": int}
	 */
	this.GetCellInHeading = function(r,c,heading) {
				if(heading == NORTH) 	return {"r": Math.max(r-1, 0), 				"c":c};
		else 	if(heading == EAST ) 	return {"r": r, 							"c": Math.min(c+1, this.size_c-1)};
		else 	if(heading == SOUTH) 	return {"r": Math.min(r+1, this.size_r-1), 	"c":c};
		else 					   		return {"r": r, 							"c": Math.max(c-1, 0)};
	};
	
	
	/**
	 * Test a cell to see if it is a valid spawn cell (On the map, on the edge. not blocked, not occupied)
	 * @param {int} cell_r
	 * @param {int} cell_c
	 * @return {bool}
	 */
	this.IsValidSpawnCell = function(cell_r, cell_c) {

		return ( 	cell_r >= 0 && 	
					cell_r < this.size_r &&
					cell_c >= 0 &&
					cell_c < this.size_c &&
					(	
						cell_r < this.edge_spawn_size_cells || 
						cell_r >= this.size_r-this.edge_spawn_size_cells ||
						cell_c < this.edge_spawn_size_cells ||
						cell_c >= this.size_c-this.edge_spawn_size_cells
					) &&
					!(
						this.walls[cell_r][cell_c][NORTH] && 
						this.walls[cell_r][cell_c][EAST] && 
						this.walls[cell_r][cell_c][SOUTH] && 
						this.walls[cell_r][cell_c][WEST]
					) &&
					!this.walls[cell_r][cell_c][BLOCKED] &&
					this.entities[cell_r][cell_c] == null &&
					this.items[cell_r][cell_c] == null
					
				);
	};
	

	/**
	 * Test a cell to see if it is a valid "open" cell (on the map, no walls, barriers, or entites.)
	 * @param {int} cell_r
	 * @param {int} cell_c
	 * @return {bool}
	 */
	this.IsValidOpenCell = function(cell_r, cell_c) {
		return ( 	(cell_r >= 0 && 	
					cell_r < this.size_r &&
					cell_c >= 0 &&
					cell_c < this.size_c) &&
					!this.walls[cell_r][cell_c][NORTH]   &&
					!this.walls[cell_r][cell_c][EAST]    &&
					!this.walls[cell_r][cell_c][SOUTH]   && 
					!this.walls[cell_r][cell_c][WEST]    &&
					!this.walls[cell_r][cell_c][BLOCKED] &&
					this.entities[cell_r][cell_c] == null &&
					this.items[cell_r][cell_c] == null		
				);
	};
	
	
	/**
	 * Get a cell that is able to be spawned on using pure random guessing. (infinite time worst case, but it usually only takes 1-10 iterations)
	 * @return {"r": int, "c": int}
	 */
	this.GetRandomSpawnCell = function() { 
		var cell_c = null;
		var cell_r = null;

		while(cell_c == null || cell_r == null || !this.IsValidSpawnCell(cell_r, cell_c))
		{	 
			cell_r = Math.floor(Math.random()*this.size_r);
			cell_c = Math.floor(Math.random()*this.size_c);
		}
		
		return {"r": cell_r, "c": cell_c};
	};
	
	
	/**
	 * Get a cell that has no walls or barriers using pure random guessing. (infinite time worst case, but is pretty good in practice.)
	 * @return {"r": int, "c": int}
	 */
	this.GetRandomOpenCell = function() { 
		var cell_c = null;
		var cell_r = null;
		
		while(cell_c == null || cell_r == null || !this.IsValidOpenCell(cell_r, cell_c))
		{	 
			cell_r = Math.floor(Math.random()*this.size_r);
			cell_c = Math.floor(Math.random()*this.size_c);
		}
		return {"r": cell_r, "c": cell_c};
	};
	
	
	/**
	 * Test adjacent cells and return the number of which of those cells were spawnable
	 * @param {int} r
	 * @param {int} c
	 * @return {int}
	 */
	this.GetNumberOfSpawnableAdjacentCells = function(r, c) {
		var count = 0;
		if(this.IsValidSpawnCell(r+1, c)) count++;
		if(this.IsValidSpawnCell(r-1, c))  count++;
		if(this.IsValidSpawnCell(r, c+1)) count++;
		if(this.IsValidSpawnCell(r, c-1))  count++;
		return count;
	};
	
	
	/**
	 * This method will add a fence onto the map.
	 * @param {int} row
	 * @param {int} col
	 * @param {string} heading
	 */
	this.SetWall = function(row, col, heading) {
		this.walls[row][col][heading] = true;
		this.UpdateFenceImage(row, col);
		
		if(heading === NORTH) {
			if(row-1 >= 0) this.walls[row-1][col][SOUTH] = true;
			this.UpdateFenceImage(row-1, col);
			
		} else if(heading === EAST) {
			if(col+1 < this.size_c) this.walls[row][col+1][WEST] = true;
			this.UpdateFenceImage(row, col+1);
			
		} else if(heading === SOUTH) {
			if(row+1 < this.size_r) this.walls[row+1][col][NORTH] = true;
			this.UpdateFenceImage(row+1, col);
		
		} else if(heading === WEST) {
			if(row-1 >= 0) this.walls[row][col-1][EAST] = true;
			this.UpdateFenceImage(row, col-1);
		}
	};
	
	
	/**
	 * This method will add a barrier onto the map, if it can.
	 * @param {int} row
	 * @param {int} col
	 * @return {bool} True, if this was able to add the barrier.
	 */
	this.AddBarrier = function(r, c) {
		if(!this.IsValidOpenCell(r, c) || this.walls[r][c][IMAGE] == null) return false;
		
		this.walls[r][c][BLOCKED] = true;
		this.walls[r][c][IMAGE].PushImage(BARRIER_IMAGES[Math.floor(Math.random()*BARRIER_IMAGES.length)]);
		
		return true;
	};
	
	
	/**
	 * This recursive method will update the fence images onto the map cells given what is in this.walls.
	 * This can really lag up the game setup when fences are added in parallel like this | | | |.
	 * @param {int} r
	 * @param {int} c
	 */
	this.UpdateFenceImage = function(r, c) {
		if(r >= 0 && r < this.size_r && c >= 0 && c < this.size_c) {
			if(this.walls[r][c][NORTH]) this.UpdateFenceImage(r-1, c); //wall images are only south and east.
			if(this.walls[r][c][WEST]) this.UpdateFenceImage(r, c-1);
			
			if(this.walls[r][c][SOUTH] || this.walls[r][c][EAST]) {
				var image_string = (this.walls[r][c][SOUTH]) ? "/games/invasion/js/images/fence-south" : "/games/invasion/js/images/fence";
				image_string = (this.walls[r][c][EAST]) ? image_string+"-east.png" : image_string+".png";
				
				this.walls[r][c][IMAGE].PushImage(image_string);
			}
		}
	};
	
	
	/**
	 * This method uses a flood fill to drop all the items in an array on the nearby ground
	 * @param {int} row
	 * @param {int} col
	 * @param {Array} drops
	 */
	this.HandleDrops = function(row, col, drops) {
		if(drops != null && drops.length >= 0) {
			var headings = new Array(NORTH,EAST,SOUTH,WEST);
			var index = 0;
			var queue = [{"r": row, "c":col }];
			
			while(index < drops.length && queue.length > 0) {
				var frontier_cell = queue[0];
				queue.splice(0,1);
				
				if(	
					!this.walls[frontier_cell["r"]][frontier_cell["c"]][BLOCKED] && 
					this.entities[frontier_cell["r"]][frontier_cell["c"]] == null &&
					this.items[frontier_cell["r"]][frontier_cell["c"]] == null) 
				{
					if(this.items_count[drops[index].key] <= this.spawnable_items_limits[drops[index].key]) 
					{
						drops[index].ShowImageOnMap(frontier_cell.r, frontier_cell["c"]);
						this.items[frontier_cell["r"]][frontier_cell["c"]] = drops[index];
						this.items_count[drops[index].key]++;
						this.item_count++;
					}
					index++;
				}
				
				for(var i = 0; i < headings.length; i++) {
					var cell = this.GetCellInHeading(frontier_cell["r"], frontier_cell["c"], headings[i]);
					
					
					if(	
						!this.walls[frontier_cell["r"]][frontier_cell["c"]][headings[i]] && 
						!this.walls[cell["r"]][cell["c"]][BLOCKED] && 
						this.entities[cell["r"]][cell["c"]] == null &&
						this.items[cell["r"]][cell["c"]] == null) 
					{	
						queue.push(cell);
					}		
				}
			} //end while
		}
	}; //END HandleDrops
	
	
}
