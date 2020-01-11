import { useCallback, useContext, useEffect, useState } from 'react';
import { SettingsContext } from 'modules/settings/services/settings-context';

export function useSetting<K extends keyof ISettings>(
	name: K
): [
	ISettings[K] extends infer N ? N : never,
	(newValue: ISettings[K] extends infer N ? N : never) => void
] {
	type TValue = ISettings[K] extends infer N ? N : never;

	const settings = useContext(SettingsContext);

	// @ts-ignore
	const [value, dispatchValue] = useState<TValue>(settings[name].getValue());
	useEffect((): VoidFunction => {
		// @ts-ignore
		const subscription = settings[name].subscribe(dispatchValue);

		return () => subscription.unsubscribe();
	}, []);

	const setValue = useCallback((newValue: TValue): void => {
		// @ts-ignore
		settings[name].next(newValue);
	}, []);

	return [value, setValue];
}
