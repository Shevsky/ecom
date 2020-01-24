import React, { useContext, useEffect, useState } from 'react';
import { CheckboxGroup, Field, INPUT_SIZE } from 'common/components';
import { Checkbox, Input, Select } from 'modules/settings/components/common';
import { useSetting } from 'modules/settings/util/use-setting';
import { ENTRIES_TYPE, TOTAL_VALUE_MODE } from 'modules/settings/enum';
import { GlobalContext } from 'modules/settings/services/global-context';
import { DimensionType } from './dimension-type';
import { UndefinedDimensionCase } from './undefined-dimension-case';

export function DepartureScreen(): JSX.Element {
	const [isPassGoodsValue] = useSetting('pass_goods_value');

	const global = useContext(GlobalContext);
	const [regions, setRegions] = useState<TRegions>({ entities: {}, order: [] });
	useEffect((): void => {
		global.locationHandbook.getRegions('rus').then(setRegions);
	}, []);

	return (
		<>
			<Field name="Индекс места приема">
				<Input name="index_from" mask="999999" size={INPUT_SIZE.SMALL} />
			</Field>
			<Field name="Регион отправки">
				<Select
					name="region_code_from"
					options={regions.entities}
					order={regions.order}
					withEmpty
				/>
			</Field>
			<Field name="Город отправки">
				<Input name="city_name_from" />
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
			<Field name="Категория вложения">
				<Select
					name="entries_type"
					options={{
						[ENTRIES_TYPE.GIFT]: 'Подарок',
						[ENTRIES_TYPE.DOCUMENT]: 'Документы',
						[ENTRIES_TYPE.SALE_OF_GOODS]: 'Продажа товара',
						[ENTRIES_TYPE.COMMERCIAL_SAMPLE]: 'Коммерческий образец',
						[ENTRIES_TYPE.OTHER]: 'Прочее'
					}}
				/>
			</Field>
			<Field name="Дополнительные услуги">
				<CheckboxGroup>
					<Checkbox name="sms_notice_recipient" label="SMS-уведомление получателя" />
					<Checkbox name="with_fitting" label="Услуга примерки" />
					<Checkbox name="functionality_checking" label="Проверка работоспособности" />
					<Checkbox name="contents_checking" label="Проверка вложения" />
					<Checkbox name="fragile" label={`Отметка "Осторожно/Хрупко"`} />
				</CheckboxGroup>
			</Field>
			<Field name="Типоразмер отправления">
				<DimensionType />
			</Field>
			<Field name="Типоразмер по умолчанию">
				<UndefinedDimensionCase />
			</Field>
			<Field name="Габариты по умолчанию">
				<Input
					name="default_height"
					size={INPUT_SIZE.SMALL}
					type="number"
					min="1"
					max="600"
				/>{' '}
				&times;{' '}
				<Input
					name="default_length"
					size={INPUT_SIZE.SMALL}
					type="number"
					min="1"
					max="600"
				/>{' '}
				&times;{' '}
				<Input
					name="default_width"
					size={INPUT_SIZE.SMALL}
					type="number"
					min="1"
					max="600"
				/>{' '}
				мм
			</Field>
			<Field name="Вес по умолчанию">
				<Input
					name="default_weight"
					size={INPUT_SIZE.SMALL}
					type="number"
					min="0.01"
					step="0.001"
					max="15"
				/>{' '}
				кг
			</Field>
		</>
	);
}
