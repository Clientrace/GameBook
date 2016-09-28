/**
 * mapcell.js
 *
 * @class A map cell stucture. This has a stack of Kinetic Images.
 * @author Richard Habeeb, Addison Shaw 
 **/
function MapCell(layer, r, c) {
	var self 				= this;
	this.layer 				= layer;
	this.row 				= r;
	this.col 				= c;
	this.x 					= (PX_PER_CELL*c)+PX_PER_CELL/2;
	this.y 					= (PX_PER_CELL*r)+PX_PER_CELL/2;
	this.images 			= new Array();
	this.loaded 			= new Array();
	this.kinetic_images 	= new Array();
	
		
	/**
	 * Add an image to this map cell
	 * @param {string} image src
	 */
	this.PushImage = function(src) {
	
		var index = this.images.length;
		
		this.images.push(new Image());
		
		this.loaded.push(false);
		
		this.kinetic_images.push(new Kinetic.Image({
			x: this.x,
			y: this.y,
			width: PX_PER_CELL,
			height: PX_PER_CELL,
			offset: { x : PX_PER_CELL/2, y: PX_PER_CELL/2},
			image: this.images[index]
		}));
		
		this.images[index].src = src;
		
		/**
		 * This will fire once the image has been loaded. 
		 */
		this.images[index].onload = function() {
			self.layer.add(self.kinetic_images[index]);
			self.layer.draw();
			self.loaded[index] = true;
		};
		
	}; //END PushImage
}