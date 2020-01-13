import './dimension-type.sass';

import React from 'react';
import { bem } from 'util/bem';
import { Container, InlineLink, List, Paragraph } from 'common/components';
import { SS8DimensionSettingHelp } from 'modules/settings/components/help';
import { useWindowOpen } from 'util/use-window-open';

const classname = bem('dimension-type');

export function DimensionType(): JSX.Element {
	const openHelp = useWindowOpen(<SS8DimensionSettingHelp />);

	return (
		<div className={classname()}>
			<Container>
				<Paragraph smallBottomPadding>
					Каждое отправление должно быть соотнесено с обозначенными Почтой России
					типоразмерами.
				</Paragraph>
				<Paragraph smallBottomPadding>
					<List>
						<>
							<strong>Размер S</strong>. 260 &#215; 170 &#215; 80. Вес до 1 кг
						</>
						<>
							<strong>Размер M</strong>. 300 &#215; 200 &#215; 150. Вес от 1 до 3 кг
						</>
						<>
							<strong>Размер L</strong>. 400 &#215; 270 &#215; 180. Вес от 3 до 5 кг.
						</>
						<>
							<strong>Размер XL</strong>. 530 &#215; 360 &#215; 220. Вес от 5 до 10
							кг.
						</>
						<>
							<strong>Размер OVERSIZED (негабарит)</strong>. Сумма сторон 1400, одна
							сторона не более 600. Вес от 10 до 15 кг.
						</>
					</List>
				</Paragraph>
				<Paragraph smallBottomPadding>
					Если заказ по габаритам или весу не может соответствовать ни одному типоразмеру,
					его доставка осуществляться не может, расчет так же произвести невозможно.
				</Paragraph>
				<Paragraph smallBottomPadding>
					При произведении расчета, плагин автоматически распознает принадлежность заказа
					к соответствующему ему типоразмеру. Убедитесь в том, что Ваше приложение
					поддерживает передачу габаритов и настроено на его передачу, иначе типоразмер
					будет определяться только по весу заказа.
				</Paragraph>
				<Paragraph disabledBottomPadding>
					<InlineLink icon={{ name: 'info' }} onClick={openHelp}>
						Как настроить передачу габаритов для Shop-Script 8?
					</InlineLink>
				</Paragraph>
			</Container>
		</div>
	);
}
