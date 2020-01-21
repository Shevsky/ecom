import './select.sass';

import React, { ChangeEvent, HTMLProps, useCallback, Fragment } from 'react';
import ClassNames from 'classnames';
import { bem } from 'util/bem';

const classname = bem('select');

export enum SELECT_SIZE {
	SMALL = 'small',
	MEDIUM = 'medium',
	LARGE = 'large',
	AUTO = 'auto'
}

export interface ISelectProps
	extends Omit<HTMLProps<HTMLSelectElement>, 'onChange' | 'value' | 'name' | 'size'> {
	onChange(value: string, name: string): void;
	value: string;
	options: Record<string, string>;
	order?: string[];
	name?: string;
	size?: SELECT_SIZE;
	withEmpty?: boolean;
}

export function Select({
	onChange,
	size,
	options,
	order,
	withEmpty,
	...props
}: ISelectProps): JSX.Element {
	const handleChange = useCallback(
		(event: ChangeEvent<HTMLSelectElement>): void => {
			onChange(event.target.value, event.target.name);
		},
		[onChange]
	);

	return (
		<select
			{...props}
			className={ClassNames(
				classname({
					size: size || SELECT_SIZE.MEDIUM
				}),
				{ [props.className]: !!props.className }
			)}
			onChange={handleChange}
		>
			{!!withEmpty && <option value="" />}

			{!!order && order.length > 0
				? order.map(
						(key: string): JSX.Element => (
							<Fragment key={key}>
								{key in options && <option value={key}>{options[key]}</option>}
							</Fragment>
						)
				  )
				: Object.entries(options).map(
						([value, label]: [string, string]): JSX.Element => (
							<option key={value} value={value}>
								{label}
							</option>
						)
				  )}
		</select>
	);
}
