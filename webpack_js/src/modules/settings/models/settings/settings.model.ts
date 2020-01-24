import { BehaviorSubject } from 'rxjs';
import { TSettingsModel } from './settings.type';

export class SettingsModel implements TSettingsModel {
	api_login: BehaviorSubject<ISettings['api_login']>;
	api_password: BehaviorSubject<ISettings['api_password']>;
	api_token: BehaviorSubject<ISettings['api_token']>;
	is_calculate_thru_tariff: BehaviorSubject<ISettings['is_calculate_thru_tariff']>;
	tariff_agreement_number: BehaviorSubject<ISettings['tariff_agreement_number']>;
	city_name_from: BehaviorSubject<ISettings['city_name_from']>;
	completeness_checking: BehaviorSubject<ISettings['completeness_checking']>;
	contents_checking: BehaviorSubject<ISettings['contents_checking']>;
	dimension_type: BehaviorSubject<ISettings['dimension_type']>;
	entries_type: BehaviorSubject<ISettings['entries_type']>;
	fragile: BehaviorSubject<ISettings['fragile']>;
	functionality_checking: BehaviorSubject<ISettings['functionality_checking']>;
	index_from: BehaviorSubject<ISettings['index_from']>;
	inventory: BehaviorSubject<ISettings['inventory']>;
	mail_category: BehaviorSubject<ISettings['mail_category']>;
	mail_type: BehaviorSubject<ISettings['mail_type']>;
	notice_payment_method: BehaviorSubject<ISettings['notice_payment_method']>;
	pass_goods_value: BehaviorSubject<ISettings['pass_goods_value']>;
	payment_method: BehaviorSubject<ISettings['payment_method']>;
	region_code_from: BehaviorSubject<ISettings['region_code_from']>;
	sms_notice_recipient: BehaviorSubject<ISettings['sms_notice_recipient']>;
	total_value_mode: BehaviorSubject<ISettings['total_value_mode']>;
	tracking_cache_lifetime: BehaviorSubject<ISettings['tracking_cache_lifetime']>;
	tracking_login: BehaviorSubject<ISettings['tracking_login']>;
	tracking_password: BehaviorSubject<ISettings['tracking_password']>;
	undefined_dimension_case: BehaviorSubject<ISettings['undefined_dimension_case']>;
	vsd: BehaviorSubject<ISettings['vsd']>;
	default_height: BehaviorSubject<ISettings['default_height']>;
	default_length: BehaviorSubject<ISettings['default_length']>;
	default_width: BehaviorSubject<ISettings['default_width']>;
	default_weight: BehaviorSubject<ISettings['default_weight']>;
	with_electronic_notice: BehaviorSubject<ISettings['with_electronic_notice']>;
	with_fitting: BehaviorSubject<ISettings['with_fitting']>;
	with_order_of_notice: BehaviorSubject<ISettings['with_order_of_notice']>;
	with_simple_notice: BehaviorSubject<ISettings['with_simple_notice']>;

	constructor(settings: ISettings) {
		Object.entries(settings).forEach(
			([key, value]: [keyof ISettings, ISettings[keyof ISettings]]) => {
				// @ts-ignore
				this[key] = new BehaviorSubject(value);
			}
		);
	}
}
