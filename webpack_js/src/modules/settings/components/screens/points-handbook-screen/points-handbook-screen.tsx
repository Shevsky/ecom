import React, { useContext, useEffect, useState } from 'react';
import { Container, Field, Paragraph } from 'common/components';
import { GlobalContext } from 'modules/settings/services/global-context';
import { numericDeclension } from 'util/numeric-declension';
import { formatNumeric } from 'util/format-numeric';
import { PointsHandbookSynchronizer } from './points-handbook-synchronizer';

export function PointsHandbookScreen(): JSX.Element {
	const global = useContext(GlobalContext);
	const [pointsHandbookCount, setPointsHandbookCount] = useState(
		global.pointsHandbookCount.getValue()
	);
	useEffect((): VoidFunction => {
		const subscription = global.pointsHandbookCount.subscribe(setPointsHandbookCount);

		return (): void => subscription.unsubscribe();
	}, []);

	return (
		<>
			<Container>
				<Paragraph>
					Для работы плагина требуется синхронизировать справочник пуктов выдачи Почты
					России. Это можно сделать как в автоматическом режиме, настроив планировщик CRON
					(может не поддерживаться Вашим приложением, для Shop-Script доступно начиная с
					версии 8.5), а так же выполнив обновление справочника вручную через этот
					интерфейс.
				</Paragraph>
			</Container>

			<Field name="Справочник пунктов выдачи">
				{pointsHandbookCount <= 0 ? (
					<Paragraph>Справочник пуст. Запустите синхронизацию.</Paragraph>
				) : (
					<Paragraph>
						В справочнике{' '}
						<strong>
							{formatNumeric(pointsHandbookCount)}{' '}
							{numericDeclension(pointsHandbookCount, [
								'пункт выдачи',
								'пункта выдачи',
								'пунктов выдачи'
							])}
						</strong>
					</Paragraph>
				)}

				<PointsHandbookSynchronizer />
			</Field>
		</>
	);
}
