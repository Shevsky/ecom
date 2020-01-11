import { BehaviorSubject } from 'rxjs';

export type TSettingsModel = { [K in keyof ISettings]: BehaviorSubject<ISettings[K]> };
