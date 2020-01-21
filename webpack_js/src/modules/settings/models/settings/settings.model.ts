import { BehaviorSubject } from 'rxjs';
import { TSettingsModel } from './settings.type';

export class SettingsModel implements TSettingsModel {
	tracking_cache_lifetime: BehaviorSubject<ISettings['tracking_cache_lifetime']>;
	tracking_login: BehaviorSubject<ISettings['tracking_login']>;
	tracking_password: BehaviorSubject<ISettings['tracking_password']>;
	api_login: BehaviorSubject<ISettings['api_login']>;
	api_password: BehaviorSubject<ISettings['api_password']>;
	api_token: BehaviorSubject<ISettings['api_token']>;
	contents_checking: BehaviorSubject<ISettings['contents_checking']>;
	undefined_dimension_case: BehaviorSubject<ISettings['undefined_dimension_case']>;
	dimension_type: BehaviorSubject<ISettings['dimension_type']>;
	weight: BehaviorSubject<ISettings['weight']>;
	functionality_checking: BehaviorSubject<ISettings['functionality_checking']>;
	index_from: BehaviorSubject<ISettings['index_from']>;
	city_name_from: BehaviorSubject<ISettings['city_name_from']>;
	region_code_from: BehaviorSubject<ISettings['region_code_from']>;
	mail_category: BehaviorSubject<ISettings['mail_category']>;
	mail_type: BehaviorSubject<ISettings['mail_type']>;
	pass_goods_value: BehaviorSubject<ISettings['pass_goods_value']>;
	total_value_mode: BehaviorSubject<ISettings['total_value_mode']>;
	payment_method: BehaviorSubject<ISettings['payment_method']>;
	sms_notice_recipient: BehaviorSubject<ISettings['sms_notice_recipient']>;
	with_fitting: BehaviorSubject<ISettings['with_fitting']>;

	constructor(settings: ISettings) {
		Object.entries(settings).forEach(
			([key, value]: [keyof ISettings, ISettings[keyof ISettings]]) => {
				// @ts-ignore
				this[key] = new BehaviorSubject(value);
			}
		);
	}
}
