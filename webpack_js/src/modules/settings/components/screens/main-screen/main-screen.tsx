import './main-screen.sass';

import React, { useContext, useEffect, useState } from 'react';
import { bem } from 'util/bem';
import { Tabs } from 'common/components';
import {
	ApiScreen,
	DepartureScreen,
	PointsHandbookScreen
} from 'modules/settings/components/screens';
import { ParamsContext } from 'modules/settings/services/params-context';
import { GlobalParamsContext } from 'modules/settings/services/global-params-context';
import { GlobalFormPool } from './global-form-pool';

const classname = bem('main-screen');

export function MainScreen(): JSX.Element {
	const params = useContext(ParamsContext);
	const globalParams = useContext(GlobalParamsContext);
	const [isNavigationDisabled, setIsNavigationDisabled] = useState(
		globalParams.isNavigationDisabled.getValue()
	);
	useEffect((): VoidFunction => {
		const subscription = globalParams.isNavigationDisabled.subscribe(setIsNavigationDisabled);

		return (): void => subscription.unsubscribe();
	}, []);

	return (
		<div className={classname()}>
			<div className={classname('global-form-pool')}>
				<GlobalFormPool />
			</div>

			<Tabs
				options={[
					{
						title: 'API',
						component: ApiScreen
					},
					{
						title: 'Параметры отправления',
						component: DepartureScreen
					},
					{
						title: 'Справочник ПВЗ',
						component: PointsHandbookScreen
					}
				]}
				id={params.key && `shipping_${params.key}_tab_main_screen`}
				disabled={isNavigationDisabled}
			/>
		</div>
	);
}
