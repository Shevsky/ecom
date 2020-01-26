import { MAIL_CATEGORY } from 'modules/settings/enum';
import { useSetting } from './use-setting';

const MAIL_CATEGORY_NAME = {
	[MAIL_CATEGORY.ORDINARY]: 'Обыкновенное',
	[MAIL_CATEGORY.WITH_COMPULSORY_PAYMENT]: 'С обязательным платежом'
};

const MAIL_CATEGORY_DESTINATION = {
	[MAIL_CATEGORY.ORDINARY]: `Категория отправления "${
		MAIL_CATEGORY_NAME[MAIL_CATEGORY.ORDINARY]
	}" подразумевает только предоплаченные заказы`,
	[MAIL_CATEGORY.WITH_COMPULSORY_PAYMENT]: `Категория отправления "${
		MAIL_CATEGORY_NAME[MAIL_CATEGORY.WITH_COMPULSORY_PAYMENT]
	}" подразумевает оплату заказа при получении`
};

export function mailCategoryPaymentMatchVerifier(): [boolean, string] {
	const [mailCategory] = useSetting('mail_category');

	const [cardPayment] = useSetting('card_payment');
	const [cashPayment] = useSetting('cash_payment');
	const [prePayment] = useSetting('pre_payment');

	const isOrdinary = mailCategory === MAIL_CATEGORY.ORDINARY;
	const isWithCompulsoryPayment = mailCategory === MAIL_CATEGORY.WITH_COMPULSORY_PAYMENT;

	if (isOrdinary && !prePayment) {
		return [
			false,
			`${MAIL_CATEGORY_DESTINATION[mailCategory]}, но способы оплаты по предоплате отключены`
		];
	}

	if (isWithCompulsoryPayment && prePayment) {
		return [
			false,
			`${MAIL_CATEGORY_DESTINATION[mailCategory]}, но способ оплаты по предоплате включен`
		];
	}

	if (isOrdinary && (cardPayment || cashPayment)) {
		const enabledPostpayMethods = [];
		if (cardPayment) {
			enabledPostpayMethods.push('Оплата картой');
		}
		if (cashPayment) {
			enabledPostpayMethods.push('Оплата наличными');
		}

		return [
			false,
			`${
				MAIL_CATEGORY_DESTINATION[mailCategory]
			}, но в способах оплаты так же включены способы оплаты при получении (${enabledPostpayMethods.join(
				', '
			)})`
		];
	}

	if (isWithCompulsoryPayment && (!cardPayment && !cashPayment)) {
		return [
			false,
			`${
				MAIL_CATEGORY_DESTINATION[mailCategory]
			}, но ни установлен ни один способ оплаты при получении`
		];
	}

	return [true, ''];
}
