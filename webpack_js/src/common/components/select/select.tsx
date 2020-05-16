import './select.sass';

import React, { ChangeEvent, Fragment, HTMLProps, ReactNode, useCallback } from 'react';
import ClassNames from 'classnames';
import { bem } from 'util/bem';
import { Paragraph, PARAGRAPH_SIZE } from '../paragraph';
import { Container } from '../container';

const classname = bem('select');

export enum SELECT_SIZE {
	SMALL = 'small',
	MEDIUM = 'medium',
	LARGE = 'large',
	AUTO = 'auto'
}

export interface ISelectProps
	extends Omit<HTMLProps<HTMLSelectElement>, 'onChange' | 'value' | 'name' | 'size'> {
	value: string;
	options: Record<string, string>;
	details?: Record<string, ReactNode>;
	order?: Array<string>;
	name?: string;
	explanation?: string;
	size?: SELECT_SIZE;
	withEmpty?: boolean;
	onChange(value: string, name: string): void;
}

export function Select({
	onChange,
	explanation,
	details,
	size,
	options,
	order,
	withEmpty,
	...props
}: ISelectProps): JSX.Element {
	const handleChange = useCallback(
		(event: ChangeEvent<HTMLSelectElement>): void => {
			onChange(event.target.value, event.target.name);
		},
		[onChange]
	);

	return (
		<>
			<select
				{...props}
				className={ClassNames(
					classname({
						size: size || SELECT_SIZE.MEDIUM
					}),
					{ [props.className]: !!props.className }
				)}
				onChange={handleChange}
			>
				{!!withEmpty && <option value="" />}

				{!!order && order.length > 0
					? order.map(
							(key: string): JSX.Element => (
								<Fragment key={key}>
									{key in options && <option value={key}>{options[key]}</option>}
								</Fragment>
							)
					  )
					: Object.entries(options).map(
							([value, label]: [string, string]): JSX.Element => (
								<option key={value} value={value}>
									{label}
								</option>
							)
					  )}
			</select>

			{!!explanation && (
				<div className={classname('explanation')}>
					<Container>
						<Paragraph disabledBottomPadding>{explanation}</Paragraph>
					</Container>
				</div>
			)}

			{!!details && (
				<div className={classname('details')}>
					<Container>
						{Object.entries(details).map(
							([value, detail]: [string, string]): JSX.Element => (
								<div
									key={value}
									className={classname('details-item', {
										selected: value === props.value
									})}
								>
									<Paragraph size={PARAGRAPH_SIZE.SMALL} disabledBottomPadding>
										{detail}
									</Paragraph>
								</div>
							)
						)}
					</Container>
				</div>
			)}
		</>
	);
}
