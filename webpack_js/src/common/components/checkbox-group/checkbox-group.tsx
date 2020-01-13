import './checkbox-group.sass';

import React, { ReactElement } from 'react';
import { Checkbox } from 'common/components';
import { bem } from 'util/bem';

interface ICheckboxGroupProps {
	children: Array<ReactElement<typeof Checkbox>>;
}

const classname = bem('checkbox-group');

export function CheckboxGroup(props: ICheckboxGroupProps): JSX.Element {
	return (
		<div className={classname()}>
			{props.children.map(
				(checkbox: ReactElement<typeof Checkbox>, index: number): JSX.Element => (
					<div key={index} className={classname('checkbox')}>
						{checkbox}
					</div>
				)
			)}
		</div>
	);
}
