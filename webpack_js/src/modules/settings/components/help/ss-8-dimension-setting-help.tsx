import React from 'react';
import SS8ShippingSettingImageUrl from './jsx-images/ss-8-shipping-setting.png';
import SS8ShippingDimensionSettingImageUrl from './jsx-images/ss-8-shipping-dimension-setting.png';
import SS8ShippingDimensionFormImageUrl from './jsx-images/ss-8-shipping-dimension-form.png';
import { getPublicPath } from 'util/get-public-path';

export function SS8DimensionSettingHelp(): JSX.Element {
	return (
		<div>
			<p>
				Для того, чтобы плагин доставки мог определять типоразмер отправления не только по
				весу, но и по габаритам заказа, необходимо включить передачу габаритов товаров в
				заказе в Shop-Script 8.
			</p>

			<ul>
				<li>
					Перейдите в пункт <strong>"Доставка"</strong> в общих настройках приложения.
					<div style={{ margin: '10px 0' }}>
						<img
							style={{ border: '1px solid #ccc', display: 'inline-block' }}
							src={getPublicPath(SS8ShippingSettingImageUrl)}
						/>
					</div>
				</li>
				<li>
					Нажмите <strong>"Настройки"</strong> в пункте{' '}
					<strong>"Вес и размеры заказа"</strong>.
					<div style={{ margin: '10px 0' }}>
						<img
							style={{ border: '1px solid #ccc', display: 'inline-block' }}
							src={getPublicPath(SS8ShippingDimensionSettingImageUrl)}
						/>
					</div>
				</li>
				<li>
					Укажите характеристику товаров, которая содержит в себе габариты его 3 сторон,
					или же укажите их по отдельности в соответствующих параметрах настроек.
					<div style={{ margin: '10px 0' }}>
						<img
							style={{ border: '1px solid #ccc', display: 'inline-block' }}
							src={getPublicPath(SS8ShippingDimensionFormImageUrl)}
						/>
					</div>
				</li>
				<li>
					Таким образом, плагины доставки смогут получать информацию о габаритах товаров,
					содержащихся в заказе.
				</li>
				<li>
					Чтобы передать плагину доставки суммарные габариты заказа, необходимо подключить
					дополнительный плагин, который может реализовать такую задачу. Если таковой не
					подключить, то плагин доставки будет самостоятельно считать габариты заказа,
					суммируя наименьшие стороны товаров, а из остальных сторон используя
					максимальные значения.
				</li>
			</ul>
		</div>
	);
}
