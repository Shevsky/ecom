import './points-handbook-synchronizer.sass';

import React, { useCallback, useContext, useEffect, useMemo, useState } from 'react';
import { Subject } from 'rxjs';
import { takeUntil } from 'rxjs/operators';
import { Button, BUTTON_TYPE, Icon } from 'common/components';
import { ParamsContext } from 'modules/settings/services/params-context';
import { useSetting } from 'modules/settings/util/use-setting';
import { LongActionRequest } from 'util/long-action-request';
import { GlobalParamsContext } from 'modules/settings/services/global-params-context';
import { bem } from 'util/bem';

enum SYNC_POINTS_STEP {
	WAITING = 'waiting',
	INIT = 'init',
	SYNC = 'sync',
	FINISH = 'finish',
	ERROR = 'error'
}

const PROGRESSBAR_STEP_TEXT: Partial<Record<SYNC_POINTS_STEP, string>> = {
	[SYNC_POINTS_STEP.INIT]: 'Запрашиваем список пунктов выдачи с сервера Почты России...',
	[SYNC_POINTS_STEP.SYNC]: 'Сохраняем пункты выдачи в справочник...',
	[SYNC_POINTS_STEP.FINISH]: 'Справочник успешно обновлен!',
	[SYNC_POINTS_STEP.ERROR]: 'Не удалось произвести обновление справочника'
};

const PROGRESSBAR_STEP_ICON: Partial<Record<SYNC_POINTS_STEP, string>> = {
	[SYNC_POINTS_STEP.INIT]: 'loading',
	[SYNC_POINTS_STEP.SYNC]: 'loading',
	[SYNC_POINTS_STEP.FINISH]: 'yes',
	[SYNC_POINTS_STEP.ERROR]: 'no'
};

interface ISyncPointsData {
	points_count?: number;
	offset?: number;
}

const classname = bem('points-handbook-synchronizer');

export function PointsHandbookSynchronizer(): JSX.Element {
	const params = useContext(ParamsContext);
	const { pointsHandbookCount, isNavigationDisabled } = useContext(GlobalParamsContext);

	const [login] = useSetting('api_login');
	const [password] = useSetting('api_password');
	const [token] = useSetting('api_token');

	const unsubscribe$ = useMemo((): Subject<void> => new Subject(), []);
	useEffect((): VoidFunction => {
		return (): void => {
			unsubscribe$.next();
			unsubscribe$.complete();
		};
	}, []);

	const [step, setStep] = useState<SYNC_POINTS_STEP>(SYNC_POINTS_STEP.WAITING);
	const [error, setError] = useState<string>('');
	const [pointsCount, setPointsCount] = useState<number>(-1);
	const [offset, setOffset] = useState<number>(-1);

	const isUnavailable = !login || !password || !token;
	const isRunning = [SYNC_POINTS_STEP.INIT, SYNC_POINTS_STEP.SYNC].includes(step);
	const isDone = step === SYNC_POINTS_STEP.FINISH;

	const request = useMemo(
		(): LongActionRequest<ISyncPointsData, ISyncPointsData> =>
			new LongActionRequest<ISyncPointsData, ISyncPointsData>(params.sync_points_url, {
				login,
				password,
				token
			}),
		[params, login, password, token]
	);

	const updateState = useCallback((data: ISyncPointsData): void => {
		let rawOffset = data.offset;
		if (data.points_count && rawOffset && rawOffset > data.points_count) {
			rawOffset = data.points_count;
		}

		if (rawOffset) {
			setOffset(rawOffset);
		}
		pointsHandbookCount.next(rawOffset);

		if (data.points_count) {
			setPointsCount(data.points_count);
		}
	}, []);

	useEffect((): void => {
		request.error$.pipe(takeUntil(unsubscribe$)).subscribe(
			(rawError: string): void => {
				setError(rawError);
				setStep(SYNC_POINTS_STEP.ERROR);
			}
		);

		request.terminateTap$.pipe(takeUntil(unsubscribe$)).subscribe(
			(): void => {
				setError('');
				setStep(SYNC_POINTS_STEP.ERROR);
			}
		);

		request.finish$.pipe(takeUntil(unsubscribe$)).subscribe(
			(data: ISyncPointsData): void => {
				updateState(data);
				setStep(SYNC_POINTS_STEP.FINISH);
			}
		);

		request.process$.pipe(takeUntil(unsubscribe$)).subscribe(
			(data: ISyncPointsData): void => {
				updateState(data);
				setStep(SYNC_POINTS_STEP.SYNC);
			}
		);
	}, [request]);

	const handleClickSync = useCallback((): void => {
		if (isRunning || isUnavailable) {
			return;
		}

		isNavigationDisabled.next(true);

		setStep(SYNC_POINTS_STEP.INIT);
		pointsHandbookCount.next(0);

		request.run();
	}, [request]);

	const handleClickCancel = useCallback((): void => {
		request.cancel();
	}, [request]);

	const progressPercents = useMemo((): number => {
		if ([SYNC_POINTS_STEP.ERROR, SYNC_POINTS_STEP.FINISH].includes(step)) {
			return 100;
		}

		if (pointsCount <= 0 || offset <= 0) {
			return 0;
		}

		return (offset / pointsCount) * 100;
	}, [pointsCount, offset, step]);

	return (
		<div className={classname()}>
			<Button
				onClick={handleClickSync}
				type={BUTTON_TYPE.PRIMARY}
				disabled={isRunning || isDone || isUnavailable}
				loading={isRunning}
			>
				Запустить синхронизацию справочника
			</Button>

			{isRunning && (
				<div className={classname('cancel-box')}>
					<Button onClick={handleClickCancel} type={BUTTON_TYPE.DELETE}>
						Отменить синхронизацию
					</Button>
				</div>
			)}

			{isUnavailable && (
				<div className={classname('unavailable-box')}>
					Недоступно. Укажите параметры API сервиса Отправка Почта России
				</div>
			)}

			{step !== SYNC_POINTS_STEP.WAITING && (
				<div className={classname('progressbar')}>
					<div className={classname('progressbar-step', { step })}>
						{step in PROGRESSBAR_STEP_TEXT
							? PROGRESSBAR_STEP_TEXT[step]
							: 'Инициализация...'}

						{step === SYNC_POINTS_STEP.SYNC && (offset > 0 && pointsCount > 0) && (
							<strong>
								{' '}
								({offset} из {pointsCount})
							</strong>
						)}

						{step in PROGRESSBAR_STEP_ICON && (
							<Icon name={PROGRESSBAR_STEP_ICON[step]} paddedLeft />
						)}
					</div>
					<div className={classname('progressbar-content')}>
						<div className="progressbar blue">
							<div className="progressbar-outer">
								<div
									className="progressbar-inner"
									id="my-custom-progressbar-id"
									style={{ width: `${progressPercents.toString()}%` }}
								/>
							</div>
						</div>
					</div>
				</div>
			)}
		</div>
	);
}
