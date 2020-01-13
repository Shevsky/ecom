import './button.sass';

import React, { ButtonHTMLAttributes } from 'react';
import ClassNames from 'classnames';
import { bem } from 'util/bem';

export enum BUTTON_TYPE {
	DANGER = 'red',
	SUCCESS = 'green',
	PRIMARY = 'blue',
	WARNING = 'yellow',
	INFO = 'purple',
	DELETE = 'delete'
}

interface IButtonProps extends Omit<ButtonHTMLAttributes<HTMLButtonElement>, 'type'> {
	type?: BUTTON_TYPE;
	small?: boolean;
	loading?: boolean;
}

const BUTTON_TYPE_COLORS = [
	BUTTON_TYPE.DANGER,
	BUTTON_TYPE.SUCCESS,
	BUTTON_TYPE.PRIMARY,
	BUTTON_TYPE.WARNING,
	BUTTON_TYPE.INFO
];

const classname = bem('button');

export function Button({ type, small, loading, className, ...props }: IButtonProps): JSX.Element {
	return (
		<button
			{...props}
			className={ClassNames(
				'button',
				{ [type]: BUTTON_TYPE_COLORS.includes(type) },
				classname({
					size_small: !!small,
					style_loading: !!loading,
					type: !!type && !BUTTON_TYPE_COLORS.includes(type) ? type : false
				}),
				{ [className]: !!className }
			)}
			type="button"
		/>
	);
}
