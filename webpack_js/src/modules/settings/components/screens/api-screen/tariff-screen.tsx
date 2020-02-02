import React, { useCallback, useContext, useMemo, useState } from 'react';
import { Field, Icon, InlineLink, INPUT_SIZE, Select } from 'common/components';
import { Checkbox, Input } from 'modules/settings/components/common';
import { ParamsContext } from 'modules/settings/services/params-context';
import { useSetting } from 'modules/settings/util/use-setting';

interface IGetAgreementNumberResponse {
	data?: string;
	errors?: Array<Array<string>>;
	status: 'ok' | 'fail';
}

enum TARIFF_API {
	OTPRAVKA_API = 'otpravka_api',
	TARIFF_API = 'tariff_api'
}

export function TariffScreen(): JSX.Element {
	const [isCalculateThruTariff, setIsCalculateThruTariff] = useSetting(
		'is_calculate_thru_tariff'
	);
	const tariffApi = useMemo(
		(): TARIFF_API => (isCalculateThruTariff ? TARIFF_API.TARIFF_API : TARIFF_API.OTPRAVKA_API),
		[isCalculateThruTariff]
	);
	const handleChangeTariffApi = useCallback((newTariffApi: TARIFF_API): void => {
		setIsCalculateThruTariff(newTariffApi === TARIFF_API.TARIFF_API);
	}, []);

	const [login] = useSetting('api_login');
	const [password] = useSetting('api_password');
	const [token] = useSetting('api_token');
	const params = useContext(ParamsContext);
	const isAllowedGetAgreementNumber = !!login && !!password && !!token;
	const [, setAgreementNumber] = useSetting('tariff_agreement_number');
	const [isLoadingAgreementNumber, setIsLoadingAgreementNumber] = useState(false);

	const getAgreementNumber = useCallback(
		(rawLogin: string, rawPassword: string, rawToken: string): Promise<string> => {
			return new Promise(
				(
					resolve: (agreementNumber: string) => void,
					reject: (reason: string) => void
				): void => {
					$.post(
						params.get_agreement_number_url,
						{ login: rawLogin, password: rawPassword, token: rawToken },
						(response: IGetAgreementNumberResponse): void => {
							setIsLoadingAgreementNumber(false);

							if (!response || !response.status) {
								reject('Не удалось получить номер договора');
							} else if (response.status === 'fail') {
								reject(response.errors[0][0]);
							} else if (response.status === 'ok') {
								resolve(response.data);
							}
						},
						'json'
					);
				}
			);
		},
		[]
	);
	const handleClickGetAgreementNumber = useCallback((): void => {
		setIsLoadingAgreementNumber(true);

		getAgreementNumber(login, password, token)
			.then(setAgreementNumber)
			.catch(alert)
			.finally((): void => setIsLoadingAgreementNumber(false));
	}, [login, password, token]);

	return (
		<>
			<Field name="API для расчета стоимости доставки">
				<Select
					value={tariffApi}
					onChange={handleChangeTariffApi}
					options={{
						[TARIFF_API.OTPRAVKA_API]: 'Отправка',
						[TARIFF_API.TARIFF_API]: 'Тарификатор'
					}}
				/>
			</Field>

			{tariffApi === TARIFF_API.TARIFF_API && (
				<Field
					name="Номер договора"
					description={
						isAllowedGetAgreementNumber && (
							<>
								<InlineLink
									onClick={
										!isLoadingAgreementNumber && handleClickGetAgreementNumber
									}
								>
									Получить номер договора из профиля сервиса Отправка
								</InlineLink>
							</>
						)
					}
				>
					<Input name="tariff_agreement_number" />

					{isLoadingAgreementNumber && (
						<Icon name="loading" paddedLeft verticalAlignMiddle />
					)}
				</Field>
			)}

			<Field name="Кешировать результаты расчета">
				<Checkbox name="is_calculate_caching" label="Кешировать результаты расчета" />
			</Field>
		</>
	);
}
