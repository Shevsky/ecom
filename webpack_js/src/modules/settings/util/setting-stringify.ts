export namespace SettingStringify {
	export function decode(
		rawValue: string,
		baseValue: ISettings[keyof ISettings]
	): ISettings[keyof ISettings] {
		const type = typeof baseValue;

		switch (type) {
			case 'number':
				return (+rawValue as unknown) as ISettings[keyof ISettings];
			case 'object':
				return JSON.parse(rawValue);
			default:
				return rawValue as string;
		}
	}

	export function encode(rawValue: ISettings[keyof ISettings]): string {
		const type = typeof rawValue;

		switch (type) {
			case 'number':
				return rawValue.toString();
			case 'object':
				return rawValue !== null ? JSON.stringify(rawValue) : '';
			case 'undefined':
				return '';
			case 'boolean':
				return rawValue ? '1' : '';
			default:
				return rawValue as string;
		}
	}
}
