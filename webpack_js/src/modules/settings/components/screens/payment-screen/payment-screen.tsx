import React from 'react';
import { Container, Field, Paragraph } from 'common/components';
import { Checkbox } from 'modules/settings/components/common';

export function PaymentScreen(): JSX.Element {
	return (
		<>
			<Container>
				<Paragraph>
					Плагин будет фильтровать возможные способы оплаты при выборе этого способа
					доставки согласно установленным настройкам (если ваше приложение поддерживает
					такой функционал), а так же фильтровать доступные для выбора пункты выдачи по
					возможности использования такого способа оплаты.
				</Paragraph>
			</Container>

			<Field
				name="Оплата картой"
				description="Будут показаны пункты выдачи с возможностью оплаты картой"
			>
				<Checkbox name="card_payment" />
			</Field>
			<Field
				name="Оплата наличными"
				description="Будут показаны пункты выдачи с возможностью оплаты наличными"
			>
				<Checkbox name="card_payment" />
			</Field>
			<Field name="Предоплата" description="Будут показаны все пункты выдачи">
				<Checkbox name="pre_payment" />
			</Field>
		</>
	);
}
