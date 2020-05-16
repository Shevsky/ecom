import React, { useCallback, useContext } from 'react';
import {
	CheckboxGroup,
	Container,
	Field,
	Header,
	InlineCode,
	InlineLink,
	Paragraph,
	SELECT_SIZE
} from 'common/components';
import { Checkbox, Input, Select } from 'modules/settings/components/common';
import { CALCULATION_MODE } from 'modules/settings/enum';
import { GlobalContext } from 'modules/settings/services/global-context';
import { PaymentScreen } from 'modules/settings/components/screens';

export function FilterScreen(): JSX.Element {
	const global = useContext(GlobalContext);
	const handleClickGoToPaymentTab = useCallback(
		(): void => global.tabController.next(PaymentScreen),
		[]
	);

	return (
		<div>
			<Header>Параметры пунктов выдачи</Header>
			<Field name="Режим расчета стоимости доставки в пункты выдачи">
				<Select
					name="calculation_mode"
					options={{
						[CALCULATION_MODE.EACH_POINT]: 'Для каждого пункта выдачи',
						[CALCULATION_MODE.FIRST_IN_CITY_POINT]:
							'Считать стоимость до первого пункта выдачи в городе',
						[CALCULATION_MODE.GROUP_BY_NAME]:
							'Группировать пункты выдачи по организациям'
					}}
					explanation="Перед отображением клиенту списка всех пунктов выдачи, плагину необходимо
						произвести расчет стоимости доставки до каждого пункта выдачи через
						построение последовательных обращений к серверам API Почты России. Такой
						процесс может занять очень длительное время, поэтому можно изменить режим
						расчета стоимости доставки в пункты выдачи и уменьшить количество обращений
						к серверам API Почты России."
					details={{
						[CALCULATION_MODE.EACH_POINT]: (
							<>
								<strong>Для каждого пункта выдачи</strong> &ndash; плагин будет
								производить расчет стоимости доставки для каждого пункта выдачи в
								городе клиента
							</>
						),
						[CALCULATION_MODE.FIRST_IN_CITY_POINT]: (
							<>
								<strong>
									Считать стоимость для первого пункта выдачи в городе
								</strong>{' '}
								&ndash; плагин произведет расчет до первого пункта выдачи в городе
								клиента и покажет такую стоимость доставки и сроки для всех
								остальных пунктов выдачи в городе клиента
							</>
						),
						[CALCULATION_MODE.GROUP_BY_NAME]: (
							<>
								<strong>Группировать пункты выдачи по организациям</strong> &ndash;
								плагин сначала сгруппирует доступные пункты выдачи по организациям,
								осуществляющим доставку, затем произведет расчет до каждого первого
								пункта выдачи внутри каждой организации, и покажет такую стоимость
								доставки и сроки для всех пунктов выдачи, связанных со своей
								организацией
							</>
						)
					}}
					size={SELECT_SIZE.AUTO}
				/>
			</Field>

			<Field name="Отображать пункты выдачи...">
				<CheckboxGroup>
					<Checkbox name="delivery_point_type" label="ПВЗ" />
					<Checkbox name="pickup_point_type" label="Почтоматы" />
				</CheckboxGroup>
			</Field>

			<Field name="Фильтр по способам оплаты">
				<InlineLink onClick={handleClickGoToPaymentTab}>
					Редактировать на вкладке "Параметры оплаты"
				</InlineLink>
			</Field>

			<Header paddedTop>Параметры расчета</Header>

			<Field name="Дополнительная наценка к стоимости доставки">
				<Input name="extra_charge" />
			</Field>
			<Field>
				<Container>
					<Paragraph smallBottomPadding>
						В этом поле можно использовать функции Smarty для расчета формул и некоторые
						переменные.
					</Paragraph>

					<Paragraph smallBottomPadding>
						<InlineCode>{'{$order}'}</InlineCode> &ndash;сумма заказа
					</Paragraph>
					<Paragraph disabledBottomPadding>
						<InlineCode>{'{$shipping}'}</InlineCode> &ndash;стоимость доставки исходная
					</Paragraph>
				</Container>
			</Field>

			<Field
				name="Дополнительный коэффициент к стоимости доставки"
				description="Стоимость доставки будет умножена на указанное число (после наценки)"
			>
				<Input name="extra_coeff" type="tel" />
			</Field>
		</div>
	);
}
