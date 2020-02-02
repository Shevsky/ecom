import React from 'react';
import { Select as UISelect, ISelectProps as UISelectProps } from 'common/components';
import { useSetting } from 'modules/settings/util/use-setting';

type TStringSettingKeys = {
	[K in keyof ISettings]: ISettings[K] extends string ? K : never
}[keyof ISettings];

interface ISelectProps<K extends TStringSettingKeys>
	extends Omit<UISelectProps, 'onChange' | 'value' | 'name'> {
	name: K;
	// @ts-ignore
	options: Partial<Record<ISettings[K] extends infer N ? N : string, string>>;
}

export function Select<K extends TStringSettingKeys>({
	name,
	...props
}: ISelectProps<K>): JSX.Element {
	// tslint:disable-next-line:no-any
	const [value, setValue] = useSetting<any>(name);

	return <UISelect {...props} onChange={setValue} value={value} />;
}
