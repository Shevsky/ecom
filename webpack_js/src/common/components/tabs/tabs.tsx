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

const classname = bem('tabs');

interface ITabsOption {
	title: ReactNode;
	component: ComponentType;
}

interface ITabsProps {
	options: ITabsOption[];
}

export function Tabs(props: ITabsProps) {
	const [selectedId, setSelectedId] = useState<number>(0);
	const selectedComponent =
		selectedId in props.options ? props.options[selectedId].component : null;

	const handleClick = useCallback(
		(event: MouseEvent<HTMLAnchorElement> & { target: { dataset: { id: string } } }): void => {
			const {
				target: {
					dataset: { id }
				}
			} = event;

			setSelectedId(+id);

			event.preventDefault();
		},
		[]
	);

	return (
		<div className={classname()}>
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
