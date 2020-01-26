import './departure-screen.sass';

import React, { useCallback, useContext, useEffect, useState } from 'react';
import { filter } from 'rxjs/operators';
import {
	CheckboxGroup,
	Container,
	Field,
	InlineLink,
	INPUT_SIZE,
	Paragraph
} from 'common/components';
import { Checkbox, Input, Select } from 'modules/settings/components/common';
import { useSetting } from 'modules/settings/util/use-setting';
import { ENTRIES_TYPE, MAIL_CATEGORY, TOTAL_VALUE_MODE } from 'modules/settings/enum';
import { GlobalContext } from 'modules/settings/services/global-context';
import { SettingsContext } from 'modules/settings/services/settings-context';
import { mailCategoryPaymentMatchVerifier } from 'modules/settings/util/mail-category-payment-match-verifier';
import { bem } from 'util/bem';
import { DimensionType } from './dimension-type';
import { UndefinedDimensionCase } from './undefined-dimension-case';
import { PaymentScreen } from '../payment-screen';

const classname = bem('departure-screen');

export function DepartureScreen(): JSX.Element {
	const [isPassGoodsValue, setIsPassGoodsValue] = useSetting('pass_goods_value');

	const [mailCategory] = useSetting('mail_category');
	const settings = useContext(SettingsContext);
	useEffect((): VoidFunction => {
		const subscription = settings.mail_category
			.pipe(
				filter(
					(value: MAIL_CATEGORY): boolean =>
						value === MAIL_CATEGORY.WITH_COMPULSORY_PAYMENT
				)
			)
			.subscribe((): void => setIsPassGoodsValue(true));

		return (): void => subscription.unsubscribe();
	}, []);

	const global = useContext(GlobalContext);
	const [regions, setRegions] = useState<TRegions>({ entities: {}, order: [] });
	useEffect((): void => {
		global.locationHandbook.getRegions('rus').then(setRegions);
	}, []);

	const [isMailCategoryMatchPayment] = mailCategoryPaymentMatchVerifier();
	const handleClickGoToPaymentTab = useCallback(
		(): void => global.tabController.next(PaymentScreen),
		[]
	);

	return (
		<div className={classname()}>
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
				name="Категория отправления"
				description={
					<>
						<div>
							<strong>Обыкновенное</strong> &ndash; отправление без оплаты стоимости
							заказа
						</div>
						<div>
							<strong>С обязательным платежом</strong> &ndash; отправление с оплатой
							стоимости заказа при получении
						</div>
					</>
				}
			>
				<Select
					name="mail_category"
					options={{
						[MAIL_CATEGORY.ORDINARY]: 'Обыкновенное',
						[MAIL_CATEGORY.WITH_COMPULSORY_PAYMENT]: 'С обязательным платежом'
					}}
				/>
			</Field>
			{!isMailCategoryMatchPayment && (
				<Field>
					<Container>
						<Paragraph disabledBottomPadding>
							<div className={classname('mail-category-payment-match-error')}>
								Категория отправления не стыкуется с установленными параметрами
								оплаты. См. подробности на вкладке{' '}
								<InlineLink onClick={handleClickGoToPaymentTab}>
									"Параметры оплаты"
								</InlineLink>
								.
							</div>
						</Paragraph>
					</Container>
				</Field>
			)}
			<Field
				name="Передавать стоимость заказа"
				description="Будет передавать стоимость заказа при расчете стоимости доставки (сумма товаров). Не может включать в себя стоимость доставки, так как на этом этапе она как раз таки расчитывается"
			>
				<Checkbox
					name="pass_goods_value"
					disabled={mailCategory === MAIL_CATEGORY.WITH_COMPULSORY_PAYMENT}
				/>
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
					<Checkbox
						name="with_fitting"
						label="Услуга примерки (временно не поддерживается самой почтой)"
						disabled
					/>
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
		</div>
	);
}
