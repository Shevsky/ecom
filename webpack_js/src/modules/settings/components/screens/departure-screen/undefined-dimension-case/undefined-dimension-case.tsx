import './undefined-dimension-case.sass';

import React from 'react';
import { Container, Paragraph, SELECT_SIZE } from 'common/components';
import { Select } from 'modules/settings/components/common';
import { DIMENSION_TYPE, UNDEFINED_DIMENSION_CASE } from 'modules/settings/enum';
import { useSetting } from 'modules/settings/util/use-setting';
import { bem } from 'util/bem';

const classname = bem('undefined-dimension-case');

export function UndefinedDimensionCase(): JSX.Element {
	const [undefinedDimensionCase] = useSetting('undefined_dimension_case');

	return (
		<div className={classname()}>
			<Container>
				<Paragraph>
					Если заказ по габаритам или весу не может соответствовать ни одному типоразмеру,
					или плагин не смог распознать к какому типоразмеру относится заказ (например,
					если передача габаритов не была настроена, или для товаров в заказе габариты не
					были определены), то можно установить фиксированный типоразмер для всех таких
					заказов, или же полностью запретить доставку в таком случае.
				</Paragraph>
			</Container>

			<div className={classname('box')}>
				<Select
					className={classname('box-item')}
					name="undefined_dimension_case"
					options={{
						[UNDEFINED_DIMENSION_CASE.FIXED_DIMENSION_TYPE]: 'Фиксированный типоразмер',
						[UNDEFINED_DIMENSION_CASE.DISABLE_SHIPPING]: 'Запретить доставку'
					}}
					size={SELECT_SIZE.AUTO}
				/>

				{undefinedDimensionCase === UNDEFINED_DIMENSION_CASE.FIXED_DIMENSION_TYPE && (
					<Select
						className={classname('box-item')}
						name="dimension_type"
						options={{
							[DIMENSION_TYPE.SMALL]: 'S',
							[DIMENSION_TYPE.MEDIUM]: 'M',
							[DIMENSION_TYPE.LARGE]: 'L',
							[DIMENSION_TYPE.EXTRA_LARGE]: 'XL',
							[DIMENSION_TYPE.OVERSIZED]: 'OVERSIZED'
						}}
						size={SELECT_SIZE.AUTO}
					/>
				)}
			</div>
		</div>
	);
}
