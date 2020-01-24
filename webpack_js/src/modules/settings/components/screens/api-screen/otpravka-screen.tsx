import React from 'react';
import { Container, Field, Paragraph, InlineLink } from 'common/components';
import { Input } from 'modules/settings/components/common';
import { OtpravkaApiHelp } from 'modules/settings/components/help';
import { useWindowOpen } from 'util/use-window-open';

export function OtpravkaScreen(): JSX.Element {
	const openHelp = useWindowOpen(<OtpravkaApiHelp />);

	return (
		<>
			<Container>
				<Paragraph>
					Работа с отправлениями по схеме электронной коммерции Почты России, а именно,
					получение списка пунктов выдачи, и расчет стоимости доставки до выбранного
					пункта выдачи, осуществляется через сервис{' '}
					<a href="https://otpravka.pochta.ru/" target="_blank">
						Отправка Почта России
					</a>
					, поэтому заполнение этих полей обязательно для работы плагина.
				</Paragraph>
			</Container>

			<Field name="Логин">
				<Input name="api_login" />
			</Field>
			<Field name="Пароль">
				<Input name="api_password" />
			</Field>
			<Field name="Токен авторизации">
				<Input name="api_token" />
			</Field>

			<Field paddedTop>
				<InlineLink icon={{ name: 'info' }} onClick={openHelp}>
					Где взять эти данные?
				</InlineLink>
			</Field>
		</>
	);
}
