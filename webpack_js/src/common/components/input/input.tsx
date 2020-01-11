import './input.sass';

import React, { ChangeEvent, HTMLProps, useCallback } from 'react';
import { bem } from 'util/bem';

const classname = bem('input');

export interface IInputProps
	extends Omit<HTMLProps<HTMLInputElement>, 'onChange' | 'value' | 'name'> {
	onChange(value: string, name: string): void;
	value: string;
	name?: string;
}

export function Input({ onChange, ...props }: IInputProps): JSX.Element {
	const handleChange = useCallback(
		(event: ChangeEvent<HTMLInputElement>): void => {
			onChange(event.target.value, event.target.name);
		},
		[onChange]
	);

	return <input {...props} className={classname()} onChange={handleChange} />;
}
