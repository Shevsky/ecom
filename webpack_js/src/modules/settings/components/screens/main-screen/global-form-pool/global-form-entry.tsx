import React, { useContext, useEffect, useState } from 'react';
import { SettingsContext } from 'modules/settings/services/settings-context';
import { SettingStringify } from 'modules/settings/util/setting-stringify';

interface IGlobalFormEntry {
	name: keyof ISettings;
}

export function GlobalFormEntry(props: IGlobalFormEntry): JSX.Element {
	const settings = useContext(SettingsContext);

	const [value, setValue] = useState<string>(
		SettingStringify.encode(settings[props.name].getValue())
	);
	useEffect((): VoidFunction => {
		const subscription = settings[props.name].subscribe(
			// @ts-ignore
			(newValue: ISettings[keyof ISettings]) => {
				setValue(SettingStringify.encode(newValue));
			},
			null
		);

		return (): void => subscription.unsubscribe();
	}, []);

	return <textarea name={`shipping[settings][${props.name}]`} value={value} readOnly />;
}
