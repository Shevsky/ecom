<?php
return array(
	'wa_shipping_ecom_points' => array(
		'id' => array('int', 11, 'null' => 0, 'autoincrement' => 1),
		'object_id' => array('int', 11, 'null' => 0, 'default' => '0'),
		'name' => array('varchar', 255),
		'description' => array('text'),
		'way' => array('text'),
		'legal_name' => array('varchar', 255),
		'legal_short_name' => array('varchar', 255),
		'status' => array('tinyint', 1, 'null' => 0, 'default' => '1'),
		'weight_limit' => array('varchar', 11),
		'type' => array('enum', "'DELIVERY_POINT','PICKUP_POINT',''"),
		'office_index' => array('varchar', 6),
		'latitude' => array('varchar', 255),
		'longitude' => array('varchar', 255),
		'location_type' => array('enum', "'DEFAULT','BOX','DEMAND','HOTEL',''"),
		'region' => array('varchar', 255),
		'region_code' => array('varchar', 8, 'null' => 0),
		'place' => array('varchar', 255),
		'city_name' => array('varchar', 255, 'null' => 0),
		'micro_district' => array('varchar', 255),
		'area' => array('varchar', 255),
		'street' => array('varchar', 255),
		'house' => array('varchar', 50),
		'building' => array('varchar', 50),
		'corpus' => array('varchar', 50),
		'letter' => array('varchar', 50),
		'hotel' => array('varchar', 50),
		'room' => array('varchar', 50),
		'slash' => array('varchar', 50),
		'office' => array('varchar', 50),
		'vladenie' => array('varchar', 50),
		'card_payment' => array('tinyint', 1, 'null' => 0, 'default' => '0'),
		'cash_payment' => array('tinyint', 1, 'null' => 0, 'default' => '0'),
		'options_json' => array('text', 'null' => 0),
		'schedule_monday' => array('varchar', 11),
		'schedule_tuesday' => array('varchar', 11),
		'schedule_wednesday' => array('varchar', 11),
		'schedule_thursday' => array('varchar', 11),
		'schedule_friday' => array('varchar', 11),
		'schedule_saturday' => array('varchar', 11),
		'schedule_sunday' => array('varchar', 11),
		':keys' => array(
			'PRIMARY' => 'id',
		),
	),
);
