import './points-handbook-finder.sass';

import React, { useCallback, useContext, useEffect, useState } from 'react';
import { Button, BUTTON_TYPE, Field, Input, Paragraph, Select } from 'common/components';
import { GlobalContext } from 'modules/settings/services/global-context';
import { ParamsContext } from 'modules/settings/services/params-context';
import { bem } from 'util/bem';
import { IPointsHandbookFinderPoint } from './points-handbook-finder.type';
import { PointsHandbookFinderTable } from './points-handbook-finder.table';

interface IFindPointsResponse {
	data?: Array<IPointsHandbookFinderPoint>;
	errors?: Array<Array<string>>;
	status: 'ok' | 'fail';
}

const classname = bem('points-handbook-finder');

export function PointsHandbookFinder(): JSX.Element {
	const global = useContext(GlobalContext);
	const [regions, setRegions] = useState<TRegions>({ entities: {}, order: [] });
	useEffect((): void => {
		global.locationHandbook.getRegions('rus').then(setRegions);
	}, []);

	const [isLoading, setIsLoading] = useState(false);
	const [isFound, setIsFound] = useState(false);
	const [error, setError] = useState('');

	const [region, setRegion] = useState('');
	const [city, setCity] = useState('');

	const params = useContext(ParamsContext);

	const [points, setPoints] = useState<Array<IPointsHandbookFinderPoint>>([]);
	const findPoints = useCallback((rawRegion: string, rawCity: string): Promise<
		Array<IPointsHandbookFinderPoint>
	> => {
		return new Promise(
			(
				resolve: (nextPoints: Array<IPointsHandbookFinderPoint>) => void,
				reject: (reason: string) => void
			): void => {
				$.post(
					params.find_points_url,
					{ region: rawRegion, city: rawCity },
					(response: IFindPointsResponse): void => {
						if (!response || !response.status) {
							reject('Не удалось получить пункты выдачи');
						} else if (response.status === 'fail') {
							reject(response.errors[0][0]);
						} else if (response.status === 'ok') {
							resolve(response.data);
						}
					},
					'json'
				);
			}
		);
	}, []);
	const handleClickFind = useCallback((): void => {
		setError('');
		setIsLoading(true);

		findPoints(region, city)
			.then(
				(nextPoints: Array<IPointsHandbookFinderPoint>): void => {
					setPoints(nextPoints);
					setIsFound(true);
				}
			)
			.catch(
				(nextError: string): void => {
					setIsFound(false);
					setError(nextError);
				}
			)
			.finally((): void => setIsLoading(false));
	}, [region, city]);

	return (
		<div className={classname()}>
			<Paragraph>
				Поиск пунктов выдачи для указанного региона и города в справочнике.
			</Paragraph>

			<Field name="Регион" vertical>
				<Select
					options={regions.entities}
					order={regions.order}
					value={region}
					onChange={setRegion}
					withEmpty
				/>
			</Field>
			<Field name="Город" vertical>
				<Input value={city} onChange={setCity} />
			</Field>
			<Field vertical>
				<Button
					onClick={handleClickFind}
					type={BUTTON_TYPE.PRIMARY}
					small
					disabled={isLoading}
					loading={isLoading}
				>
					Найти пункты выдачи
				</Button>

				{!!error && <div className={classname('error')}>{error}</div>}
			</Field>

			{isFound && (
				<>
					{points.length === 0 ? (
						<Paragraph>Пунктов выдачии для этого региона/города не найдено</Paragraph>
					) : (
						<PointsHandbookFinderTable points={points} />
					)}
				</>
			)}
		</div>
	);
}
