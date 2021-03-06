import exp = require('constants');

export enum UNDEFINED_DIMENSION_CASE {
	DISABLE_SHIPPING = 'disable_shipping',
	FIXED_DIMENSION_TYPE = 'fixed_dimension_type'
}

export enum DIMENSION_TYPE {
	SMALL = 'S',
	MEDIUM = 'M',
	LARGE = 'L',
	EXTRA_LARGE = 'XL',
	OVERSIZED = 'OVERSIZED'
}

export enum MAIL_CATEGORY {
	SIMPLE = 'SIMPLE',
	ORDERED = 'ORDERED',
	ORDINARY = 'ORDINARY',
	WITH_DECLARED_VALUE = 'WITH_DECLARED_VALUE',
	WITH_DECLARED_VALUE_AND_CASH_ON_DELIVERY = 'WITH_DECLARED_VALUE_AND_CASH_ON_DELIVERY',
	WITH_DECLARED_VALUE_AND_COMPULSORY_PAYMENT = 'WITH_DECLARED_VALUE_AND_COMPULSORY_PAYMENT',
	WITH_COMPULSORY_PAYMENT = 'WITH_COMPULSORY_PAYMENT'
}

export enum MAIL_TYPE {
	POSTAL_PARCEL = 'POSTAL_PARCEL',
	ONLINE_PARCEL = 'ONLINE_PARCEL',
	ONLINE_COURIER = 'ONLINE_COURIER',
	EMS = 'EMS',
	EMS_OPTIMAL = 'EMS_OPTIMAL',
	EMS_RT = 'EMS_RT',
	EMS_TENDER = 'EMS_TENDER',
	LETTER = 'LETTER',
	LETTER_CLASS_1 = 'LETTER_CLASS_1',
	BANDEROL = 'BANDEROL',
	BUSINESS_COURIER = 'BUSINESS_COURIER',
	BUSINESS_COURIER_ES = 'BUSINESS_COURIER_ES',
	PARCEL_CLASS_1 = 'PARCEL_CLASS_1',
	BANDEROL_CLASS_1 = 'BANDEROL_CLASS_1',
	VGPO_CLASS_1 = 'VGPO_CLASS_1',
	SMALL_PACKET = 'SMALL_PACKET',
	EASY_RETURN = 'EASY_RETURN',
	VSD = 'VSD',
	ECOM = 'ECOM',
	COMBINED = 'COMBINED'
}

export enum ENTRIES_TYPE {
	GIFT = 'GIFT',
	DOCUMENT = 'DOCUMENT',
	SALE_OF_GOODS = 'SALE_OF_GOODS',
	COMMERCIAL_SAMPLE = 'COMMERCIAL_SAMPLE',
	OTHER = 'OTHER'
}

export enum PAYMENT_METHOD {
	CASHLESS = 'CASHLESS',
	STAMP = 'STAMP',
	FRANKING = 'FRANKING',
	TO_FRANKING = 'TO_FRANKING',
	ONLINE_PAYMENT_MARK = 'ONLINE_PAYMENT_MARK'
}

export enum TOTAL_VALUE_MODE {
	WITH_DISCOUNTS = 'with_discounts',
	WITHOUT_DISCOUNTS = 'without_discounts'
}

export enum ORDER_PAYMENT_TYPE {
	PREPAID_ONLY = 'prepaid_only',
	POSTPAY_ONLY = 'postpay_only'
}

export enum DEBUG_MODE {
	ERRORS = 'errors',
	DETAILS = 'details',
	DEBUG = 'debug'
}

export enum CALCULATION_MODE {
	EACH_POINT = 'each_point',
	FIRST_IN_CITY_POINT = 'first_in_city_point',
	GROUP_BY_NAME = 'group_by_name'
}

export enum POINT_TYPE {
	DELIVERY = 'DELIVERY_POINT',
	PICKUP = 'PICKUP_POINT'
}
