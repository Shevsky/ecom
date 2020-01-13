import './inline-link.sass';

import React, { HTMLProps, MouseEvent, useCallback } from 'react';
import { Icon, IIconProps } from 'common/components';
import ClassNames from 'classnames';
import { bem } from 'util/bem';

interface IInlineLinkProps extends HTMLProps<HTMLAnchorElement> {
	icon?: Omit<IIconProps, 'paddedLeft' | 'paddedRight'>;
}

const classname = bem('inline-link');

export function InlineLink({
	className,
	children,
	href = '#',
	icon,
	...props
}: IInlineLinkProps): JSX.Element {
	const handleClick = useCallback(
		(event: MouseEvent<HTMLAnchorElement>): void => {
			if (href === '#') {
				event.preventDefault();
			}

			if (props.onClick) {
				props.onClick(event);
			}
		},
		[href, props.onClick]
	);

	return (
		<a
			{...props}
			href={href}
			className={ClassNames('inline-link', classname(), className)}
			onClick={handleClick}
		>
			{icon ? <Icon className={classname('icon')} {...icon} paddedRight /> : ''}

			<b>
				<i>{children}</i>
			</b>
		</a>
	);
}
