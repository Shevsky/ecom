import React from 'react';

export const ParamsContext = React.createContext<Omit<IParams, 'settings'>>({} as Omit<
	IParams,
	'settings'
>);
