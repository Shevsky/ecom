import { useCallback } from 'react';
import { renderToString } from 'react-dom/server';

interface IUseHelpOpenParams {
	width: number;
	height: number;
}

export function useWindowOpen(
	content: JSX.Element,
	params: IUseHelpOpenParams = { width: 575, height: 600 }
): VoidFunction {
	return useCallback((): void => {
		const createdWindow = window.open(
			'',
			'',
			`width=${params.width.toString()},height=${params.height.toString()}`
		);

		createdWindow.document.write(renderToString(content));
	}, []);
}
