import './icon.sass';

import React from 'react';
import ClassNames from 'classnames';
import { bem } from 'util/bem';

const classname = bem('icon');

export enum ICON_SIZE {
	MEDIUM = 16,
	SMALL = 10
}

export interface IIconProps {
	className?: string;
	name: string;
	small?: boolean;
	paddedLeft?: boolean;
	paddedRight?: boolean;
	size?: ICON_SIZE;
}

export function Icon(props: IIconProps): JSX.Element {
	return (
		<i
			className={ClassNames(
				`icon${props.size || ICON_SIZE.MEDIUM}`,
				props.name,
				{ s: props.small },
				classname({ padded_left: !!props.paddedLeft, padded_right: !!props.paddedRight }),
				{ [props.className]: !!props.className }
			)}
		/>
	);
}
