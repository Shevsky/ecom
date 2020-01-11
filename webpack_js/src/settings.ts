import { SettingsModule } from 'modules/settings';

declare global {
	interface Window {
		shipping_ecom: {
			construct: typeof SettingsModule;
			instance?: SettingsModule;
		};
	}
}

window.shipping_ecom = {
	construct: SettingsModule
};
