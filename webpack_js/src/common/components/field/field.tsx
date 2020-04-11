import './field.sass';

import React, { PropsWithChildren, ReactNode } from 'react';
import { bem } from 'util/bem';
import { Container } from '../container';

const classname = bem('field');

interface IFieldProps {
	name?: string;
	description?: ReactNode;
	paddedTop?: boolean;
	paddedBottom?: boolean;
	vertical?: boolean;
}

export function Field(props: PropsWithChildren<IFieldProps>): JSX.Element {
	return (
		<div
			className={classname({
				padded_top: !!props.paddedTop,
				padded_bottom: !!props.paddedBottom,
				vertical: !!props.vertical
			})}
		>
			<div className={classname('name')}>{!!props.name && props.name}</div>
			<div className={classname('value')}>
				{Array.isArray(props.children)
					? props.children.map(
							(children: ReactNode, index: number): JSX.Element => (
								<div key={index} className={classname('value-item')}>
									{children}
								</div>
							)
					  )
					: props.children}

				{props.description !== undefined && (
					<Container>
						<div className={classname('description')}>{props.description}</div>
					</Container>
				)}
			</div>
		</div>
	);
}
