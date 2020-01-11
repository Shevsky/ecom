import React from 'react';
import { Field } from 'common/components/field';
import { Paragraph } from 'common/components/paragraph';
import { Container } from 'common/components/container';
import { Input } from 'modules/settings/components/common';
import { ApiHelp } from 'modules/settings/components/help';
import { useWindowOpen } from 'modules/settings/util/use-window-open';
import { InlineLink } from 'common/components/inline-link/inline-link';
import { Icon } from '../../../../common/components/icon';

export function ApiTab(): JSX.Element {
	const openApiHelp = useWindowOpen(<ApiHelp />);

	return (
		<>
			<Container>
				<Paragraph>
					Работа с отправлениями по схеме электронной коммерции Почты России, а именно,
					получение списка пунктов выдачи, и расчет стоимости доставки до выбранного
					пункта выдачи, возможна только через сервис{' '}
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
				<InlineLink icon={{ name: 'info' }} onClick={openApiHelp}>
					Где взять эти данные?
				</InlineLink>
			</Field>
		</>
	);
}
