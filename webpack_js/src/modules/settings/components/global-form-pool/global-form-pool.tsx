import React, { useContext } from 'react';
import { SettingsContext } from 'modules/settings/services/settings-context';
import { GlobalFormEntry } from './global-form-entry';

export function GlobalFormPool(): JSX.Element {
	const settings = useContext(SettingsContext);

	return (
		<>
			{Object.keys(settings).map(
				(name: keyof ISettings): JSX.Element => (
					<GlobalFormEntry key={name} name={name} />
				)
			)}
		</>
	);
}
