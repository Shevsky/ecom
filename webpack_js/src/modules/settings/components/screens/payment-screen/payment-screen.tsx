import './payment-screen.sass';

import React, { useCallback, useContext } from 'react';
import { Container, Field, InlineLink, Paragraph } from 'common/components';
import { MAIL_CATEGORY } from 'modules/settings/enum';
import { Checkbox, Select } from 'modules/settings/components/common';
import { GlobalContext } from 'modules/settings/services/global-context';
import { mailCategoryPaymentMatchVerifier } from 'modules/settings/util/mail-category-payment-match-verifier';
import { bem } from 'util/bem';
import { DepartureScreen } from '../departure-screen';

const classname = bem('payment-screen');

export function PaymentScreen(): JSX.Element {
	const global = useContext(GlobalContext);
	const handleClickGoToDepartureTab = useCallback(
		(): void => global.tabController.next(DepartureScreen),
		[]
	);

	const [
		isMailCategoryMatchPayment,
		mailCategoryMatchPaymentDescription
	] = mailCategoryPaymentMatchVerifier();

	return (
		<div className={classname()}>
			<Container>
				<Paragraph>
					Плагин будет фильтровать возможные способы оплаты при выборе этого способа
					доставки согласно установленным настройкам (если ваше приложение поддерживает
					такой функционал), а так же фильтровать доступные для выбора пункты выдачи по
					возможности использования такого способа оплаты.
				</Paragraph>
			</Container>

			<Field
				name="Категория отправления"
				description={
					<>
						<InlineLink onClick={handleClickGoToDepartureTab}>
							Редактировать на вкладке "Параметры отправления"
						</InlineLink>
					</>
				}
			>
				<Select
					name="mail_category"
					options={{
						[MAIL_CATEGORY.ORDINARY]: 'Обыкновенное',
						[MAIL_CATEGORY.WITH_COMPULSORY_PAYMENT]: 'С обязательным платежом'
					}}
					disabled
				/>
			</Field>

			<Field
				name="Оплата картой"
				description="Будут показаны пункты выдачи с возможностью оплаты картой"
			>
				<Checkbox name="card_payment" label="Оплата картой" />
			</Field>
			<Field
				name="Оплата наличными"
				description="Будут показаны пункты выдачи с возможностью оплаты наличными"
			>
				<Checkbox name="cash_payment" label="Оплата наличными" />
			</Field>
			<Field name="Предоплата" description="Будут показаны все пункты выдачи">
				<Checkbox name="pre_payment" label="Предоплата" />
			</Field>
			{!isMailCategoryMatchPayment && (
				<Field>
					<Container>
						<Paragraph disabledBottomPadding>
							<div className={classname('error')}>
								{mailCategoryMatchPaymentDescription}
							</div>
						</Paragraph>
					</Container>
				</Field>
			)}
		</div>
	);
}
