import { formatNumeric } from './format-numeric';

test('case', () => {
	expect(formatNumeric(16000)).toBe('16 000');
	expect(formatNumeric(1500000)).toBe('1 500 000');
});
