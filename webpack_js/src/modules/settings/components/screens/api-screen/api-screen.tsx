import React from 'react';
import { OtpravkaScreen } from './otpravka-screen';
import { TariffScreen } from './tariff-screen';
import { TrackingScreen } from './tracking-screen';
import { Header } from 'common/components';

export function ApiScreen(): JSX.Element {
	return (
		<>
			<Header>API сервиса Отправка Почта России</Header>
			<OtpravkaScreen />
			<Header paddedTop>Расчет стоимости доставки</Header>
			<TariffScreen />
			<Header paddedTop>API для отслеживания через Трекинг Почты России</Header>
			<TrackingScreen />
		</>
	);
}
