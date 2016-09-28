//ITEM TEMPLATES

var ITEM_DICT = {
	"TAZER": {
		name: "Tazer",
		type: ITEM_TYPES.EQUIP,
		icon_image: "images/tazer.png",
		map_image: "images/tazer.png",
		animation_image: "images/tazer-anim.png", 
		animation_type: "BLINK",
		animation_duration: 300,
		single_direction: true,
		melee: true,
		range: 1,
		base_damage: 100
	},

	"CROWBAR": {
		name: "Crowbar",
		type: ITEM_TYPES.EQUIP,
		icon_image: "images/tazer-anim.png",
		map_image: "images/blue-orb.png",
		animation_image: "images/small-scratch.png", 
		animation_type: "BLINK",
		animation_duration: 1000,
		single_direction: true,
		melee: true,
		range: 1,
		base_damage: 10
	},
	
	"LASER_VISION": {
		name: "Laser Vision",
		type: ITEM_TYPES.EQUIP,
		icon_image: "images/lazer.png",
		map_image: "images/gun.png",
		animation_image: "images/lazer.png", 
		animation_type: "BLINK",
		animation_duration: 1000,
		single_direction: true,
		melee: false,
		range: 100,
		base_damage: 200
	},

	"REPAIR_KIT": {
		name: "Repair Kit",
		type: ITEM_TYPES.SINGLE_USE_BUFF,
		icon_image: "images/repair-kit.png",
		map_image: "images/repair-kit.png",
		animation_image: "images/repair-kit.png", 
		animation_duration: 0.1,
		animation_type: "NONE",
		buff_attribute: "health",
		buff_amount: 25
	},

	"BOMB": {
		name: "Bomb",
		type: ITEM_TYPES.TRAP,
		icon_image: "images/bomb.png",
		map_image: "images/bomb.png",
		animation_image: "images/bomb.png",
		animation_duration: 10000,
		animation_type: "BLINK",
		melee: false,
		range: 50,
		radius: 50,
		base_damage: 150
	}
};