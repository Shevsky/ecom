import React from 'react';
import { Field } from 'common/components/field';
import { Checkbox, Input } from 'modules/settings/components/common';

export function DepartureTab(): JSX.Element {
	return (
		<>
			<Field name="Индекс места приема">
				<Input name="index_from" />
			</Field>

			<Field
				name="Передавать стоимость заказа"
				description="Будет передавать стоимость заказа при расчете стоимости доставки (сумма товаров). Не может включать в себя стоимость доставки, так как на этом этапе она как раз таки расчитывается"
			>
				<Checkbox name="pass_goods_value" />
			</Field>
		</>
	);
}
