import React from 'react';
import ApiTokenImageUrl from './jsx-images/api-token.png';

export function ApiHelp(): JSX.Element {
	return (
		<div>
			<p>
				Для работы плагина расчета стоимости доставки через онлайн-сервис{' '}
				<b>Почта России "Отправка"</b> необходимо настроить доступ к{' '}
				<a href="https://otpravka.pochta.ru/" target="_blank">
					сервису "Отправка"
				</a>
				.
			</p>
			<ul>
				<li>
					В настройках доставки в полях "Логин" и "Пароль" укажите логин и пароль от{' '}
					<a href="https://otpravka.pochta.ru/" target="_blank">
						сервиса "Отправка"
					</a>
					.
				</li>
				<li>
					Авторизовавшись в сервисе "Отправка", зайдите в{' '}
					<a href="https://otpravka.pochta.ru/settings#/api-settings" target="_blank">
						настройки API
					</a>{' '}
					своего профиля для получения токена авторизации.
					<div style={{ margin: '10px 0' }}>
						<img
							style={{ border: '1px solid #ccc', display: 'inline-block' }}
							src={window.shipping_ecom_public_path + ApiTokenImageUrl}
						/>
					</div>
				</li>
				<li>
					Скопируйте токен авторизации и укажите его в настройках доставки в поле "Токен
					авторизации".
				</li>
			</ul>
		</div>
	);
}
