import './tabs.sass';

import React, {
	ComponentType,
	ReactNode,
	MouseEvent,
	useCallback,
	useState,
	createElement
} from 'react';
import { bem } from 'util/bem';
import Cookies from 'js-cookie';

const classname = bem('tabs');

interface ITabsOption {
	title: ReactNode;
	component: ComponentType;
}

interface ITabsProps {
	options: ITabsOption[];
	id?: string | null;
	disabled?: boolean;
}

export function Tabs(props: ITabsProps) {
	let defaultSelectedId = props.id ? +Cookies.get(props.id) : undefined;
	if (isNaN(defaultSelectedId) || defaultSelectedId === undefined) {
		defaultSelectedId = 0;
	}

	const [selectedId, setSelectedId] = useState<number>(defaultSelectedId);
	const selectedComponent =
		selectedId in props.options ? props.options[selectedId].component : null;

	const handleClick = useCallback(
		(event: MouseEvent<HTMLAnchorElement> & { target: { dataset: { id: string } } }): void => {
			event.preventDefault();

			if (props.disabled) {
				return;
			}

			const {
				target: {
					dataset: { id }
				}
			} = event;

			setSelectedId(+id);
			if (props.id) {
				Cookies.set(props.id, id);
			}
		},
		[props.id, props.disabled]
	);

	return (
		<div className={classname({ disabled: !!props.disabled })}>
			<ul className="tabs">
				{props.options.map(
					(option: ITabsOption, id: number): JSX.Element => {
						const isSelected = id === selectedId;

						return (
							<li className={isSelected ? ' selected' : ''} key={id}>
								<a
									className={classname('option')}
									href="#"
									onClick={handleClick}
									data-id={id.toString()}
								>
									{option.title}
								</a>
							</li>
						);
					}
				)}
			</ul>

			<div className="tab-content">
				<div className={classname('content')}>
					{selectedComponent ? createElement(selectedComponent) : ''}
				</div>
			</div>
		</div>
	);
}
