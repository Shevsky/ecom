import './header.sass';

import React, { PropsWithChildren } from 'react';
import { bem } from 'util/bem';

export enum HEADER_SIZE {
	SMALL = 'small',
	MEDIUM = 'medium',
	LARGE = 'large'
}

interface IHeaderProps {
	size?: HEADER_SIZE;
	paddedTop?: boolean;
}

const classname = bem('header');

export function Header(props: PropsWithChildren<IHeaderProps>): JSX.Element {
	return (
		<div
			className={classname({
				size: props.size || HEADER_SIZE.MEDIUM,
				padded_top: !!props.paddedTop
			})}
		>
			{props.children}
		</div>
	);
}
