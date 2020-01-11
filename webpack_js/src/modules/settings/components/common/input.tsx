import React, { useCallback, useContext, useEffect, useState } from 'react';
import { Input as UIInput, IInputProps as UIInputProps } from 'common/components';
import { SettingsContext } from 'modules/settings/services/settings-context';
import { SettingStringify } from 'modules/settings/util/setting-stringify';

interface IInputProps extends Omit<UIInputProps, 'onChange' | 'value' | 'name'> {
	name: keyof ISettings;
}

export function Input({ name, ...props }: IInputProps): JSX.Element {
	const settings = useContext(SettingsContext);

	const [value, setValue] = useState<string>(SettingStringify.encode(settings[name].getValue()));
	useEffect((): VoidFunction => {
		// @ts-ignore
		const subscription = settings[name].subscribe((newValue: ISettings[keyof ISettings]) => {
			setValue(SettingStringify.encode(newValue));
		}, null);

		return () => subscription.unsubscribe();
	}, []);
	const handleChange = useCallback((newValue: string) => {
		// @ts-ignore
		settings[name].next(SettingStringify.decode(newValue, value));
	}, []);

	return <UIInput {...props} onChange={handleChange} value={value} />;
}
