import React from 'react';
import { Checkbox as UICheckbox, ICheckboxProps as UICheckboxProps } from 'common/components';
import { useSetting } from 'modules/settings/util/use-setting';

type TBooleanSettingKeys = {
	[K in keyof ISettings]: ISettings[K] extends boolean ? K : never
}[keyof ISettings];

interface ICheckboxProps extends Omit<UICheckboxProps, 'onChange' | 'checked' | 'name'> {
	name: TBooleanSettingKeys;
}

export function Checkbox({ name, ...props }: ICheckboxProps): JSX.Element {
	const [checked, setChecked] = useSetting(name);

	return <UICheckbox {...props} value="1" onChange={setChecked} checked={checked} />;
}
