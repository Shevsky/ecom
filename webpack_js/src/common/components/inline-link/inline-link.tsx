import './inline-link.sass';

import React, { HTMLProps } from 'react';
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
	href = 'javascript: void 0;',
	icon,
	...props
}: IInlineLinkProps): JSX.Element {
	return (
		<a {...props} className={ClassNames('inline-link', classname(), className)}>
			{icon ? <Icon className={classname('icon')} {...icon} paddedRight /> : ''}

			<b>
				<i>{children}</i>
			</b>
		</a>
	);
}
