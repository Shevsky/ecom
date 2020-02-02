import './checkbox.sass';

import React, { ChangeEvent, HTMLProps, ReactNode, useCallback } from 'react';
import { bem } from 'util/bem';

const classname = bem('checkbox');

export interface ICheckboxProps
	extends Omit<HTMLProps<HTMLInputElement>, 'onChange' | 'checked' | 'name' | 'label'> {
	checked: boolean;
	name?: string;
	label?: ReactNode;
	onChange(checked: boolean, name: string): void;
}

export function Checkbox({ onChange, ...props }: ICheckboxProps): JSX.Element {
	const handleChange = useCallback(
		(event: ChangeEvent<HTMLInputElement>): void => {
			onChange(event.target.checked, event.target.name);
		},
		[onChange]
	);

	return (
		<div className={classname()}>
			<label className={classname('label')}>
				<input
					{...props}
					type="checkbox"
					className={classname('input')}
					onChange={handleChange}
				/>

				{props.label && <div className={classname('label-content')}>{props.label}</div>}
			</label>
		</div>
	);
}
