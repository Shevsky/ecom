import React, { useCallback, useContext, useEffect, useState } from 'react';
import {
	Checkbox as UICheckbox,
	ICheckboxProps as UICheckboxProps
} from 'common/components/checkbox';
import { SettingsContext } from 'modules/settings/services/settings-context';

type TBooleanSettingKeys = {
	[K in keyof ISettings]: ISettings[K] extends boolean ? K : never
}[keyof ISettings];

interface ICheckboxProps extends Omit<UICheckboxProps, 'onChange' | 'checked' | 'name'> {
	name: TBooleanSettingKeys;
}

export function Checkbox({ name, ...props }: ICheckboxProps): JSX.Element {
	const settings = useContext(SettingsContext);

	const [checked, setChecked] = useState<boolean>(settings[name].getValue());
	useEffect((): VoidFunction => {
		const subscription = settings[name].subscribe(setChecked);

		return () => subscription.unsubscribe();
	}, []);
	const handleChange = useCallback((newChecked: boolean) => {
		settings[name].next(newChecked);
	}, []);

	return <UICheckbox {...props} value="1" onChange={handleChange} checked={checked} />;
}
