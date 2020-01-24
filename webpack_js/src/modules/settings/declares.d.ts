declare interface ISettings {
	api_login: string;
	api_password: string;
	api_token: string;

	tracking_login: string;
	tracking_password: string;
	tracking_cache_lifetime: number;

	index_from: string;
	region_code_from: string;
	city_name_from: string;
	undefined_dimension_case: import('./enum').UNDEFINED_DIMENSION_CASE;
	dimension_type: import('./enum').DIMENSION_TYPE;
	default_length: number;
	default_height: number;
	default_width: number;
	default_weight: number;
	pass_goods_value: boolean;
	total_value_mode: import('./enum').TOTAL_VALUE_MODE;
	mail_category:
		| import('./enum').MAIL_CATEGORY.ORDINARY
		| import('./enum').MAIL_CATEGORY.WITH_COMPULSORY_PAYMENT;
	mail_type: import('./enum').MAIL_TYPE.ECOM;
	entries_type: import('./enum').ENTRIES_TYPE;
	payment_method: import('./enum').PAYMENT_METHOD.CASHLESS;
	fragile: boolean;
	inventory: boolean;
	vsd: boolean;
	notice_payment_method: import('./enum').PAYMENT_METHOD;
	with_electronic_notice: boolean;
	with_order_of_notice: boolean;
	with_simple_notice: boolean;
	sms_notice_recipient: boolean;
	with_fitting: boolean;
	functionality_checking: boolean;
	contents_checking: boolean;
	completeness_checking: boolean;
}

declare interface ICountry {
	iso3letter: string;
	name: string;
}

declare type TRegions = {
	entities: Record<string, string>;
	order: string[];
};

declare interface IParams {
	id: string;
	key: string | null;
	sync_points_url: string;
	get_regions_url: string;
	points_handbook_count: number;
	countries: ICountry[];
	settings: ISettings;
}
