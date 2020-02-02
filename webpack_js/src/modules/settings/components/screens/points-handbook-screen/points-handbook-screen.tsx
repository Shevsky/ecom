import React, { useCallback, useContext, useEffect, useState } from 'react';
import moment from 'moment';
import 'moment/locale/ru';
import { Container, Field, Paragraph, Icon, InlineLink } from 'common/components';
import { GlobalContext } from 'modules/settings/services/global-context';
import { ParamsContext } from 'modules/settings/services/params-context';
import { numericDeclension } from 'util/numeric-declension';
import { formatNumeric } from 'util/format-numeric';
import { PointsHandbookSynchronizer } from './points-handbook-synchronizer';

moment.locale('ru');

interface ISyncData {
	time?: string;
	successTime?: string;
	failureTime?: string;
}

export function PointsHandbookScreen(): JSX.Element {
	const global = useContext(GlobalContext);
	const [pointsHandbookCount, setPointsHandbookCount] = useState(
		global.pointsHandbookCount.getValue()
	);
	useEffect((): VoidFunction => {
		const subscription = global.pointsHandbookCount.subscribe(setPointsHandbookCount);

		return (): void => subscription.unsubscribe();
	}, []);

	const params = useContext(ParamsContext);
	const syncData: ISyncData = {
		time: params.sync_data.time
			? moment.unix(params.sync_data.time).format('LL в LT')
			: undefined,
		successTime: params.sync_data.success_time
			? moment.unix(params.sync_data.success_time).format('LL')
			: undefined,
		failureTime: params.sync_data.failure_time
			? moment.unix(params.sync_data.failure_time).format('LL')
			: undefined
	};

	const hasAutoSyncHelpLink = !!document.getElementById('cron-message-link');
	const handleClickAutoSyncHelp = useCallback(
		(): void =>
			document.getElementById('cron-message-link') &&
			document.getElementById('cron-message-link').click(),
		[]
	);

	return (
		<>
			<Container>
				<Paragraph>
					Для работы плагина требуется синхронизировать справочник пуктов выдачи Почты
					России. Это можно сделать как в автоматическом режиме, настроив планировщик CRON
					(может не поддерживаться вашим приложением, для Shop-Script доступно начиная с
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

			<Field name="Автообновление справочника">
				<Container>
					<Paragraph>
						{!!params.is_auto_sync_available ? (
							<>
								<Icon name="yes" verticalAlignMiddle /> Автообновление справочника
								выполняется.
								{!!syncData.time && ` Последнее обновление ${syncData.time}`}
							</>
						) : (
							<>
								<Icon name="no" verticalAlignMiddle /> Автообновление справочника не
								выполняется.
								{hasAutoSyncHelpLink && (
									<>
										{' '}
										<InlineLink onClick={handleClickAutoSyncHelp}>
											Как настроить автообновление?
										</InlineLink>
									</>
								)}
							</>
						)}
					</Paragraph>
				</Container>
			</Field>
		</>
	);
}
