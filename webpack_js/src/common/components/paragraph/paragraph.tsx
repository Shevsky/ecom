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
}

const classname = bem('paragraph');

export function Paragraph(props: PropsWithChildren<IParagraphProps>): JSX.Element {
	return (
		<div className={classname({ size: props.size || PARAGRAPH_SIZE.MEDIUM })}>
			{props.children}
		</div>
	);
}
