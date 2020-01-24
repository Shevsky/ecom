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
							<strong>Размер S</strong>. До 260 &times; 170 &times; 80 мм. Вес до 1 кг
						</>
						<>
							<strong>Размер M</strong>. До 300 &times; 200 &times; 150 мм. Вес от 1
							до 3 кг
						</>
						<>
							<strong>Размер L</strong>. До 400 &times; 270 &times; 180 мм. Вес от 3
							до 5 кг.
						</>
						<>
							<strong>Размер XL</strong>. До 530 &times; 360 &times; 220 мм. Вес от 5
							до 10 кг.
						</>
						<>
							<strong>Размер OVERSIZED (негабарит)</strong>. Сумма сторон 1400 мм,
							одна сторона не более 600 мм. Вес от 10 до 15 кг.
						</>
					</List>
				</Paragraph>
				<Paragraph smallBottomPadding>
					При произведении расчета, плагин автоматически распознает принадлежность заказа
					к соответствующему ему типоразмеру. Убедитесь в том, что ваше приложение
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
