import './paragraph.sass';

import React, { PropsWithChildren } from 'react';
import { bem } from 'util/bem';

export enum PARAGRAPH_SIZE {
	SMALL = 'small',
	MEDIUM = 'medium',
	LARGE = 'large'
}

interface IParagraphProps {
	size?: PARAGRAPH_SIZE;
	smallBottomPadding?: boolean;
	disabledBottomPadding?: boolean;
}

const classname = bem('paragraph');

export function Paragraph(props: PropsWithChildren<IParagraphProps>): JSX.Element {
	return (
		<div
			className={classname({
				size: props.size || PARAGRAPH_SIZE.MEDIUM,
				small_bottom_padding: !!props.smallBottomPadding,
				disabled_bottom_padding: !!props.disabledBottomPadding
			})}
		>
			{props.children}
		</div>
	);
}
