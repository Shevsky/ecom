import './inline-code.sass';

import React, { MouseEvent, PropsWithChildren, useCallback } from 'react';
import ClassNames from 'classnames';
import { bem } from 'util/bem';

const classname = bem('inline-code');

interface IInlineCodeProps {
	className?: string;
}

export function InlineCode(props: PropsWithChildren<IInlineCodeProps>): JSX.Element {
	const handleClick = useCallback((event: MouseEvent): void => {
		if (window.getSelection) {
			window.getSelection().selectAllChildren(event.currentTarget);
		}
	}, []);

	return (
		<span
			className={ClassNames(classname(), { [props.className]: !!props.className })}
			onClick={handleClick}
		>
			{props.children}
		</span>
	);
}
