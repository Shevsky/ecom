import { numericDeclension } from './numeric-declension';

test('case', () => {
	expect(numericDeclension(5, ['пункт', 'пункта', 'пунктов'])).toBe('пунктов');
});
