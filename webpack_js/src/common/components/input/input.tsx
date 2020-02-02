import './input.sass';

import React, {
	ChangeEvent,
	createElement,
	HTMLProps,
	InputHTMLAttributes,
	useCallback
} from 'react';
import { bem } from 'util/bem';
import InputMask from 'react-input-mask';

const classname = bem('input');

export enum INPUT_SIZE {
	SMALL = 'small',
	MEDIUM = 'medium',
	LARGE = 'large'
}

export interface IInputProps
	extends Omit<HTMLProps<HTMLInputElement>, 'onChange' | 'value' | 'name' | 'size'> {
	value: string;
	name?: string;
	size?: INPUT_SIZE;
	mask?: string | Array<string | RegExp>;
	onChange(value: string, name: string): void;
}

export function Input({ onChange, size, mask, ...props }: IInputProps): JSX.Element {
	const handleChange = useCallback(
		(event: ChangeEvent<HTMLInputElement>): void => {
			onChange(event.target.value, event.target.name);
		},
		[onChange]
	);

	const elementProps: InputHTMLAttributes<HTMLInputElement> = {
		...props,
		className: classname({ size: size || INPUT_SIZE.MEDIUM }),
		onChange: handleChange
	};

	if (mask) {
		return createElement(InputMask, { ...elementProps, mask });
	} else {
		return createElement('input', elementProps);
	}
}
