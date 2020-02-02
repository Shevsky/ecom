import { SettingsModule } from 'modules/settings';

declare global {
	// tslint:disable-next-line:interface-name
	interface Window {
		shipping_ecom_settings: {
			construct: typeof SettingsModule;
			instance?: SettingsModule;
		};
	}
}

window.shipping_ecom_settings = {
	construct: SettingsModule
};
