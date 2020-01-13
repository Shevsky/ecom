import React from 'react';
import { CheckboxGroup, Field, INPUT_SIZE } from 'common/components';
import { Checkbox, Input, Select } from 'modules/settings/components/common';
import { DimensionType } from 'modules/settings/components/dimension-type';
import { useSetting } from 'modules/settings/util/use-setting';
import { TOTAL_VALUE_MODE } from 'modules/settings/enum';

export function DepartureScreen(): JSX.Element {
	const [isPassGoodsValue] = useSetting('pass_goods_value');

	return (
		<>
			<Field name="Индекс места приема">
				<Input name="index_from" mask="999999" size={INPUT_SIZE.SMALL} />
			</Field>
			<Field
				name="Передавать стоимость заказа"
				description="Будет передавать стоимость заказа при расчете стоимости доставки (сумма товаров). Не может включать в себя стоимость доставки, так как на этом этапе она как раз таки расчитывается"
			>
				<Checkbox name="pass_goods_value" />
			</Field>
			{isPassGoodsValue && (
				<Field name="Режим передачи стоимости заказа">
					<Select
						name="total_value_mode"
						options={{
							[TOTAL_VALUE_MODE.WITH_DISCOUNTS]: 'Со всеми скидками',
							[TOTAL_VALUE_MODE.WITHOUT_DISCOUNTS]: 'Не учитывая скидки'
						}}
					/>
				</Field>
			)}
			<Field name="Дополнительные услуги">
				<CheckboxGroup>
					<Checkbox name="sms_notice_recipient" label="SMS-уведомление получателя" />
					<Checkbox name="with_fitting" label="Услуга примерки" />
					<Checkbox name="contents_checking" label="Проверка комплектности" />
				</CheckboxGroup>
			</Field>
			<Field name="Типоразмер отправления">
				<DimensionType />
			</Field>
		</>
	);
}
