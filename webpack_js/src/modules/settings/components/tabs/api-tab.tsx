import React from 'react';
import { Field } from 'common/components/field';
import { Input } from 'modules/settings/components/common';

export function ApiTab(): JSX.Element {
	return (
		<>
			<Field name="Логин">
				<Input name="api_login" />
			</Field>
			<Field name="Пароль">
				<Input name="api_password" />
			</Field>
			<Field name="Токен авторизации">
				<Input name="api_token" />
			</Field>
		</>
	);
}
