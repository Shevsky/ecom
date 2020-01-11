import './main-screen.sass';

import React from 'react';
import { bem } from 'util/bem';
import { Tabs } from 'common/components/tabs';
import { ApiTab, DepartureTab } from 'modules/settings/components/tabs';
import { GlobalFormPool } from 'modules/settings/components/global-form-pool';

const classname = bem('main-screen');

export function MainScreen(): JSX.Element {
	return (
		<div className={classname()}>
			<div className={classname('global-form-pool')}>
				<GlobalFormPool />
			</div>

			<Tabs
				options={[
					{
						title: 'API',
						component: ApiTab
					},
					{
						title: 'Параметры отправления',
						component: DepartureTab
					}
				]}
			/>
		</div>
	);
}
