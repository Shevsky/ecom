import React from 'react';
import { Field } from 'common/components/field';
import { Checkbox, Select } from 'modules/settings/components/common';
import { DEBUG_MODE } from 'modules/settings/enum';
import { useSetting } from 'modules/settings/util/use-setting';

export function DebugScreen(): JSX.Element {
	const [isDebug] = useSetting('is_debug');
	const [isDebugCalculator] = useSetting('is_debug_calculator');
	const [isDebugTarifficator] = useSetting('is_debug_tarifficator');

	return (
		<>
			<Field
				name="Режим отладки плагина"
				description={
					'Плагин будет логировать ход своих действий при произведении расчета стоимости доставки. Режим отладки плагина будет работать только при включенном режиме отладки сайта (устанавливается в приложении "Настройки")'
				}
			>
				<Checkbox name="is_debug" label="Режим отладки плагина" />
			</Field>

			{!!isDebug && (
				<>
					<Field
						name="Логирование калькулятора"
						description="Будет записывать в лог процесс расчета стоимости доставки от получения пунктов выдачи до получения ответа со стоимостями доставок по всем показанным пунктам выдачи пользователю"
					>
						<Checkbox
							name="is_debug_calculator"
							label="Логировать процесс калькулятора стоимости доставки"
						/>

						{!!isDebugCalculator && (
							<Select
								name="calculator_debug_mode"
								options={{
									[DEBUG_MODE.ERRORS]: 'Только ошибки',
									[DEBUG_MODE.DEBUG]: 'Весь процесс калькулятора'
								}}
							/>
						)}
					</Field>

					<Field
						name="Логирование тарификатора"
						description="Будет записывать в лог исходные данные запросов и ответов от API тарификатора (или API сервиса Отправка)"
					>
						<Checkbox
							name="is_debug_tarifficator"
							label="Логировать процесс тарификации"
						/>

						{!!isDebugTarifficator && (
							<Select
								name="tarifficator_debug_mode"
								options={{
									[DEBUG_MODE.ERRORS]: 'Только ошибки',
									[DEBUG_MODE.DEBUG]: 'Весь процесс тарификатора'
								}}
							/>
						)}
					</Field>
				</>
			)}
		</>
	);
}
