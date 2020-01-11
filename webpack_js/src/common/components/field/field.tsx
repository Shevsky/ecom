import './field.sass';

import React, { PropsWithChildren, ReactNode } from 'react';
import { bem } from 'util/bem';

const classname = bem('field');

interface IFieldProps {
	name: string;
	description?: ReactNode;
}

export function Field(props: PropsWithChildren<IFieldProps>): JSX.Element {
	return (
		<div className={classname()}>
			<div className={classname('name')}>{props.name}</div>
			<div className={classname('value')}>
				{props.children}

				{props.description !== undefined && (
					<div className={classname('description')}>{props.description}</div>
				)}
			</div>
		</div>
	);
}
