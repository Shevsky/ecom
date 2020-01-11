import './main-screen.sass';

import React, { useContext } from 'react';
import { bem } from 'util/bem';
import { Tabs } from 'common/components/tabs';
import { ApiTab, DepartureTab } from 'modules/settings/components/tabs';
import { GlobalFormPool } from 'modules/settings/components/global-form-pool';
import { ParamsContext } from 'modules/settings/services/params-context';

const classname = bem('main-screen');

export function MainScreen(): JSX.Element {
	const params = useContext(ParamsContext);

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
				id={params.key && `shipping_${params.key}_tab_main_screen`}
			/>
		</div>
	);
}
