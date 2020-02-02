import { getPublicPath } from './get-public-path';

test('case', () => {
	window.shipping_ecom_public_path = 'https://test.ru/';

	expect(getPublicPath('subpath')).toBe('https://test.ru/subpath');
});
