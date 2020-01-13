import './list.sass';

import React, { ReactNode } from 'react';
import { bem } from 'util/bem';

interface IListProps {
	children: ReactNode[];
}

const classname = bem('list');

export function List(props: IListProps): JSX.Element {
	return (
		<div className={classname()}>
			{props.children.map(
				(item: ReactNode, index: number): JSX.Element => (
					<div key={index} className={classname('item')}>
						{item}
					</div>
				)
			)}
		</div>
	);
}
