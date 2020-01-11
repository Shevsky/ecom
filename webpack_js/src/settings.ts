import { SettingsModule } from 'modules/settings';

declare global {
	interface Window {
		shipping_ecom_public_path: string;
		shipping_ecom_settings: {
			construct: typeof SettingsModule;
			instance?: SettingsModule;
		};
	}
}

window.shipping_ecom_settings = {
	construct: SettingsModule
};
