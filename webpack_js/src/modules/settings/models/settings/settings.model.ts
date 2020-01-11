import { BehaviorSubject } from 'rxjs';
import { TSettingsModel } from './settings.type';

export class SettingsModel implements TSettingsModel {
	api_login: BehaviorSubject<ISettings['api_login']>;
	api_password: BehaviorSubject<ISettings['api_password']>;
	api_token: BehaviorSubject<ISettings['api_token']>;
	contents_checking: BehaviorSubject<ISettings['contents_checking']>;
	dimension_type: BehaviorSubject<ISettings['dimension_type']>;
	functionality_checking: BehaviorSubject<ISettings['functionality_checking']>;
	index_from: BehaviorSubject<ISettings['index_from']>;
	mail_category: BehaviorSubject<ISettings['mail_category']>;
	mail_type: BehaviorSubject<ISettings['mail_type']>;
	pass_goods_value: BehaviorSubject<ISettings['pass_goods_value']>;
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
