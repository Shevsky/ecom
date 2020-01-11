import './select.sass';

import React, { ChangeEvent, HTMLProps, useCallback } from 'react';
import { bem } from 'util/bem';

const classname = bem('select');

export enum SELECT_SIZE {
	SMALL = 'small',
	MEDIUM = 'medium',
	LARGE = 'large'
}

export interface ISelectProps
	extends Omit<HTMLProps<HTMLSelectElement>, 'onChange' | 'value' | 'name' | 'size'> {
	onChange(value: string, name: string): void;
	value: string;
	options: Record<string, string>;
	name?: string;
	size?: SELECT_SIZE;
}

export function Select({ onChange, size, options, ...props }: ISelectProps): JSX.Element {
	const handleChange = useCallback(
		(event: ChangeEvent<HTMLSelectElement>): void => {
			onChange(event.target.value, event.target.name);
		},
		[onChange]
	);

	return (
		<select
			{...props}
			className={classname({ size: size || SELECT_SIZE.MEDIUM })}
			onChange={handleChange}
		>
			{Object.entries(options).map(
				([value, label]: [string, string]): JSX.Element => (
					<option key={value} value={value}>
						{label}
					</option>
				)
			)}
		</select>
	);
}
