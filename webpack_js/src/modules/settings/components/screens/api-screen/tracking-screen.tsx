import React from 'react';
import { Container, Field, InlineLink, INPUT_SIZE, Paragraph } from 'common/components';
import { Input } from 'modules/settings/components/common';

export function TrackingScreen(): JSX.Element {
	return (
		<>
			<Container>
				<Paragraph smallBottomPadding>
					Отслеживание отправлений осуществляется через сервис{' '}
					<a href="https://tracking.pochta.ru/" target="_blank">
						Трекинг Почта России
					</a>
					.
				</Paragraph>
				<Paragraph>
					<InlineLink
						icon={{ name: 'info' }}
						href="https://tracking.pochta.ru/support/faq/how_to_get_access"
						target="_blank"
					>
						Как получить доступ к трекингу
					</InlineLink>
				</Paragraph>
			</Container>
			<Field name="Логин">
				<Input name="tracking_login" />
			</Field>
			<Field name="Пароль">
				<Input name="tracking_password" />
			</Field>
			<Field
				name="Время жизни кеша"
				description="Как часто должны обновляться данные в трекинге при их запросе"
			>
				<Input name="tracking_cache_lifetime" type="tel" size={INPUT_SIZE.SMALL} /> сек.
			</Field>
		</>
	);
}
