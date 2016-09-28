function Hud(stage) {
	var self = this;
	this.layer = new Kinetic.Layer();
	this.stage = stage;
	this.stage.add(this.layer);

	
	this.player_health_bar = new Kinetic.Rect({
		x: 20,
		y: 10,
		width: 300,
		height: 30,
		fillLinearGradientStartPoint: 	{x: 0, y:0 },
		fillLinearGradientEndPoint: 	{x: 0, y:30 },
		fillLinearGradientColorStops: [0, "#94B84D", 1, "#669900"]
	});
	
	this.player_health_bar_bg = new Kinetic.Rect({
		x: 20,
		y: 10,
		width: 300,
		height: 30,
		fillLinearGradientStartPoint: 	{x: 0, y:0 },
		fillLinearGradientEndPoint: 	{x: 0, y:30 },
		fillLinearGradientColorStops: [0, "#303030", 1, "#1E1E1E"]
	});
	
	this.player_exp_bar = new Kinetic.Rect({
		x: 20,
		y: 10,
		width: 10,
		height: 5,
		fill: 'white'
	});
	

	this.player_text = new Kinetic.Text({
		x: 15,
		y: 6,
		rotationDeg: 90,
		text: 'PLAYER',
		fontSize: 10,
		fontFamily: 'Square',
		fill: 'white'
	});
	
	
	this.cow_health_bar = new Kinetic.Rect({
		offset: [300,0],
		x: this.stage.getWidth()-20,
		y: 10,
		width: 300,
		height: 30,
		fillLinearGradientStartPoint: 	{x: 0, y:0 },
		fillLinearGradientEndPoint: 	{x: 0, y:30 },
		fillLinearGradientColorStops: [0, "#FF73B0", 1, "#FF4093"]
	});
	
	this.cow_health_bar_bg = new Kinetic.Rect({
		offset: [300,0],
		x: this.stage.getWidth()-20,
		y: 10,
		width: 300,
		height: 30,
		fillLinearGradientStartPoint: 	{x: 0, y:0 },
		fillLinearGradientEndPoint: 	{x: 0, y:30 },
		fillLinearGradientColorStops: [0, "#303030", 1, "#1E1E1E"]
	});
	
	this.cow_text = new Kinetic.Text({
		x: this.stage.getWidth()-8,
		y: 15,
		rotationDeg: 90,
		text: 'COW',
		fontSize: 10,
		fontFamily: 'Square',
		fill: 'white'
	});
	
	this.kills_text_title = new Kinetic.Text({
		x: 330,
		y: 8,
		text: 'KILLS',
		fontSize: 12,
		fontFamily: 'Square',
		fill: '#cc3333'
	});
	
	this.kills_text = new Kinetic.Text({
		x: 330,
		y: 18,
		y: 18,
		text: '0',
		fontSize: 25,
		fontFamily: 'Square',
		fill: '#cc6666'
	});
	
	this.levels_text_title = new Kinetic.Text({
		x: 380,
		y: 8,
		text: 'LEVEL',
		fontSize: 12,
		fontFamily: 'Square',
		fill: '#A030BF'
	});
	
	this.levels_text = new Kinetic.Text({
		x: 380,
		y: 18,
		y: 18,
		text: '1',
		fontSize: 25,
		fontFamily: 'Square',
		fill: '#E073FF'
	});
	
	
	this.equip_text_title = new Kinetic.Text({
		x: 442,
		y: 8,
		text: 'EQUIP',
		fontSize: 12,
		fontFamily: 'Square',
		rotationDeg: 90,
		fill: '#669900'
	});
	this.equipImg = new Image();
	this.equipImg.src = 'images/tazer.png';
	this.equipImg.style.border ='2px solid #F00';
	this.equip = new Kinetic.Image({
			x: 442,
			y: 8,
			width: PX_PER_CELL,
			height: PX_PER_CELL,
			image: this.equipImg
	});
	this.equipImg.onload = function() {
		self.layer.add(self.equip);
	}
	this.equip_bg = new Kinetic.Rect({
		x: 442,
		y: 8,
		width: PX_PER_CELL,
		height: PX_PER_CELL,
		fillLinearGradientStartPoint: 	{x: 0, y:0 },
		fillLinearGradientEndPoint: 	{x: 0, y:30 },
		fillLinearGradientColorStops: [0, "#303030", 1, "#1E1E1E"]
	});
	
	
	this.single_text_title = new Kinetic.Text({
		x: 492,
		y: 8,
		text: 'ITEM',
		fontSize: 12,
		rotationDeg: 90,
		fontFamily: 'Square',
		fill: '#94B84D'
	});
	
	this.singleImg = new Image();
	this.singleImg.src = 'images/repair-kit.png';
	this.single = new Kinetic.Image({
			x: 492,
			y: 8,
			width: PX_PER_CELL,
			height: PX_PER_CELL,
			image: this.singleImg
	});
	this.singleImg.onload = function() {
		self.layer.add(self.single);
	}
	this.single_bg = new Kinetic.Rect({
		x: 492,
		y: 8,
		width: PX_PER_CELL,
		height: PX_PER_CELL,
		fillLinearGradientStartPoint: 	{x: 0, y:0 },
		fillLinearGradientEndPoint: 	{x: 0, y:30 },
		fillLinearGradientColorStops: [0, "#303030", 1, "#1E1E1E"]
	});
	
	this.layer.add(this.player_health_bar_bg);
	this.layer.add(this.player_health_bar);
	this.layer.add(this.player_exp_bar);
	this.layer.add(this.cow_health_bar_bg);
	this.layer.add(this.cow_health_bar);
	this.layer.add(this.player_text);
	this.layer.add(this.cow_text);
	this.layer.add(this.kills_text);
	this.layer.add(this.kills_text_title);
	this.layer.add(this.levels_text);
	this.layer.add(this.levels_text_title);
	this.layer.add(this.equip_bg);
	this.layer.add(this.equip_text_title);
	this.layer.add(this.single_bg);
	this.layer.add(this.single_text_title);
	
	//This should queue up animations if it is already animating.
	this.UpdateStats = function(entity) {
		
		if(entity.type == PLAYER) {
			var tween = new Kinetic.Tween({
				node: this.player_health_bar, 
				duration: 0.3,
				width: this.player_health_bar_bg.getWidth()*entity.health/entity.max_health,
				easing: Kinetic.Easings.BounceEaseInOut
			});
			tween.play();
			
			if(parseInt(this.kills_text.attrs.text) != entity.kills) {
				var tween = new Kinetic.Tween({
					node: this.kills_text, 
					duration: 0.1,
					fontSize: 30,
					easing: Kinetic.Easings.BounceEaseInOut,
					onFinish: function() {
						self.kills_text.setText(entity.kills);
						tween.reverse();
					}
				});
				tween.play();
				
			}
		}
		
		if(entity.type == COW) {
			var tween = new Kinetic.Tween({
				node: this.cow_health_bar, 
				duration: 0.3,
				width: this.cow_health_bar_bg.getWidth()*entity.health/entity.max_health,
				offsetX: this.cow_health_bar_bg.getWidth()*entity.health/entity.max_health,
				easing: Kinetic.Easings.BounceEaseInOut
			});
			tween.play();
		}
		
		
		this.layer.draw();
	}

}